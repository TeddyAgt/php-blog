<!-- script pour seed les articles test dans la BDD -->
<?php
$dns = "mysql:host=localhost;dbname=teddy_blog";
$user = "root";
$pwd = "Octopus!127"; // Pas besoin de le masquer, il sert uniquement en test en local
$articles = json_decode(file_get_contents("./articles.json"), true);

$pdo = new PDO($dns, $user, $pwd);
$statement = $pdo->prepare("INSERT INTO article (article_title, article_image, article_category, article_content) VALUES (:title, :image, :category, :content)");

foreach ($articles as $article) {
    $statement->bindValue(":title", $article["title"]);
    $statement->bindValue(":image", $article["image"]);
    $statement->bindValue(":category", $article["category"]);
    $statement->bindValue(":content", $article["content"]);
    $statement->execute();
}
