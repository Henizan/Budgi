<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budgi_db";


try{
  $conn = new PDO("mysql:host=$servername;dbname=budgi_db", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "connexion réussie !";
}
catch(PDOException $e){
    echo "Erreur :".$e->getMessage();
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $surname =$_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['password_confirm'];

    if ($password!==$confirm_password){
        echo "les mots de passe ne correspondent pas !";
        exit;
    }

    $requete = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
    $requete->execute([
            "name" => $name,
            "surname" => $surname,
            "email" => $email,
            "password" => $password]
        );
        echo "Inscription réussie";
        header("Location: signin.html");
        exit;
}
?>