const redactor_plugin_clips_options_lang_ru = {
    clips: [
        ['OFFICIAL WEBSITE', '<b>OFFICIAL WEBSITE</b>'],
        ['купил на Aliexpress за', 'купил на <b>Aliexpress</b> за'],
        ['купил на Amazon за', 'купил на <b>Amazon</b> за']
    ]
};

if (typeof Redactor.options !== "undefined") {
    Redactor.options = { ...Redactor.options, ...redactor_plugin_clips_options_lang_ru };
}
