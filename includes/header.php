<?php
$currentUser = $currentUser ?? false;
?>

<header>
    <a href="/" class="logo">Teddy Blog</a>
    <button class="mobile-menu-toggler" aria-label="Ouvrir le menu de navigation" aria-expanded="false" aria-controls="main-navigation">
        <i class="fa-solid fa-bars"></i>
    </button>
    <nav class="main-navigation" role="navigation" aria-label="Navigation principale" id="main-navigation">
        <?php if ($currentUser) : ?>
            <a href="/auth-profile.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-profile.php" ? "active" : ""; ?>>Ma page</a>
            <a href="/form-article.php" class=<?= $_SERVER['REQUEST_URI'] === "/form-article.php" ? "active" : ""; ?>>Écrire un article</a>
            <a href="/auth-logout.php">Déconnexion</a>
        <?php else : ?>
            <a href="/auth-login.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-login.php" ? "active" : ""; ?>>Connexion</a>
            <a href="/auth-register.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-register.php" ? "active" : ""; ?>>Inscription</a>
        <?php endif; ?>
    </nav>
</header>