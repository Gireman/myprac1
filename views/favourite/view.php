<?php

/** @var yii\web\View $this */
/** @var app\models\Favourite $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';

$user = $model->user;
$recipe = $model->recipe;
$rows = [
    ['ID', Html::encode($model->id)],
    ['Пользователь', $user ? Html::encode($user->username) . ' (ID ' . (int)$model->id_user . ')' : 'ID ' . (int)$model->id_user . ' — не найден'],
    ['Рецепт', $recipe
        ? Html::a(Html::encode($recipe->title), ['/site/recipe', 'id' => $recipe->id]) . ' (ID ' . (int)$model->id_recipe . ')'
        : 'ID ' . (int)$model->id_recipe . ' — не найден'],
];
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Избранное</a></li>
      <li aria-current="page">№ <?= (int)$model->id ?></li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="fav-view-title">
    <p class="eyebrow">Запись избранного</p>
    <h1 id="fav-view-title">№ <?= (int)$model->id ?></h1>
  </section>

  <section class="container" aria-label="Данные записи">
    <div class="detail-card">
      <dl class="detail-list">
        <?php foreach ($rows as $row): ?>
        <div class="detail-row">
          <dt><?= $row[0] ?></dt>
          <dd><?= $row[1] ?></dd>
        </div>
        <?php endforeach; ?>
      </dl>
    </div>

    <div class="admin-actions">
      <a class="primary-button" href="<?= Html::encode(Url::to(['update', 'id' => $model->id])) ?>">Изменить</a>
      <?= Html::beginForm(['delete', 'id' => $model->id], 'post', ['class' => 'inline-form']) ?>
        <button class="secondary-button is-danger" type="submit" onclick="return confirm('Удалить запись из избранного?');">Удалить</button>
      <?= Html::endForm() ?>
    </div>
  </section>
</main>