<template>
    <!--Sobre el componente se escriben los listeners de cada action q se pasa como prop al DataTable "@show"-->
    <div>
        <DataTable :title="$tc('permisos.name',0)" :has_new="false" section="permisos" :items="permisos" :headers="headers"
                   :actions="actions"
                   @show="openModal"/>
        <v-dialog
                v-model="modal_show.is_opened"
                width="450"
                color="blue"
        >
            <v-card>

                <v-card-title
                        class="headline grey lighten-2 text-capitalize"
                        primary-title
                >
                    {{$t('permisos.form.titles.details')}}
                </v-card-title>

                <v-card-text>
                    <v-layout row>
                        <v-flex>
                            <v-list two-line>
                                <v-list-tile
                                        avatar
                                >
                                    <v-list-tile-avatar>
                                        <v-icon>
                                            vpn_key
                                        </v-icon>
                                    </v-list-tile-avatar>

                                    <v-list-tile-content>
                                        {{selected_item.name|capitalize}}
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-divider/>
                                <v-list-tile
                                        avatar
                                >
                                    <v-list-tile-avatar>
                                        <v-icon>
                                            date_range
                                        </v-icon>
                                    </v-list-tile-avatar>

                                    <v-list-tile-content>
                                        {{selected_item.created_at|dateFormat('d-m-Y')}}
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-layout>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="modal_show.is_opened = false">{{$t('buttons.close')}}</v-btn>
                    </v-card-actions>
                </v-card-text>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import DataTable from '@/components/admin/DataTable.vue'
    import BaseSync from '@/components/Base/BaseSync.vue'
    import { Permission } from "@/store/modules/Permission"

    export default {
        name: "permisos",
        layout: 'admin',
        extends: BaseSync,
        middleware: 'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['listar-permisos'],
            store_props: {
                model: Permission,
                fetch: '/admin/permisos'
            },
            redirect: '/admin'
        },
        components: {
            DataTable
        },
        data() {
            return {
                selected_item: {},
                modal_show: {
                    is_opened: false
                },
                //**
                headers: [
                    {
                        sortable: true,
                        value: 'name',
                        showable:true,
                        in_drawer:true
                    },
                    {
                        sortable: true,
                        value: 'active',
                        icon: {
                            true: 'check_box',
                            false: 'check_box_outline_blank'
                        }
                    },
                    {
                        sortable: true,
                        value: 'created_at',
                        showable:true
                    },
                ],
                actions: {
                    table: {
                        default: [],
                        plus: [
                            {
                                name: 'show',
                                icon: 'remove_red_eye',
                                permissions: ['show-permisos']
                            }
                        ]
                    },
                    footer: {
                        default: ['active'],
                        plus: [
                            // {
                            //     name:'footer_show',
                            //     icon:'remove_red_eye',
                            //     tooltip:'Mostrar seleccionados',
                            //     color:'pink'
                            // }
                        ]
                    }
                }
            }
        },
        methods: {
            openModal(item) {
                this.selected_item = item;
                this.modal_show.is_opened = true;
            }
        },
        computed:{
            permisos(){
                return Permission.query().orderBy('pos').get()
            }
        }
    }
</script>

<style scoped>

</style>