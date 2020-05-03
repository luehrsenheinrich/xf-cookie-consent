const path = require( 'path' );
const webpack = require( 'webpack' );
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = {
	mode: 'none',
	entry: {
		'klaro.bundle': path.resolve( __dirname, './build/CookieConsent/js/klaro.js' ),
	},
	output: {
		path: path.resolve(__dirname, './build/CookieConsent/_files/js/lh/cookieconsent/'),
		filename: '[name].js',
	},
	plugins: [
		new BrowserSyncPlugin({
			proxy: 'localhost',
			files: ['build/CookieConsent/**/*.php'],
			open: false,
		}),
	],
	module: {
		rules: [
	    {
	      test: /\.m?js$/,
	      exclude: /(node_modules|bower_components)/,
	      use: {
	        loader: 'babel-loader',
	        options: {
	          presets: ['@babel/preset-env']
	        }
	      }
	    }
	  ]
	},
	watch: true
}
