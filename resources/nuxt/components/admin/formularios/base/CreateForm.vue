<template>
    <base-form :link_to_list="`${this.routes.base.admin}/${section}`"
               :title="($t('keywords.create') + ' '+title)|capitalize" icon_action="fibber_new">
        <template slot="form">
            <slot name="form"></slot>
        </template>
        <template slot="actions">
            <slot name="actions"></slot>
            <v-tooltip top>
                <template #activator="data">
                    <v-btn v-on="data.on" :loading="`${section}/create`|whoIsSending" :disabled="isSending"
                           v-can.disabled="[`crear-${section}`]" fab color="info" @click="$emit('reload')">
                        <v-icon>
                            refresh
                        </v-icon>
                    </v-btn>
                </template>
                <span>{{$t('buttons.tooltips.save_reload')}}</span>
            </v-tooltip>

            <v-tooltip top>
                <template #activator="data">
                    <v-btn v-on="data.on" :disabled="isSending" :loading="`${section}/create`|whoIsSending"
                           v-can.disabled="[`crear-${section}`]" fab color="success" @click="$emit('create')">
                        <v-icon>
                            save
                        </v-icon>
                    </v-btn>
                </template>
                <span>{{$t('buttons.tooltips.save')}}</span>
            </v-tooltip>

            <nuxt-link :to="`${this.routes.base.admin}/${section}`">
                <v-tooltip top>
                    <template #activator="data">
                        <v-btn v-on="data.on" :disabled="isSending" absolute fab left color="grey  white--text">
                            <v-icon>
                                format_list_bulleted
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.list')}}</span>
                </v-tooltip>
            </nuxt-link>


        </template>
    </base-form>
</template>

<script>
    import BaseForm from '@/components/admin/formularios/base/BaseForm.vue'
    import CrudActions from '@/components/admin/CrudActions.vue'

    export default {
        name: "create-form",
        extends: CrudActions,
        components: {
            BaseForm
        },
        props: {
            data: Object,
            title: String,
            section: String
        },
        created() {
            //Clono el objeto para que no entre en conflicto con el store cuando se intente modificar
            this.$bus.$on('created', () => {
                setTimeout(() => {
                    this.$router.replace(`${this.routes.base.admin}/${this.section}`)
                }, this.mixin.admin.notification.timeout)
            });
            this.$bus.$on('reloaded', () => {
                this.data = {};
            });
        }
    }
</script>

<style scoped>

</style>