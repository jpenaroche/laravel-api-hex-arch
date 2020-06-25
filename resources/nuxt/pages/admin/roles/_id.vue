<template>
    <show-form :data="rol" :title="rol.name|capitalize" section="roles">
        <template slot="form">
            <v-container>
                <v-layout>
                    <v-flex>
                        <p><strong>Name: </strong>{{rol.name}}</p>
                        <p v-if="rol.description"><strong>Description: </strong>{{rol.description}}</p>
                        <div v-if="rol.permissions.length > 0">
                            <v-divider/>
                            <h3 class="mt-3">Permisos</h3>
                            <v-chip v-for="permission of rol.permissions" :key="permission.id" color="green" text-color="white">
                                <v-avatar>
                                    <v-icon>fa fa-key fa-1x</v-icon>
                                </v-avatar>
                                {{permission.name}}
                            </v-chip>
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </template>
    </show-form>
</template>

<script>
    import ShowForm from '@/components/admin/formularios/base/ShowForm.vue'
    import { Role } from "@/store/modules/Role";

    export default {
        layout: 'admin',
        extends:ShowForm,
        validate({params}){
            return /^\d+$/.test(params.id)
        },
        components:{
            ShowForm
        },
        middleware:'CheckPermissions',
        meta: {
            //Permisos que se leen en el middleware CheckPermissions para manejar el acceso a la pagina
            permissions: ['mostrar-roles'],
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
            data_var:'rol',
            redirect: '/admin/roles'
        },
        data(){
            return {
                rol:{}
            }
        }
    }
</script>

<style scoped>

</style>