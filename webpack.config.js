'use strict';

var path = require('path');
var webpack = require('webpack');
const CompressionPlugin = require("compression-webpack-plugin");
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// const VueLoaderPlugin = require('vue-loader/lib/plugin');
const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin');
// const TerserPlugin = require('terser-webpack-plugin');
// const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
// const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const HtmlPlugin = require('html-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
// var tran = process.hrtime()[1];
process.traceDeprecation = true;

//initialize entries
var entries = {
    general: path.resolve(__dirname, 'vue', 'starter', 'general.js'),
    admin: path.resolve(__dirname, 'vue', 'starter', 'admin.js'),
    checker: path.resolve(__dirname, 'vue', 'starter', 'checker.js'),
    field_inspector: path.resolve(__dirname, 'vue', 'starter', 'field_inspector.js'),
    institute: path.resolve(__dirname, 'vue', 'starter', 'institute.js'),
};
var type = null, devSeverPort = null;
var htmlIndexes = {
    'general': '/bundles/general/index.html',
    'admin': '/bundles/admin/index.html',
    'checker': '/bundles/checker/index.html',
    'field_inspector': '/bundles/field_inspector/index.html',
    'institute': '/bundles/institute/index.html',
};
//initialize entries
if (process.env.NODE_ENV === 'development') {
    /*** @development **/
    type = process.env.DEV.toLowerCase();
    devSeverPort = process.argv[process.argv.length - 1];
    module.exports = {
        entry: entries[type],
        output: {
            path: path.resolve(__dirname, 'public'),
            filename: 'bundle.js',
            chunkFilename: `[id].lazy.[hash].js`,
            publicPath: '/'
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    options: {
                        loaders: {
                            sass: [
                                'vue-style-loader',
                                'css-loader',
                                'sass-loader?indentedSyntax=1',
                                {
                                    loader: 'sass-resources-loader',
                                    options: {
                                        resources: [path.resolve(__dirname, 'public', 'css', 'main.scss')]
                                    },
                                },
                            ],
                            scss: [
                                'vue-style-loader',
                                'css-loader',
                                'sass-loader',
                                {
                                    loader: 'sass-resources-loader',
                                    options: {
                                        resources: [path.resolve(__dirname, 'public', 'css', 'main.scss')]
                                    },
                                },
                            ],
                        },
                    },
                },
                {
                    test: /\.css$/,
                    loader: 'style-loader!css-loader'
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    exclude: /(node_modules)/
                },
                {
                    test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                    loader: 'url-loader',
                    exclude: /(node_modules)/
                },
                {
                    test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
                    exclude: /(node_modules)/,
                    loader: 'url-loader',
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                    exclude: /(node_modules)/,
                    loader: 'url-loader',
                },
                {
                    test: /\.(png|jpg|gif|svg)$/,
                    loader: 'file-loader',
                    exclude: /(node_modules)/,
                    options: {
                        name: '[name].[ext]?[hash]'
                    }
                },
                {
                    test: /\.scss$/,
                    loader: ["style", "css?sourceMap", "sass?sourceMap"],
                    exclude: /(node_modules)/
                }
            ]
        },
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js',
                '@cus-com': path.resolve(__dirname, 'vue/customize-components'),
                '@com': path.resolve(__dirname, 'vue/components'),
                '@store': path.resolve(__dirname, 'vue/stores'),
                '@route': path.resolve(__dirname, 'vue/routes'),
                '@vue': path.resolve(__dirname, 'vue'),
                '@bases': path.resolve(__dirname, 'vue', 'components', 'Bases'),
            }
        },
        devServer: {
            contentBase: path.resolve(__dirname, 'public'),
            compress: true,
            historyApiFallback: {
                index: htmlIndexes[type]
            },
            noInfo: true,
        },
        performance: {
            hints: false
        },
        plugins: [
            new FriendlyErrorsPlugin(),
            new BrowserSyncPlugin({
                    // browse to http://localhost:3000/ during development,
                    // ./public directory is being served
                    host: 'localhost',
                    proxy: 'http://localhost:' + devSeverPort + '/',
                    files: [
                        "./public/**/*.css",
                        "./public/bundles/" + type + "/assets/css/*.css",
                        {
                            match: ['./public/bundles/' + type + '/index.html'],
                            fn: function (event, file) {
                                if (event === 'change') {
                                    const bs = require('browser-sync').get('bs-webpack-plugin');
                                    bs.reload();
                                }
                            }
                        }
                    ]
                },
                // plugin options
                {
                    // prevent BrowserSync from reloading the page
                    // and let Webpack Dev Server take care of this
                    reload: false
                }
            )
        ],
        devtool: '#eval-source-map'
    }
} else {
    /*** @production **/
    type = process.env.PROD.toLowerCase();//for production
    module.exports = {
        entry: entries[type],
        output: {
            path: path.resolve(__dirname, 'public', 'bundles', 'generated', type),
            filename: type + '.[hash].bundle.js',
            chunkFilename: type + '.[name].[chunkhash].js',
            publicPath: "/bundles/generated/" + type + "/",
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    exclude: /(node_modules)/,
                    options: {
                        loaders: {
                            sass: [
                                'vue-style-loader',
                                'css-loader',
                                'sass-loader?indentedSyntax=1',
                                {
                                    loader: 'sass-resources-loader',
                                    options: {
                                        resources: [path.resolve(__dirname, 'public', 'css', 'main.scss')]
                                    },
                                },
                            ],
                            scss: [
                                'vue-style-loader',
                                'css-loader',
                                'sass-loader',
                                {
                                    loader: 'sass-resources-loader',
                                    options: {
                                        resources: [path.resolve(__dirname, 'public', 'css', 'main.scss')]
                                    },
                                },
                            ],
                        },
                    },
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    exclude: /(node_modules)/
                },
                {
                    test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                    loader: 'url-loader',
                    exclude: /(node_modules)/
                },
                {
                    test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
                    exclude: /(node_modules)/,
                    loader: 'url-loader',
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                    exclude: /(node_modules)/,
                    loader: 'url-loader',
                },
                {
                    test: /\.(png|jpg|gif|svg)$/,
                    loader: 'file-loader',
                    exclude: /(node_modules)/,
                    options: {
                        name: '[name].[ext]?[hash]'
                    }
                },
                {
                    test: /\.css$/,
                    loader: 'style-loader!css-loader'
                },
                {
                    test: /\.scss$/,
                    loader: ["style", "css?sourceMap", "sass?sourceMap"],
                    exclude: /(node_modules)/
                },
                {
                    test: /\.sass$/,
                    use: ["style", "css?sourceMap", "sass?sourceMap"]
                }
            ]
        },
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js',
                '@cus-com': path.resolve(__dirname, 'vue/customize-components'),
                '@com': path.resolve(__dirname, 'vue/components'),
                '@store': path.resolve(__dirname, 'vue/stores'),
                '@route': path.resolve(__dirname, 'vue/routes'),
                '@vue': path.resolve(__dirname, 'vue'),
                '@bases': path.resolve(__dirname, 'vue', 'components', 'Bases'),
            }
        },
        devServer: {
            historyApiFallback: true,
            noInfo: true
        },
        plugins: [
            new HtmlPlugin({
                template: path.resolve(__dirname, 'public', 'bundles', 'generated', type, 'index.html'),
                chunksSortMode: 'dependency'
            }),
            new CompressionPlugin(
                {
                    filename: '[path].gz[query]',
                    algorithm: "gzip",
                    test: /\.vue$|\.js$|\.css$|\.html$/,
                    threshold: 10240,
                    minRatio: 0.8
                }),
            new webpack.HashedModuleIdsPlugin()
        ],
        performance: {
            hints: false
        },
        devtool: '#source-map'
    };

    if (process.env.NODE_ENV === 'production') {
        module.exports.devtool = '#source-map';
        // http://vue-loader.vuejs.org/en/workflow/production.html
        module.exports.plugins = (module.exports.plugins || []).concat([
            new webpack.DefinePlugin({
                'process.env': {
                    NODE_ENV: '"production"'
                }
            }),
            new webpack.optimize.UglifyJsPlugin({
                minimize: true,
                sourceMap: true,
                compress: {
                    warnings: false
                },
                output: {
                    comments: false,
                },
                exclude: [/\.min\.js$/gi] // skip pre-minified libs
            }),
            new webpack.LoaderOptionsPlugin({
                minimize: true
            }),
            new webpack.NoEmitOnErrorsPlugin()
        ])
    }
}
