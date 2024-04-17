<?php
$filename = __DIR__ . "/data/articles.json";
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";
$articles = [];

if (!$articleId) {
    // S'il n'y a pas d'id on redirige vers la page d'accueil
    header("Location: /");
} else {
    // Sinon on récupère les articles
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIndex = array_search($articleId, array_column($articles, "id"));
        $article = $articles[$articleIndex];
    }
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
                <div class="article-cover-img" style="background-image:url(<?= $article["image"]; ?>)"></div>
                <h1 class="article-title"><?= $article["title"]; ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article["content"]; ?></p>

                <div class="actions">
                    <a href="/form-article.php?id=<?= $article["id"]; ?>" class="btn btn-primary edit-btn">Éditer l'article</a>
                    <a href="/delete-article.php?id=<?= $article["id"]; ?>" class="btn btn-red delete-btn">Supprimer l'article</a>
                </div>
            </article>


        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src="./public/js/index.js"></script>
</body>

</html>