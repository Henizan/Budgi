<?php
session_start();


if(!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit;
}

$error_msg = "";
require_once __DIR__ . '/../config/database.php';

try {
    $conn = new PDO($dsn, $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $req = $conn->prepare("SELECT budget_limit, current_budget FROM users WHERE id = :user_id ");
    $req->execute(['user_id' => $_SESSION['user_id']]);
    $user = $req->fetch((PDO::FETCH_ASSOC));

    $budget_limit = $user['budget_limit'] ?? 0;
    $current_budget = $user['current_budget'] ?? 0;

    $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $transactions = $stmt->fetchAll((PDO::FETCH_ASSOC));

    // Aggregate data for Chart.js
    $categoryData = [];
    foreach ($transactions as $t) {
        $cat = $t['categorie'];
        $amt = (float)$t['amount'];
        if (!isset($categoryData[$cat])) {
            $categoryData[$cat] = 0;
        }
        $categoryData[$cat] += $amt;
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,700,1,200" />
    <link rel="stylesheet" href="style.css?v=5">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Tableau de bord</title>
</head>
<body>
    <nav>
        <a href="gestion.php" style="color: #254888;">Tableau de bord</a>
        <a href="profile.php" >Profil</a>
        <a href="logout.php">Se déconnecter</a>
    </nav>
    <button type="button" aria-label="toggle curtain navigation" class="nav-toggler">
        <span class="line l1"></span>
        <span class="line l2"></span>
        <span class="line l3"></span>
    </button>
<a href="gestion.php"><img src="images/budji transp.png" alt="logo" class="logo1"></a>
<div class="titre">
    <h1>Tableau de bord</h1></div>
    <div class="gestion-page">
    <div class="contenu">
        <h3>Votre budget du mois</h3>
        <p>Limite de dépenses : <strong><?= htmlspecialchars($budget_limit) ?></strong>€</p>
        <p>Budget actuel : <strong><?= htmlspecialchars($current_budget) ?></strong>€</p>
        
        <div style="max-width: 300px; margin: 20px auto;">
            <canvas id="spendingChart"></canvas>
        </div>
    </div>

    <div class="new-transac">
        <h3>Ajouter une nouvelle transaction</h3>
        <form action="new-transac.php" method="post">
            <label for="description">Description :</label>
            <input type="text" id="description" name="description" placeholder="Description..." class="form" required>
            <label for="montant">Montant :</label>
            <input type="number" step="0.01" id="amount" name="amount" placeholder="0.00€" class="form" required> 
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie" placeholder="Categorie..." class="form">
                <option value="Nourriture">Nourriture</option>
                <option value="Loisirs">Loisirs</option>
                <option value="Transport">Transport</option>
                <option value="Santé">Santé</option>
                <option value="Études">Études</option>
                <option value="Autres">Autres</option>
             </select>
            <input type="submit" value="Ajouter la transaction" class="register_signin boutton" style="margin-left: 3.5vh;">
        </form>
    </div>
    <div class="transac-table">
        <h3>Vos dernières transactions</h3>
        <table>
            <thead>
                <tr>
                    <th class="transac-table-title">Description</th>
                    <th class="transac-table-title">Montant</th>
                    <th class="transac-table-title">Catégorie</th>
                    <th class="transac-table-title">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td class="transac-table-title"><?= htmlspecialchars($transaction['description']) ?></td>
                    <td class="transac-table-title"><?= htmlspecialchars($transaction['amount']) ?></td>
                    <td class="transac-table-title"><?= htmlspecialchars($transaction['categorie']) ?></td>
                    <td class="transac-table-title">
                        <a href="edit-transac.php?id=<?= $transaction['id'] ?>" class="action-btn edit-btn">Modifier</a>
                        <a href="delete-transac.php?id=<?= $transaction['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune transaction n'a été ajoutée pour le moment.</td>
                    </tr>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <div class="footer-links">
        <a href="#">Accueil</a>
        <a href="register.php">S'inscrire</a>
        <a href="signin.php">Se connecter</a>
        <a href="#">Mentions légales</a>
        <a href="#">Politiques de confidentialité</a>
    </div>
    <p>&copy; 2024 Budgi. Tous droits réservés</p>
</footer>

    <script src="script.js"></script>
    <script>
        const ctx = document.getElementById('spendingChart').getContext('2d');
        const categoryLabels = <?= json_encode(array_keys($categoryData)) ?>;
        const categoryValues = <?= json_encode(array_values($categoryData)) ?>;

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Dépenses par catégorie (€)',
                    data: categoryValues,
                    backgroundColor: [
                        '#9ee3d1', '#ffb3b3', '#acecdb', '#7bebcd', '#f1f1f1', '#c7e6dd'
                    ],
                    borderColor: '#0e1c36',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Répartition des dépenses',
                        font: {
                            family: 'Poppins',
                            size: 16,
                            weight: '600'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>