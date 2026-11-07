<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Редактирование рецепта — Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Рецепты</a></li>
      <li><a href="<?= Html::encode(Url::to(['view', 'id' => $model->id])) ?>"><?= Html::encode($model->getOldAttribute('title')) ?></a></li>
      <li aria-current="page">Редактирование</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="recipe-update-title">
    <p class="eyebrow">Рецепт № <?= (int)$model->id ?></p>
    <h1 id="recipe-update-title">Данные<br><em>рецепта</em></h1>
  </section>

  <section class="container" aria-label="Форма редактирования">
    <?= $this->render('_form', ['model' => $model]) ?>
  </section>
</main>
