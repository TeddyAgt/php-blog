<?php

class AuthDB
{
    private PDOStatement $statementRegister;
    private PDOStatement $statementReadSession;
    private PDOStatement $statementReadUserById;
    private PDOStatement $statementReadUserByEmail;
    private PDOStatement $statementCreateSession;
    private PDOStatement $statementDeleteSession;

    function __construct(private PDO $pdo)
    {
        $this->statementRegister = $pdo->prepare("INSERT INTO user VALUES (
            DEFAULT,
            :firstname,
            :lastname,
            :email,
            :password
        )");

        $this->statementReadSession = $pdo->prepare("SELECT * FROM session WHERE session_id=:sessionId");

        $this->statementReadUserById = $pdo->prepare("SELECT * FROM user WHERE user_id=:userId");

        $this->statementReadUserByEmail = $pdo->prepare("SELECT * FROM user WHERE user_email=:email");

        $this->statementCreateSession = $pdo->prepare("INSERT INTO session VALUES (
            DEFAULT,
            :user_id
        )");

        $this->statementDeleteSession = $pdo->prepare("DELETE FROM session WHERE session_id=:sessionId");
    }

    function register(array $user): void
    {

        $hashedPassword = password_hash($user["password"], PASSWORD_ARGON2I);
        $this->statementRegister->bindValue(":firstname", $user["firstname"]);
        $this->statementRegister->bindValue(":lastname", $user["lastname"]);
        $this->statementRegister->bindValue(":email", $user["email"]);
        $this->statementRegister->bindValue(":password", $hashedPassword);
        $this->statementRegister->execute();
        return;
    }

    function getUserByEmail(string $email): array
    {
        $this->statementReadUserByEmail->bindValue(":email", $email);
        $this->statementReadUserByEmail->execute();
        return $this->statementReadUserByEmail->fetch();
    }

    function login(string $userId): void
    {
        $this->statementCreateSession->bindValue("user_id", $userId);
        $this->statementCreateSession->execute();
        $sessionId = $this->pdo->lastInsertId();
        setcookie("session", $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);
        return;
    }

    function isLoggedIn(): array | false
    {
        $sessionId = $_COOKIE["session"] ?? "";

        if ($sessionId) {
            $this->statementReadSession->bindValue(":sessionId", $sessionId);
            $this->statementReadSession->execute();
            $session = $this->statementReadSession->fetch();

            if ($session) {
                $this->statementReadUserById->bindValue(":userId", $session["session_user_id"]);
                $this->statementReadUserById->execute();
                $user = $this->statementReadUserById->fetch();
            }
        }

        return $user ?? false;
    }

    function logout(string $sessionId): void
    {
        $this->statementDeleteSession->bindValue(":sessionId", $sessionId);
        $this->statementDeleteSession->execute();
        setcookie("session", "", time() - 1);
    }
}

return new AuthDB($pdo);
