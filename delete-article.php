<?php
require_once __DIR__ . "/database/database.php";
$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";

if ($articleId) {
    $articleDB->deleteOne($articleId);
}
header("Location: /");
