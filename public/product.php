<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';

$pdo = db();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
  SELECT p.*
  FROM products p
  WHERE p.id = ? AND p.is_active = 1
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo "Товар не найден";
    exit;
}

$specsStmt = $pdo->prepare("SELECT spec_key, spec_value FROM product_specs WHERE product_id = ? ORDER BY spec_key");
$specsStmt->execute([$id]);
$specs = $specsStmt->fetchAll();
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($product['title']) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
        <div class="product-page">
            <img class="product-big" src="/<?= htmlspecialchars($product['main_image'] ?? '') ?>" alt="">
            <div>
                <h1><?= htmlspecialchars($product['title']) ?></h1>
                <p class="price"><?= number_format((float)$product['price'], 2, '.', ' ') ?> ₽</p>
                <p class="stock">В наличии: <strong><?= (int)$product['stock_qty'] ?></strong></p>

                <?php if ($specs): ?>
                    <h3>Характеристики</h3>
                    <ul class="specs">
                        <?php foreach ($specs as $s): ?>
                            <li><span><?= htmlspecialchars($s['spec_key']) ?></span><b><?= htmlspecialchars($s['spec_value']) ?></b></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <h3>Описание</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <?php if (current_user()): ?>
                    <form method="post" action="/wishlist.php">
                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                        <button class="btn" type="submit" name="action" value="add">Добавить в список покупок</button>
                    </form>
                <?php else: ?>
                    <p class="muted">Чтобы добавлять в список покупок — войдите.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>