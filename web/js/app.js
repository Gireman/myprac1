/**
 * Crema — интерактив на чистом JavaScript.
 *
 * Модули запускаются только на тех страницах, где есть нужные элементы:
 *   initMenu      — мобильное меню (все страницы)
 *   initFavorites — избранное, хранится в БД (все страницы)
 *   initSlider    — слайдер «Рецепты недели» (index)
 *   initCatalog   — поиск, фильтры и сортировка (recipes)
 *   initMatcher   — подсветка ползунка в тесте «Подобрать вкус» (matcher)
 *   initAuthForms — проверка полей форм входа и регистрации перед отправкой на сервер
 */
(function () {
  "use strict";

  /* ---------- Вспомогательные функции ---------- */

  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  // localStorage может быть недоступен (приватный режим, запрет cookies) — не падаем
  const store = {
    get(key, fallback) {
      try {
        const raw = localStorage.getItem(key);
        return raw === null ? fallback : JSON.parse(raw);
      } catch (error) {
        return fallback;
      }
    },
    set(key, value) {
      try {
        localStorage.setItem(key, JSON.stringify(value));
      } catch (error) {
        /* ничего: просто не сохраняем */
      }
    },
    remove(key) {
      try {
        localStorage.removeItem(key);
      } catch (error) {
        /* ничего */
      }
    },
  };

  const pad2 = (n) => String(n).padStart(2, "0");

  // «1 рецепт», «2 рецепта», «5 рецептов»
  function pluralize(n, one, few, many) {
    const mod10 = n % 10;
    const mod100 = n % 100;
    if (mod10 === 1 && mod100 !== 11) return one;
    if (mod10 >= 2 && mod10 <= 4 && (mod100 < 10 || mod100 >= 20)) return few;
    return many;
  }

  /* ---------- Уведомление (toast) ---------- */

  const TOAST_KEY = "crema.toast";
  let toastTimer;

  function showToast(message) {
    const toast = $("#toast");
    const text = $("#toast-text");
    if (!toast || !text) return;
    text.textContent = message;
    toast.classList.add("show");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
  }

  // Сообщение, которое нужно показать уже на следующей странице (после redirect)
  function queueToast(message) {
    try {
      sessionStorage.setItem(TOAST_KEY, message);
    } catch (error) {
      /* ничего */
    }
  }

  function flushQueuedToast() {
    try {
      const message = sessionStorage.getItem(TOAST_KEY);
      if (message) {
        sessionStorage.removeItem(TOAST_KEY);
        showToast(message);
      }
    } catch (error) {
      /* ничего */
    }
  }

  /* ---------- Мобильное меню ---------- */

  function initMenu() {
    const button = $(".menu-button");
    const nav = $("#site-nav");
    if (!button || !nav) return;

    const desktop = window.matchMedia("(min-width: 768px)");
    const isOpen = () => button.getAttribute("aria-expanded") === "true";

    function setOpen(open) {
      nav.classList.toggle("is-open", open);
      button.setAttribute("aria-expanded", String(open));
      button.setAttribute("aria-label", open ? "Закрыть меню" : "Открыть меню");
    }

    button.addEventListener("click", () => setOpen(!isOpen()));

    // Клик по ссылке или кнопке внутри меню закрывает его
    nav.addEventListener("click", (event) => {
      if (event.target.closest("a, button")) setOpen(false);
    });

    // Клик мимо шапки закрывает меню
    document.addEventListener("click", (event) => {
      if (isOpen() && !event.target.closest(".header")) setOpen(false);
    });

    // Escape закрывает меню и возвращает фокус на кнопку
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && isOpen()) {
        setOpen(false);
        button.focus();
      }
    });

    // При переходе на планшет/десктоп меню сбрасывается
    desktop.addEventListener("change", (event) => {
      if (event.matches) setOpen(false);
    });
  }

  /* ---------- Избранное (хранится в БД, пользователь определяется на сервере) ---------- */

  // window.CREMA_FAV выводится в layout: { auth, ids, url, loginUrl, csrf }
  const FAV = Object.assign(
    { auth: false, ids: [], url: "", loginUrl: "login", csrf: "" },
    window.CREMA_FAV
  );
  let favoriteIds = new Set(FAV.ids.map(Number));
  const getFavorites = () => Array.from(favoriteIds);
  const pendingFavorites = new Set();

  function syncFavoriteButtons() {
    const favorites = getFavorites();
    $$("[data-favorite]").forEach((button) => {
      const active = favorites.includes(Number(button.dataset.favorite));
      button.classList.toggle("active", active);
      button.setAttribute("aria-pressed", String(active));
      const label = $("[data-favorite-label]", button);
      if (label) label.textContent = active ? "Сохранено" : "В избранное";
    });
  }

  function goToLogin() {
    queueToast("Войдите, чтобы сохранять рецепты");
    window.location.href = FAV.loginUrl;
  }

  async function toggleFavorite(id) {
    if (!FAV.auth) {
      goToLogin();
      return;
    }
    if (pendingFavorites.has(id)) return;
    pendingFavorites.add(id);

    try {
      const response = await fetch(FAV.url, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-Token": FAV.csrf,
        },
        body: new URLSearchParams({ id: String(id) }),
      });
      if (response.status === 401) {
        goToLogin();
        return;
      }
      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const data = await response.json();
      favoriteIds = new Set(data.ids.map(Number));
      showToast(data.active ? "Рецепт добавлен в избранное" : "Рецепт удалён из избранного");
      syncFavoriteButtons();
      document.dispatchEvent(new CustomEvent("favorites:change"));
    } catch (error) {
      showToast("Не удалось сохранить, попробуйте ещё раз");
    } finally {
      pendingFavorites.delete(id);
    }
  }

  function initFavorites() {
    // Делегирование: работает и для карточек, созданных скриптом
    document.addEventListener("click", (event) => {
      const button = event.target.closest("[data-favorite]");
      if (!button) return;
      event.preventDefault();
      toggleFavorite(Number(button.dataset.favorite));
    });
    syncFavoriteButtons();
  }

  /* ---------- Слайдер «Рецепты недели» ---------- */

  function initSlider() {
    const root = $("[data-slider]");
    if (!root) return;

    const track = $("[data-slider-track]", root);
    const prev = $("[data-slider-prev]", root);
    const next = $("[data-slider-next]", root);
    const dotsBox = $("[data-slider-dots]", root);
    const slides = $$("[data-slider-track] > li", root);
    if (!track || !slides.length) return;

    let pages = 1;
    let page = 0;

    // Сколько карточек помещается сразу (1 / 2 / 3 — задаёт CSS)
    const perView = () => {
      const slideWidth = slides[0].getBoundingClientRect().width;
      return Math.max(1, Math.round(track.clientWidth / slideWidth));
    };
    const pageWidth = () => track.clientWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);

    function goTo(index) {
      const clamped = Math.min(Math.max(index, 0), pages - 1);
      track.scrollTo({ left: clamped * pageWidth(), behavior: reduceMotion ? "auto" : "smooth" });
    }

    function buildDots() {
      pages = Math.ceil(slides.length / perView());
      dotsBox.innerHTML = "";
      for (let i = 0; i < pages; i += 1) {
        const dot = document.createElement("button");
        dot.type = "button";
        dot.className = "slider-dot";
        dot.setAttribute("aria-label", `Показать группу рецептов ${i + 1} из ${pages}`);
        dot.addEventListener("click", () => goTo(i));
        dotsBox.appendChild(dot);
      }
    }

    function update() {
      const maxScroll = track.scrollWidth - track.clientWidth;
      const atEnd = track.scrollLeft >= maxScroll - 4;
      page = atEnd ? pages - 1 : Math.round(track.scrollLeft / pageWidth());
      $$(".slider-dot", dotsBox).forEach((dot, i) => {
        const current = i === page;
        dot.classList.toggle("is-active", current);
        if (current) dot.setAttribute("aria-current", "true");
        else dot.removeAttribute("aria-current");
      });
      prev.disabled = track.scrollLeft <= 4;
      next.disabled = atEnd;
    }

    let ticking = false;
    track.addEventListener("scroll", () => {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(() => {
        update();
        ticking = false;
      });
    });

    prev.addEventListener("click", () => goTo(page - 1));
    next.addEventListener("click", () => goTo(page + 1));

    // Стрелки клавиатуры, когда фокус на ленте
    track.addEventListener("keydown", (event) => {
      if (event.key === "ArrowRight") {
        event.preventDefault();
        goTo(page + 1);
      } else if (event.key === "ArrowLeft") {
        event.preventDefault();
        goTo(page - 1);
      }
    });

    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        buildDots();
        update();
      }, 120);
    });

    buildDots();
    update();
  }

  /* ---------- Каталог: поиск, фильтры, сортировка ---------- */

  function initCatalog() {
    const list = $("#catalog-grid");
    if (!list) return;

    const items = $$("li", list);
    const search = $("#recipe-search");
    const sort = $("#recipe-sort");
    const filterButtons = $$("[data-filter]");
    const count = $("#result-count");
    const empty = $("#empty-state");
    const emptyTitle = $("#empty-title");
    const emptyText = $("#empty-text");
    const emptyReset = $("#empty-reset");

    const state = { filter: "Все", query: "", sort: "popular" };

    // Данные карточки берём прямо из разметки
    const info = items.map((li, index) => {
      const card = $(".recipe-card", li);
      return {
        li,
        index,
        id: Number(card.dataset.id),
        method: card.dataset.method,
        minutes: Number(card.dataset.minutes),
        title: $("h3", card).textContent.trim(),
        text: `${$("h3", card).textContent} ${$(".eyebrow", card).textContent}`.toLowerCase(),
      };
    });

    function matches(item) {
      const favorites = getFavorites();
      let okFilter = true;
      if (state.filter === "Избранное") okFilter = favorites.includes(item.id);
      else if (state.filter !== "Все") okFilter = item.method === state.filter;
      return okFilter && item.text.includes(state.query);
    }

    function sorted(arr) {
      const copy = arr.slice();
      if (state.sort === "time") copy.sort((a, b) => a.minutes - b.minutes || a.index - b.index);
      else if (state.sort === "title") copy.sort((a, b) => a.title.localeCompare(b.title, "ru"));
      else copy.sort((a, b) => a.index - b.index);
      return copy;
    }

    function render() {
      const visible = info.filter(matches);
      info.forEach((item) => {
        item.li.hidden = !visible.includes(item);
      });
      sorted(info).forEach((item) => list.appendChild(item.li));

      count.textContent = `${pad2(visible.length)} ${pluralize(visible.length, "рецепт", "рецепта", "рецептов")}`;
      filterButtons.forEach((button) => {
        const active = button.dataset.filter === state.filter;
        button.classList.toggle("active", active);
        button.setAttribute("aria-pressed", String(active));
      });

      const isEmpty = visible.length === 0;
      empty.hidden = !isEmpty;
      list.hidden = isEmpty;
      if (isEmpty) {
        const onlyFavorites = state.filter === "Избранное" && !state.query;
        emptyTitle.textContent = onlyFavorites ? "Здесь пока пусто" : "Ничего не найдено";
        emptyText.textContent = onlyFavorites
          ? "Добавьте рецепты в избранное — они появятся в этой подборке."
          : "Попробуйте изменить запрос или выбрать другой способ.";
        emptyReset.textContent = onlyFavorites ? "Смотреть все" : "Сбросить фильтры";
      }
    }

    filterButtons.forEach((button) => {
      button.addEventListener("click", () => {
        state.filter = button.dataset.filter;
        render();
      });
    });
    search.addEventListener("input", () => {
      state.query = search.value.trim().toLowerCase();
      render();
    });
    sort.addEventListener("change", () => {
      state.sort = sort.value;
      render();
    });
    emptyReset.addEventListener("click", () => {
      state.filter = "Все";
      state.query = "";
      search.value = "";
      render();
    });
    document.addEventListener("favorites:change", () => {
      if (state.filter === "Избранное") render();
    });

    // Ссылки с главной: recipes?method=Пуровер
    const preset = new URLSearchParams(window.location.search).get("method");
    if (preset && filterButtons.some((button) => button.dataset.filter === preset)) {
      state.filter = preset;
    }

    render();
  }

  /* ---------- «Подобрать вкус»: подсветка ползунка (сам подбор считает сервер) ---------- */

  function initMatcher() {
    const form = $("#matcher-form");
    const range = $("#acidity");
    if (!form || !range) return;

    const setRange = () => form.style.setProperty("--range", `${range.value}%`);
    range.addEventListener("input", setRange);
    setRange();
  }

  /* ---------- Формы входа и регистрации ---------- */

  const NAME_WORD = "[A-Za-zА-Яа-яЁё][A-Za-zА-Яа-яЁё'’-]+";
  const RE_FULL_NAME = new RegExp(`^${NAME_WORD}(\\s+${NAME_WORD}){1,2}$`);
  const RE_LOGIN = /^[A-Za-z0-9._-]{3,20}$/;
  const RE_EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  // Приводит ввод к виду «+7 999 000-00-00»
  function formatPhone(value) {
    let digits = value.replace(/\D/g, "");
    if (!digits) return "";
    if (digits[0] === "8") digits = `7${digits.slice(1)}`;
    else if (digits[0] !== "7") digits = `7${digits}`;
    digits = digits.slice(0, 11);
    const p = [digits.slice(1, 4), digits.slice(4, 7), digits.slice(7, 9), digits.slice(9, 11)];
    let out = "+7";
    if (p[0]) out += ` ${p[0]}`;
    if (p[1]) out += ` ${p[1]}`;
    if (p[2]) out += `-${p[2]}`;
    if (p[3]) out += `-${p[3]}`;
    return out;
  }

  // Правила: функция возвращает текст ошибки или пустую строку
  const RULES = {
    fullName(value) {
      if (!value.trim()) return "Введите фамилию и имя";
      return RE_FULL_NAME.test(value.trim())
        ? ""
        : "Укажите фамилию и имя (можно с отчеством) буквами, без цифр";
    },
    login(value, form) {
      if (!value.trim()) return "Введите логин";
      if (form.dataset.mode === "login") return "";
      return RE_LOGIN.test(value.trim())
        ? ""
        : "От 3 до 20 символов: латинские буквы, цифры, точка, дефис или подчёркивание";
    },
    phone(value) {
      if (!value.trim()) return "Введите номер телефона";
      return value.replace(/\D/g, "").length === 11 ? "" : "Номер должен содержать 11 цифр, например +7 999 000-00-00";
    },
    email(value) {
      if (!value.trim()) return "Введите email";
      return RE_EMAIL.test(value.trim()) ? "" : "Проверьте email — нужен адрес вида name@example.com";
    },
    password(value) {
      if (!value) return "Введите пароль";
      return value.length >= 6 ? "" : "Пароль — не менее 6 символов";
    },
    consent(_value, _form, input) {
      return input.checked ? "" : "Необходимо согласие с правилами и политикой";
    },
  };

  function initAuthForms() {
    $$("form[data-auth-form]").forEach((form) => {
      const fields = $$("[data-rule]", form);

      function validate(input) {
        const rule = RULES[input.dataset.rule];
        const message = rule(input.value, form, input);
        const error = document.getElementById(input.getAttribute("aria-describedby"));
        input.setAttribute("aria-invalid", message ? "true" : "false");
        input.classList.toggle("is-invalid", Boolean(message));
        if (error) {
          error.textContent = message;
          error.hidden = !message;
        }
        return !message;
      }

      fields.forEach((input) => {
        // Маска телефона
        if (input.dataset.rule === "phone") {
          input.addEventListener("input", () => {
            input.value = formatPhone(input.value);
          });
        }
        // Ошибку показываем после первого ухода с поля, затем проверяем на лету
        input.addEventListener("blur", () => {
          input.dataset.touched = "true";
          validate(input);
        });
        const liveEvent = input.type === "checkbox" ? "change" : "input";
        input.addEventListener(liveEvent, () => {
          if (input.dataset.touched === "true" || input.type === "checkbox") validate(input);
        });
      });

      form.addEventListener("submit", (event) => {
        let firstInvalid = null;
        fields.forEach((input) => {
          input.dataset.touched = "true";
          if (!validate(input) && !firstInvalid) firstInvalid = input;
        });
        if (firstInvalid) {
          event.preventDefault();
          firstInvalid.focus();
        }
        // Если всё верно, форма уходит на сервер (вход и регистрация — через Yii)
      });

      const forgot = $("[data-forgot]", form);
      if (forgot) {
        forgot.addEventListener("click", () => showToast("Восстановление пароля пока недоступно"));
      }
    });
  }

  /* ---------- Запуск ---------- */

  function init() {
    const year = $("[data-year]");
    if (year) year.textContent = String(new Date().getFullYear());

    initMenu();
    initFavorites();
    initSlider();
    initCatalog();
    initMatcher();
    initAuthForms();
    flushQueuedToast();
  }

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
  else init();
})();