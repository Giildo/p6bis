var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('js/collectionTypeTrickModification', './assets/js/collectionTypeTrickModification.js')
    .addEntry('js/collectionTypeNewTrick', './assets/js/collectionTypeNewTrick.js')
    .addEntry('js/menuButton', './assets/js/menuButton.js')
    .addEntry('js/homeAddTricks', './assets/js/homeAddTricks.js')

    .addEntry('js/tricks/modal', './assets/js/tricks/modal.js')
    .addEntry('js/tricks/commentButton', './assets/js/tricks/commentButton.js')

    .addStyleEntry('css/global', './assets/scss/global/global.scss')
    .addStyleEntry('css/form', './assets/scss/form.scss')

    .addStyleEntry('css/trick/home', './assets/scss/trick/home.scss')
    .addStyleEntry('css/trick/show', './assets/scss/trick/show.scss')
    .addStyleEntry('css/trick/new', './assets/scss/trick/new.scss')
    .addStyleEntry('css/trick/modification', './assets/scss/trick/modification.scss')

    .addStyleEntry('css/security/formFrame', './assets/scss/security/formFrame.scss')

    .addStyleEntry('css/gcu/gcu', './assets/scss/gcu/gcu.scss')

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
