<?php
$pdo = require_once "./database/database.php";
$authDB = require_once "./database/security.php";

const ERROR_REQUIRED = "Veuillez renseigner ce champs";
const ERROR_TOO_SHORT = "Ce champs doit faire 3 caractères minimum";
const ERROR_PASSWORD_TOO_SHORT = "Le mot de passe doit faire au moins 6 caractères";
const ERROR_PASSWORD_MISMATCH = "Le mot de passe de confirmation ne correspond pas";
const ERROR_EMAIL_INVALID = "L'adresse mail n'est pas valide";

$errors = [
    "firstname" => "",
    "lastname" => "",
    "email" => "",
    "password" => "",
    "passwordConfirmation" => ""
];



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = filter_input_array(INPUT_POST, [
        "firstname" => FILTER_SANITIZE_SPECIAL_CHARS,
        "lastname" => FILTER_SANITIZE_SPECIAL_CHARS,
        "email" => FILTER_SANITIZE_EMAIL
    ]);
    $firstname = $input["firstname"] ?? "";
    $lastname = $input["lastname"] ?? "";
    $email = $input["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $passwordConfirmation = $_POST["passwordConfirmation"] ?? "";

    if (!$firstname) {
        $errors["firstname"] = ERROR_REQUIRED;
    } elseif (mb_strlen($firstname) < 3) {
        $errors["firstname"] = ERROR_TOO_SHORT;
    }

    if (!$lastname) {
        $errors["lastname"] = ERROR_REQUIRED;
    } elseif (mb_strlen($firstname) < 3) {
        $errors["lastname"] = ERROR_TOO_SHORT;
    }

    if (!$email) {
        $errors["email"] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = ERROR_EMAIL_INVALID;
    }

    if (!$password) {
        $errors["password"] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 6) {
        $errors["password"] = ERROR_PASSWORD_TOO_SHORT;
    }

    if (!$passwordConfirmation) {
        $errors["passwordConfirmation"] = ERROR_REQUIRED;
    } elseif ($passwordConfirmation !== $password) {
        $errors["passwordConfirmation"] = ERROR_PASSWORD_MISMATCH;
    }

    if (!count(array_filter($errors, fn ($e) => $e !== ""))) {
        $authDB->register([
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
        ]);

        header("Location: /auth-login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet" href="./public/css/index.css">
    <link rel="stylesheet" href="./public/css/auth-register.css">
    <title>Inscription | Blog</title>
</head>

<body>
    <div class="container">

        <?php require_once "./includes/header.php" ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1>Inscription</h1>
                <form action="/auth-register.php" role="form" method="POST">
                    <div class="form-control">
                        <label for="firstname">Prénom</label>
                        <input type="text" id="firstname" name="firstname" value="<?= $firstname ?? "" ?>">
                        <?php if ($errors["firstname"]) : ?>
                            <p class="error-msg"><?= $errors["firstname"] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-control">
                        <label for="lastname">Nom</label>
                        <input type="text" id="lastname" name="lastname" value="<?= $lastname ?? "" ?>">
                        <?php if ($errors["lastname"]) : ?>
                            <p class="error-msg"><?= $errors["lastname"] ?></p>
                        <?php endif; ?>
                    </div>
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
                    <div class="form-control">
                        <label for="password-confirmation">Confirmation de mot de passe</label>
                        <input type="password" id="password-confirmation" name="passwordConfirmation">
                        <?php if ($errors["passwordConfirmation"]) : ?>
                            <p class="error-msg"><?= $errors["passwordConfirmation"] ?></p>
                        <?php endif; ?>
                    </div>


                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">Valider</button>
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