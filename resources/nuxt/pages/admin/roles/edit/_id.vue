<template>
    <edit-form :data="clone" title="Editar Rol" section="roles" @update="update(clone.id,clone)">
        <template slot="form">
            <rol-form :rol="clone"></rol-form>
        </template>
    </edit-form>
</template>

<script>
    import CrudActions from '@/components/admin/CrudActions.vue'
    import EditForm from '@/components/admin/formularios/base/EditForm.vue'
    import RolForm from '@/components/admin/formularios/RolForm.vue'
    import { Role } from "@/store/modules/Role"

    export default {
        layout:'admin',
        extends:CrudActions,
        validate({params}){
            return /^\d+$/.test(params.id)
        },
        middleware:'CheckPermissions',
        components:{
            RolForm,
            EditForm
        },
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['editar-roles'],
            store_props: {
                model: Role,
                query: "query().withAll().find(route.params.id)",
                fetch: 'admin/roles',
                dependencies: {
                    /*Se declaran aca las dependencias q se usaran en la vista a cargar
                    * con llave = llave del modulo en el store, url = apiUrl para cargar*/
                    Permission: 'admin/permisos'
                }
            },
            //Variable donde se almacena el resultado del getter en el asyncData
            data_var:'rol',
            redirect: '/admin/roles'
        },
        data(){
            return {
                repository:this.$repositories.roles,
                clone:{}
            }
        },
        created(){
            //Clono el objeto para que no entre en conflicto con el store cuando se intente modificar
            Object.assign(this.clone,this.rol);
        }
    }
</script>

<style scoped>

</style>