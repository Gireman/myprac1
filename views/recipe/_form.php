<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe $model */
/** @var yii\widgets\ActiveForm $form */

use app\components\RecipeImage;
use app\models\Equipment;
use app\models\Method;
use app\models\Note;
use app\models\Recipe;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$jsFile = Yii::getAlias('@webroot/js/admin-recipe.js');
$this->registerJsFile('@web/js/admin-recipe.js?v=' . (is_file($jsFile) ? filemtime($jsFile) : 1), ['position' => View::POS_END]);

$methods = ArrayHelper::map(Method::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'title');
$levels = array_combine(Recipe::LEVELS, Recipe::LEVELS);
$images = RecipeImage::existing();
$images = array_combine($images, $images);
$noteTitles = ArrayHelper::getColumn(Note::find()->orderBy(['title' => SORT_ASC])->all(), 'title');
$equipmentTitles = ArrayHelper::getColumn(Equipment::find()->orderBy(['title' => SORT_ASC])->all(), 'title');
$cancelUrl = $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id];
$imgBase = Url::to('@web/img');
$currentImg = $model->img && RecipeImage::exists($model->img) ? $model->img : null;

// Цвета, которые сайт уже использует для плашек и нот
$swatches = ['#d8f35d', '#efa3a3', '#afd6ed'];

/**
 * Строка повторяемого списка (ноты, оборудование, шаги).
 * Имена полей JS перенумеровывает сам при каждом изменении.
 */
$repeaterRow = function ($kind, $index, $values) {
    $base = 'Recipe[' . $kind . 'Input][' . $index . ']';
    if ($kind === 'steps') {
        $fields = Html::textInput($base . '[title]', $values['title'] ?? '', [
            'class' => 'repeater-input',
            'maxlength' => 255,
            'placeholder' => 'Название шага, например «Промойте фильтр»',
            'aria-label' => 'Название шага',
            'data-field' => 'title',
        ]) . Html::textarea($base . '[text]', $values['text'] ?? '', [
            'class' => 'repeater-input',
            'rows' => 2,
            'maxlength' => 255,
            'placeholder' => 'Описание шага (до 255 символов)',
            'aria-label' => 'Описание шага',
            'data-field' => 'text',
        ]);
    } else {
        $fields = Html::textInput($base, is_string($values) ? $values : '', [
            'class' => 'repeater-input',
            'maxlength' => 255,
            'list' => $kind === 'notes' ? 'dl-notes' : 'dl-equipment',
            'placeholder' => $kind === 'notes' ? 'Например, «Цитрус»' : 'Например, «Воронка V60»',
            'aria-label' => $kind === 'notes' ? 'Вкусовая нота' : 'Оборудование',
            'data-field' => '',
            'autocomplete' => 'off',
        ]);
    }

    return '<li class="repeater-row" data-row>'
        . '<span class="repeater-num" data-num aria-hidden="true"></span>'
        . '<div class="repeater-fields">' . $fields . '</div>'
        . '<div class="repeater-controls">'
        . '<button type="button" class="repeater-btn" data-action="up" aria-label="Поднять выше">↑</button>'
        . '<button type="button" class="repeater-btn" data-action="down" aria-label="Опустить ниже">↓</button>'
        . '<button type="button" class="repeater-btn repeater-btn--danger" data-action="remove" aria-label="Удалить">×</button>'
        . '</div></li>';
};

/**
 * Блок повторяемого списка целиком.
 */
$repeater = function ($kind, $legend, $hint, $addLabel, array $rows) use ($model, $repeaterRow) {
    $attribute = $kind . 'Input';
    $html = '<fieldset class="form-section repeater" data-repeater data-name="Recipe[' . $attribute . ']">';
    $html .= '<legend>' . Html::encode($legend) . '</legend>';
    $html .= '<p class="field-hint">' . Html::encode($hint) . '</p>';
    $html .= '<ol class="repeater-list" data-list>';
    foreach (array_values($rows) as $i => $values) {
        $html .= $repeaterRow($kind, $i, $values);
    }
    $html .= '</ol>';
    $html .= '<template data-template>' . $repeaterRow($kind, 0, $kind === 'steps' ? ['title' => '', 'text' => ''] : '') . '</template>';
    $html .= '<button type="button" class="secondary-button repeater-add" data-add>' . Html::encode($addLabel) . '</button>';
    if ($model->hasErrors($attribute)) {
        $html .= '<ul class="field-error repeater-errors">';
        foreach ($model->getErrors($attribute) as $error) {
            $html .= '<li>' . Html::encode($error) . '</li>';
        }
        $html .= '</ul>';
    }
    return $html . '</fieldset>';
};

