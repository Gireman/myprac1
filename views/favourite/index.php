<?php

/** @var yii\web\View $this */
/** @var app\models\FavouriteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li aria-current="page">Избранное</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="fav-title">
    <p class="eyebrow">Всего записей: <?= (int)$dataProvider->totalCount ?></p>
    <h1 id="fav-title">Избранное<br><em>пользователей</em></h1>
  </section>

  <section class="container" aria-label="Таблица избранного">
    <div class="admin-toolbar">
      <a class="primary-button" href="<?= Html::encode(Url::to(['create'])) ?>">Добавить запись</a>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'data-grid'],
        'tableOptions' => ['class' => 'data-table'],
        'layout' => "<div class=\"table-wrap\">{items}</div>\n<div class=\"table-footer\">{summary}{pager}</div>",
        'summary' => 'Показано {begin}–{end} из {totalCount}',
        'summaryOptions' => ['class' => 'table-summary'],
        'emptyText' => 'В избранном пока ничего нет.',
        'emptyTextOptions' => ['class' => 'table-empty'],
        'pager' => [
            'options' => ['class' => 'pager'],
            'prevPageLabel' => '←',
            'nextPageLabel' => '→',
            'maxButtonCount' => 5,
        ],
        'columns' => [
            ['attribute' => 'id', 'label' => 'ID'],
            [
                'attribute' => 'id_user',
                'label' => 'Пользователь',
                'value' => function ($model) {
                    return $model->user ? $model->user->username : 'ID ' . $model->id_user;
                },
            ],
            [
                'attribute' => 'id_recipe',
                'label' => 'Рецепт',
                'value' => function ($model) {
                    return $model->recipe ? $model->recipe->title : 'ID ' . $model->id_recipe;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '',
                'template' => '<div class="row-actions">{view}{update}{delete}</div>',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('Открыть', $url, ['class' => 'row-action']);
                    },
                    'update' => function ($url) {
                        return Html::a('Изменить', $url, ['class' => 'row-action']);
                    },
                    // Удаление — настоящая POST-форма с CSRF, не зависит от yii.js
                    'delete' => function ($url) {
                        return Html::beginForm($url, 'post', ['class' => 'inline-form'])
                            . Html::submitButton('Удалить', [
                                'class' => 'row-action row-action--danger',
                                'onclick' => "return confirm('Удалить запись из избранного?');",
                            ])
                            . Html::endForm();
                    },
                ],
            ],
        ],
    ]) ?>
  </section>
</main>