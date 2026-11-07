<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */

use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

$fieldConfig = [
    'options' => ['class' => 'field'],
    'template' => "{label}\n{input}\n{error}",
    'errorOptions' => ['class' => 'field-error', 'tag' => 'p'],
];

$invalid = function ($attr) use ($model) {
    return $model->hasErrors($attr) ? 'true' : 'false';
};
?>
<main id="main" class="auth-page">
  <section class="auth-aside" aria-labelledby="auth-title">
    <div>
      <h1 id="auth-title">Ваш кофе.<br><em>Ваши правила.</em></h1>
    </div>
    <div class="auth-quote">
      <svg class="icon" width="26" height="26" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg>
      <p>«Лучший рецепт — тот, который хочется повторить завтра».</p>
      <span>Crema notes · 08</span>
    </div>
  </section>
  <section class="auth-panel" aria-labelledby="auth-heading">
    <div class="auth-panel-inner">
      <p class="auth-step">Новый профиль</p>
      <h2 id="auth-heading">Создать аккаунт</h2>
      <br>

      <?php $form = ActiveForm::begin([
          'id' => 'registration-form',
          'options' => ['class' => 'auth-form', 'novalidate' => true, 'data-mode' => 'register'],
          'enableClientValidation' => false,
          'fieldConfig' => $fieldConfig,
      ]); ?>

          <?= $form->field($model, 'fullname')
              ->label('ФИО', ['for' => 'reg-name'])
              ->textInput([
                  'id' => 'reg-name',
                  'autocomplete' => 'name',
                  'required' => true,
                  'data-rule' => 'fullname',
                  'aria-invalid' => $invalid('fullname'),
              ]) ?>

          <?= $form->field($model, 'username')
              ->label('Логин', ['for' => 'reg-login'])
              ->textInput([
                  'id' => 'reg-login',
                  'autocomplete' => 'username',
                  'required' => true,
                  'data-rule' => 'login',
                  'aria-invalid' => $invalid('username'),
              ]) ?>

          <?= $form->field($model, 'phone')
              ->label('Телефон', ['for' => 'reg-phone'])
              ->input('tel', [
                  'id' => 'reg-phone',
                  'autocomplete' => 'tel',
                  'inputmode' => 'tel',
                  'required' => true,
                  'data-rule' => 'phone',
                  'aria-invalid' => $invalid('phone'),
              ]) ?>

          <?= $form->field($model, 'mail')
              ->label('Email', ['for' => 'reg-email'])
              ->input('email', [
                  'id' => 'reg-email',
                  'autocomplete' => 'email',
                  'required' => true,
                  'data-rule' => 'email',
                  'aria-invalid' => $invalid('mail'),
              ]) ?>

          <?= $form->field($model, 'password')
              ->label('Пароль', ['for' => 'reg-password'])
              ->passwordInput([
                  'id' => 'reg-password',
                  'autocomplete' => 'new-password',
                  'required' => true,
                  'minlength' => 6,
                  'data-rule' => 'password',
                  'aria-invalid' => $invalid('password'),
              ]) ?>

          <?= $form->field($model, 'consent', [
              'template' => "<label class=\"remember consent\">{input}<span class=\"check-box\"></span>\n              Согласен с правилами и политикой конфиденциальности\n            </label>\n{error}",
          ])->checkbox([
              'id' => 'reg-consent',
              'required' => true,
              'data-rule' => 'consent',
              'aria-invalid' => $invalid('consent'),
              'uncheck' => null,
          ], false) ?>

        <button class="primary-button auth-submit" type="submit">Создать аккаунт <svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></button>

      <?php ActiveForm::end(); ?>

      <p class="auth-switch">Уже есть аккаунт? <a href="login">Войти</a></p>
    </div>
  </section>
</main>