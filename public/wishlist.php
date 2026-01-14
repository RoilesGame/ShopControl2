<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

require_auth();
$pdo = db();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($action === 'add' && $productId > 0) {
        $stmt = $pdo->prepare("
      INSERT INTO wishlist_items(user_id, product_id, qty)
      VALUES(?,?,1)
      ON DUPLICATE KEY UPDATE qty = qty + 1
    ");
        $stmt->execute([(int)$user['id'], $productId]);
        header("Location: /wishlist.php");
        exit;
    }

    if ($action === 'remove' && $productId > 0) {
        $stmt = $pdo->prepare("DELETE FROM wishlist_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([(int)$user['id'], $productId]);
        header("Location: /wishlist.php");
        exit;
    }
}

$stmt = $pdo->prepare("
  SELECT w.product_id, w.qty, p.title, p.price, p.main_image
  FROM wishlist_items w
  JOIN products p ON p.id = w.product_id
  WHERE w.user_id = ?
  ORDER BY w.created_at DESC
");
$stmt->execute([(int)$user['id']]);
$items = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Список покупок</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
        <h1>Список покупок</h1>

        <?php if (!$items): ?>
            <p>Список пуст.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($items as $it): ?>
                    <article class="card">
                        <img class="card-img" src="/<?= e($it['main_image'] ?? '') ?>" alt="">
                        <h3><?= e($it['title']) ?></h3>
                        <p>Кол-во: <strong><?= (int)$it['qty'] ?></strong></p>
                        <p>Цена: <strong><?= number_format((float)$it['price'], 2, '.', ' ') ?> ₽</strong></p>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= (int)$it['product_id'] ?>">
                            <button class="btn" name="action" value="remove">Убрать</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
