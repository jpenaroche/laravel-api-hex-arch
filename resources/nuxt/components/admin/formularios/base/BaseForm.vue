<template>
    <v-card>
        <v-toolbar
                card
                color="cyan"
                dark
        >
            <nuxt-link class="link" :to="link_to_list">
                <v-icon>list</v-icon>
            </nuxt-link>
            <v-toolbar-title>{{title}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <slot name="icon_actions"></slot>
            <v-icon>{{icon_action}}</v-icon>
        </v-toolbar>

        <slot name="form"></slot>
        <v-alert
                :value="hasErrors"
                type="error"
                color="red accent--1"
                transition="scale-transition"
        >
            {{$t('alerts.invalid_form')}}
            <ul>
                <li v-for="item of $validator.errors.items" :key="item.id">{{item.field}}</li>
            </ul>
        </v-alert>

        <v-divider/>

        <v-card-actions>
            <v-layout justify-end>
                <slot name="actions"></slot>
            </v-layout>
        </v-card-actions>

        <modal-loading :is_sending="isSending && sending_condition"/>
    </v-card>
</template>

<script>
    import ModalLoading from '@/components/admin/ModalLoading.vue'

    export default {
        name: "base-form",
        components: {
            ModalLoading
        },
        props: {
            title: String,
            icon_action: String,
            link_to_list: String,
            data: Object,
            time: {
                type: Number,
                default: 1500
            },
            sending_condition: {
                type: Boolean,
                default: true
            }
        },
        created() {
            this.$bus.$on('errors', (errors) => {
                
                Object.entries(errors).forEach((value) => {
                    //Aqui busco el campo en el validator base pues es
                    const field = this.$validator._base.fields.find({name: value[0]});
                    this.errors.add({
                        id: field.id,
                        field:field.name,
                        msg: value[1][0],
                        scope: field.scope,
                        vmId:field.vmId
                    });
                    field.setFlags({
                        valid: ! value[1].length,
                        dirty: true
                    });
                })
            })
        },
        computed: {
            hasErrors() {
                return this.$validator.errors.items.length > 0
            }
        }
    }
</script>

<style scoped>

</style>