<template>
    <v-layout wrap>
        <v-flex sm12>
            <v-card id="secondary-info">
                <v-layout class="relative">
                    <v-flex
                            xs12
                            sm6
                            md8
                            text-xs-center
                            layout
                            align-center
                            justify-center
                            class="user-avatar"
                    >
                        <v-avatar :size="160" class="grey lighten-4">
                            <img :src="mixin.static.images.no_profile_img" alt="avatar">
                        </v-avatar>
                        <v-layout class="name">
                            <h3 class="text-capitalize">{{user.fullname}}</h3>
                        </v-layout>
                    </v-flex>
                </v-layout>
                <v-layout row>
                    <v-flex xs12>
                        <gmap :zoom="8"/>
                    </v-flex>
                </v-layout>
                <v-layout wrap>
                    <v-flex>
                        <v-divider></v-divider>
                        <v-layout>
                            <v-flex sm12 md4>
                                <v-flex id="user-show">
                                    <!-- <v-responsive>
                                         <img class="qr-code" :src="mixin.static.images.logo_sitios" aspect-ratio="2.75">
                                     </v-responsive>-->
                                    <v-list two-line>
                                        <v-list-tile v-if="user.profile.mobile">
                                            <v-list-tile-action>
                                                <v-icon class="indigo--text">phone</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{user.profile.mobile}}</v-list-tile-title>
                                                <v-list-tile-sub-title>{{$t('usuarios.form.fields.mobile')}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile v-if="user.profile.phone">
                                            <v-list-tile-action></v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{user.profile.phone}}</v-list-tile-title>
                                                <v-list-tile-sub-title>{{$t('usuarios.form.fields.phone')}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-divider inset  v-if="user.profile.phone||user.profile.mobile"></v-divider>
                                        <v-list-tile v-if="user.email" @click>
                                            <v-list-tile-action>
                                                <v-icon class="indigo--text">mail</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{user.email}}</v-list-tile-title>
                                                <v-list-tile-sub-title>{{$t('usuarios.form.fields.email')}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile v-if="user.email_verified_at">
                                            <v-list-tile-action></v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{user.email_verified_at}}</v-list-tile-title>
                                                <v-list-tile-sub-title>{{$t('usuarios.form.fields.email_verified_at')}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-divider inset  v-if="user.profile.email || user.profile.email_verified_at"></v-divider>
                                        <!--<v-list-tile v-if="user.profile.address" @click>
                                            <v-list-tile-action>
                                                <v-icon class="indigo&#45;&#45;text">location_on</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{user.profile.address}}</v-list-tile-title>
                                                <v-list-tile-sub-title>{{$t('usuarios.form.fields.address')}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>-->
                                    </v-list>
                                </v-flex>
                            </v-flex>
                            <v-flex sm12 md8>
                                <!--<v-layout wrap id="user-company-info">
                                    <v-flex>
                                        <v-icon large class="green&#45;&#45;text text&#45;&#45;darken-2">business</v-icon>
                                        <v-subheader>
                                            <h2>Company:</h2>
                                        </v-subheader>
                                        <p class="mb-0">Sitios</p>
                                    </v-flex>
                                    <v-flex>
                                        <v-icon large class="blue&#45;&#45;text text&#45;&#45;darken-2">account_circle</v-icon>
                                        <v-subheader>
                                            <h2>Puesto:</h2>
                                        </v-subheader>
                                        <p class="mb-0">Maquetador</p>
                                    </v-flex>
                                    <v-flex>
                                        <v-icon large class="teal&#45;&#45;text text&#45;&#45;darken-2">star</v-icon>
                                        <v-subheader>
                                            <h2>Rank:</h2>
                                        </v-subheader>
                                        <p class="mb-0">7,8</p>
                                    </v-flex>
                                </v-layout>
                                <v-divider></v-divider>-->
                                <div  v-if="user.roles.length > 0">
                                    <v-flex id="interes">
                                        <v-layout>
                                            <v-icon large class="pink--text text--darken-2">supervisor_account</v-icon>
                                            <v-subheader>
                                                <h2>{{$tc('roles.name',2)}}</h2>
                                            </v-subheader>
                                        </v-layout>
                                        <div class="text-xs-left">
                                            <v-chip class="red white--text" v-for="rol of user.roles" :key="rol.id">{{rol.name}}
                                            </v-chip>
                                        </div>
                                    </v-flex>
                                    <v-divider></v-divider>
                                </div>
                                <v-flex v-if="user.permissions.length > 0" id="interes">
                                    <v-layout>
                                        <v-icon large class="pink--text text--darken-2">vpn_key</v-icon>
                                        <v-subheader>
                                            <h2>{{$tc('permisos.name',2)}}</h2>
                                        </v-subheader>
                                    </v-layout>
                                    <div class="text-xs-left">
                                        <v-chip outline class="red red--text" v-for="permission of user.permissions"
                                                :key="permission.id">{{permission.name}}
                                        </v-chip>
                                    </div>
                                </v-flex>
                                <v-flex id="alquiler">
                                    <v-layout>
                                        <v-icon large class="pink--text text--darken-2">image</v-icon>
                                        <v-subheader>
                                            <h2>{{$t('keywords.background_photo')}}</h2>
                                        </v-subheader>
                                    </v-layout>
                                    <v-layout id="data">
                                        <v-flex sm12>
                                            <v-card class="elevation-0">
                                                <v-img @click :src="mixin.static.images.no_background_photo" height="200px"></v-img>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <!--<v-divider></v-divider>

                                <v-flex id="suscripciones">
                                    <v-layout>
                                        <v-icon large class="pink&#45;&#45;text text&#45;&#45;darken-2">loyalty</v-icon>
                                        <v-subheader>
                                            <h2>Subscripciones:</h2>
                                        </v-subheader>
                                    </v-layout>
                                    <v-layout row wrap class="subscriptions">
                                        <v-flex sm12 md3>
                                            <v-card class="purple white&#45;&#45;text">
                                                <v-card-media src="/img/pics/cards/halcyon.png" height="125px" cover></v-card-media>
                                                <v-container fluid grid-list-lg class="px-2">
                                                    <v-layout row>
                                                        <v-flex>
                                                            <div>
                                                                <div class="headline">Halycon Days</div>
                                                                <div>Ellie Goulding</div>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                        <v-flex sm12 md3>
                                            <v-card class="blue white&#45;&#45;text">
                                                <v-card-media src="/img/pics/cards/halcyon.png" height="125px" cover></v-card-media>
                                                <v-container fluid grid-list-lg class="px-2">
                                                    <v-layout row>
                                                        <v-flex>
                                                            <div>
                                                                <div class="headline">Halycon Days</div>
                                                                <div>Ellie Goulding</div>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                        <v-flex sm12 md3>
                                            <v-card class="orange white&#45;&#45;text">
                                                <v-card-media src="/img/pics/cards/halcyon.png" height="125px" cover></v-card-media>
                                                <v-container fluid grid-list-lg class="px-2">
                                                    <v-layout row>
                                                        <v-flex>
                                                            <div>
                                                                <div class="headline">Halycon Days</div>
                                                                <div>Ellie Goulding</div>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                        <v-flex sm12 md3>
                                            <v-card class="deep-orange accent-3 white&#45;&#45;text">
                                                <v-card-media src="/img/pics/cards/halcyon.png" height="125px" cover></v-card-media>
                                                <v-container fluid grid-list-lg class="px-2">
                                                    <v-layout row>
                                                        <v-flex>
                                                            <div>
                                                                <div class="headline">Halycon Days</div>
                                                                <div>Ellie Goulding</div>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-divider></v-divider>-->
                                <!--<v-layout wrap id="metadata">
                                    <v-flex sm12 md6>
                                        <v-layout>
                                            <v-icon large class="pink&#45;&#45;text text&#45;&#45;darken-2">loyalty</v-icon>
                                            <v-subheader>
                                                <h2>Ingresos:</h2>
                                            </v-subheader>
                                        </v-layout>
                                        <v-flex>
                                            <v-card class="mt-3 mx-auto sparkline-offset-top elevation-0" max-width="400">
                                                <v-sheet
                                                        class="v-sheet&#45;&#45;offset mx-auto"
                                                        color="cyan"
                                                        elevation="12"
                                                        max-width="calc(100% - 32px)"
                                                >
                                                    <v-sparkline
                                                            color="white"
                                                            line-width="2"
                                                            padding="16"
                                                    ></v-sparkline>
                                                </v-sheet>

                                                <v-card-text class="pt-0">
                                                    <div class="title font-weight-light mb-2">User Registrations</div>
                                                    <div class="subheading font-weight-light grey&#45;&#45;text">Last Campaign
                                                        Performance
                                                    </div>
                                                    <v-divider class="my-2"></v-divider>
                                                    <v-icon class="mr-2" small>mdi-clock</v-icon>
                                                    <span
                                                            class="caption grey&#45;&#45;text font-weight-light"
                                                    >last registration 26 minutes ago</span>
                                                </v-card-text>
                                            </v-card>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>-->
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <nuxt-link :to="`${this.routes.base.admin}/${section}/edit/${user.id}`">
                        <v-btn  absolute fab right color="success">
                            <v-icon>
                                edit
                            </v-icon>
                        </v-btn>
                    </nuxt-link>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import BaseSync from '@/components/Base/BaseSync.vue'
    import {User} from "@/store/modules/User"
    import Gmap from '@/components/Map/Map.vue'

    export default {
        layout: 'admin',
        extends: BaseSync,
        components: {
            Gmap
        },
        validate({params}) {
            return /^\d+$/.test(params.id)
        },
        // middleware:'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['mostrar-usuarios'],
            store_props: {
                model: User,
                query: "query().with('profile|roles|permissions').find(route.params.id)",
                fetch: 'admin/usuarios',
                dependencies: {
                    /*Se declaran aca las dependencias q se usaran en la vista a cargar
                    * con llave = llave del modulo en el store, url = apiUrl para cargar*/
                    Role: 'admin/roles',
                    Permission: 'admin/permisos'
                }
            },
            data_var: 'user',
            redirect: '/admin/usuarios'
        },
        data() {
            return {
                user: {},
                email: '',
                email2: ''
            }
        }
    }
</script>

<style scoped>
    .v-list__tile__sub-title {
        text-transform: capitalize;
    }

    h2 {
        text-transform: capitalize;
    }
    .v-image:hover{
        opacity:.5;
        cursor:pointer;
    }
</style>