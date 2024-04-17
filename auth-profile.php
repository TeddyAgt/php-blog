<?php
require_once __DIR__ . "/database/database.php";
require_once __DIR__ . "/database/security.php";

$currentUser = isLoggedIn();

if (!$currentUser) {
    header("Location: /auth-login.php");
}

$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";
$articles = $articleDB->fetchUserArticles($currentUser["user_id"]) ?? [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/index.css">
    <link rel="stylesheet" href="./public/css/auth-profile.css">
    <title>Profil | Blog</title>
</head>

<body>
    <div class="container">

        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <h1>Mon espace</h1>

            <h2>Mes informations</h2>
            <div class="info-container">
                <ul>
                    <li>
                        <strong>Prénom:</strong>
                        <p><?= $currentUser["user_firstname"]; ?></p>
                    </li>
                    <li>
                        <strong>Nom:</strong>
                        <p><?= $currentUser["user_lastname"]; ?></p>
                    </li>
                    <li>
                        <strong>Email:</strong>
                        <p><?= $currentUser["user_email"]; ?></p>
                    </li>
                </ul>
            </div>
            <h2>Mes articles</h2>
            <ul class="articles-list">
                <?php foreach ($articles as $a) : ?>
                    <li>
                        <span><?= $a["article_title"]; ?></span>
                        <div class="article-actions">
                            <a href="/form-article.php?id=<?= $a["article_id"] ?>" class="btn btn-small btn-primary">Modifier</a>
                            <a href="/delete-article.php?id=<?= $a["article_id"] ?>" class="btn btn-small btn-red">Supprimer</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src=" ./public/js/index.js">
    </script>
</body>

</html>