<?php
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";
$filename = __DIR__ . "/data/articles.json";

if (!$articleId) {
    header("Location: /");
} else {
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIndex = array_search($articleId, array_column($articles, "id"));
        array_splice($articles, $articleIndex, 1);
        file_put_contents($filename, json_encode($articles));
        header("Location: /");
    }
}
