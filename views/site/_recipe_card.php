<?php

/** @var yii\web\View $this */
/** @var app\models\Recipe $recipe */

use yii\helpers\Html;
use yii\helpers\Url;

$icon = function ($name, $size = 20) {
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><use href="#i-' . $name . '"></use></svg>';
};

list($w, $h) = $recipe->getImgSize();
$base = '@web/img/' . $recipe->img;
$methodTitle = $recipe->method ? $recipe->method->title : '';
?>
<article class="recipe-card" data-id="<?= (int)$recipe->id ?>" data-method="<?= Html::encode($methodTitle) ?>" data-minutes="<?= (int)$recipe->card_minutes ?>">
  <div class="card-image">
    <picture><source type="image/webp" srcset="<?= Url::to($base . '-400.webp') ?> 400w, <?= Url::to($base . '-800.webp') ?> 800w, <?= Url::to($base . '-1200.webp') ?> 1200w" sizes="(min-width: 1200px) 400px, (min-width: 768px) 45vw, 92vw"><img src="<?= Url::to($base . '-800.jpg') ?>" alt="<?= Html::encode($recipe->img_alt) ?>" width="<?= $w ?>" height="<?= $h ?>" loading="lazy" decoding="async"></picture>
    <span class="method-pill" style="background:<?= Html::encode($recipe->pill_color) ?>"><?= Html::encode($methodTitle) ?></span>
    <button class="favorite-button" type="button" data-favorite="<?= (int)$recipe->id ?>" aria-pressed="false" aria-label="В избранное: <?= Html::encode($recipe->title) ?>"><?= $icon('heart', 18) ?></button>
  </div>
  <div class="card-body">
    <p class="eyebrow"><?= Html::encode(implode(' · ', $recipe->getNoteTitles())) ?></p>
    <h3><a class="card-link" href="<?= Url::to(['site/recipe', 'id' => $recipe->id]) ?>"><?= Html::encode($recipe->title) ?></a></h3>
    <div class="card-meta">
      <span><?= $icon('clock', 16) ?> <?= (int)$recipe->card_minutes ?> мин</span>
      <span><?= Html::encode($recipe->level) ?></span>
      <span class="card-arrow" aria-hidden="true"><?= $icon('arrow', 18) ?></span>
    </div>
  </div>
</article>
