<header>
    <a href="/" class="logo">Teddy Blog</a>
    <nav class="main-navigation" role="navigation" aria-label="Navigation principale">
        <a href="/auth-register.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-register.php" ? "active" : ""; ?>>Inscription</a>
        <a href="/auth-login.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-login.php" ? "active" : ""; ?>>Connexion</a>
        <a href="/auth-profile.php" class=<?= $_SERVER['REQUEST_URI'] === "/auth-profile.php" ? "active" : ""; ?>>Ma page</a>
        <a href="/auth-logout.php">Déconnexion</a>
        <a href="/form-article.php" class=<?= $_SERVER['REQUEST_URI'] === "/form-article.php" ? "active" : ""; ?>>Écrire un article</a>
    </nav>
</header>