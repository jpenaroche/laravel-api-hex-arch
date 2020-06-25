<template>
    <create-form :data="clone" title="Nuevo Rol" section="roles" @create="save(clone)">
        <template slot="form">
            <rol-form :rol="clone"></rol-form>
        </template>
    </create-form>
</template>

<script>
    import CreateForm from '@/components/admin/formularios/base/CreateForm.vue'
    import RolForm from '@/components/admin/formularios/RolForm.vue'
    import CrudActions from '@/components/admin/CrudActions.vue'

    export default {
        layout:'admin',
        extends:CrudActions,
        middleware:'CheckPermissions',
        components:{
            CreateForm,
            RolForm
        },
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['nuevo-roles'],
            store_props: {
                dependencies: {
                    /*Se declaran aca las dependencias q se usaran en la vista a cargar
                    * con llave = llave del modulo en el store, url = apiUrl para cargar*/
                    Permission: 'admin/permisos'
                }
            },
            redirect: '/admin/roles'
        },
        data(){
            return {
                show_pass:false,
                clone:{
                    name:'',
                    active:1,
                    permissions:[],
                    description:''
                },
                repository: this.$repositories.roles
            }
        }
    }
</script>

<style scoped>

</style>