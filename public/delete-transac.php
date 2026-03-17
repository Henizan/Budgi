<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: gestion.php");
    exit;
}

$transaction_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    $conn = new PDO($dsn, $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get transaction details and verify ownership
    $stmt = $conn->prepare("SELECT amount FROM transactions WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        $amount = $transaction['amount'];

        // Start transaction
        $conn->beginTransaction();

        // Delete the transaction
        $deleteStmt = $conn->prepare("DELETE FROM transactions WHERE id = :id");
        $deleteStmt->execute(['id' => $transaction_id]);

        // Refund the amount to the user's current budget
        $updateStmt = $conn->prepare("UPDATE users SET current_budget = current_budget + :amount WHERE id = :user_id");
        $updateStmt->execute(['amount' => $amount, 'user_id' => $user_id]);

        $conn->commit();
    }

    header("Location: gestion.php");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
