const path  = require('path');
const webpack = require("webpack");

const HtmlWebpackPlugin    = require('html-webpack-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const CopyWebpackPlugin    = require('copy-webpack-plugin');
const TerserPlugin         = require("terser-webpack-plugin");

module.exports = {
	mode: 'production',
	watch: true,
	entry: {
		bundle: path.resolve(__dirname, 'src/index.js'),
	},
	output: {
		path: path.resolve(__dirname, 'build'),
		//filename: '[name][contenthash].js',
		filename: '[name].js',
		clean: false,
		assetModuleFilename: 'assets/[name][ext]',
	},
	devtool: 'source-map',
	module: {
		rules: [
			{
				test: /\.scss$/,
				use: ['style-loader', 'css-loader', 'sass-loader'],
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
					},
				},
			},
			{
				test: /\.(png|svg|jpg|jpeg|gif|webp)$/i,
				type: 'asset/resource'
			},
		],
	},
	optimization: {
		minimize: true,
		minimizer: [new TerserPlugin()],
	}
}