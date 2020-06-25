<script>
    import {User} from '@/store/modules/User'

    export default {
        name: "base-component",
        created() {
            let locale = 'es';
            if(this.$auth.user){
                locale = this.$auth.user.locale;
                User.insertOrUpdate({
                    data:this.$auth.user
                });
            }
            this.$store.commit('setLocale',locale);
            this.$axios.defaults.headers["X-Socket-Id"] = this.$echo.socketId()
        },
        watch:{
            '$store.state.locale'(newVal){
                this.$i18n.locale = newVal;
            }
        }
    }
</script>