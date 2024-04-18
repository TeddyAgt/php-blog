<?php
$pdo = require_once "./database/database.php";
$authDB = require_once "./database/security.php";

const ERROR_REQUIRED = "Veuillez renseigner ce champs";
const ERROR_PASSWORD_WRONG = "Le mot de passe est incorrect";
const ERROR_EMAIL_INVALID = "L'adresse mail n'est pas valide";
const ERROR_EMAIL_UNKOWN = "L'adresse mail est inconnue";

$errors = [
    "email" => "",
    "password" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = filter_input_array(INPUT_POST, [
        "email" => FILTER_SANITIZE_EMAIL
    ]);
    $firstname = $input["firstname"] ?? "";
    $lastname = $input["lastname"] ?? "";
    $email = $input["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $passwordConfirmation = $_POST["passwordConfirmation"] ?? "";


    if (!$email) {
        $errors["email"] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = ERROR_EMAIL_INVALID;
    }

    if (!$password) {
        $errors["password"] = ERROR_REQUIRED;
    }

    if (!count(array_filter($errors, fn ($e) => $e !== ""))) {

        $user = $authDB->getUserByEmail($email);

        if (!$user) {
            $errors["email"] = ERROR_EMAIL_UNKOWN;
        } else {
            if (!password_verify($password, $user["user_password"])) {
                $errors["password"] = ERROR_PASSWORD_WRONG;
            } else {
                $authDB->login($user["user_id"]);
                header("Location: /auth-profile.php");
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/index.css">
    <link rel="stylesheet" href="./public/css/auth-login.css">
    <title>Connexion | Blog</title>
</head>

<body>
    <div class="container">

        <?php require_once "./includes/header.php" ?>
        <div class="content">

            <div class="block p-20 form-container">
                <h1>Connexion</h1>
                <form action="/auth-login.php" role="form" method="POST">

                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?= $email ?? "" ?>">
                        <?php if ($errors["email"]) : ?>
                            <p class="error-msg"><?= $errors["email"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password">
                        <?php if ($errors["password"]) : ?>
                            <p class="error-msg"><?= $errors["password"] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">Connexion</button>
                    </div>
                </form>
            </div>



        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

    <script defer src=" ./public/js/index.js">
    </script>
</body>

</html>