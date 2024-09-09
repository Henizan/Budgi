<?php
$error_msg = "";
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "budgi_db";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['password_confirm'];

     if (empty($name)){
        $error_msg = "Veuillez entrer votre nom";
    } elseif (empty($surname)){
        $error_msg = "Veuillez entrer votre prénom";
    } elseif (empty($email)){
        $error_msg = "Veuillez entrer votre email";
    }elseif (empty($password)){
        $error_msg = "Veuillez entrer votre mot de passe";
    }elseif(empty($confirm_password)){
        $error_msg = "Veuillez confirmer votre mot de passe";
    } elseif ($password !== $confirm_password){
        $error_msg = "les mots de passe ne correspondent pas !";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn = new PDO("mysql:host=$servername;dbname=budgi_db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $req = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $req->execute(['email' => $email]);
            $rep = $req->fetch(PDO::FETCH_ASSOC);

            if ($rep) {
                $error_msg = "Cet email est déjà utilisé.";
            } else {
                $req = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
                $req->execute(
                    [
                        "name" => $name,
                        "surname" => $surname,
                        "email" => $email,
                        "password" => $hased_password
                    ]
                );
                header("Location: signin.php");
                exit;

            }


        } catch (PDOException $e) {
            $error_msg = "Erreur lors de l'inscription :" . $e->getMessage();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,700,1,200" />
    <link rel="stylesheet" href="style.css">
    <title>Accueil</title>
</head>

<body>
    <nav>
        <a href="#">Accueil</a>
        <a href="register.html" style="color: #254888;">Créer un compte</a>
        <a href="signin.html">Se connecter</a>
    </nav>
    <button type="button" aria-label="toggle curtain navigation" class="nav-toggler">
        <span class="line l1"></span>
        <span class="line l2"></span>
        <span class="line l3"></span>
    </button>
    <a href="index.html"><img src="images/budji transp.png" alt="logo" class="logo1"></a>
    <div class="titre">

        <div class="register_signin_phrase_logo">

            <h1><span>Budgi</span></h1>
            <p>Avec Budgi, gérez facilement et totalement gratuitement votre budget</p>
        </div>
        <div class="register_box">
            <h2>Inscription à Budgi</h2>
            <p>C'est simple et rapide !</p>

            <?php if (!empty($error_msg)): ?>
                <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>

            <form action="register.php" method="post" class="register_signin_form">
                <div class="name_form">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" placeholder="Entrez votre nom..." class="form"
                            >
                    </div>
                    <div class="form-group">
                        <label for="name">Prénom</label>
                        <input type="text" name="surname" id="surname" placeholder="Entrez votre prénom..." class="form"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="surname">Email</label>
                    <input type="email" name="email" id="email" placeholder="Entrez votre email..." class="form"
                        >
                </div>
                <div class="form-group">
                    <label for="surname">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe..."
                        class="form" >
                </div>
                <div class="form-group">
                    <label for="surname">Confirmez le Mot de passe</label>
                    <input type="password" name="password_confirm" id="password"
                        placeholder="Confirmez votre mot de passe..." class="form" >
                </div>
                <div>
                    <input type="submit" name="submit" value="Entrer !" class="register_signin_button">
                </div>
            </form>
            <a href="signin.php">
                <p>j'ai déjà un compte, me connecter</p>
            </a>
        </div>
    </div>



    <footer>
        <div class="footer-links">
            <a href="index.html">Accueil</a>
            <a href="#">S'inscrire</a>
            <a href="signin.php">Se connecter</a>
            <a href="#">Mentions légales</a>
            <a href="#">Politiques de confidentialité</a>
        </div>
        <p>&copy; 2024 Budgi. Tous droits réservés</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>