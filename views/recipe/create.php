<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Новый рецепт — Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Рецепты</a></li>
      <li aria-current="page">Новый рецепт</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="recipe-create-title">
    <p class="eyebrow">Рецепты</p>
    <h1 id="recipe-create-title">Новый<br><em>рецепт</em></h1>
  </section>

  <section class="container" aria-label="Форма создания">
    <?= $this->render('_form', ['model' => $model]) ?>
  </section>
</main>
