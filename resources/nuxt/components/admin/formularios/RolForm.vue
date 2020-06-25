<template>
    <v-form ref="form" class="vee" novalidate>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 sm12>
                    <v-text-field
                            v-validate="'required'"
                            label="Nombre"
                            v-model="rol.name"
                            name="name"
                            :error-messages="errors.collect('name')"
                            outline
                    ></v-text-field>
                    <v-textarea
                            outline
                            name="description"
                            label="Description"
                            v-model="rol.description"
                    ></v-textarea>
                </v-flex>
            </v-layout>
            <select-two :tags="rol.permissions" @synced="(data)=>rol.permissions = data"
                        :item_list="permisos"></select-two>
            <v-divider/>
            <v-layout row justify-end>
                <v-flex xs3 sm3>
                    <v-switch color="teal" v-model="rol.active" :label="$t('roles.form.fields.active')"></v-switch>
                </v-flex>
            </v-layout>
        </v-container>
        <slot name="button"></slot>

    </v-form>
</template>

<script>
    import SelectTwo from '@/components/admin/SelectTwo.vue'
    import BaseForm from '@/components/Base/BaseForm'
    import { Permission } from "@/store/modules/Permission"

    export default {
        name: "rol-form",
        inject: ['$validator'],
        extends:BaseForm,
        components: {
            SelectTwo
        },
        props: {
            rol: Object
        },
        computed:{
            permisos(){
                return Permission.query().where('active',true).get()
            }
        }
    }


</script>

<style scoped>

</style>