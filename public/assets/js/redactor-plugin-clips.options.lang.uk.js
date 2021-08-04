const redactor_plugin_clips_options_lang_uk = {
    clips: [
        ['OFFICIAL WEBSITE', '<b>OFFICIAL WEBSITE</b>'],
        ['купив на Aliexpress за', 'купив на <b>Aliexpress</b> за'],
        ['купив на Amazon за', 'купив на <b>Amazon</b> за']
    ]
};

if (typeof Redactor.options !== "undefined") {
    Redactor.options = { ...Redactor.options, ...redactor_plugin_clips_options_lang_uk };
}
