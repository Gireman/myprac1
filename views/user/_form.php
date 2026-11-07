<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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

  <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autocomplete' => 'off'])->label('Логин') ?>

  <?= $form->field($model, 'mail')->input('email', ['maxlength' => true])->label('Email') ?>

  <?= $form->field($model, 'fullname')->textInput(['maxlength' => true])->label('ФИО') ?>

  <?= $form->field($model, 'phone')->input('tel', ['maxlength' => true])->label('Телефон') ?>

  <?= $form->field($model, 'role')->input('number', ['min' => 0])->label('Роль')->hint('1 — администратор') ?>

  <div class="form-actions">
    <?= Html::submitButton('Сохранить', ['class' => 'primary-button']) ?>
    <a class="secondary-button" href="<?= Html::encode(Url::to(['view', 'id' => $model->id])) ?>">Отмена</a>
  </div>

<?php ActiveForm::end(); ?>