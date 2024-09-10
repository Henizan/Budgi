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

try {
    $conn = new PDO("mysql:host=$servername;dbname=budgi_db", $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
        $amount = $_POST['amount'];
        $descritption = $_POST['description'];
        $user_id = $_SESSION['user_id'];

        $req = $conn->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (:user_id, :amount, :description)");
        $req->execute([
            'user_id' => $user_id,
            'amount' => $amount,
            'description' => $descritption
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