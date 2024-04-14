<?php
$filename = __DIR__ . "/data/articles.json";
$articles = [];

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/index.css">
    <title>Accueil | Blog</title>
</head>

<body>
    <div class="container">

        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <main class="articles-container">
                <?php foreach ($articles as $a) : ?>
                    <article class="article block">
                        <div class="overflow">
                            <div class="img-container" style="background-image:url(<?= $a["image"] ?>)"></div>
                        </div>
                        <h2><?= $a["title"]; ?></h2>

                    </article>
                <?php endforeach; ?>
            </main>

        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src=" ./public/js/index.js">
    </script>
</body>

</html>