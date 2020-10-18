import path from 'path'
import HtmlWebpackPlugin from 'html-webpack-plugin'

const src  = path.resolve(__dirname, 'src')
const dist = path.resolve(__dirname, 'dist')

export default {
  mode: 'development',
  entry: ['@babel/polyfill', src + '/jsx/index.jsx'],

  output: {
    path: dist,
    filename: 'bundle.js'
  },

  module: {
    rules: [
    {
        test: /\.jsx$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
    },
    {
        test: /\.scss/,
        use: [
            'style-loader',
            {
                loader: 'css-loader',
                options: {
                    url: false
                },
            },
            {
                loader: 'sass-loader',
                options: {
                    sourceMap: true,
                }
            }
        ]
    }
    ]
    },

  resolve: {
    extensions: ['.js', '.jsx']
  },

  plugins: [
    new HtmlWebpackPlugin({
        template: src + '/index.html',
        filename: 'index.html',
    })
  ]
}