<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Vul je e-mail en wachtwoord in.';
    } else {
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: overzicht.php');
            exit;
        } else {
            $error = 'E-mail of wachtwoord is niet goed.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Z - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Recipe Z</h1>
</header>

<main class="login-box">
    <div class="box">
        <h2>Login</h2>
        <p class="small">Log in om recepten te beheren.</p>

        <?php if (isset($_GET['registered'])): ?>
            <p class="message">Account gemaakt. Je kunt nu inloggen.</p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Inloggen</button>
        </form>

        <p class="small center">Nog geen account? <a href="register.php">Registreer hier</a>.</p>
    </div>
</main>
</body>
</html>
