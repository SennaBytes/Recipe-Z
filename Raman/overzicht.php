<?php
session_start();
require 'database.php';

$stmt = $db->query('SELECT recipes.*, users.name AS user_name FROM recipes JOIN users ON users.id = recipes.user_id ORDER BY recipes.id DESC');
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Z - Overzicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Recipe Z</h1>
    <nav>
        <a href="overzicht.php">Overzicht</a>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="invoer.php">Recept toevoegen</a>
            <a href="logout.php">Uitloggen</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Registreren</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <div class="box">
        <h2>Recepten overzicht</h2>
        <p class="small">Bekijk en zoek recepten.</p>

        <?php if (!empty($_SESSION['user_name'])): ?>
            <p>Ingelogd als <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>.</p>
        <?php endif; ?>

        <div class="filters">
            <input type="text" id="search" placeholder="Zoek een recept...">
            <select id="category">
                <option value="">Alle categorieën</option>
                <option value="Ontbijt">Ontbijt</option>
                <option value="Lunch">Lunch</option>
                <option value="Diner">Diner</option>
                <option value="Voorgerecht">Voorgerecht</option>
                <option value="Hoofdgerecht">Hoofdgerecht</option>
                <option value="Nagerecht">Nagerecht</option>
            </select>
        </div>

        <div class="recipes" id="recipeList">
            <?php if (empty($recipes)): ?>
                <p>Nog geen recepten toegevoegd.</p>
            <?php endif; ?>

            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card" data-title="<?= htmlspecialchars(strtolower($recipe['title'])) ?>" data-category="<?= htmlspecialchars($recipe['category']) ?>">
                    <h3><?= htmlspecialchars($recipe['title']) ?></h3>
                    <p><strong>Categorie:</strong> <?= htmlspecialchars($recipe['category']) ?></p>
                    <p><strong>Ingrediënten:</strong><br><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
                    <p><strong>Bereiding:</strong><br><?= nl2br(htmlspecialchars($recipe['preparation'])) ?></p>
                    <p class="small">Toegevoegd door <?= htmlspecialchars($recipe['user_name']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
const search = document.getElementById('search');
const category = document.getElementById('category');

function filterRecipes() {
    const cards = document.querySelectorAll('.recipe-card');
    const text = search.value.toLowerCase();
    const selectedCategory = category.value;

    cards.forEach(card => {
        const title = card.dataset.title;
        const cardCategory = card.dataset.category;
        const matchesText = title.includes(text);
        const matchesCategory = selectedCategory === '' || cardCategory === selectedCategory;
        card.style.display = matchesText && matchesCategory ? 'block' : 'none';
    });
}

search.addEventListener('input', filterRecipes);
category.addEventListener('change', filterRecipes);
</script>
</body>
</html>
