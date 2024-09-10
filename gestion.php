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
<a href="gestion.html"><img src="images/budji transp.png" alt="logo" class="logo1"></a>
<div class="titre">
    <h1>Tableau de bord</h1></div>
    <div class="gestion-page">
    <div class="contenu">
        <h3>Votre budget du mois</h3>
        <p>Budget Actuel : <strong><?= htmlspecialchars($budget_limit) ?></strong>€</p>
        <p>Dépenses totales : <strong><?= htmlspecialchars($current_budget) ?></strong>€</p>
    </div>

    <div class="new-transac">
        <h3>Ajouter une nouvelle transaction</h3>
        <form action="new-transac.php" method="post">
            <label for="description">Description :</label>
            <input type="text" id="description" name="description" placeholder="Description..." class="form" required>
            <label for="montant">Montant :</label>
            <input type="number" step="0.01" id="montant" name="montant" placeholder="0.00€" class="form" required> 
            <label for="categorie">Catégorie :</label>
            <input type="text" id="categorie" name="categorie" placeholder="Categorie..." class="form">
            <label for="date">Date de la transaction :</label>
            <input type="datetime-local" id="date" name="date" required class="form">
            <input type="submit" value="Ajouter la transaction" class="register_signin boutton" style="margin-left: 3.5vh;">
        </form>
    </div>
    <div class="transac-table">
        <h3>Vos dernière transactions</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Montant</th>
                    <th>Catégorie</th>
                    <th>Date et heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="transactions">

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