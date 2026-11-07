<?php

/** @var yii\web\View $this */
/** @var app\models\Favourite $model */
/** @var yii\widgets\ActiveForm $form */

use app\models\Recipe;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$users = ArrayHelper::map(User::find()->orderBy(['username' => SORT_ASC])->all(), 'id', 'username');
$recipes = ArrayHelper::map(Recipe::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title');
$cancelUrl = $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id];
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'auth-form admin-form'],
    'fieldConfig' => [
        'options' => ['class' => 'field'],
        'template' => "{label}\n{input}\n{hint}\n{error}",
        'errorOptions' => ['class' => 'field-error'],
        'hintOptions' => ['class' => 'field-hint'],
    ],
]); ?>

  <?= $form->field($model, 'id_user')->dropDownList($users, ['prompt' => 'Выберите пользователя'])->label('Пользователь') ?>

  <?= $form->field($model, 'id_recipe')->dropDownList($recipes, ['prompt' => 'Выберите рецепт'])->label('Рецепт') ?>

  <div class="form-actions">
    <?= Html::submitButton('Сохранить', ['class' => 'primary-button']) ?>
    <a class="secondary-button" href="<?= Html::encode(Url::to($cancelUrl)) ?>">Отмена</a>
  </div>

<?php ActiveForm::end(); ?>