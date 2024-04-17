<?php
require_once __DIR__ . "/database/database.php";
require_once __DIR__ . "/database/security.php";

$currentUser = isLoggedIn();

$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";

if (!$articleId) {
    // S'il n'y a pas d'id on redirige vers la page d'accueil
    header("Location: /");
} else {
    $article = $articleDB->fetchOne($articleId);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/show-article.css">
    <title>Article | Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <article class="article-container">
                <a class="back-index" href="/">Retour à la liste des articles</a>
                <div class="article-cover-img" style="background-image:url(<?= $article["article_image"]; ?>)"></div>
                <h1 class="article-title"><?= $article["article_title"]; ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article["article_content"]; ?></p>

                <?php if ($currentUser && $currentUser["user_id"] === $article["article_author"]) : ?>
                    <div class="actions">
                        <a href="/form-article.php?id=<?= $article["article_id"]; ?>" class="btn btn-primary edit-btn">Éditer l'article</a>
                        <a href="/delete-article.php?id=<?= $article["article_id"]; ?>" class="btn btn-red delete-btn">Supprimer l'article</a>
                    </div>
                <?php endif; ?>
            </article>


        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src="./public/js/index.js"></script>
</body>

</html>