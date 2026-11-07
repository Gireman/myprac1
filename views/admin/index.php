<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';

// Разделы админки: чтобы добавить новый, допишите элемент в массив
$sections = [
    [
        'title' => 'Управление пользователями',
        'text' => 'Учётные записи, роли и редактирование',
        'url' => ['user/index'],
        'icon' => 'user',
    ],
    [
        'title' => 'Рецепты',
        'text' => 'Каталог: шаги, ноты, оборудование и картинки',
        'url' => ['recipe/index'],
        'icon' => 'bean',
    ],
    [
        'title' => 'Избранное',
        'text' => 'Рецепты, которые сохранили пользователи',
        'url' => ['favourite/index'],
        'icon' => 'heart',
    ],
];

$icon = function ($name, $size = 20) {
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><use href="#i-' . $name . '"></use></svg>';
};
?>
<main id="main" class="admin-page">
  <section class="page-heading container" aria-labelledby="admin-title">
    <p class="eyebrow">Управление сайтом</p>
    <h1 id="admin-title">Админ-<em>панель</em></h1>
    <p class="page-lead">Разделы для работы с данными сайта.</p>
  </section>

  <section class="container" aria-label="Разделы админ-панели">
    <ul class="admin-grid">
      <?php foreach ($sections as $section): ?>
      <li>
        <a class="admin-card" href="<?= Html::encode(Url::to($section['url'])) ?>">
          <span class="admin-card-icon"><?= $icon($section['icon'], 22) ?></span>
          <span class="admin-card-text">
            <strong><?= Html::encode($section['title']) ?></strong>
            <span><?= Html::encode($section['text']) ?></span>
          </span>
          <?= $icon('arrow') ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
</main>