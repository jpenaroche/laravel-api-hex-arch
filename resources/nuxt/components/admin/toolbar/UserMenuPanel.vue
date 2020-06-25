<template>
    <v-menu
            bottom left
            transition="slide-x-transition"
            :close-on-content-click="false"
            :nudge-width="200"
            offset-x
            v-model="menu"
    >
        <v-btn ref="switcher"
                slot="activator"
                dark
                icon
        >
            <v-icon>account_circle</v-icon>
        </v-btn>
        <v-card>
            <v-list>
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <img v-if="profile_img" :src="profile_img|filePath" alt="profile_img.parsed_attr.title">
                        <img v-else :src="mixin.static.images.no_profile_img" alt="profile">
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>
                            <small>{{user.name}}</small>
                        </v-list-tile-title>
                        <v-list-tile-sub-title>{{user.email}}</v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-btn
                                fab
                                flat
                                small
                                color="teal"
                                @click.stop="edit"
                        >
                            <v-icon>edit</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-divider></v-divider>

            <v-list>
                <!--<v-list-tile>
                    <v-list-tile-action>
                        <v-switch color="purple"></v-switch>
                    </v-list-tile-action>
                    <v-list-tile-title>{{$t('forms.enable_notifications')}}</v-list-tile-title>
                </v-list-tile>-->
                <v-list-tile>
                    <v-list-tile-action>
                        <v-select
                                v-model="locale"
                                :items="mixin.locales"
                                chips
                        >
                            <template v-slot:selection="data">
                                <v-chip
                                        :key="JSON.stringify(data.item)"
                                        :selected="data.selected"
                                        :disabled="data.disabled"
                                        class="v-chip--select-multi"
                                        @click.stop="data.parent.selectedIndex = data.index"
                                        @input="data.parent.selectItem(data.item)"
                                >
                                    <v-avatar class="accent white--text">
                                        {{ data.item.slice(0, 2).toUpperCase() }}
                                    </v-avatar>
                                    {{ data.item }}
                                </v-chip>
                            </template>
                        </v-select>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                        :loading="`usuarios/updating`|whoIsSending"
                        @click.prevent="save"
                        flat
                        small
                        fab
                        color="teal"
                >
                    <v-icon>save</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>

    </v-menu>
</template>

<script>
    import {User} from '@/store/modules/User'

    export default {
        name: "user-menu-panel",
        data() {
            return {
                locale:'es',
                fav: '',
                menu: false,
                repository: this.$repositories.usuarios,
                profile_img: null
            }
        },
        async created(){
            //TODO Cambiar a uso del store cuando me den respuesta del issue de vuex-orm
            const result = await this.$repositories.usuarios.medias(this.user.id,['img','profile','icon']);
            if(result.length > 0)
                this.profile_img = result[0];

            this.locale = this.user.locale;
        },
        methods: {
            edit() {
                this.$router.replace(`${this.routes.base.admin}/usuarios/edit/${this.$auth.user.id}`);
                this.menu=false;
            },
            save() {
                const formData = new FormData();
                this.mixin.sending_now = 'usuarios/updating';
                formData.append('data', JSON.stringify(this.user));
                this.repository.update(this.user.id, formData)
                    .then((response) => {
                        this.parseResponse(response);
                        this.menu=false;
                    })
                    .catch(({response})=>{
                        this.parseResponse(response)
                    })
                    .finally(() => {
                        this.mixin.sending_now = '';
                    })
            }
        },
        computed:{
            user(){
                return User.query().with('profile|roles|permissions').find(this.$auth.user.id)
            }
        },
        watch:{
            locale(newVal){
                User.update({
                    where:this.user.id,
                    data:{locale:newVal}
                }).then(()=>{
                    this.$store.commit('setLocale',newVal)
                })
            }
        }
    }
</script>

<style scoped>

</style>