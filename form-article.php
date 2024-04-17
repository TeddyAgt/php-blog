<?php
require_once __DIR__ . "/database/database.php";
require_once __DIR__ . "/database/security.php";

$currentUser = isLoggedIn();

if (!$currentUser) {
    header("Location: /auth-login.php");
}

$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";
const ERROR_REQUIRED = "Veuillez renseigner ce champs";
const ERROR_TITLE_TOO_SHORT = "Le titre doit faire 8 caractères minimum";
const ERROR_CONTENT_TOO_SHORT = "L'article doit faire 50 caractères minimum";
const ERROR_IMAGE_URL = "L'URL de l'image n'est pas valide";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";
$filename = __DIR__ . "/data/articles.json";
$category = "";
$errors = [
    "title" => "",
    "image" => "",
    "category" => "",
    "content" => ""
];

if ($articleId) {
    $article = $articleDB->fetchOne($articleId);
    if ($article["article_author"] !== $currentUser["user_id"]) {
        header("Location: /");
    }
    $title = $article['article_title'];
    $image = $article['article_image'];
    $category = $article['article_category'];
    $content = $article['article_content'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $_POST = filter_input_array(INPUT_POST, [
        "title" => FILTER_SANITIZE_SPECIAL_CHARS,
        "image" => FILTER_SANITIZE_URL,
        "category" => FILTER_SANITIZE_SPECIAL_CHARS,
        "content" => [
            "filter" => FILTER_SANITIZE_SPECIAL_CHARS,
            "flags" => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $title = $_POST["title"] ?? "";
    $image = $_POST["image"] ?? "";
    $category = $_POST["category"] ?? "";
    $content = $_POST["content"] ?? "";

    if (!$title) {
        $errors["title"] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 8) {
        $errors["title"] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors["image"] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors["image"] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors["category"] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors["content"] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 50) {
        $errors["content"] = ERROR_CONTENT_TOO_SHORT;
    }

    if (!count(array_filter($errors, fn ($e) => $e !== ""))) {

        if ($articleId) {
            $article["article_title"] = $title;
            $article["article_image"] = $image;
            $article["article_category"] = $category;
            $article["article_content"] = $content;
            $articleDB->updateOne($article);
        } else {
            $articleDB->createOne([
                "article_title" => $title,
                "article_image" => $image,
                "article_category" => $category,
                "article_content" => $content,
                "article_author" => $currentUser["user_id"]
            ]);
        }
        header("Location: /");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <title><?= $articleId ? "Éditer" : "Écrire"; ?> un article | Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <div class="block p-20 form-container">
                <h1><?= $articleId ? "Éditer" : "Écrire"; ?> un article</h1>

                <form action="/form-article.php<?= $articleId ? "?id=$articleId" : ""; ?>" role="form" method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?= $title ?? "" ?>">
                        <?php if ($errors["title"]) : ?>
                            <p class="error-msg"><?= $errors["title"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" id="image" name="image" value="<?= $image ?? "" ?>">
                        <?php if ($errors["image"]) : ?>
                            <p class="error-msg"><?= $errors["image"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="category">Catégorie</label>
                        <select id="category" name="category">
                            <option <?= !$category || $category === 'lifestyle' ? "selected" : ""; ?> value="lifestyle">Lifestyle</option>
                            <option <?= $category === 'technology' ? "selected" : ""; ?> value="technology">Technologie</option>
                            <option <?= $category === 'hobbies' ? "selected" : ""; ?> value="hobbies">Hobbies</option>
                        </select>
                        <?php if ($errors["category"]) : ?>
                            <p class="error-msg"><?= $errors["category"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="content">Article</label>
                        <textarea name="content" id="content"><?= $content ?? "" ?></textarea>
                        <?php if ($errors["content"]) : ?>
                            <p class="error-msg"><?= $errors["content"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit"><?= $articleId ? "Modifier" : "Créer"; ?></button>
                    </div>
                </form>
            </div>

        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src="./public/js/index.js"></script>
</body>

</html>