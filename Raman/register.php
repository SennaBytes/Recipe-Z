<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Vul alle velden in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Vul een geldig e-mailadres in.';
    } else {
        $check = $db->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = 'Dit e-mailadres bestaat al.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hashedPassword]);

            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Z - Registreren</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Recipe Z</h1>
</header>

<main class="login-box">
    <div class="box">
        <h2>Registreren</h2>
        <p class="small">Maak een account om recepten toe te voegen.</p>

        <?php if (!empty($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Account maken</button>
        </form>

        <p class="small center">Heb je al een account? <a href="login.php">Log hier in</a>.</p>
    </div>
</main>
</body>
</html>
