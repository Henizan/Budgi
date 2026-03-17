<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

try {
    $conn = new PDO($dsn, $username, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user info
    $stmt = $conn->prepare("SELECT name, surname, email, budget_limit, current_budget FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $budget_limit = $_POST['budget_limit'];
        $current_budget = $_POST['current_budget'];
        $new_password = $_POST['new_password'];

        if (empty($name) || empty($surname) || empty($email)) {
            $error_msg = "Veuillez remplir les champs obligatoires.";
        } else {
            // Update basic info and budget
            $update_stmt = $conn->prepare("UPDATE users SET name = :name, surname = :surname, email = :email, budget_limit = :budget_limit, current_budget = :current_budget WHERE id = :id");
            $update_stmt->execute([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'budget_limit' => $budget_limit,
                'current_budget' => $current_budget,
                'id' => $user_id
            ]);

            // Update password if provided
            if (!empty($new_password)) {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $pass_stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $pass_stmt->execute(['password' => $password_hash, 'id' => $user_id]);
            }

            $success_msg = "Profil mis à jour avec succès !";
            
            // Refresh local user data
            $user['name'] = $name;
            $user['surname'] = $surname;
            $user['email'] = $email;
            $user['budget_limit'] = $budget_limit;
            $user['current_budget'] = $current_budget;
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
    <title>Mon Profil - Budgi</title>
</head>
<body>
    <nav>
        <a href="gestion.php">Tableau de bord</a>
        <a href="profile.php" style="color: #254888;">Profil</a>
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
            <h2>Mon Profil</h2>
            <?php if ($success_msg): ?>
                <p style="color: green;"><?= htmlspecialchars($success_msg) ?></p>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>

            <form action="profile.php" method="post" class="register_signin_form">
                <div class="name_form">
                    <div class="form-group">
                        <label for="surname">Prénom</label>
                        <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($user['surname']) ?>" class="form" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" class="form" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" class="form" required>
                </div>

                <div class="name_form">
                    <div class="form-group">
                        <label for="budget_limit">Limite de budget (€)</label>
                        <input type="number" step="0.01" name="budget_limit" id="budget_limit" value="<?= htmlspecialchars($user['budget_limit']) ?>" class="form" required>
                    </div>
                    <div class="form-group">
                        <label for="current_budget">Budget actuel (€)</label>
                        <input type="number" step="0.01" name="current_budget" id="current_budget" value="<?= htmlspecialchars($user['current_budget']) ?>" class="form" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe (laisser vide pour garder l'actuel)</label>
                    <input type="password" name="new_password" id="new_password" class="form" placeholder="********">
                </div>

                <input type="submit" value="Enregistrer les modifications" class="boutton register_signin" style="width: 100%; margin: 20px 0;">
            </form>
            
            <a href="gestion.php" style="text-decoration: none; color: #0e1c36; font-size: 0.9em;">← Retour au tableau de bord</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Budgi. Tous droits réservés</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
