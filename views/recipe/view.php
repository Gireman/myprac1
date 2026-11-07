<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->title . ' — Crema';

$noteColors = ['#d8f35d', '#efa3a3', '#afd6ed'];
$notes = $model->orderedNotes;
$equipments = $model->orderedEquipments;
$steps = $model->recipeSteps;

$rows = [
    ['ID', Html::encode($model->id)],
    ['Название', Html::encode($model->title)],
    ['Способ', Html::encode($model->method ? $model->method->title : '')],
    ['Сложность', Html::encode($model->level)],
    ['Обжарка', Html::encode($model->roast)],
    ['Описание', Html::encode($model->description)],
    ['Время на карточке', (int)$model->card_minutes . ' мин'],
    ['Картинка', Html::img(Url::to('@web/img/' . $model->img . '-400.webp'), [
        'class' => 'admin-preview',
        'alt' => $model->img_alt,
        'width' => 160,
        'loading' => 'lazy',
    ]) . '<br><span class="field-hint">' . Html::encode($model->img) . '</span>'],
    ['Описание картинки', Html::encode($model->img_alt)],
    ['Цвет плашки', '<span class="swatch swatch--static" style="background:' . Html::encode($model->pill_color) . '"></span> ' . Html::encode($model->pill_color)],
    ['Кофе', Html::encode($model->fact_coffee)],
    ['Вода', Html::encode($model->fact_water)],
    ['Время', Html::encode($model->fact_time)],
    ['Совет', Html::encode($model->tip)],
    ['Кислотность', (int)$model->taste_acidity . ' / 100'],
    ['Плотность', (int)$model->taste_body . ' / 100'],
    ['Сладость', (int)$model->taste_sweetness . ' / 100'],
    ['Молоко', $model->has_milk ? 'С молоком' : 'Без молока'],
    ['Время в подборе вкуса', (int)$model->taste_minutes . ' мин'],
];
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Рецепты</a></li>
      <li aria-current="page"><?= Html::encode($model->title) ?></li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="recipe-title">
    <p class="eyebrow">Рецепт № <?= (int)$model->id ?></p>
    <h1 id="recipe-title"><?= Html::encode($model->title) ?></h1>
  </section>

  <section class="container" aria-label="Данные рецепта">
    <?= $this->render('_flash') ?>

    <div class="detail-card">
      <dl class="detail-list">
        <?php foreach ($rows as $row): ?>
        <div class="detail-row">
          <dt><?= $row[0] ?></dt>
          <dd><?= $row[1] !== '' ? $row[1] : '—' ?></dd>
        </div>
        <?php endforeach; ?>
      </dl>
    </div>

    <h2 class="admin-subtitle">Вкусовые ноты</h2>
    <div class="detail-card admin-block">
      <?php if ($notes): ?>
      <ul class="flavor-notes admin-notes">
        <?php foreach ($notes as $i => $note): ?>
        <li style="--note-color:<?= $noteColors[$i % count($noteColors)] ?>"><?= Html::encode($note->title) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p class="field-hint">Ноты не указаны.</p>
      <?php endif; ?>
    </div>

    <h2 class="admin-subtitle">Оборудование</h2>
    <div class="detail-card admin-block">
      <?php if ($equipments): ?>
      <ol class="admin-list">
        <?php foreach ($equipments as $item): ?>
        <li><?= Html::encode($item->title) ?></li>
        <?php endforeach; ?>
      </ol>
      <?php else: ?>
      <p class="field-hint">Оборудование не указано.</p>
      <?php endif; ?>
    </div>

    <h2 class="admin-subtitle">Шаги приготовления</h2>
    <div class="detail-card admin-block">
      <?php if ($steps): ?>
      <ol class="admin-list admin-list--steps">
        <?php foreach ($steps as $step): ?>
        <li><strong><?= Html::encode($step->title) ?></strong><span><?= Html::encode($step->text) ?></span></li>
        <?php endforeach; ?>
      </ol>
      <?php else: ?>
      <p class="field-hint">Шагов пока нет.</p>
      <?php endif; ?>
    </div>

    <div class="admin-actions">
      <a class="primary-button" href="<?= Html::encode(Url::to(['update', 'id' => $model->id])) ?>">Изменить</a>
      <a class="secondary-button" href="<?= Html::encode(Url::to(['/site/recipe', 'id' => $model->id])) ?>">Открыть на сайте</a>
      <?= Html::beginForm(['delete', 'id' => $model->id], 'post', ['class' => 'inline-form']) ?>
        <button class="secondary-button is-danger" type="submit" onclick="return confirm('Удалить рецепт? Он также пропадёт из избранного у пользователей.');">Удалить</button>
      <?= Html::endForm() ?>
    </div>
  </section>
</main>
