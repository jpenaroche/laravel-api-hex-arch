<template>
    <edit-form :data="clone" :title="$tc('usuarios.name',1)" section="usuarios" @update="go">
        <template slot="form">
            <user-form ref="user_form" :user="clone" :files="files"></user-form>
        </template>
    </edit-form>
</template>

<script>
    import CrudActions from '@/components/admin/CrudActions.vue'
    import EditForm from '@/components/admin/formularios/base/EditForm.vue'
    import UserForm from '@/components/admin/formularios/UserForm.vue'
    import {User} from "@/store/modules/User";

    export default {
        layout: 'admin',
        middleware: 'CheckPermissions',
        extends: CrudActions,
        components: {
            UserForm,
            EditForm
        },
        validate({params}) {
            return /^\d+$/.test(params.id)
        },
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['editar-usuarios'],
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
            //Variable donde se almacena el resultado del getter en el asyncData
            data_var: 'user',
            redirect: '/admin/usuarios'
        },
        data() {
            return {
                show_pass: false,
                //Declarado el repository para el acceso a las funciones del mismo desde crud-actions
                repository:this.$repositories.usuarios,
                clone: {},
                files:[]
            }
        },
        created() {
            //Clono el objeto para que no entre en conflicto con el store cuando se intente modificar
            Object.assign(this.clone, this.user);
        },
        methods: {
            go() {
                // this.$refs.user_form.getData();
                this.update(this.clone.id, this.$refs.user_form.getData());
            }
        }

    }
</script>

<style scoped>

</style>