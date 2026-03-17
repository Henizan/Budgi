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
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $user_id = $_SESSION['user_id'];
        $categorie = $_POST['categorie'];

        $req = $conn->prepare("INSERT INTO transactions (user_id, amount, description, categorie) VALUES (:user_id, :amount, :description, :categorie)");
        $req->execute([
            'user_id' => $user_id,
            'amount' => $amount,
            'description' => $description,
            'categorie' => $categorie
        ]);

        $nouvreq = $conn->prepare("UPDATE users SET current_budget = current_budget - :amount WHERE id = :user_id");
        $nouvreq->execute(([
            'amount' => $amount,
            'user_id' => $user_id
        ]));

        header("Location: gestion.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}



?>