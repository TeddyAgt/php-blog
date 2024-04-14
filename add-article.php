<?php
const ERROR_REQUIRED = "Veuillez renseigner ce champs";
const ERROR_TITLE_TOO_SHORT = "Le titre doit faire 8 caractères minimum";
const ERROR_CONTENT_TOO_SHORT = "L'article doit faire 50 caractères minimum";
const ERROR_IMAGE_URL = "L'URL de l'image n'est pas valide";
$filename = __DIR__ . "/data/articles.json";
$errors = [
    "title" => "",
    "image" => "",
    "category" => "",
    "content" => ""
];
$articles = [];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
    }

    $_POST = filter_input_array(INPUT_POST, [
        "title" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "image" => FILTER_SANITIZE_URL,
        "category" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "content" => [
            "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
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
        $articles = [...$articles, [
            "title" => $title,
            "image" => $image,
            "category" => $category,
            "content" => $content,
            "id" => time()
        ]];
        file_put_contents($filename, json_encode($articles));
        header("Location: /");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/add-article.css">
    <title>Écrire un article | Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <div class="block p-20 form-container">
                <h1>Écrire un article</h1>
                <form action="/add-article.php" role="form" method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value=<?= $title ?? "" ?>>
                        <?php if ($errors["title"]) : ?>
                            <p class="error-msg"><?= $errors["title"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" id="image" name="image" value=<?= $image ?? "" ?>>
                        <?php if ($errors["image"]) : ?>
                            <p class="error-msg"><?= $errors["image"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="category">Catégorie</label>
                        <select id="category" name="category">
                            <option value="lifestyle">Lifestyle</option>
                            <option value="technology">Technologie</option>
                            <option value="hobbies">Hobbies</option>
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
                        <button class="btn btn-primary" type="submit">Sauvegarder</button>
                    </div>
                </form>
            </div>

        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src="./public/js/index.js"></script>
</body>

</html>