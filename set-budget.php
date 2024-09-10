<?php
session_start();
var_dump($_SESSION);

$error_msg = "";

$error_msg = "";
$servername = "localhost";
$port=3306;
$username = "root";
$dbpassword = "";
$dbname = "budgi_db";


if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $budget_limit = $_POST['budget_limit'];
    $current_budget = $_POST['current_budget'];

    if (empty($budget_limit)){
        $error_msg = "Veuilez indiquer votre limite de budget.";
    }elseif(empty($current_budget)){
        $error_msg = "Veuillez indiquer votre budget actuel. ";
    }else{
        try {
            $conn = new PDO("mysql:host=$servername;port=$port;dbname=budgi_db", $username, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if(isset($_SESSION['user_id'])){

            $req = $conn ->prepare("UPDATE users SET budget_limit = :budget_limit, current_budget = :current_budget, budget_setup_complete = 1 WHERE id = :user_id");
            $req->execute([
                'budget_limit' => $budget_limit,
                'current_budget' => $current_budget,
                'user_id' => $_SESSION['user_id']
                
            ]);
            header("location: gestion.php");
            exit;
    } else {
        $error_msg = "Vous devez être connecté pour définir un budget";
    }

} catch (PDOException $e) {
    $error_msg = "Erreur lors de la connexion à la bdd :" . $e->getMessage();


}
}
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
    <title>Mise en place du budget</title>
</head>
<body>
    <nav>
        <a href="#" style="color: #254888;">Accueil</a>
        <a href="register.php" >Créer un compte</a>
        <a href="signin.php">Se connecter</a>
    </nav>
    <button type="button" aria-label="toggle curtain navigation" class="nav-toggler">
        <span class="line l1"></span>
        <span class="line l2"></span>
        <span class="line l3"></span>
    </button>
<a href="index.html"><img src="images/budji transp.png" alt="logo" class="logo1"></a>
    <div class="titre">
        <h2>Commençons par définir votre budget...</h2>
    </div>
    <div class="gestion-page">
    <div class="register_box">
    <?php if (!empty($error_msg)): ?>
                <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>
        <form action="set-budget.php" method="post" class="register_signin_form">
            <div class="form-group">
                <label for="budget_limit">Limite de Dépenses</label>
                <input type="number" name="budget_limit" id="budget_limit" placeholder="Entrez votre limite de dépenses..." class="form budget-setup">
                <label for="budget_current">Budget Actuel</label>
                <input type="number" name="current_budget" id="current_budget" placeholder="Entrez votre budget actuel..." class="form">
                <input type="submit" name="submit" value="Définir le Budget !" class="budget boutton">
            </div>
        </form>
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