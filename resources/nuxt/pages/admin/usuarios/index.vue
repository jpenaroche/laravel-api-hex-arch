<template>
    <!--Sobre el componente se escriben los listeners de cada action q se pasa como prop al DataTable "@show"-->
    <DataTable :title="$tc('usuarios.name',0)" section="usuarios" :items="users" :headers="headers"/>
</template>

<script>
    import DataTable from '@/components/admin/DataTable.vue'
    import BaseSync from '@/components/Base/BaseSync.vue'
    import {User} from "@/store/modules/User"
    import {Mediable} from "../../../store/modules/Media";

    export default {
        name: "users",
        layout: 'admin',
        extends: BaseSync,
        middleware: 'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['listar-usuarios'],
            store_props: {
                model: User,
                fetch: 'admin/usuarios'
            },
            redirect: '/admin'
        },
        components: {
            DataTable
        },
        mounted(){
           /* console.log(User.query().with('media',(query)=>{
                query.with('mediable').whereHas('mediable',(query)=>{
                    query.where('tag','url')
                })
            }).find(127));*/
        },
        data() {
            return {
                headers: [
                    {
                        sortable: true,
                        value: 'name',
                        showable:true,
                        in_drawer:true
                    },
                    {
                        sortable: true,
                        value: 'email',
                        showable:true
                    },
                    {
                        sortable: true,
                        value: 'is_admin',
                        icon: {
                            true: 'check_box',
                            false: 'check_box_outline_blank'
                        }
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
                        value: 'created_at'
                    },
                ]
            }
        },
        computed: {
            users() {
                return User.query().where(record => record.id!==this.$auth.user.id).orderBy('pos').get()
            }
        }
    }
</script>

<style scoped>

</style>