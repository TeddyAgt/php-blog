<?php
$pdo = require_once "./database.php";
$statement = $pdo->prepare("DELETE FROM article WHERE article_id=:articleId");
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";

if ($articleId) {
    $statement->bindvalue(":articleId", $articleId);
    $statement->execute();
}
header("Location: /");
