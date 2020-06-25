import Vue from 'vue'

const data = {
    mixin: {
        admin: {
            notification: {
                timeout: 2000,
                active: false,
                text: ''
            }
        },
        copyright:'Sitios© 2019',
        locales: ['en', 'es'],
        sending_now: '',
        static: {
            images: {
                logo_sitios: '/img/sitios_logo/iso/sitios-iso_c_128.png',
                no_profile_img: '/img/profile/no_profile_red.svg',
                no_background_photo:'/img/material.jpg',
            }
        },
        colors:['blue','indigo','green','purple','cyan','pink']
    },
    routes: {
        base: {
            admin: '/admin',
            app: '/'
        }
    }
};

Vue.mixin({
    data() {
        return data;
    },
    methods: {
        parseResponse(response) {
            switch (response.status) {
                case 200:
                    if (response.data.status === 'success') {
                        if (response.data.message)
                            this.notify(response.data.message);
                        return true
                    }
                    this.notify(response.data.message, 'error');
                    break;
                case 401:
                    this.notify(response.data.message, 'warning');
                    this.$bus.$emit('errors', response.data.errors);
                    break;
                default:
                    this.notify(response.data.message, 'error');
                    break;
            }

            return false;
        },
        notify(text, type = 'success') {
            this.mixin.admin.notification.text = text;
            this.mixin.admin.notification.type = type;
            this.mixin.admin.notification.active = true;
        },
        randomColor(){
             return this.mixin.colors[Math.floor(Math.random() * this.mixin.colors.length)];
        }
    },
    filters: {
        //Mixin Filters here
        dateFormat(date, options = null) {
            if (!options)
                options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};

            return new Date(date).toLocaleString('es-ES', options);
        },
        priceFormat: function (price) {
            return new Intl.NumberFormat('de-DE').format(price);
        },
        capitalize: function (word = '') {
            return word.charAt(0).toUpperCase() + word.slice(1);
        },
        whoIsSending: function (comparable) {
            return data.mixin.sending_now === comparable;
        },
        clear: function (array) {
            return array.filter(v => v)
        },
        first: function (array) {
            if (array.length > 0)
                return array[0]
        },
        filePath(file){
            return `/api/storage/${file.directory}/${file.filename}.${file.extension}`
        },
        isEmpty(list){
            return list.length === 0;
        }
    },
    computed: {
        isSending() {
            return this.mixin.sending_now !== '';
        }
    }
});