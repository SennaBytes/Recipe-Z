<?php
session_start();
require 'database.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $preparation = trim($_POST['preparation'] ?? '');

    if ($title !== '' && $category !== '' && $ingredients !== '' && $preparation !== '') {
        $stmt = $db->prepare('INSERT INTO recipes (user_id, title, category, ingredients, preparation) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['user_id'],
            $title,
            $category,
            $ingredients,
            $preparation
        ]);
        $message = 'Recept is opgeslagen in de database.';
    } else {
        $error = 'Vul alle velden in.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Z - Invoer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Recipe Z</h1>
    <nav>
        <a href="overzicht.php">Overzicht</a>
        <a href="invoer.php">Recept toevoegen</a>
        <a href="logout.php">Uitloggen</a>
    </nav>
</header>

<main>
    <div class="box">
        <h2>Recept invoeren</h2>
        <p class="small">Voeg een nieuw recept toe.</p>

        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="title">Naam recept</label>
            <input type="text" id="title" name="title" required>

            <label for="category">Categorie</label>
            <select id="category" name="category" required>
                <option value="">Kies een categorie</option>
                <option value="Ontbijt">Ontbijt</option>
                <option value="Lunch">Lunch</option>
                <option value="Diner">Diner</option>
                <option value="Voorgerecht">Voorgerecht</option>
                <option value="Hoofdgerecht">Hoofdgerecht</option>
                <option value="Nagerecht">Nagerecht</option>
            </select>

            <label for="ingredients">Ingrediënten</label>
            <textarea id="ingredients" name="ingredients" placeholder="Bijvoorbeeld: pasta, tomaat, pesto..." required></textarea>

            <label for="preparation">Bereidingswijze</label>
            <textarea id="preparation" name="preparation" placeholder="Leg kort uit hoe je het recept maakt." required></textarea>

            <button type="submit">Recept toevoegen</button>
        </form>
    </div>
</main>
</body>
</html>
