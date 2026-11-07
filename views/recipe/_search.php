<?php

/** @var yii\web\View $this */
/** @var app\models\RecipeSearch $model */

use app\models\Method;
use app\models\Recipe;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$methods = ArrayHelper::map(Method::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'title');
$levels = array_combine(Recipe::LEVELS, Recipe::LEVELS);
$isFiltered = $model->title !== null && $model->title !== '' || $model->id_method || $model->level;
?>
<form class="admin-filters" action="<?= Html::encode(Url::to(['index'])) ?>" method="get" role="search" aria-label="Фильтры рецептов">
  <div class="field">
    <label for="f-title">Название</label>
    <?= Html::activeTextInput($model, 'title', ['id' => 'f-title', 'placeholder' => 'Начните вводить название', 'autocomplete' => 'off']) ?>
  </div>
  <div class="field">
    <label for="f-method">Способ</label>
    <?= Html::activeDropDownList($model, 'id_method', $methods, ['id' => 'f-method', 'prompt' => 'Все способы']) ?>
  </div>
  <div class="field">
    <label for="f-level">Сложность</label>
    <?= Html::activeDropDownList($model, 'level', $levels, ['id' => 'f-level', 'prompt' => 'Любая']) ?>
  </div>
  <div class="admin-filters-actions">
    <button class="primary-button" type="submit">Найти</button>
    <?php if ($isFiltered): ?>
    <a class="secondary-button" href="<?= Html::encode(Url::to(['index'])) ?>">Сбросить</a>
    <?php endif; ?>
  </div>
</form>
