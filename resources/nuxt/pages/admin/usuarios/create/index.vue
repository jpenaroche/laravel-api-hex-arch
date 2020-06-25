<template>
    <create-form :data="user" :title="$tc('usuarios.name',1)" section="usuarios" @create="go" @reload="go">
        <template slot="form">
            <user-form ref="user_form" :user="user"></user-form>
        </template>
    </create-form>
</template>

<script>
    import CrudActions from '@/components/admin/CrudActions.vue'
    import CreateForm from '@/components/admin/formularios/base/CreateForm.vue'
    import UserForm from '@/components/admin/formularios/UserForm.vue'


    export default {
        layout:'admin',
        extends:CrudActions,
        components:{
            UserForm,
            CreateForm
        },
        middleware:'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['crear-usuarios'],
            redirect: '/admin/users',
            store_props: {
                fetch: 'admin/usuarios',
                dependencies: {
                    /*Se declaran aca las dependencias q se usaran en la vista a cargar
                    * con llave = llave del modulo en el store, url = apiUrl para cargar*/
                    Role: 'admin/roles',
                    Permission: 'admin/permisos'
                }
            },
        },
        data(){
            return {
                //Inicializo el objeto usuario
                user:{
                    profile:{},
                    roles:[],
                    permissions:[]
                },
                show_pass:false,
                repository:this.$repositories.usuarios,
            }
        },
        methods:{
            go(){
                // this.$refs.user_form.getData();
               this.save(this.$refs.user_form.getData());
            },
            reload(){
                this.saveAndReload(this.$refs.user_form.getData())
            }
        }
    }
</script>

<style scoped>

</style>