const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  // transpileDependencies must be an array; set to empty array when none required
  transpileDependencies: [],
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  },
  // Set HTML title and favicon
  chainWebpack: config => {
    try {
      config.plugins.delete('progress');
    } catch (e) {
      // no-op if plugin not present
    }
    // Set HTML title
    config.plugin('html').tap(args => {
      args[0].title = 'Amin Talent Solutions';
      return args;
    });
  }
})
