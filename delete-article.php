<?php
require_once __DIR__ . "/database/database.php";
require_once __DIR__ . "/database/security.php";

$currentUser = isLoggedIn();

if ($currentUser) {
    $articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $articleId = $_GET["id"] ?? "";

    if ($articleId) {
        $article = $articleDB->fetchOne($articleId);
        if ($article["article_author"] == $currentUser["user_id"]) {
            $articleDB->deleteOne($articleId);
        }
    }
}


header("Location: /");
