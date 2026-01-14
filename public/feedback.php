<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

require_auth();
$pdo = db();
$user = current_user();
$error = null;
$success = get_flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($subject === '' || $message === '') {
        $error = 'Заполните тему и сообщение.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO feedback_messages (user_id, subject, message) VALUES (?, ?, ?)');
        $stmt->execute([(int)$user['id'], $subject, $message]);
        set_flash('success', 'Сообщение отправлено. Спасибо!');
        redirect('/feedback.php');
    }
}

$stmt = $pdo->prepare('SELECT subject, message, created_at FROM feedback_messages WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([(int)$user['id']]);
$messages = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Обратная связь</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
        <h1>Обратная связь</h1>

        <?php if ($success): ?>
            <div class="alert success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="feedback-form" method="post">
            <label>
                Тема
                <input type="text" name="subject" required>
            </label>
            <label>
                Сообщение
                <textarea name="message" rows="5" required></textarea>
            </label>
            <button class="btn" type="submit">Отправить</button>
        </form>

        <h2>Ваши сообщения</h2>
        <?php if (!$messages): ?>
            <p>Сообщений пока нет.</p>
        <?php else: ?>
            <div class="feedback-list">
                <?php foreach ($messages as $msg): ?>
                    <article>
                        <div class="feedback-head">
                            <strong><?= e($msg['subject']) ?></strong>
                            <span><?= e(date('d.m.Y H:i', strtotime($msg['created_at']))) ?></span>
                        </div>
                        <p><?= nl2br(e($msg['message'])) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
