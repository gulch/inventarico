const redactor_plugins_lang_ru = {
    // Alignment plugin
    align: "Виравнивание",
    "align-left": "По левой стороне",
    "align-center": "По центру",
    "align-right": "По правой стороне",
    "align-justify": "По всей длине",
    // Clips plugin
    clips: "Фрагменты",
    "clips-select": "Пожалуйста, выберите фрагмент",
    // Counter plugin
    words: "слов",
    chars: "символов",
    // Fontcolor plugin
    fontcolor: "Цвет текста",
    text: "Цвет текста",
    highlight: "Цвет фона",
    // Fontsize plugin
    size: "Размер",
    "remove-size": "Убрать опцию розмера",
    // Specialchars plugin
    specialchars: "Специальные символы",
    // Table plugin
    table: "Таблица",
    "insert-table": "Вставить таблицу",
    "insert-row-above": "Вставить строку внизу",
    "insert-row-below": "Вставить строку сверху",
    "insert-column-left": "Вставити колонку слева",
    "insert-column-right": "Вставить колонку справа",
    "add-head": "Добавить заголовок таблицы",
    "delete-head": "Удалить заголовок таблицы",
    "delete-column": "Удалить колонку",
    "delete-row": "Удалить строку",
    "delete-table": "Удалить таблицу",
    // Video plugin
    video: "Видео",
    "video-html-code": "Код вставки видео или ссылки на Youtube/Vimeo",
};

if (typeof Redactor.lang.uk !== "undefined") {
    Redactor.lang.uk = { ...Redactor.lang.uk, ...redactor_plugins_lang_uk };
}
