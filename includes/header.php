<?php
session_start();
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Order.php';

$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportShop - Matériel de Sport</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/index.php">SportShop</a>
            </div>
            <ul>
                <li><a href="/index.php">Accueil</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="/profile.php">Mon Compte</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="/admin/index.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/login.php">Connexion</a></li>
                    <li><a href="/register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
