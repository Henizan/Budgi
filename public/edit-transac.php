<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: gestion.php");
    exit;
}

$transaction_id = $_GET['id'] ?? $_POST['id'];
$user_id = $_SESSION['user_id'];
$error_msg = "";

try {
    $conn = new PDO($dsn, $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch existing transaction
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
        header("Location: gestion.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'];
        $amount = $_POST['amount'];
        $categorie = $_POST['categorie'];
        $old_amount = $transaction['amount'];

        if (empty($description) || empty($amount)) {
            $error_msg = "Veuillez remplir tous les champs.";
        } else {
            // Start transaction
            $conn->beginTransaction();

            // Update transaction
            $updateStmt = $conn->prepare("UPDATE transactions SET description = :description, amount = :amount, categorie = :categorie WHERE id = :id");
            $updateStmt->execute([
                'description' => $description,
                'amount' => $amount,
                'categorie' => $categorie,
                'id' => $transaction_id
            ]);

            // Adjust budget: refund old amount and deduct new amount
            $budgetAdjustment = $old_amount - $amount;
            if ($budgetAdjustment != 0) {
                $adjustStmt = $conn->prepare("UPDATE users SET current_budget = current_budget + :adjustment WHERE id = :user_id");
                $adjustStmt->execute(['adjustment' => $budgetAdjustment, 'user_id' => $user_id]);
            }

            $conn->commit();
            header("Location: gestion.php");
            exit;
        }
    }

} catch (PDOException $e) {
    $error_msg = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=5">
    <title>Modifier la transaction</title>
</head>
<body>
    <nav>
        <a href="gestion.php">Tableau de bord</a>
        <a href="profile.php">Profil</a>
        <a href="logout.php">Se déconnecter</a>
    </nav>
    <button type="button" aria-label="toggle curtain navigation" class="nav-toggler">
        <span class="line l1"></span>
        <span class="line l2"></span>
        <span class="line l3"></span>
    </button>
    <a href="gestion.php"><img src="images/budji transp.png" alt="logo" class="logo1"></a>
    
    <div class="titre">
        <div class="register_box">
            <h2>Modifier la transaction</h2>
            <?php if ($error_msg): ?>
                <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>
            <form action="edit-transac.php" method="post" class="register_signin_form">
                <input type="hidden" name="id" value="<?= htmlspecialchars($transaction_id) ?>">
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" value="<?= htmlspecialchars($transaction['description']) ?>" class="form" required>
                </div>
                
                <div class="form-group">
                    <label for="amount">Montant (€)</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($transaction['amount']) ?>" class="form" required>
                </div>
                
                <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <select id="categorie" name="categorie" class="form">
                        <?php 
                        $categories = ["Nourriture", "Loisirs", "Transport", "Santé", "Études", "Autres"];
                        foreach ($categories as $cat) {
                            $selected = ($transaction['categorie'] == $cat) ? "selected" : "";
                            echo "<option value=\"$cat\" $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <input type="submit" value="Enregistrer" class="boutton" style="flex: 1;">
                    <a href="gestion.php" class="boutton" style="flex: 1; text-decoration: none; text-align: center; background-color: #ffb3b3; color: #721c24; border-color: #721c24;">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Budgi. Tous droits réservés</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
