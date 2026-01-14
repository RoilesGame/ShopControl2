<?php
$user = current_user();
$redirectUrl = $_SERVER['REQUEST_URI'] ?? '/index.php';
?>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="/index.php">ShopControl</a>
        <nav class="nav">
            <a href="/index.php#about">О магазине</a>
            <a href="/index.php#featured">Хиты</a>
            <a href="/index.php#contacts">Контакты</a>
            <a href="/shop.php">Магазин</a>
            <?php if ($user): ?>
                <a href="/wishlist.php">Список покупок</a>
                <a href="/feedback.php">Обратная связь</a>
            <?php endif; ?>
        </nav>
        <div class="auth">
            <?php if ($user): ?>
                <div class="user-info">
                    <span>Привет, <?= e($user['full_name'] ?: $user['email']) ?></span>
                    <a class="btn btn-outline" href="/logout.php">Выйти</a>
                </div>
            <?php else: ?>
                <form class="login-mini" method="post" action="/login.php">
                    <input type="hidden" name="redirect" value="<?= e($redirectUrl) ?>">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Пароль" required>
                    <button class="btn" type="submit">Войти</button>
                    <a class="link" href="/register.php">Регистрация</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</header>
