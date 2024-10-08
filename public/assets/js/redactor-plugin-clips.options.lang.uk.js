const redactor_plugin_clips_options_lang_uk = {
    clips: [
        ['OFFICIAL WEBSITE', '<a href="https://website.com" target="_blank" rel="nofollow"><b>OFFICIAL WEBSITE</b></a>'],
        ['купив на Aliexpress за XXX', 'купив на <a href="https://www.aliexpress.com" target="_blank" rel="nofollow"><b>Aliexpress</b></a> за XXX'],
        ['купив на Amazon за XXX', 'купив на <a href="https://amazon.com" target="_blank" rel="nofollow"><b>Amazon</b></a> за XXX'],
        ['продав за XXX грн Xшт через OLX доставку в Київ - DD.MM.2022', '<p>&gt;&gt;</p><p>продав за <strong><span style="color: rgb(97, 189, 109);">XXX</span>&nbsp;</strong>грн <strong>Xшт&nbsp;</strong>через OLX доставку в Київ - DD.MM.2022</p>'],
        ['отримав посилку', '<p>&gt;&gt;</p><p>отримав посилку - DD.MM.2022</p>'],
        ['виставив на продаж', '<p>&gt;&gt;</p><p>виставив на продаж - DD.MM.2022</p>'],
        ['отримав посилку у відділенні Укрпошта 34402', 'отримав посилку у відділенні Укрпошта 34402'],
        ['отримав посилку у відділенні Нова Пошта Вараш 2', 'отримав посилку у відділенні Нова Пошта Вараш 2'],
        ['отримав посилку у відділенні Meest Вараш 11', 'отримав посилку у відділенні Meest Вараш 11'],
        ['*** скоригувати ціну з врахуванням доставки ***', '<strong><span style="color: #ff8484;">*** скоригувати ціну з врахуванням доставки ***</span></strong>']
    ]
};

if (typeof Redactor.options !== "undefined") {
    Redactor.options = { ...Redactor.options, ...redactor_plugin_clips_options_lang_uk };
}
