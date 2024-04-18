<?php
// $user = getenv("DB_USER");
// $pwd = getenv("DB_PWD");

$dns = "mysql:host=localhost;dbname=teddy_blog";
$user = "root";
$pwd = "Octopus!127"; // Pas besoin de le masquer, il sert uniquement en test en local


try {
    $pdo = new PDO($dns, $user, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

return $pdo;
