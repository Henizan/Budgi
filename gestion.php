<?php
session_start();
var_dump($_SESSION);

if(!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit;
}

$error_msg = "";
$servername = "localhost";
$port=3306;
$username = "root";
$dbpassword = "";
$dbname = "budgi_db";

try{
    $conn = new PDO("mysql:host=$servername;dbname=budgi_db", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $req = $conn->prepare("SELECT budget_limit, current_budget FROM users WHERE id = :user_id ");
  $req->execute(['user_id' => $_SESSION['user_id']]);
  $user = $req->fetch((PDO::FETCH_ASSOC));

  $budget_limit = $user['budget_limit'];
  $current_budget = $user['current_budget'];

   $stmt = $conn->prepare(
    "SELECT description, amount, categories
    FROM transactions 
    WHERE user_id = :user_id 
    ORDER BY date DESC"
   );
   $stmt->execute(['user_id' => $_SESSION['user_id']]);
  $transactions = $stmt->fetch((PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,700,1,200" />
    <link rel="stylesheet" href="style.css">
    <title>Accueil</title>
</head>
<body>
    <nav>
        <a href="#" style="color: #254888;">Tableau de bord</a>
        <a href="#" >Profil</a>
        <a href="index.html">Se déconnecter</a>
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
        <p>Limite dépense : <strong><?= htmlspecialchars($budget_limit) ?></strong>€</p>
        <p>Budget Actuel : <strong><?= htmlspecialchars($current_budget) ?></strong>€</p>

    </div>

    <div class="new-transac">
        <h3>Ajouter une nouvelle transaction</h3>
        <form action="new-transac.php" method="post">
            <label for="description">Description :</label>
            <input type="text" id="description" name="description" placeholder="Description..." class="form" required>
            <label for="montant">Montant :</label>
            <input type="number" step="0.01" id="amount" name="amount" placeholder="0.00€" class="form" required> 
            <label for="categorie">Catégorie :</label>
            <select type="text" id="categorie" name="categorie" placeholder="Categorie..." class="form">
                <option value="Nourriture">Nourriture</option>
                <option value="Loisirs">Loisirs</option>
                <option value="Transport">Transport</option>
                <option value="Santé">Santé</option>
                <option value="Étude">Étude</option>
                <option value="Autres">Autres</option>
             </select>
            <input type="submit" value="Ajouter la transaction" class="register_signin boutton" style="margin-left: 3.5vh;">
        </form>
    </div>
    <div class="transac-table">
        <h3>Vos dernière transactions</h3>
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
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['amount']) ?></td>
                    <td><?= htmlspecialchars($transaction['category']) ?></td>
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
</body>
</html>