<script>
    import VueRecaptcha from 'vue-recaptcha'

    export default {
        name: "base-form",
        components: {
            vueRecaptcha: VueRecaptcha,
        },
        props: {
            useRecaptcha: {
                required: false,
                type: Boolean,
                default: false
            },
            recaptchaKey: {
                required: false,
                type: String
            }
        },
        created() {
            this.$bus.$on('turnOffValidator', () => {
                this.$validator._paused = true;
            });
            this.$bus.$on('turnOnValidator', () => {
                this.$validator._paused = false;
            });
            this.$bus.$on('validate', () => {
                this.$validator.validate().then(result => {
                    this.useRecaptcha?
                        //El metodo pasado en el evento @verify del recaptcha
                        // tiene q ser la emision del payload validated
                        this.$refs.recaptcha.execute()
                        :this.$bus.$emit('validated',result);
                });
            });
        },
        destroyed() {
            this.$bus.$off('validate');
            this.mixin.admin.notification.active = false;
        },
        methods: {
            resetRecaptcha() {
                this.$refs.recaptcha.reset();
            },
            getMedias(dropzones, form_data) {
                dropzones.map(zone => zone.processFiles()).forEach(d => {
                    d.medias.forEach((m) => {
                        form_data.append(`medias[${d.group}][files][]`, m.file);
                        delete m.file;
                        form_data.append(`medias[${d.group}][attributes][]`, JSON.stringify(m));
                    });
                    form_data.append(`medias[${d.group}][mimes]`, JSON.stringify(d.mimes));
                    form_data.append(`medias[${d.group}][uploaded]`, JSON.stringify(d.uploaded));
                });
                return form_data
            }
        }
    }
</script>