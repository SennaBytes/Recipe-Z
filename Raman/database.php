<?php
// Simpele SQLite database. Wordt automatisch aangemaakt in de map data.
$dbFolder = __DIR__ . '/data';
if (!is_dir($dbFolder)) {
    mkdir($dbFolder, 0777, true);
}

$db = new PDO('sqlite:' . $dbFolder . '/recipez.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    category TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    preparation TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");
?>
