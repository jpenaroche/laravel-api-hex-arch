<template>
    <base-form :link_to_list="`${this.routes.base.admin}/${section}`" :title="title" icon_action="info_outline">
        <template slot="form">
            <slot name="form"></slot>
        </template>
        <template slot="actions">
            <slot name="actions"></slot>
            <nuxt-link :to="`${this.routes.base.admin}/${section}`">
                <v-tooltip top>
                    <template #activator="data">
                        <v-btn v-on="data.on" absolute fab left color="grey  white--text">
                            <v-icon>
                                format_list_bulleted
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.list')}}</span>
                </v-tooltip>
            </nuxt-link>
            <nuxt-link :to="`${this.routes.base.admin}/${section}/edit/${data.id}`">
                <v-tooltip top>
                    <template #activator="data">
                        <v-btn v-on="data.on" absolute fab right color="success">
                            <v-icon>
                                edit
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.edit')}}</span>
                </v-tooltip>
            </nuxt-link>
        </template>
    </base-form>
</template>

<script>
    import BaseForm from '@/components/admin/formularios/base/BaseForm.vue'
    import CrudActions from '@/components/admin/CrudActions.vue'

    export default {
        name: "show-form",
        extends:CrudActions,
        components:{
            BaseForm
        },
        props:{
            data:Object,
            section:String,
            title:String
        }
    }
</script>

<style scoped>

</style>