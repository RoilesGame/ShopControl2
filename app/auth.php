<?php
session_start();

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!current_user()) {
        header('Location: /login.php');
        exit;
    }
}

function login_user(array $user): void
{
    // Не кладем password_hash в сессию
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $p["path"],
            $p["domain"],
            $p["secure"],
            $p["httponly"]
        );
    }
    session_destroy();
}
