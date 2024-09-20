<?php

session_start();

                $error_msg= "";
                $servername = "localhost";
                $username = "root";
                $dbpassword = "";
                $dbname = "budgi_db";
                
                
                try{
                  $conn = new PDO("mysql:host=$servername;dbname=budgi_db", $username, $dbpassword);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
                catch(PDOException $e){
                    echo "Erreur :".$e->getMessage();
                }

                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $email = $_POST['email'];
                    $password = $_POST['password'];


                    if (!empty($email) && !empty($password)) {

                        $req = $conn->prepare("SELECT * FROM users WHERE email = :email");
                        $req->execute(['email' => $email]);
                        $rep = $req->fetch(PDO::FETCH_ASSOC);

                        if ($rep) {

                        if(password_verify($password, $rep['password'])){
                            $_SESSION['user_id'] = $rep['id'];
                            if($rep['budget_setup_complete']==0){
                            header("location: set-budget.php");
                        }else{
                            header("location: gestion.php");
                        }

}
                    else{
                            $error_msg = "Mot de passe incorrect. ";
                        }
                    } else {
                        $error_msg = "Aucun utilisateur trouvé avec cet email";
                    }
                } else {
                    $error_msg = "Veuillez remplir tous les champs";
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
    <title>Accueil</title>
</head>
<body>
    <nav>
        <a href="#" >Accueil</a>
        <a href="register.php" >Créer un compte</a>
        <a href="signin.php" style="color: #254888;">Se connecter</a>
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
                <h2>Se connecter à Budgi</h2>
                <p>C'est simple et rapide !</p>


                <?php if (!empty($error_msg)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
           <?php endif; ?>
                

                <form action="signin.php" method="post" class="register_signin_form">
                    <div class="form-group">
                        <label for="surname">Email</label>
                        <input type="email" name="email" id="email" placeholder="Entrez votre email..." class="form register-form">
                    </div>
                    <div class="form-group">
                        <label for="surname">Mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe..."
                            class="form register-form">
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Entrer !" class="register_signin boutton">
                    </div>
                </form>
                <a href="#">
                    <p>mot de passe oublié</p>
                </a>
                <a href="register.php">
                    <p>créer un compte</p>
                </a>
            </div>
        </div>
    
            <footer>
                <div class="footer-links">
                    <a href="index.html">Accueil</a>
                    <a href="register.html">S'inscrire</a>
                    <a href="#">Se connecter</a>
                    <a href="#">Mentions légales</a>
                    <a href="#">Politiques de confidentialité</a>
                </div>
                <p>&copy; 2024 Budgi. Tous droits réservés</p>
            </footer>

    <script src="script.js"></script>
</body>
</html>