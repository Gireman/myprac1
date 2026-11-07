<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Favourite;
use yii\helpers\Json;
use yii\web\View;
 
$isGuest = Yii::$app->user->isGuest;
// Пункт «Админ панель» виден только администратору (так же, как в UserController: role == 1)
$isAdmin = !$isGuest && Yii::$app->user->identity->role == 1;
$route = Yii::$app->controller->route;
$this->registerJs(
    'window.CREMA_FAV = ' . Json::htmlEncode([
        'auth' => !$isGuest,
        'ids' => $isGuest ? [] : Favourite::recipeIdsForUser(Yii::$app->user->identity->id),
        'url' => Url::to(['site/toggle-favorite']),
        'loginUrl' => Url::to(['site/login']),
        'csrf' => Yii::$app->request->csrfToken,
    ]) . ';',
    View::POS_HEAD
);

// Описание по умолчанию; страница (например, рецепт) может переопределить своим
if (!isset($this->metaTags['description'])) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => 'Понятные рецепты кофе, точные пропорции и подбор вкуса: V60, эспрессо, аэропресс, капучино и другие способы.',
    ], 'description');
}

?><?php $this->beginPage() ?><!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= Html::encode($this->title ?: 'Crema') ?></title>
  <meta name="theme-color" content="#f5f0e8">
  <link rel="icon" href="<?= Url::to("@web/svg/favicon.svg") ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= Url::to("@web/css/style.css") ?>">
  <link rel="preload" as="image" type="image/webp" imagesrcset="<?= Url::to("@web/img/hero-400.webp") ?> 400w, <?= Url::to("@web/img/hero-800.webp") ?> 800w, <?= Url::to("@web/img/hero-1200.webp") ?> 1200w" imagesizes="(min-width: 1200px) 560px, (min-width: 768px) 45vw, 92vw" fetchpriority="high">
  <script src="<?= Url::to("@web/js/app.js") ?>" defer></script>
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<a class="skip-link" href="#main">Перейти к содержимому</a>
<svg class="sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14M14 6l6 6-6 6"/></symbol><symbol id="i-bean" viewBox="0 0 24 24"><path d="M18.4 5.6c3.1 3.1.7 8.5-3.1 12.3s-9.2 6.2-12.3 3.1-.7-8.5 3.1-12.3S15.3 2.5 18.4 5.6Z"/><path d="M5.7 19.3c2.3-5 4.6-5.2 7.3-7.9 1.5-1.5 2.5-3.1 3.2-5.1"/></symbol><symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></symbol><symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol><symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol><symbol id="i-close" viewBox="0 0 24 24"><path d="m6 6 12 12M18 6 6 18"/></symbol><symbol id="i-heart" viewBox="0 0 24 24"><path d="M20.8 5.7a5.4 5.4 0 0 0-7.6 0L12 6.9l-1.2-1.2a5.4 5.4 0 1 0-7.6 7.6L12 22l8.8-8.7a5.4 5.4 0 0 0 0-7.6Z"/></symbol><symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></symbol><symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></symbol><symbol id="i-thermometer" viewBox="0 0 24 24"><path d="M14 14.8V5a4 4 0 0 0-8 0v9.8a6 6 0 1 0 8 0Z"/><path d="M10 5v11"/></symbol><symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c.7-4.2 3.3-6 8-6s7.3 1.8 8 6"/></symbol></svg>
<header class="header">
  <div class="header-inner container">
    <a class="brand" href="<?= Url::to(['site/index']) ?>"><span class="brand-mark"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg></span>crema</a>
    <button class="menu-button" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Открыть меню">
      <svg class="icon icon-menu" width="24" height="24" aria-hidden="true" focusable="false"><use href="#i-menu"></use></svg><svg class="icon icon-close" width="24" height="24" aria-hidden="true" focusable="false"><use href="#i-close"></use></svg>
    </button>
    <nav class="nav" id="site-nav" aria-label="Основная навигация">
      <ul>
        <li><a href="<?= Url::to(['site/index']) ?>" aria-current="<?= Yii::$app->controller->route == 'site/index' ? 'page' : '' ?>">Главная</a></li>
        <li><a href="<?= Url::to(['site/recipes']) ?>" aria-current="<?= Yii::$app->controller->route == 'site/recipes' ? 'page' : '' ?>">Рецепты</a></li>
        <li><a href="<?= Url::to(['site/matcher']) ?>" aria-current="<?= Yii::$app->controller->route == 'site/matcher' ? 'page' : '' ?>">Подобрать вкус</a></li>
        <?php if ($isAdmin): ?>
        <li><a href="<?= Url::to(['/admin']) ?>" aria-current="<?= (strpos($route, 'admin') === 0 || strpos($route, 'user/') === 0 || strpos($route, 'recipe/') === 0 || strpos($route, 'favourite/') === 0) ? 'page' : '' ?>">Админ панель</a></li>
        <?php endif; ?>
        <li class="nav-auth">
          <?php if (Yii::$app->user->isGuest): ?>
            <a href="<?= Url::to(['/site/login']) ?>">Войти</a>
          <?php else: ?>
            <?= Html::beginForm(['/site/logout'], 'post', ['style' => 'display:inline;margin:0']) ?>
              <button type="submit">Выйти</button>
            <?= Html::endForm() ?>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
    <div class="header-actions">
    <?php if (Yii::$app->user->isGuest): ?>
      <a class="login-button" href="<?= Html::encode(Url::to(['/site/login'])) ?>"><svg class="icon" width="17" height="17" aria-hidden="true" focusable="false"><use href="#i-user"></use></svg>Войти</a>
    <?php else: ?>
      <?= Html::beginForm(['/site/logout'], 'post', ['style' => 'display:inline;margin:0']) ?>
        <span class="user-name">(<?= Html::encode(Yii::$app->user->identity->username) ?>)</span>
        <button class="logout-button" type="submit">Выйти</button>
      <?= Html::endForm() ?>
    <?php endif; ?>
</div>
  </div>
</header>
        <?= $content ?>
<footer class="footer">
  <div class="footer-minimal container">
    <a class="brand footer-brand" href="index"><span class="brand-mark"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg></span>crema</a>
    <nav class="footer-nav" aria-label="Навигация в подвале">
      <ul>
        <li><a href="<?= Url::to(['site/index']) ?>">Главная</a></li>
        <li><a href="<?= Url::to(['site/recipes']) ?>">Рецепты</a></li>
        <li><a href="<?= Url::to(['site/matcher']) ?>">Подобрать вкус</a></li>
      </ul>
    </nav>
    <p class="footer-copyright">© <span data-year>2026</span> Crema</p>
  </div>
</footer>
<div class="toast" id="toast" role="status" aria-live="polite"><svg class="icon" width="17" height="17" aria-hidden="true" focusable="false"><use href="#i-check"></use></svg><span id="toast-text"></span></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>