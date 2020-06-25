<template>
    <v-layout row wrap>
        <v-flex xs12 sm3>
            <preview-card :data="clone" :only="visualizable"/>
        </v-flex>
        <v-flex xs12 sm9>
            <v-form ref="form" class="vee" novalidate>
                <v-tabs
                        centered
                        color="cyan"
                        dark
                        icons-and-text
                >
                    <v-tabs-slider color="yellow"></v-tabs-slider>

                    <v-tab href="#data">
                        {{$t('usuarios.form.titles.first_data')}}
                        <v-icon>account_box</v-icon>
                    </v-tab>

                    <v-tab href="#profile">
                        {{$t('usuarios.form.titles.my_profile')}}
                        <v-icon>image</v-icon>
                    </v-tab>
                    <v-tab href="#security">
                        {{$t('usuarios.form.titles.access')}}
                        <v-icon>shield</v-icon>
                    </v-tab>

                    <v-tab-item value="data">
                        <v-container>
                            <v-layout row wrap mt-2>
                                <v-flex>
                                    <v-text-field
                                            v-validate="'required'"
                                            :label="$t('usuarios.form.fields.name')"
                                            @change="e => user.name = e"
                                            v-model="clone.name"
                                            name="name"
                                            :error-messages="errors.collect('name')"
                                            outline
                                    ></v-text-field>
                                    <v-text-field
                                            :label="$t('usuarios.form.fields.email')"
                                            v-validate="'required|email'"
                                            name="email"
                                            :error-messages="errors.collect('email')"
                                            @change="e => user.email = e"
                                            v-model="clone.email"
                                            :hint="$t('usuarios.form.hints.email')"
                                            outline
                                    ></v-text-field>
                                    <v-text-field
                                            :label="$t('usuarios.form.fields.password')"
                                            @change="e => user.password = e"
                                            v-model="user.password"
                                            v-validate="'min:8'"
                                            name="password"
                                            :error-messages="errors.collect('password')"
                                            :append-icon="show_pass ? 'visibility' : 'visibility_off'"
                                            :type="show_pass ? 'text' : 'password'"
                                            :hint="$t('usuarios.form.hints.password')"
                                            outline
                                            counter
                                            @click:append="show_pass = !show_pass"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tab-item>
                    <v-tab-item value="profile">
                        <v-container>
                            <v-layout row wrap mt-2>
                                <v-flex xs12 sm8>
                                    <v-layout
                                            row wrap
                                    >
                                        <v-flex xs12 sm12>

                                            <v-text-field
                                                    :label="$t('usuarios.form.fields.lastname')"
                                                    v-validate="'required'"
                                                    @change="e => user.profile.lastname = e"
                                                    v-model="clone.profile.lastname"
                                                    name="lastname"
                                                    :error-messages="errors.collect('lastname')"
                                                    outline
                                            ></v-text-field>
                                            <v-text-field
                                                    :label="$t('usuarios.form.fields.mobile')"
                                                    @change="e => user.profile.mobile =  e"
                                                    v-model="clone.profile.mobile"
                                                    name="mobile"
                                                    outline
                                            ></v-text-field>
                                            <v-text-field
                                                    :label="$t('usuarios.form.fields.phone')"
                                                    name="phone"
                                                    v-validate="'min:8'"
                                                    :error-messages="errors.collect('phone')"
                                                    @change="e => user.profile.phone = e"
                                                    v-model="clone.profile.phone"
                                                    outline
                                            ></v-text-field>
                                            <v-select
                                                    :label="$t('usuarios.form.fields.locale')"
                                                    v-model="clone.locale"
                                                    @change="e => user.locale = e"
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
                                        </v-flex>
                                    </v-layout>
                                </v-flex>

                                <v-flex xs12 sm12 pl-3>
                                    <h3>{{$t('profile_photo')}}</h3>
                                    <drop-zone v-if="drop_options.mounted" :only="['files','videos']" ref="zone" group="profile" :types="['image']"
                                               :options="drop_options"/>
                                </v-flex>
                            </v-layout>
                        </v-container>

                    </v-tab-item>
                    <v-tab-item value="security">
                        <v-container>
                            <v-layout>
                                <v-flex>
                                    <v-autocomplete
                                            :label="$tc('usuarios.form.fields.roles',2)"
                                            v-model="clone.roles"
                                            @change="e => user.roles = e"
                                            :disabled="false"
                                            outline
                                            :hide-selected="true"
                                            :items="roles"
                                            item-text="name"
                                            :item-value="e => e"
                                            multiple
                                    >
                                        <template v-slot:selection="data">
                                            <v-chip
                                                    :selected="data.selected"
                                                    close
                                                    class="chip--select-multi"
                                                    @input="remove(data.item,user.roles)"
                                            >
                                                {{ data.item.name }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <v-list-tile-content v-text="data.item.name"/>
                                        </template>
                                    </v-autocomplete>

                                    <v-autocomplete
                                            :label="$tc('usuarios.form.fields.permissions',2)"
                                            v-model="clone.permissions"
                                            @change="e => user.permissions = e"
                                            :disabled="false"
                                            outline
                                            :hide-selected="true"
                                            :items="permissions"
                                            :item-value="e => e"
                                            item-text="name"
                                            multiple
                                    >
                                        <template v-slot:selection="data">
                                            <v-chip
                                                    :selected="data.selected"
                                                    close
                                                    class="chip--select-multi"
                                                    @input="remove(data.item,user.permissions)"
                                            >
                                                {{ data.item.name }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <v-list-tile-content v-text="data.item.name"/>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tab-item>
                </v-tabs>
                <v-divider/>
                <v-layout v-itself row justify-end>
                    <v-flex xs3 sm3>
                        <v-switch color="teal" v-model="clone.active"
                                  :label="$t('usuarios.form.fields.active')"></v-switch>
                    </v-flex>
                </v-layout>
                <slot name="button"></slot>
            </v-form>
        </v-flex>
    </v-layout>
</template>

<script>
    import DropZone from "@/components/DropZone/DropZone.vue";
    import BaseForm from "@/components/Base/BaseForm.vue";
    import PreviewCard from "@/components/admin/formularios/PreviewCard.vue";
    import Vue from 'vue';
    import {Role} from "@/store/modules/Role";
    import {Permission} from "@/store/modules/Permission";

    export default {
        name: "user-form",
        inject: ['$validator'],
        extends: BaseForm,
        components: {
            DropZone,
            PreviewCard
        },
        props: {
            user: Object
        },
        async created() {
            //Valido si existe,sino lo inicializo con los valores de relaciones y cargo las dependencias de los ficheros
            if (this.user.hasOwnProperty('id')){
                if(this.$auth.user.id === this.user.id)
                    this.$bus.$emit('cantDelete');

                this.drop_options.uploaded = await this.$repositories.usuarios.medias(this.user.id,['img','profile','thumb']);
                this.drop_options.videos.uploaded = await this.$repositories.usuarios.medias(this.user.id,['url','profile']);
            }
            else
                 this.user.profile = {};

            this.drop_options.mounted = true;
        },
        data() {
            const clone = Object.assign({}, this.user);
            return {
                show_pass: false,
                drop_options: {
                    mounted:false,
                    autoProcessQueue: false,
                    url: "/", //Tiene que ir obligatoriamente el option url :\
                    maxFiles: 2,
                    uploaded: [],
                    videos: {
                        maxFiles: 2,
                        uploaded: []
                    }
                },
                //Campos que se escuchan desde el preview Card cuando se ejecute un cambio
                visualizable: ['name', 'email', 'profile.lastname', 'profile.phone', 'profile.mobile', 'locale', 'roles', 'permissions'],
                //Declaro el objeto clone para asignarle reactividad al objeto
                // y pueda reflejarse el cambio directo en el componente PreviewCard,
                //de lo contrario modificando el prop directamente el objeto no es reactivo
                clone: clone
            }
        },
        methods: {
            setProp(obj, props, value) {
                const prop = props.shift()
                if (!obj[prop]) {
                    Vue.set(obj, prop, {})
                }
                if (!props.length) {
                    if (value && typeof value === 'object' && !Array.isArray(value)) {
                        obj[prop] = {...obj[prop], ...value}
                    } else {
                        obj[prop] = value
                    }
                    return
                }
                this.setProp(obj[prop], props, value)
            },

            getData() {
                let form_data = new FormData();
                form_data.append('data', JSON.stringify(this.user));
                form_data = this.getMedias([
                    //dropzones
                    this.$refs.zone
                ], form_data);

                return form_data;
            },
            remove(item, scope) {
                const index = scope.findIndex(({id}) => id === item.id);
                if (index >= 0) scope.splice(index, 1)
            }
        },
        computed: {
            roles() {
                return Role.all();
            },
            permissions() {
                return Permission.all();
            }
        }
    }

</script>

<style scoped>
    label {
        text-transform: capitalize;
    }
</style>