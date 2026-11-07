<?php

/** @var yii\web\View $this */
/** @var app\models\RecipeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Рецепты — Crema';
?>
<main id="main" class="admin-page">
  <nav class="breadcrumbs container" aria-label="Навигационная цепочка">
    <ol>
      <li><a href="<?= Html::encode(Url::to(['/admin'])) ?>">Админ-панель</a></li>
      <li aria-current="page">Рецепты</li>
    </ol>
  </nav>

  <section class="page-heading container" aria-labelledby="recipes-title">
    <p class="eyebrow">Всего записей: <?= (int)$dataProvider->totalCount ?></p>
    <h1 id="recipes-title">Список<br><em>рецептов</em></h1>
  </section>

  <section class="container" aria-label="Таблица рецептов">
    <?= $this->render('_flash') ?>

    <div class="admin-toolbar">
      <a class="primary-button" href="<?= Html::encode(Url::to(['create'])) ?>">Добавить рецепт</a>
    </div>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'data-grid'],
        'tableOptions' => ['class' => 'data-table'],
        'layout' => "<div class=\"table-wrap\">{items}</div>\n<div class=\"table-footer\">{summary}{pager}</div>",
        'summary' => 'Показано {begin}–{end} из {totalCount}',
        'summaryOptions' => ['class' => 'table-summary'],
        'emptyText' => 'Рецептов не найдено.',
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
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img(Url::to('@web/img/' . $model->img . '-400.webp'), [
                        'class' => 'admin-thumb',
                        'alt' => '',
                        'width' => 48,
                        'height' => 48,
                        'loading' => 'lazy',
                    ]);
                },
            ],
            [
                'attribute' => 'title',
                'label' => 'Название',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id], ['class' => 'row-action']);
                },
            ],
            [
                'attribute' => 'id_method',
                'label' => 'Способ',
                'value' => function ($model) {
                    return $model->method ? $model->method->title : '—';
                },
            ],
            ['attribute' => 'level', 'label' => 'Сложность'],
            ['attribute' => 'roast', 'label' => 'Обжарка', 'enableSorting' => false],
            [
                'attribute' => 'card_minutes',
                'label' => 'Время',
                'value' => function ($model) {
                    return (int)$model->card_minutes . ' мин';
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
                                'onclick' => "return confirm('Удалить рецепт? Он также пропадёт из избранного у пользователей.');",
                            ])
                            . Html::endForm();
                    },
                ],
            ],
        ],
    ]) ?>
  </section>
</main>
