const redactor_plugins_lang_uk = {
    // Alignment plugin
    align: "Вирівнювання",
    "align-left": "По лівій стороні",
    "align-center": "По центру",
    "align-right": "По правій стороні",
    "align-justify": "По всій довжині",
    // Clips plugin
    clips: "Фрагменти",
    "clips-select": "Будь ласка, оберіть фрагмент",
    // Counter plugin
    words: "слів",
    chars: "символів",
    // Fontcolor plugin
    fontcolor: "Колір тексту",
    text: "Колір тексту",
    highlight: "Колір фону",
    // Fontsize plugin
    size: "Розмір",
    "remove-size": "Прибрати опцію розміру",
    // Specialchars plugin
    specialchars: "Спеціальні символи",
    // Table plugin
    table: "Таблиця",
    "insert-table": "Вставити таблицю",
    "insert-row-above": "Вставити рядок внизу",
    "insert-row-below": "Вставити рядок зверху",
    "insert-column-left": "Вставити колонку зліва",
    "insert-column-right": "Вставити колонку справа",
    "add-head": "Додати заголовок таблиці",
    "delete-head": "Видалити заголовок таблиці",
    "delete-column": "Видалити колонку",
    "delete-row": "Видалити рядок",
    "delete-table": "Видалити таблицю",
    // Video plugin
    video: "Відео",
    "video-html-code": "Код вставлення відео чи посилання на Youtube/Vimeo",
};

if (typeof Redactor.lang.uk !== "undefined") {
    Redactor.lang.uk = { ...Redactor.lang.uk, ...redactor_plugins_lang_uk };
}
