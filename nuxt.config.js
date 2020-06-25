import pkg from './package'
import VuetifyLoaderPlugin from 'vuetify-loader/lib/plugin'
import {VuetifyProgressiveModule} from 'vuetify-loader'

export default {
    mode: 'spa',

    /*
    ** Headers of the page
    */
    head: {
        title: pkg.name,
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: pkg.description}
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'},
            {rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons'}
        ]
    },

    env: {
        ENV: process.env.ENV || 'production',
        API_URL: process.env.API_URL,
        HOST: process.env.host || 'localhost',
        PORT: process.env.host || '3000',
    },

    /*
    ** Customize the progress-bar color
    */
    loading: '~/components/admin/Loader/Loader.vue',
    // loading: {color: '#41B883'},

    /*
    ** Global CSS
    */
    css: [
        '~/assets/style/app.styl',
        "~/assets/style/admin/app.styl"
    ],

    /*
    ** Plugins to load before mounting the App
    */
    plugins: [
        '@/plugins/vuetify',
        '@/plugins/vuei18n',
        '@/plugins/vee-validate',
        '@/plugins/vue-quill-editor',
        '@/plugins/mixins',
        '@/plugins/directives',
        '@/plugins/event-bus',
        {src: '@/plugins/laravel-echo', ssr: false},
        '@/plugins/repository'
    ],

    /*
    ** Nuxt.js modules
    */
    modules: [
        // Doc: https://github.com/nuxt-community/axios-module#usage
        '@nuxtjs/axios',
        '@nuxtjs/auth'
    ],
    /*
    ** Axios module configuration
    */
    axios: {
        proxy: !!process.env.API_URL,
        defaults: {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
        // See https://github.com/nuxt-community/axios-module#options
        // requestInterceptor: (config, { app }) => {
        //     if (app.$auth.loggedIn) {
        //         config.headers.common['Authorization'] = app.$auth.getToken('local')
        //     }
        //     return config
        // }
    },
    proxy: {
        //API REST Address
        "/api": {
            target: process.env.API_URL,
            changeOrigin: false,
            prependPath: false
        }
    },

    /*
    ** Build configuration
    */
    build: {
        /*
        ** You can extend webpack config here
        */
        extend(config, ctx) {
            const vueLoader = config.module.rules.find((rule) => rule.loader === 'vue-loader');
            vueLoader.options.compilerOptions = {
                modules: [VuetifyProgressiveModule]
            };

            config.module.rules.push(
                {
                    test: /\.(png|jpe?g|gif)$/,
                    resourceQuery: /vuetify-preload/,
                    use: [
                        'vuetify-loader/progressive-loader',
                        {
                            loader: 'url-loader',
                            options: {limit: 8000}
                        }
                    ]
                });

            config.plugins.push(
                new VuetifyLoaderPlugin()
            );
        },
    },
    //Auth strategies
    auth: {
        strategies: {
            local: {
                endpoints: {
                    login: {url: '/api/auth/login', method: 'post', propertyName: 'token'},
                    logout: {url: '/api/auth/logout', method: 'post'},
                    user: {url: '/api/auth/user/details', method: 'get', propertyName: 'user'}
                },
                tokenRequired: true,
                tokenType: 'Bearer',
            }
        }
    },
    generate: {
        dir: 'public',
        ignore: ['*.php', '*.config']
    },
    srcDir: 'resources/nuxt'
}
