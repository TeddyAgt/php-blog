<?php

function isLoggedIn()
{
    global $pdo;
    $sessionId = $_COOKIE["session"] ?? "";

    if ($sessionId) {
        $statementSession = $pdo->prepare("SELECT * FROM session WHERE session_id=:sessionId");
        $statementSession->bindValue(":sessionId", $sessionId);
        $statementSession->execute();
        $session = $statementSession->fetch();

        if ($session) {
            $statementUser = $pdo->prepare("SELECT * FROM user WHERE user_id=:userId");
            $statementUser->bindValue(":userId", $session["session_user_id"]);
            $statementUser->execute();
            $user = $statementUser->fetch();
        }
    }

    return $user ?? false;
}
