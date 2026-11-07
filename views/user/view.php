<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';

$isAdmin = $model->role == 1;
$rows = [
    ['ID', Html::encode($model->id)],
    ['Логин', Html::encode($model->username)],
    ['Email', Html::encode($model->mail)],
    ['ФИО', Html::encode($model->fullname)],
    ['Телефон', Html::encode($model->phone)],
    ['Роль', Html::tag('span', $isAdmin ? 'Админ' : 'Пользователь', [
        'class' => 'role-pill' . ($isAdmin ? ' is-admin' : ''),
    ])],
];
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li><a href="<?= Html::encode(Url::to(['index'])) ?>">Пользователи</a></li>
      <li aria-current="page"><?= Html::encode($model->username) ?></li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="user-title">
    <p class="eyebrow">Пользователь № <?= (int)$model->id ?></p>
    <h1 id="user-title"><?= Html::encode($model->username) ?></h1>
  </section>

  <section class="container" aria-label="Данные пользователя">
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

    <div class="admin-actions">
      <a class="primary-button" href="<?= Html::encode(Url::to(['update', 'id' => $model->id])) ?>">Изменить</a>
      <?= Html::beginForm(['delete', 'id' => $model->id], 'post', ['class' => 'inline-form']) ?>
        <button class="secondary-button is-danger" type="submit" onclick="return confirm('Удалить пользователя?');">Удалить</button>
      <?= Html::endForm() ?>
    </div>
  </section>
</main>