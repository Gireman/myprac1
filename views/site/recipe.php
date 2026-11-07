<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe|null $recipe */

use yii\helpers\Html;
use yii\helpers\Url;

$icon = function ($name, $size = 20) {
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><use href="#i-' . $name . '"></use></svg>';
};
$pad2 = function ($n) {
    return str_pad((string)$n, 2, '0', STR_PAD_LEFT);
};
?>
<main id="main" class="detail-page">
<?php if ($recipe === null): ?>
  <?php $this->title = 'Рецепт не найден — Crema'; ?>
  <section class="page-heading container">
    <h1>Рецепт<br><em>не найден</em></h1>
    <p>Возможно, ссылка устарела. Загляните в каталог — там все доступные рецепты.</p>
    <a class="primary-button not-found-button" href="<?= Url::to(['site/recipes']) ?>">Смотреть рецепты <?= $icon('arrow') ?></a>
  </section>
<?php else: ?>
  <?php
  $this->title = 'Crema';
  $this->registerMetaTag([
      'name' => 'description',
      'content' => $recipe->title . ': ' . $recipe->description,
  ], 'description');

  list($w, $h) = $recipe->getImgSize();
  $base = '@web/img/' . $recipe->img;
  $methodTitle = $recipe->method ? $recipe->method->title : '';
  $noteColors = ['#d8f35d', '#efa3a3', '#afd6ed'];
  ?>
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Url::to(['site/recipes']) ?>">Рецепты</a></li>
      <li aria-current="page"><?= Html::encode($methodTitle) ?></li>
    </ol>
  </nav>

  <section class="detail-hero container" aria-labelledby="recipe-title">
    <div class="detail-image">
      <picture>
        <source type="image/webp" srcset="<?= Url::to($base . '-400.webp') ?> 400w, <?= Url::to($base . '-800.webp') ?> 800w, <?= Url::to($base . '-1200.webp') ?> 1200w" sizes="(min-width: 1200px) 560px, (min-width: 768px) 45vw, 92vw">
        <img src="<?= Url::to($base . '-800.jpg') ?>" alt="<?= Html::encode($recipe->img_alt) ?>" width="<?= $w ?>" height="<?= $h ?>" fetchpriority="high" decoding="async">
      </picture>
      <span class="detail-index" aria-hidden="true"><?= $pad2($recipe->id) ?></span>
    </div>
    <div class="detail-copy">
      <p class="eyebrow"><?= Html::encode($methodTitle) ?> · <?= Html::encode($recipe->roast) ?></p>
      <h1 id="recipe-title"><?= Html::encode($recipe->title) ?></h1>
      <p class="detail-lead"><?= Html::encode($recipe->description) ?></p>
      <ul class="flavor-notes" aria-label="Вкусовые ноты">
        <?php foreach ($recipe->getNoteTitles() as $i => $note): ?>
        <li style="--note-color:<?= $noteColors[$i % count($noteColors)] ?>"><?= Html::encode($note) ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="detail-actions">
        <a class="primary-button" href="#steps">Начать готовить <?= $icon('arrow') ?></a>
        <button class="save-button" type="button" data-favorite="<?= (int)$recipe->id ?>" aria-pressed="false">
          <?= $icon('heart') ?><span data-favorite-label>В избранное</span>
        </button>
      </div>
      <ul class="recipe-facts" aria-label="Пропорции">
        <li><?= $icon('bean') ?><span>Кофе<strong><?= Html::encode($recipe->fact_coffee) ?></strong></span></li>
        <li><?= $icon('thermometer') ?><span>Вода<strong><?= Html::encode($recipe->fact_water) ?></strong></span></li>
        <li><?= $icon('clock') ?><span>Время<strong><?= Html::encode($recipe->fact_time) ?></strong></span></li>
      </ul>
    </div>
  </section>

  <section class="instructions" id="steps" aria-labelledby="steps-title">
    <div class="instructions-inner container">
      <div class="equipment">
        <p class="eyebrow">Что понадобится</p>
        <h2>Оборудование</h2>
        <ul>
          <?php foreach ($recipe->orderedEquipments as $i => $item): ?>
          <li class="equipment-item"><span><?= $pad2($i + 1) ?></span><?= Html::encode($item->title) ?><?= $icon('check', 18) ?></li>
          <?php endforeach; ?>
        </ul>
        <aside class="tip">
          <b>Совет</b>
          <p><?= Html::encode($recipe->tip) ?></p>
        </aside>
      </div>
      <div class="steps-block">
        <p class="eyebrow">Пошаговый рецепт</p>
        <h2 id="steps-title">Завариваем</h2>
        <ol class="steps-list">
          <?php foreach ($recipe->recipeSteps as $i => $step): ?>
          <li>
            <label class="step">
              <input class="step-check" type="checkbox">
              <span class="step-number"><span class="step-num"><?= $pad2($i + 1) ?></span><?= $icon('check') ?></span>
              <span class="step-text"><span class="step-title"><?= Html::encode($step->title) ?></span><span class="step-desc"><?= Html::encode($step->text) ?></span></span>
            </label>
          </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>
<?php endif; ?>
</main>