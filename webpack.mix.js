const mix = require('laravel-mix');

// Configure base path for mix stuff going to web
mix.setPublicPath('www/media/');

// Configure base path for media assets
mix.setResourceRoot('/media/');

// Fix for resolve-url-loader error regarding paths
Mix.listen('configReady', (config) => {
    for (rule of config.module.rules) {
        if (new RegExp("\.scss$").test(rule.test.toString())) {
            let resolveUrlLoaderIndex = 0;
            rule.use.forEach(function (element, index) {
                if (element.loader === "resolve-url-loader") {
                    resolveUrlLoaderIndex = index;
                }
            });
            rule.use.splice(resolveUrlLoaderIndex - 1, 0, rule.use.splice(resolveUrlLoaderIndex, 1)[0]);
        }
    }
});

// Core app CSS
mix
    .sass('assets/scss/help.scss', 'css')
    .options({
        postCss: [
            require('autoprefixer')()
        ]
    })
;

// Version assets
mix.version();
