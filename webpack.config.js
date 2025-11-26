const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: {
    index: './src/index.js',
    style: './src/blocks/multi-slider/style.css',
    editor: './src/blocks/multi-slider/editor.css',
  },
  output: {
    path: path.resolve(__dirname, 'build'),
    filename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'],
          },
        },
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader'
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
  resolve: {
    extensions: ['.js', '.jsx'],
  },
  externals: {
    react: 'React',
    'react-dom': 'ReactDOM',
    '@wordpress/blocks': ['wp', 'blocks'],
    '@wordpress/element': ['wp', 'element'],
    '@wordpress/block-editor': ['wp', 'blockEditor'],
    '@wordpress/components': ['wp', 'components'],
    '@wordpress/i18n': ['wp', 'i18n'],
  },
};
