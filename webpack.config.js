var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    //.enableVersioning(Encore.isProduction())

    .addEntry('js/dev', './assets/js/dev.js')
    .addStyleEntry('css/action', './assets/less/action/style.less')
    .addStyleEntry('css/action.r', './assets/less/action/responsive.less')

    .configureUrlLoader({
        images: {
            limit: 1024,
            //mimetype: 'image/png'
        }
    })

    .enableLessLoader()    
;

module.exports = Encore.getWebpackConfig();