$steps = $model->stepsInput ?: [];
if (!$steps && $model->isNewRecord && !$model->hasErrors()) {
    $steps = [['title' => '', 'text' => '']]; // на новой странице сразу показываем первый шаг
}
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => ['class' => 'auth-form admin-form admin-form--wide', 'enctype' => 'multipart/form-data', 'novalidate' => true],
    'fieldConfig' => [
        'options' => ['class' => 'field'],
        'template' => "{label}\n{input}\n{hint}\n{error}",
        'errorOptions' => ['class' => 'field-error'],
        'hintOptions' => ['class' => 'field-hint'],
    ],
]); ?>

  <?php if ($model->hasErrors('general')): ?>
  <div class="admin-alert admin-alert--error" role="alert"><?= Html::encode($model->getFirstError('general')) ?></div>
  <?php elseif ($model->hasErrors()): ?>
  <div class="admin-alert admin-alert--error" role="alert">Рецепт не сохранён — проверьте отмеченные поля.</div>
  <?php endif; ?>

  <fieldset class="form-section">
    <legend>Основное</legend>
    <div class="form-grid">
      <?= $form->field($model, 'title', ['options' => ['class' => 'field field--full']])->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

      <?= $form->field($model, 'id_method')->dropDownList($methods, ['prompt' => 'Выберите способ']) ?>

      <?= $form->field($model, 'level')->dropDownList($levels) ?>

      <?= $form->field($model, 'roast')->textInput(['maxlength' => true])->hint('Например: светлая, средняя') ?>

      <?= $form->field($model, 'card_minutes')->input('number', ['min' => 1, 'max' => 600]) ?>

      <?= $form->field($model, 'description', ['options' => ['class' => 'field field--full']])->textarea(['rows' => 3, 'maxlength' => 255])->hint('Короткое описание под заголовком рецепта, до 255 символов.') ?>
    </div>
  </fieldset>

  <fieldset class="form-section">
    <legend>Изображение</legend>
    <div class="form-grid">
      <div class="field field--full img-picker" data-img-base="<?= Html::encode($imgBase) ?>">
        <div class="img-preview" data-preview-box<?= $currentImg ? '' : ' hidden' ?>>
          <img data-preview alt="Предпросмотр" width="160" height="160" src="<?= $currentImg ? Html::encode($imgBase . '/' . $currentImg . '-400.webp') : '' ?>">
        </div>
      </div>

      <?= $form->field($model, 'img')->dropDownList($images, ['prompt' => 'Выберите из библиотеки', 'data-img-select' => '1']) ?>

      <?= $form->field($model, 'imgFile')->fileInput(['accept' => 'image/jpeg,image/png,image/webp', 'data-img-file' => '1'])->hint('JPG, PNG или WebP от 800 px по ширине, до 8 МБ. Версии 400/800/1200 px создаются автоматически. Если указаны и файл, и картинка из списка — берётся файл.') ?>

      <?= $form->field($model, 'img_alt', ['options' => ['class' => 'field field--full']])->textInput(['maxlength' => true])->hint('Для скринридеров и на случай, если картинка не загрузится.') ?>

      <?= $form->field($model, 'pill_color', [
          'template' => "{label}\n<div class=\"color-row\">{input}<span class=\"swatches\">"
              . implode('', array_map(function ($c) {
                  return '<button type="button" class="swatch" data-color="' . $c . '" style="background:' . $c . '" aria-label="Цвет ' . $c . '"></button>';
              }, $swatches))
              . "</span></div>\n{hint}\n{error}",
      ])->input('color')->hint('Фон плашки со способом на карточке рецепта.') ?>
    </div>
  </fieldset>

  <fieldset class="form-section">
    <legend>Пропорции и совет</legend>
    <div class="form-grid">
      <?= $form->field($model, 'fact_coffee')->textInput(['maxlength' => true])->hint('Например: 15 г') ?>

      <?= $form->field($model, 'fact_water')->textInput(['maxlength' => true])->hint('Например: 250 мл, 94 °C') ?>

      <?= $form->field($model, 'fact_time')->textInput(['maxlength' => true])->hint('Например: 2:30') ?>

      <?= $form->field($model, 'tip', ['options' => ['class' => 'field field--full']])->textarea(['rows' => 2, 'maxlength' => 255]) ?>
    </div>
  </fieldset>

  <fieldset class="form-section">
    <legend>Подбор вкуса</legend>
    <p class="field-hint">По этим значениям страница «Подобрать вкус» выбирает рецепт.</p>
    <div class="form-grid">
      <?php foreach (['taste_acidity', 'taste_body', 'taste_sweetness'] as $attr): ?>
      <?= $form->field($model, $attr, [
          'template' => "{label}\n<div class=\"range-row\">{input}<output class=\"range-value\">" . (int)$model->$attr . "</output></div>\n{hint}\n{error}",
      ])->input('range', ['min' => 0, 'max' => 100, 'step' => 1, 'data-range' => '1']) ?>
      <?php endforeach; ?>

      <?= $form->field($model, 'has_milk')->dropDownList([0 => 'Без молока', 1 => 'С молоком']) ?>

      <?= $form->field($model, 'taste_minutes')->input('number', ['min' => 1, 'max' => 600])->hint('Подбор считает: 2 — «до 2 минут», 5 — «5 минут», 9 — «можно не спешить».') ?>
    </div>
  </fieldset>

  <?= $repeater('notes', 'Вкусовые ноты', 'Порядок задаёт порядок на сайте. Начните вводить — подскажем существующие; новой ноты в списке нет — она создастся сама.', '+ Добавить ноту', $model->notesInput) ?>

  <?= $repeater('equipment', 'Оборудование', 'Список «Что понадобится» на странице рецепта. Новое оборудование тоже добавится в справочник само.', '+ Добавить оборудование', $model->equipmentInput) ?>

  <?= $repeater('steps', 'Шаги приготовления', 'Минимум один шаг. Нумерация на сайте — по порядку в этом списке.', '+ Добавить шаг', $steps) ?>

  <datalist id="dl-notes">
    <?php foreach ($noteTitles as $title): ?><option value="<?= Html::encode($title) ?>"><?php endforeach; ?>
  </datalist>
  <datalist id="dl-equipment">
    <?php foreach ($equipmentTitles as $title): ?><option value="<?= Html::encode($title) ?>"><?php endforeach; ?>
  </datalist>

  <div class="form-actions">
    <?= Html::submitButton($model->isNewRecord ? 'Создать рецепт' : 'Сохранить', ['class' => 'primary-button']) ?>
    <a class="secondary-button" href="<?= Html::encode(Url::to($cancelUrl)) ?>">Отмена</a>
  </div>

<?php ActiveForm::end(); ?>
