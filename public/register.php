<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

$pdo = db();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordRepeat = $_POST['password_repeat'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Заполните обязательные поля.';
    } elseif ($password !== $passwordRepeat) {
        $error = 'Пароли не совпадают.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Такой email уже зарегистрирован.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (email, password_hash, full_name) VALUES (?, ?, ?)');
            $insert->execute([$email, $hash, $fullName ?: null]);

            $userId = (int)$pdo->lastInsertId();
            $user = [
                'id' => $userId,
                'email' => $email,
                'full_name' => $fullName,
                'role' => 'user',
                'is_active' => 1,
            ];
            login_user($user);
            set_flash('success', 'Аккаунт создан. Добро пожаловать!');
            redirect('/index.php');
        }
    }
}
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container auth-page">
        <h1>Регистрация</h1>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post">
            <label>
                Имя и фамилия
                <input type="text" name="full_name" placeholder="Например, Иван Петров">
            </label>
            <label>
                Email*
                <input type="email" name="email" required>
            </label>
            <label>
                Пароль*
                <input type="password" name="password" required>
            </label>
            <label>
                Повторите пароль*
                <input type="password" name="password_repeat" required>
            </label>
            <button class="btn" type="submit">Создать аккаунт</button>
            <p>Уже есть аккаунт? <a class="link" href="/login.php">Войдите</a>.</p>
        </form>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
