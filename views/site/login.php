<?php

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Вход';
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
      <p class="auth-step">С возвращением</p>
      <h2 id="auth-heading">Войти в коллекцию</h2>
      <br><br>

      <?php $form = ActiveForm::begin([
          'id' => 'login-form',
          'options' => ['class' => 'auth-form', 'novalidate' => true, 'data-mode' => 'login'],
          'enableClientValidation' => false,
          'fieldConfig' => [
              'options' => ['class' => 'field'],
              'template' => "{label}\n{input}\n{error}",
              'errorOptions' => ['class' => 'field-error', 'tag' => 'p'],
          ],
      ]); ?>

          <?= $form->field($model, 'username')
              ->label('Логин', ['for' => 'login-login'])
              ->textInput([
                  'id' => 'login-login',
                  'autocomplete' => 'username',
                  'required' => true,
                  'data-rule' => 'login',
                  'aria-invalid' => $model->hasErrors('username') ? 'true' : 'false',
              ]) ?>

          <?= $form->field($model, 'password')
              ->label('Пароль', ['for' => 'login-password'])
              ->passwordInput([
                  'id' => 'login-password',
                  'autocomplete' => 'current-password',
                  'required' => true,
                  'data-rule' => 'password',
                  'aria-invalid' => $model->hasErrors('password') ? 'true' : 'false',
              ]) ?>

        <button class="primary-button auth-submit" type="submit">Войти <svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></button>

      <?php ActiveForm::end(); ?>

      <p class="auth-switch">Ещё нет аккаунта? <a href="<?= Url::to(['user/create']) ?>">Зарегистрироваться</a></p>
    </div>
  </section>
</main>