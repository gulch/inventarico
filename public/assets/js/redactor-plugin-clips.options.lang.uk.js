const redactor_plugin_clips_options_lang_uk = {
    clips: [
        ['OFFICIAL WEBSITE', '<b>OFFICIAL WEBSITE</b>'],
        ['купив на Aliexpress за', 'купив на <b>Aliexpress</b> за '],
        ['купив на Amazon за', 'купив на <b>Amazon</b> за '],
        ['продав за XXX грн Xшт через OLX доставку в Київ - DD.MM.2022', '<p>&gt;&gt;</p><p>продав за <strong><span style="color: rgb(97, 189, 109);">XXX</span>&nbsp;</strong>грн <strong>Xшт&nbsp;</strong>через OLX доставку в Київ - DD.MM.2022</p>'],
        ['отримав посилку', '<p>&gt;&gt;</p><p>отримав посилку - DD.MM.2022</p>'],
        ['виставив на продаж', '<p>&gt;&gt;</p><p>виставив на продаж - DD.MM.2022</p>']
    ]
};

if (typeof Redactor.options !== "undefined") {
    Redactor.options = { ...Redactor.options, ...redactor_plugin_clips_options_lang_uk };
}
