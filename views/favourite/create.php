<?php

/** @var yii\web\View $this */
/** @var app\models\Favourite $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Избранное</a></li>
      <li aria-current="page">Новая запись</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="fav-create-title">
    <p class="eyebrow">Избранное</p>
    <h1 id="fav-create-title">Новая<br><em>запись</em></h1>
  </section>

  <section class="container" aria-label="Форма создания">
    <?= $this->render('_form', ['model' => $model]) ?>
  </section>
</main>