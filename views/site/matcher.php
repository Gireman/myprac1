<?php

/** @var yii\web\View $this */
/** @var app\models\MatcherForm $model */
/** @var array|null $result */

use app\models\MatcherForm;
use yii\helpers\Html;
use yii\helpers\Url;

$icon = function ($name, $size = 20) {
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><use href="#i-' . $name . '"></use></svg>';
};

$questions = [
    ['name' => 'mood', 'num' => '01', 'legend' => 'Какое настроение хочется поймать?', 'options' => MatcherForm::moods()],
    ['name' => 'format', 'num' => '02', 'legend' => 'Как вы обычно пьёте кофе?', 'options' => MatcherForm::formats()],
    ['name' => 'effort', 'num' => '03', 'legend' => 'Сколько времени готовы уделить?', 'options' => MatcherForm::efforts()],
];
?>
<main id="main" class="matcher-page container">
<?php if ($result !== null): ?>
  <?php
  $parts = explode(' ', $result['title'], 2);
  $first = $parts[0];
  $rest = isset($parts[1]) ? $parts[1] : '';
  $bars = [
      ['Кислотность', $result['acidity']],
      ['Сладость', $result['sweetness']],
      ['Плотность', $result['body']],
  ];
  $editUrl = Url::to(['site/matcher',
      'mood' => $model->mood,
      'format' => $model->format,
      'effort' => $model->effort,
      'acidity' => (int)$model->acidity,
      'edit' => 1,
  ]);
  ?>
  <section id="matcher-result" aria-label="Результат подбора">
    <div class="match-result">
      <div class="result-copy">
        <p class="eyebrow">Ваш вкусовой профиль</p>
        <h1 id="result-title" tabindex="-1"><?= Html::encode($first) ?><br><em><?= Html::encode($rest) ?></em></h1>
        <p>Результат рассчитан по кислотности, плотности, формату напитка и времени, которое вы готовы уделить приготовлению.</p>
        <ul class="profile-bars">
          <?php foreach ($bars as $bar): ?>
          <li>
            <span><?= $bar[0] ?><b><?= (int)$bar[1] ?>%</b></span>
            <i><em style="width:<?= (int)$bar[1] ?>%"></em></i>
          </li>
          <?php endforeach; ?>
        </ul>
        <a class="secondary-button" href="<?= Html::encode($editUrl) ?>">Изменить ответы</a>
      </div>
      <div class="result-recipe">
        <p class="result-label">Ваш рецепт</p>
        <?= $this->render('_recipe_card', ['recipe' => $result['recipe']]) ?>
      </div>
    </div>
    <aside class="result-note">
      <?= $icon('bean', 32) ?>
      <p><b>Ищите зерно из <?= Html::encode($result['origins'][0]) ?></b><span>с нотами <?= Html::encode($result['origins'][1]) ?>.</span></p>
    </aside>
  </section>
<?php else: ?>
  <div id="matcher-start">
    <section class="matcher-intro" aria-labelledby="matcher-title">
      <div>
        <p class="eyebrow">Персональный подбор</p>
        <h1 id="matcher-title">Какой кофе<br><em>подходит вам?</em></h1>
      </div>
      <p>Нет правильных и неправильных ответов. Выбирайте то, что ближе — и мы найдём ваш рецепт.</p>
    </section>
    <?= Html::beginForm(['site/matcher'], 'get', [
        'class' => 'matcher-form',
        'id' => 'matcher-form',
        'aria-label' => 'Тест вкусовых предпочтений',
        'style' => '--range:' . (int)$model->acidity . '%',
    ]) ?>
      <?php foreach ($questions as $question): ?>
        <fieldset class="question">
          <legend><span><?= $question['num'] ?></span><?= Html::encode($question['legend']) ?></legend><br>
          <div class="choice-group">
            <?php foreach ($question['options'] as $option): ?>
            <label class="choice">
              <input type="radio" name="<?= $question['name'] ?>" value="<?= Html::encode($option) ?>"<?= $model->{$question['name']} === $option ? ' checked' : '' ?>>
              <span class="choice-box"><span class="choice-mark"><?= $icon('check', 16) ?></span><?= Html::encode($option) ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </fieldset>
      <?php endforeach; ?>
        <fieldset class="question">
          <legend><span>04</span>Как относитесь к кислинке?</legend><br><br>
          <div class="range-wrap">
            <label class="visually-hidden" for="acidity">Любовь к кислинке: от «Не люблю» до «Обожаю»</label>
            <input id="acidity" name="acidity" type="range" min="0" max="100" value="<?= (int)$model->acidity ?>">
            <div class="range-scale" aria-hidden="true"><span>Не люблю</span><span>Обожаю</span></div>
          </div>
        </fieldset>
        <button class="primary-button submit-match" type="submit">Показать мой профиль <?= $icon('arrow') ?></button>
    <?= Html::endForm() ?>
  </div>
<?php endif; ?>
</main>