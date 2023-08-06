const path = require('path')
const HtmlWebpackPlugin = require("html-webpack-plugin");
const copyWebpackPluging = require("copy-webpack-plugin")
const { CleanWebpackPlugin } = require('clean-webpack-plugin')

module.exports = {
    entry: {
        main: path.join(__dirname, 'src/index.js'),
        form: path.join(__dirname, 'src/form/form.js'),
        login: path.join(__dirname, 'src/login/login.js'),
        signup: path.join(__dirname, 'src/signup/signup.js'),
        topbar: path.join(__dirname, 'src/topbar/topbar.js')
    },
    output: {
        path: path.join(__dirname, 'dist'),
        filename: '[name].bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.scss$/i,
                use: ["style-loader", "css-loader", "sass-loader"]
            }
        ]
    },
    plugins: [
        new copyWebpackPluging({
            patterns: [
                {
                    from: './src/assets/images/*',
                    to: 'asssets/images/[name][ext]',
                }
            ]
        }),
        new HtmlWebpackPlugin({
            filename: "index.html",
            template: path.join(__dirname, "./src/index.html"),
            chunks: ["main", "topbar"]
        }),
        new HtmlWebpackPlugin({
            filename: "form.html",
            template: path.join(__dirname, "./src/form/form.html"),
            chunks: ["form", "topbar"]
        }),
        new HtmlWebpackPlugin({
            filename: "login.html",
            template: path.join(__dirname, "./src/login/login.html"),
            chunks: ["login", "topbar"]
        }),
        new HtmlWebpackPlugin({
            filename: "signup.html",
            template: path.join(__dirname, "./src/signup/signup.html"),
            chunks: ["signup", "topbar"]
        })
    ],
    stats: "minimal",
    devtool: "source-map",
    mode: "development",
    devServer: {
        static: path.resolve(__dirname, './dist'),
        open: true,
        watchFiles: ['./src/**'],
        port: 4000,
        hot: true,
    }
}