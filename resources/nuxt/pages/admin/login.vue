<template>
    <v-container fluid>
        <v-layout
                row
                align-center
                justify-center
        >
            <v-flex sm5 l4 x4 mt-15>
                <v-card>
                    <figure class="logo">
                        <SitiosLogo width="20%"/>
                    </figure>
                    <v-card-title primary-title>
                        <div class="header">
                            <div class="headline">Sitios Agencia Digital</div>
                            <span class="grey--text">AW3 Admin Dashboard</span>
                        </div>
                    </v-card-title>
                    <v-layout  v-if="exists && user.hasOwnProperty('id')" column align-center justify-center>
                        <v-flex mt-20>
                            <v-chip  color="teal" text-color="white">
                                <v-avatar>
                                    <v-img v-if="user.media.length > 0" :src="user.media|first|filePath"></v-img>
                                    <v-icon v-else>account_circle</v-icon>
                                </v-avatar>
                                {{user.name}}
                            </v-chip>
                        </v-flex>
                    </v-layout>
                    <v-form class="flex-center p-5 vee" ref="form" novalidate>
                        <v-text-field @keypress.enter.prevent="go" :error-messages="errors.collect('email')"
                                      v-validate="'required|email'"
                                      name="email" v-model="email" label="Email" required></v-text-field>
                        <v-text-field ref="password" @keypress.enter.prevent="go" v-if="exists"
                                      :error-messages="errors.collect('password')" v-validate="'required'"
                                      name="password" type="password" v-model="pass" label="Password"
                                      required></v-text-field>
                        <v-btn :loading="'login'|whoIsSending" block color="success" @click.stop="go">
                            <span v-if="!sending">Siguiente</span>
                        </v-btn>
                        <v-btn block v-show="exists" color="default" @click.stop="exists = false">Cancelar</v-btn>
                    </v-form>
                    <v-snackbar
                            v-model="error.has"
                            color="error"
                            :timeout="2500"
                            :top="true"
                    >
                        {{ error.text }}
                    </v-snackbar>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import SitiosLogo from '~/components/SitiosLogo.vue'

    export default {
        name: "login",
        layout: 'none',
        middleware: 'IsAdminAuthenticated',
        components: {
            SitiosLogo
        },
        data() {
            return {
                email: '',
                pass: '',
                exists: false,
                sending: false,
                error:{
                    has:false,
                    text:''
                },
                user:{}
            }
        },
        methods: {
            go() {
                this.$validator._paused = false;
                if (!this.exists) {
                    this.$validator.validate().then(result => {
                        if (result) {
                            this.mixin.sending_now = 'login';
                            this.$axios.get(`/api/auth/check/${this.email}`)
                                .then(({data}) => {
                                    this.user = data.user;
                                    this.exists = true;
                                })
                                .catch(() => {
                                    this.email = '';
                                    this.$validator._paused = true;
                                })
                                .finally(() => {
                                    this.mixin.sending_now = '';
                                })
                        }
                    });
                    return
                }

                this.$validator.validate().then(result => {
                    if (result) {
                        this.mixin.sending_now = 'login';
                        this.$auth.options.redirect.home = this.routes.base.admin;
                        this.$auth.loginWith('local', {
                            data: {
                                email: this.email,
                                password: this.pass
                            }
                        }).then(()=>{
                            this.$auth.$state.strategy = 'local';
                        })
                            .catch(({response}) => {
                                this.$validator._paused = true;
                                this.pass = '';
                                this.error.text = response.data.message;
                                this.error.has = true;
                            })
                            .finally(() => {
                                this.mixin.sending_now = '';
                            })
                    }
                });

            }
        }
    }
</script>

<style scoped>
    .logo {
        text-align: center;
        padding: 20px;
    }

    .header {
        width: 100%;
        text-align: center
    }

    form {
        padding: 20px;
    }
</style>