const redactor_plugin_clips_options_lang_ru = {
    clips: [
        ['OFFICIAL WEBSITE', '<b>OFFICIAL WEBSITE</b>'],
        ['купил на Aliexpress за', 'купил на <b>Aliexpress</b> за'],
        ['купил на Amazon за', 'купил на <b>Amazon</b> за'],
        ['продал за XXX грн Xшт через OLX доставку в Киев - DD.MM.2022', '<p>&gt;&gt;</p><p>продал за <strong><span style="color: rgb(97, 189, 109);">XXX</span>&nbsp;</strong>грн <strong>Xшт&nbsp;</strong>через OLX доставку в Киев - DD.MM.2021</p>'],
        ['получил посылку', '<p>&gt;&gt;</p><p>получил посылку - DD.MM.2022</p>'],
        ['виставил на продажу', '<p>&gt;&gt;</p><p>виставил на продажу - DD.MM.2022</p>']
    ]
};

if (typeof Redactor.options !== "undefined") {
    Redactor.options = { ...Redactor.options, ...redactor_plugin_clips_options_lang_ru };
}
