const path = require( 'path' );
const webpack = require( 'webpack' );
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
	mode: 'none',
	entry: {
		'test.bundle': path.resolve( __dirname, './build/CookieConsent/js/test.js' ),
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
		new CopyPlugin([
				{ from: 'node_modules/klaro/dist', to: '' },
		]),
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
