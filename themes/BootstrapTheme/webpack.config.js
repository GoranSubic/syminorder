const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/bootstrap-theme')
  .setPublicPath('/bootstrap-theme')
  .addEntry('appBootstrap', './themes/BootstrapTheme/assets/app.js')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSassLoader()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

const config = Encore.getWebpackConfig();
config.name = 'bootstrapTheme';

module.exports = config;
