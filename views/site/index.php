<?php 
use yii\helpers\Url;
?>
<main id="main">
  <section class="hero container" aria-labelledby="hero-title">
    <div class="hero-copy">
      <p class="eyebrow">Коллекция рецептов кофе</p>
      <h1 id="hero-title">Хороший кофе<br>начинается <em>дома</em></h1>
      <p class="hero-lead">Понятные рецепты, точные пропорции и всё об оборудовании — для вашей идеальной чашки.</p>
      <div class="hero-actions">
        <a class="primary-button" href="recipes">Смотреть рецепты <svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></a>
        <a class="secondary-button" href="matcher">Подобрать по вкусу</a>
      </div>
      <ul class="hero-stats">
        <li><strong>36</strong><span>проверенных рецептов</span></li>
        <li><strong>08</strong><span>способов заваривания</span></li>
        <li><strong>4.9</strong><span>средняя оценка</span></li>
      </ul>
    </div>
    <div class="hero-visual">
      <picture><source type="image/webp" srcset="<?= Url::to("@web/img/hero-400.webp") ?> 400w, <?= Url::to("@web/img/hero-800.webp") ?> 800w, <?= Url::to("@web/img/hero-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 560px, (min-width: 768px) 45vw, 92vw"><img src="img/hero-800.jpg" alt="Бариста переливает кофе из колбы в чашку" width="800" height="1200" fetchpriority="high" decoding="async"></picture>
      <div class="hero-note">
        <span class="note-icon"><svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg></span>
        <p><b>Выбор редакции</b><small>Светлая обжарка · Эфиопия</small></p>
      </div>
      <span class="hero-stamp" aria-hidden="true">brew<br>slowly</span>
    </div>
  </section>

  <section class="content-section method-section container" aria-labelledby="methods-title">
    <div class="section-title">
      <div>
        <p class="eyebrow">Выберите свой способ</p>
        <h2 id="methods-title">Как будем заваривать?</h2>
      </div>
      <a class="text-link" href="recipes"><span class="text-link-label">Все методы</span><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></a>
    </div>
    <ul class="method-grid">
        <li>
          <a class="method-card" href="recipes?method=%D0%9F%D1%83%D1%80%D0%BE%D0%B2%D0%B5%D1%80" style="--card-color:#d8f35d">
            <span class="method-number">01</span>
            <svg class="icon" width="32" height="32" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg>
            <h3>Пуровер</h3>
            <p>Чисто и ярко</p>
            <span class="method-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></span>
          </a>
        </li>
        <li>
          <a class="method-card" href="recipes?method=%D0%AD%D1%81%D0%BF%D1%80%D0%B5%D1%81%D1%81%D0%BE" style="--card-color:#efa3a3">
            <span class="method-number">02</span>
            <svg class="icon" width="32" height="32" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg>
            <h3>Эспрессо</h3>
            <p>Плотно и быстро</p>
            <span class="method-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></span>
          </a>
        </li>
        <li>
          <a class="method-card" href="recipes?method=%D0%98%D0%BC%D0%BC%D0%B5%D1%80%D1%81%D0%B8%D1%8F" style="--card-color:#afd6ed">
            <span class="method-number">03</span>
            <svg class="icon" width="32" height="32" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg>
            <h3>Иммерсия</h3>
            <p>Мягко и стабильно</p>
            <span class="method-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></span>
          </a>
        </li>
        <li>
          <a class="method-card" href="recipes?method=%D0%9C%D0%BE%D0%BB%D0%BE%D1%87%D0%BD%D1%8B%D0%B9" style="--card-color:#f5cf77">
            <span class="method-number">04</span>
            <svg class="icon" width="32" height="32" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg>
            <h3>С молоком</h3>
            <p>Нежно и сладко</p>
            <span class="method-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></span>
          </a>
        </li>
    </ul>
  </section>

  <section class="content-section container" aria-labelledby="week-title">
    <div class="section-title">
      <div>
        <p class="eyebrow">Популярное сейчас</p>
        <h2 id="week-title">Рецепты недели</h2>
      </div>
      <a class="text-link" href="recipes"><span class="text-link-label">Вся коллекция</span><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></a>
    </div>
    <div class="slider" data-slider role="region" aria-roledescription="карусель" aria-label="Рецепты недели">
      <ul class="slider-track" data-slider-track tabindex="0" aria-label="Список рецептов, листайте стрелками">
        <li>
          <article class="recipe-card" data-id="1" data-method="Пуровер" data-minutes="4">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/v60-400.webp") ?> 400w, <?= Url::to("@web/img/v60-800.webp") ?> 800w, <?= Url::to("@web/img/v60-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/v60-800.jpg" alt="Чашка кофе на тёмной каменной поверхности, вид сверху" width="800" height="533" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#d8f35d">Пуровер</span>
              <button class="favorite-button" type="button" data-favorite="1" aria-pressed="false" aria-label="В избранное: V60: чистая классика"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Цитрус · жасмин · мёд</p>
              <h3><a class="card-link" href="recipe?id=1">V60: чистая классика</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 4 мин</span>
                <span>Средне</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
        <li>
          <article class="recipe-card" data-id="2" data-method="Эспрессо" data-minutes="1">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/espresso-400.webp") ?> 400w, <?= Url::to("@web/img/espresso-800.webp") ?> 800w, <?= Url::to("@web/img/espresso-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/espresso-800.jpg" alt="Эспрессо вытекает из портафильтра" width="800" height="1199" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#efa3a3">Эспрессо</span>
              <button class="favorite-button" type="button" data-favorite="2" aria-pressed="false" aria-label="В избранное: Эспрессо 1:2"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Какао · вишня · карамель</p>
              <h3><a class="card-link" href="recipe?id=2">Эспрессо 1:2</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 1 мин</span>
                <span>Точно</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
        <li>
          <article class="recipe-card" data-id="3" data-method="Иммерсия" data-minutes="3">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/aeropress-400.webp") ?> 400w, <?= Url::to("@web/img/aeropress-800.webp") ?> 800w, <?= Url::to("@web/img/aeropress-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/aeropress-800.jpg" alt="Маленькая чашка кофе на белом фоне, вид сверху" width="800" height="1000" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#afd6ed">Иммерсия</span>
              <button class="favorite-button" type="button" data-favorite="3" aria-pressed="false" aria-label="В избранное: Аэропресс: мягкий"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Орех · яблоко · тростник</p>
              <h3><a class="card-link" href="recipe?id=3">Аэропресс: мягкий</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 3 мин</span>
                <span>Легко</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
        <li>
          <article class="recipe-card" data-id="4" data-method="Молочный" data-minutes="5">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/cappuccino-400.webp") ?> 400w, <?= Url::to("@web/img/cappuccino-800.webp") ?> 800w, <?= Url::to("@web/img/cappuccino-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/cappuccino-800.jpg" alt="Кофе наливается в белую чашку из кофемашины" width="800" height="1200" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#f5cf77">Молочный</span>
              <button class="favorite-button" type="button" data-favorite="4" aria-pressed="false" aria-label="В избранное: Капучино 6 oz"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Шоколад · фундук · сливки</p>
              <h3><a class="card-link" href="recipe?id=4">Капучино 6 oz</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 5 мин</span>
                <span>Средне</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
        <li>
          <article class="recipe-card" data-id="5" data-method="Капельный" data-minutes="7">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/filter-400.webp") ?> 400w, <?= Url::to("@web/img/filter-800.webp") ?> 800w, <?= Url::to("@web/img/filter-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/filter-800.jpg" alt="Эспрессо-машина, медный питчер и стеклянная чашка с кофе" width="800" height="1200" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#c9b9ed">Капельный</span>
              <button class="favorite-button" type="button" data-favorite="5" aria-pressed="false" aria-label="В избранное: Фильтр на машине"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Сухофрукты · какао · специи</p>
              <h3><a class="card-link" href="recipe?id=5">Фильтр на машине</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 7 мин</span>
                <span>Легко</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
        <li>
          <article class="recipe-card" data-id="6" data-method="Молочный" data-minutes="4">
            <div class="card-image">
              <picture><source type="image/webp" srcset="<?= Url::to("@web/img/hero-400.webp") ?> 400w, <?= Url::to("@web/img/hero-800.webp") ?> 800w, <?= Url::to("@web/img/hero-1200.webp") ?> 1200w" sizes="(min-width: 1200px) 360px, (min-width: 768px) 45vw, 92vw"><img src="img/hero-800.jpg" alt="Бариста переливает кофе из колбы в чашку" width="800" height="1200" loading="lazy" decoding="async"></picture>
              <span class="method-pill" style="background:#dcc5a9">Молочный</span>
              <button class="favorite-button" type="button" data-favorite="6" aria-pressed="false" aria-label="В избранное: Флэт уайт"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-heart"></use></svg></button>
            </div>
            <div class="card-body">
              <p class="eyebrow">Карамель · какао · ваниль</p>
              <h3><a class="card-link" href="recipe?id=6">Флэт уайт</a></h3>
              <div class="card-meta">
                <span><svg class="icon" width="16" height="16" aria-hidden="true" focusable="false"><use href="#i-clock"></use></svg> 4 мин</span>
                <span>Средне</span>
                <span class="card-arrow" aria-hidden="true"><svg class="icon" width="18" height="18" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></span>
              </div>
            </div>
          </article>
        </li>
      </ul>
      <div class="slider-controls">
        <button class="icon-button" type="button" data-slider-prev aria-label="Предыдущие рецепты"><svg class="icon icon-flip" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></button>
        <div class="slider-dots" data-slider-dots></div>
        <button class="icon-button" type="button" data-slider-next aria-label="Следующие рецепты"><svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-chevron"></use></svg></button>
      </div>
    </div>
  </section>

  <section class="taste-banner container" aria-labelledby="taste-title">
    <div class="taste-banner-copy">
      <p class="eyebrow">Не знаете, с чего начать?</p>
      <h2 id="taste-title">Найдите свой<br><em>вкусовой профиль</em></h2>
      <p>Ответьте на четыре простых вопроса — подберём зерно и способ, которые вам понравятся.</p>
      <a class="dark-button" href="matcher">Пройти тест <svg class="icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#i-arrow"></use></svg></a>
    </div>
    <div class="taste-orbit" aria-hidden="true">
      <div class="orbit-center"><svg class="icon" width="44" height="44" aria-hidden="true" focusable="false"><use href="#i-bean"></use></svg></div>
      <span class="orbit-tag tag-one">сладость</span>
      <span class="orbit-tag tag-two">кислотность</span>
      <span class="orbit-tag tag-three">плотность</span>
      <span class="orbit-tag tag-four">аромат</span>
    </div>
  </section>
</main>