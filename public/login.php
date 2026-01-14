<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

$pdo = db();
$error = null;
$success = get_flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirectTo = $_POST['redirect'] ?? '/index.php';

    if ($email === '' || $password === '') {
        $error = 'Заполните все поля.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && (int)$user['is_active'] === 1 && password_verify($password, $user['password_hash'])) {
            login_user($user);
            redirect($redirectTo);
        }

        $error = 'Неверный email или пароль.';
    }
}
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Вход</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container auth-page">
        <h1>Вход в аккаунт</h1>

        <?php if ($success): ?>
            <div class="alert success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post">
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Пароль
                <input type="password" name="password" required>
            </label>
            <button class="btn" type="submit">Войти</button>
            <p>Нет аккаунта? <a class="link" href="/register.php">Зарегистрируйтесь</a>.</p>
        </form>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
