<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Пользователи</a></li>
      <li><a href="<?= Html::encode(Url::to(['view', 'id' => $model->id])) ?>"><?= Html::encode($model->username) ?></a></li>
      <li aria-current="page">Редактирование</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="update-title">
    <p class="eyebrow">Пользователь № <?= (int)$model->id ?></p>
    <h1 id="update-title">Данные<br><em>пользователя</em></h1>
  </section>

  <section class="container" aria-label="Форма редактирования">
    <?= $this->render('_form', ['model' => $model]) ?>
  </section>
</main>