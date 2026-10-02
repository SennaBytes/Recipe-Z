<?php
$recipe_z_db = new PDO('sqlite:' . __DIR__ . '/assets/databases/recipe-z.sqlite');
$recipe_z_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);