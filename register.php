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
        <a href="#" style="color: #254888;">Accueil</a>
        <a href="register.html" >Créer un compte</a>
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
    <form action="register.php" method="post" class="register_signin_form">
        <div>
            <input type="text" id="fullname" placeholder="Nom Complet" class="form">
        </div>
        <div >
            <input type="email" id="email" placeholder="Email" class="form">
        </div>
        <div >
            <input type="password" id="password" placeholder="Mot de passe" class="form">
        </div>
        <div >
            <input type="password" id="password_confirm" placeholder="Confirmer votre mot de passe" class="form">
        </div>
        <div>
            <input type="submit" id="register" value="S'inscrire !" class="register_signin_button">
        </div>
    </form>
    <a href="signin.html"><p>j'ai déjà un compte, me connecter</p></a>
</div>
</div>



<footer>
    <div class="footer-links">
        <a href="index.html">Accueil</a>
        <a href="#">S'inscrire</a>
        <a href="signin.html">Se connecter</a>
        <a href="#">Mentions légales</a>
        <a href="#">Politiques de confidentialité</a>
    </div>
    <p>&copy; 2024 Budgi. Tous droits réservés</p>
</footer>

    <script src="script.js"></script>
</body>
</html>