<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

$pdo = db();

$stmt = $pdo->query("
  SELECT p.id, p.title, p.short_desc, p.price, p.stock_qty, p.main_image
  FROM products p
  WHERE p.is_active = 1
  ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Магазин</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script defer src="/assets/js/main.js"></script>
</head>

<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container">
        <h1>Каталог товаров</h1>

        <div class="view-switch">
            <button data-view="table" class="btn">Таблица</button>
            <button data-view="grid" class="btn">Плитка</button>
        </div>

        <section id="tableView" class="view">
            <table class="products">
                <thead>
                    <tr>
                        <th>Фото</th>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Кратко</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><img class="thumb" src="/<?= e($p['main_image'] ?? '') ?>" alt=""></td>
                            <td><?= e($p['title']) ?></td>
                            <td><?= number_format((float)$p['price'], 2, '.', ' ') ?> ₽</td>
                            <td><?= e($p['short_desc']) ?></td>
                            <td><a class="btn" href="/product.php?id=<?= (int)$p['id'] ?>">Подробнее</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="gridView" class="view hidden">
            <div class="grid">
                <?php foreach ($products as $p): ?>
                    <article class="card">
                        <img class="card-img" src="/<?= e($p['main_image'] ?? '') ?>" alt="">
                        <h3><?= e($p['title']) ?></h3>
                        <p class="muted"><?= e($p['short_desc']) ?></p>
                        <div class="row">
                            <strong><?= number_format((float)$p['price'], 2, '.', ' ') ?> ₽</strong>
                            <a class="btn" href="/product.php?id=<?= (int)$p['id'] ?>">Открыть</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
