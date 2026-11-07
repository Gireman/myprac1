<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe[] $recipes */
/** @var app\models\Method[] $methods */

use yii\helpers\Html;
use yii\helpers\Url;

// «1 рецепт», «2 рецепта», «5 рецептов»
$plural = function ($n, $one, $few, $many) {
    $mod10 = $n % 10;
    $mod100 = $n % 100;
    if ($mod10 === 1 && $mod100 !== 11) {
        return $one;
    }
    if ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
        return $few;
    }
    return $many;
};

$icon = function ($name, $size = 20) {
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><use href="#i-' . $name . '"></use></svg>';
};

$total = count($recipes);
$methodsTotal = count($methods);
?>
<main id="main" class="inner-page">
  <section class="page-heading container" aria-labelledby="catalog-title">
    <p class="eyebrow"><?= $total ?> <?= $plural($total, 'рецепт', 'рецепта', 'рецептов') ?> · <?= $methodsTotal ?> <?= $plural($methodsTotal, 'метод', 'метода', 'методов') ?></p>
    <h1 id="catalog-title">Ваша следующая<br><em>идеальная чашка</em></h1>
    <p class="page-lead">От короткого эспрессо до неспешного пуровера. Фильтруйте по способу и сохраняйте любимое.</p>
  </section>

  <section class="catalog-toolbar" aria-label="Поиск и фильтры">
    <div class="catalog-toolbar-inner container">
      <div class="search-box">
        <?= $icon('search') ?>
        <label class="visually-hidden" for="recipe-search">Найти рецепт или ноту</label>
        <input id="recipe-search" type="search" placeholder="Найти рецепт или ноту" autocomplete="off">
      </div>
      <div class="filters" role="group" aria-label="Способ заваривания">
        <button type="button" class="active" aria-pressed="true" data-filter="Все">Все</button>
        <?php foreach ($methods as $method): ?>
        <button type="button" aria-pressed="false" data-filter="<?= Html::encode($method->title) ?>"><?= Html::encode($method->title) ?></button>
        <?php endforeach; ?>
        <button type="button" aria-pressed="false" data-filter="Избранное">Избранное</button>
      </div>
    </div>
  </section>

  <section class="catalog-section container" aria-labelledby="list-title">
    <h2 class="visually-hidden" id="list-title">Список рецептов</h2>
    <div class="result-count">
      <span id="result-count" aria-live="polite"><?= str_pad((string)$total, 2, '0', STR_PAD_LEFT) ?> <?= $plural($total, 'рецепт', 'рецепта', 'рецептов') ?></span>
      <label class="sort">
        <span class="visually-hidden">Сортировка</span>
        <select id="recipe-sort">
          <option value="popular">Сначала популярные</option>
          <option value="time">Сначала быстрые</option>
          <option value="title">По названию</option>
        </select>
        <?= $icon('chevron', 16) ?>
      </label>
    </div>
    <ul class="recipe-grid catalog-grid" id="catalog-grid">
      <?php foreach ($recipes as $recipe): ?>
        <li><?= $this->render('_recipe_card', ['recipe' => $recipe]) ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="empty-state" id="empty-state" hidden>
      <?= $icon('heart', 40) ?>
      <p class="empty-title" id="empty-title">Здесь пока пусто</p>
      <p id="empty-text">Добавьте рецепты в избранное — они появятся в этой подборке.</p>
      <button class="primary-button" type="button" id="empty-reset">Смотреть все</button>
    </div>
  </section>
</main>