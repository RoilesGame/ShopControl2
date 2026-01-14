<?php
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/helpers.php';

$pdo = db();
$featuredStmt = $pdo->query("
    SELECT id, title, short_desc, price, main_image
    FROM products
    WHERE is_active = 1
    ORDER BY created_at DESC
    LIMIT 6
");
$featured = $featuredStmt->fetchAll();
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ShopControl — магазин техники</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script defer src="/assets/js/main.js"></script>
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="hero">
            <div class="container hero-inner">
                <div>
                    <p class="tag">Магазин современной электроники</p>
                    <h1>ShopControl — техника, которая делает жизнь удобнее</h1>
                    <p>Подбираем гаджеты для работы, учебы и отдыха. Честные характеристики, официальный товар и быстрый сервис.</p>
                    <div class="hero-actions">
                        <a class="btn" href="/shop.php">Перейти в магазин</a>
                        <a class="btn btn-outline" href="#featured">Смотреть хиты</a>
                    </div>
                </div>
                <div class="hero-card">
                    <h3>Слайд-шоу новинок</h3>
                    <div class="slider" data-slider>
                        <?php foreach ($featured as $item): ?>
                            <div class="slide">
                                <img src="/<?= e($item['main_image'] ?? '') ?>" alt="<?= e($item['title']) ?>">
                                <div>
                                    <h4><?= e($item['title']) ?></h4>
                                    <p class="muted"><?= e($item['short_desc']) ?></p>
                                    <span class="price"><?= number_format((float)$item['price'], 2, '.', ' ') ?> ₽</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="section">
            <div class="container two-column">
                <div>
                    <h2>О магазине</h2>
                    <p>Мы — магазин электроники с акцентом на качество и сервис. Помогаем подобрать технику под задачи и бюджет, консультируем по аксессуарам, рассказываем о новых технологиях.</p>
                    <ul class="checklist">
                        <li>Официальные поставки и гарантия производителя.</li>
                        <li>Консультации по подбору и настройке устройств.</li>
                        <li>Проверка товара перед выдачей.</li>
                    </ul>
                </div>
                <div class="info-cards">
                    <div class="info-card">
                        <h3>250+</h3>
                        <p>товаров в каталоге</p>
                    </div>
                    <div class="info-card">
                        <h3>48 ч</h3>
                        <p>срок доставки по городу</p>
                    </div>
                    <div class="info-card">
                        <h3>98%</h3>
                        <p>довольных клиентов</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="section section-alt">
            <div class="container">
                <div class="section-head">
                    <h2>Хиты недели</h2>
                    <a class="link" href="/shop.php">Весь каталог →</a>
                </div>
                <div class="grid">
                    <?php foreach ($featured as $item): ?>
                        <article class="card">
                            <img class="card-img" src="/<?= e($item['main_image'] ?? '') ?>" alt="<?= e($item['title']) ?>">
                            <h3><?= e($item['title']) ?></h3>
                            <p class="muted"><?= e($item['short_desc']) ?></p>
                            <div class="row">
                                <strong><?= number_format((float)$item['price'], 2, '.', ' ') ?> ₽</strong>
                                <a class="btn" href="/product.php?id=<?= (int)$item['id'] ?>">Подробнее</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Почему выбирают нас</h2>
                <div class="benefits">
                    <div>
                        <h4>Подбор под задачу</h4>
                        <p>Поможем собрать набор техники под учебу, работу или развлечения.</p>
                    </div>
                    <div>
                        <h4>Быстрая доставка</h4>
                        <p>Оперативно доставляем заказы по городу и области.</p>
                    </div>
                    <div>
                        <h4>Поддержка 24/7</h4>
                        <p>Отвечаем в чате и по телефону в удобное время.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>
