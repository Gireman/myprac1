/* Админка: форма рецепта — повторяемые списки, ползунки, цвет, предпросмотр картинки */
(function () {
  "use strict";

  /* ---------- Повторяемые списки (ноты, оборудование, шаги) ---------- */
  function initRepeater(root) {
    var list = root.querySelector("[data-list]");
    var template = root.querySelector("[data-template]");
    var addButton = root.querySelector("[data-add]");
    var base = root.dataset.name; // например, Recipe[stepsInput]

    function rows() {
      return Array.prototype.slice.call(list.querySelectorAll(":scope > [data-row]"));
    }

    // Имена полей всегда идут подряд (0, 1, 2…), поэтому порядок строк = порядок на сайте
    function renumber() {
      var all = rows();
      all.forEach(function (row, i) {
        var num = row.querySelector("[data-num]");
        if (num) num.textContent = String(i + 1).padStart(2, "0");
        row.querySelectorAll("[data-field]").forEach(function (input) {
          var field = input.dataset.field;
          input.name = field ? base + "[" + i + "][" + field + "]" : base + "[" + i + "]";
        });
        var up = row.querySelector('[data-action="up"]');
        var down = row.querySelector('[data-action="down"]');
        if (up) up.disabled = i === 0;
        if (down) down.disabled = i === all.length - 1;
      });
    }

    addButton.addEventListener("click", function () {
      list.appendChild(template.content.cloneNode(true));
      renumber();
      var last = rows().pop();
      var first = last && last.querySelector("input, textarea");
      if (first) first.focus();
    });

    list.addEventListener("click", function (event) {
      var button = event.target.closest("[data-action]");
      if (!button || !list.contains(button)) return;
      var row = button.closest("[data-row]");
      var action = button.dataset.action;

      if (action === "remove") {
        row.remove();
      } else if (action === "up" && row.previousElementSibling) {
        list.insertBefore(row, row.previousElementSibling);
      } else if (action === "down" && row.nextElementSibling) {
        list.insertBefore(row.nextElementSibling, row);
      }
      renumber();
      if (action !== "remove") button.focus();
    });

    // Enter в однострочном поле не должен отправлять всю форму
    list.addEventListener("keydown", function (event) {
      if (event.key === "Enter" && event.target.tagName === "INPUT") {
        event.preventDefault();
        var next = event.target.closest("[data-row]").nextElementSibling;
        if (next) {
          var field = next.querySelector("input, textarea");
          if (field) field.focus();
        } else {
          addButton.click();
        }
      }
    });

    renumber();
  }

  document.querySelectorAll("[data-repeater]").forEach(initRepeater);

  /* ---------- Ползунки вкуса: показываем текущее значение ---------- */
  document.querySelectorAll("[data-range]").forEach(function (input) {
    var output = input.parentElement.querySelector("output");
    function sync() {
      if (output) output.textContent = input.value;
    }
    input.addEventListener("input", sync);
    sync();
  });

  /* ---------- Быстрый выбор цвета плашки ---------- */
  document.querySelectorAll(".swatch[data-color]").forEach(function (swatch) {
    swatch.addEventListener("click", function () {
      var field = swatch.closest(".field");
      var input = field && field.querySelector('input[type="color"]');
      if (input) input.value = swatch.dataset.color;
    });
  });

  /* ---------- Предпросмотр картинки ---------- */
  var picker = document.querySelector(".img-picker");
  if (picker) {
    var box = picker.querySelector("[data-preview-box]");
    var preview = picker.querySelector("[data-preview]");
    var select = document.querySelector("[data-img-select]");
    var file = document.querySelector("[data-img-file]");
    var objectUrl = null;

    function show(src) {
      if (src) {
        preview.src = src;
        box.hidden = false;
      } else {
        preview.removeAttribute("src");
        box.hidden = true;
      }
    }

    if (select) {
      select.addEventListener("change", function () {
        if (file && file.files.length) return; // загруженный файл важнее списка
        show(select.value ? picker.dataset.imgBase + "/" + select.value + "-400.webp" : "");
      });
    }

    if (file) {
      file.addEventListener("change", function () {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = null;
        if (file.files.length) {
          objectUrl = URL.createObjectURL(file.files[0]);
          show(objectUrl);
        } else if (select && select.value) {
          show(picker.dataset.imgBase + "/" + select.value + "-400.webp");
        } else {
          show("");
        }
      });
    }
  }
})();
