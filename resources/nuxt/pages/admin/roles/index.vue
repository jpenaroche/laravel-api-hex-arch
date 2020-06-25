<template>
    <!--Sobre el componente se escriben los listeners de cada action q se pasa como prop al DataTable "@show"-->
    <DataTable :title="$tc('roles.name',0)" section="roles" :items="roles" :headers="headers"/>
</template>

<script>
    import DataTable from '@/components/admin/DataTable.vue'
    import BaseSync from '@/components/Base/BaseSync.vue'
    import { Role } from "@/store/modules/Role"

    export default {
        name: "roles",
        layout: 'admin',
        extends:BaseSync,
        middleware: 'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['listar-roles'],
            store_props: {
                model: Role,
                fetch: '/admin/roles'
            },
            redirect: '/admin'
        },
        components: {
            DataTable
        },
        data() {
            return {
                headers: [
                    {
                        text: 'Nombre',
                        sortable: true,
                        value: 'name',
                        in_drawer:true
                    },
                    {
                        text: 'Activo',
                        sortable:true,
                        value: 'active',
                        icon: {
                            true: 'check_box',
                            false: 'check_box_outline_blank'
                        }
                    },
                    {
                        text: 'Creado',
                        sortable: true,
                        value: 'created_at'
                    },
                ]
            }
        },
        computed:{
            roles(){
                return Role.query().orderBy('pos').get()
            }
        }
    }
</script>

<style scoped>

</style>