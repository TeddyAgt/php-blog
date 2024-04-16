<?php
$filename = __DIR__ . "/data/articles.json";
$articles = [];
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCategory = $_GET["cat"] ?? "";

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
    $cattmp = array_map(fn ($a) => $a["category"], $articles);
    $categories = array_reduce($cattmp, function ($acc, $curr) {
        if (isset($acc[$curr])) {
            $acc[$curr]++;
        } else {
            $acc[$curr] = 1;
        }
        return $acc;
    }, []);

    $articlesPerCategories = array_reduce($articles, function ($acc, $article) {
        if (isset($acc[$article["category"]])) {
            $acc[$article["category"]] = [...$acc[$article["category"]], $article];
        } else {
            $acc[$article["category"]] = [$article];
        }
        return $acc;
    }, []);
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
            <div class="newsfeed-container">
                <aside>
                    <nav role="navigation" aria-label="filter by category">
                        <ul class="category-list">
                            <li class="category-list__category <?= $selectedCategory ? "" : "category-list__category--selected"; ?>">
                                <a href="/">Tous les articles <span class="small">(<?= count($articles) ?>)</span></a>
                            </li>
                            <?php foreach ($categories as $cat => $num) : ?>
                                <li class="category-list__category <?= $selectedCategory === $cat ? "category-list__category--selected" : ""; ?>">
                                    <a href="/?cat=<?= $cat ?>"><?= $cat ?> <span class="small">(<?= $num ?>)</span></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </aside>
                <main class="categories-container">
                    <?php if (!$selectedCategory) : ?>

                        <?php foreach ($categories as $cat => $num) : ?>
                            <h2><?= $cat ?></h2>

                            <div class="articles-container">
                                <?php foreach ($articlesPerCategories[$cat] as $a) : ?>
                                    <article class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style="background-image:url(<?= $a["image"] ?>)"></div>
                                        </div>
                                        <h3><?= $a["title"]; ?></h3>

                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                    <?php else : ?>

                        <h2><?= $selectedCategory ?></h2>

                        <div class="articles-container">
                            <?php foreach ($articlesPerCategories[$selectedCategory] as $a) : ?>
                                <article class="article block">
                                    <div class="overflow">
                                        <div class="img-container" style="background-image:url(<?= $a["image"] ?>)"></div>
                                    </div>
                                    <h3><?= $a["title"]; ?></h3>

                                </article>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>

                </main>

            </div>


        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src=" ./public/js/index.js">
    </script>
</body>

</html>