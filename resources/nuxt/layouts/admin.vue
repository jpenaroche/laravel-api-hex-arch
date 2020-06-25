<template>
    <v-app light>
        <v-navigation-drawer
                :mini-variant="miniVariant"
                :clipped="clipped"
                v-model="drawer"
                fixed
                app
        >
            <v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                <v-layout pa-2 column fill-height class="lightbox white--text">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                        <div class="subheading">{{$auth.user.name}}</div>
                        <div v-if="!miniVariant" class="body-1">{{$auth.user.email}}</div>
                    </v-flex>
                </v-layout>
            </v-img>

            <v-list>
                <v-flex v-for="(item,i) in items">
                    <v-list-tile v-if="!item.children"
                                 :to="item.to||''"
                                 :key="`p-${i}`"
                                 router
                                 exact
                    >
                        <v-list-tile-action>
                            <v-icon v-html="item.icon"/>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title v-text="$t(item.title)"/>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-group v-else
                                  :prepend-icon="item.icon"
                    >
                        <template v-slot:activator>
                            <v-list-tile>
                                <v-list-tile-title>{{item.title}}</v-list-tile-title>
                            </v-list-tile>
                        </template>
                        <v-list-tile
                                v-for="(subItem, j) in item.children"
                                :to="subItem.to"
                                :key="`c-${j}`"
                                router
                                exact
                        >
                            <v-list-tile-action>
                                <v-icon v-html="subItem.icon"/>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="subItem.title"/>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                </v-flex>

            </v-list>
        </v-navigation-drawer>
        <v-toolbar
                :clipped-left="clipped"
                fixed
                dark
                color="blue"
                app
        >

            <v-toolbar-side-icon @click="drawer = !drawer"/>
            <v-btn
                    icon
                    @click.stop="miniVariant = !miniVariant"
            >
                <v-icon v-html="miniVariant ? 'chevron_right' : 'chevron_left'"/>
            </v-btn>
            <v-btn
                    icon
                    @click.stop="clipped = !clipped"
            >
                <v-icon>web</v-icon>
            </v-btn>
            <v-btn
                    icon
                    @click.stop="fixed = !fixed"
            >
                <v-icon>remove</v-icon>
            </v-btn>
            <v-toolbar-title v-text="title"/>
            <v-btn
                    icon
                    @click.stop="rightDrawer = !rightDrawer"
            >
                <v-icon>menu</v-icon>
            </v-btn>
            <v-btn
                    icon
                    @click.stop="logout()"
            >
                <v-icon>logout</v-icon>
            </v-btn>
            <v-spacer></v-spacer>

            <!--User component for user options-->
            <UserPanel/>
        </v-toolbar>
        <v-content>
            <v-container>
                <nuxt/>
            </v-container>
        </v-content>
        <v-navigation-drawer
                :right="right"
                v-model="rightDrawer"
                temporary
                fixed
        >
            <v-list>
                <v-list-tile @click.native="right = !right">
                    <v-list-tile-action>
                        <v-icon light>compare_arrows</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>Switch drawer (click me)</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-footer
                :fixed="fixed"
                app
        >
            <v-layout
                    row
                    justify-center
            >
                <v-flex class="text-center" sm5>
          <span>
            <small>{{mixin.copyright}}</small>
          </span>
                </v-flex>
            </v-layout>

        </v-footer>

        <!--notification-->
        <v-snackbar
                v-model="mixin.admin.notification.active"
                :color="mixin.admin.notification.type"
                :timeout="mixin.admin.notification.timeout"
                :top="true"
        >
            {{ mixin.admin.notification.text }}
        </v-snackbar>
    </v-app>
</template>

<script>
    import UserPanel from '@/components/admin/toolbar/UserMenuPanel.vue'
    import MainComponent from '@/components/Base/MainComponent'

    export default {
        middleware: 'IsAdmin',
        transition: true,
        extends:MainComponent,
        components: {
            UserPanel
        },
        data() {
            return {
                clipped: false,
                drawer: true,
                fixed: false,
                items: [
                    {icon: 'apps', title: 'sitios', to: '/'},
                    {
                        icon: 'settings', title: 'administracion', children: [
                            {icon: 'group', title: 'usuarios', to: '/admin/usuarios'},
                            {icon: 'vpn_key', title: 'permisos', to: '/admin/permisos'},
                            {icon: 'supervisor_account', title: 'roles', to: '/admin/roles'},
                            {icon: 'video_library', title: 'videos', to: '/admin/videos'},
                        ]
                    }
                ],
                miniVariant: true,
                right: true,
                rightDrawer: false,
                title: 'Dashboard'
            }
        },
        methods: {
            logout(to = '/admin/login') {
                this.$auth.options.redirect.logout = to;
                this.$auth.logout();
            }
        }
    }
</script>
