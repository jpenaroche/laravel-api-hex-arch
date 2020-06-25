<template>
    <div>
        <nuxt-link :to="`${this.routes.base.admin}/${section}/create`">
            <v-btn flat color="teal">
                {{$t('buttons.new')}}
                <v-icon>
                    add
                </v-icon>
            </v-btn>
        </nuxt-link>
        <base-form :link_to_list="`${this.routes.base.admin}/${section}`"
                   :title="($t('keywords.edit') + ' '+title)|capitalize" icon_action="edit">
            <template slot="form">
                <modal-action
                        :actions="modal.actions"
                        :is_opened="modal.opened"
                        :title="$t(`${section}.dialogs.delete.title`)"
                        :text="$tc(`${section}.dialogs.delete.text`,1)"
                        :is_sending="isSending"/>

                <slot name="form"></slot>
            </template>
            <template slot="actions">
                <slot name="actions"></slot>
                <v-tooltip top>
                    <template #activator="data">
                        <v-btn v-on="data.on" :disabled="isSending" :loading="`${section}/updating`|whoIsSending"
                               v-can.disabled="[`actualizar-${section}`]" fab color="success" @click="$emit('update')">
                            <v-icon>
                                save
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.save')}}</span>
                </v-tooltip>

                <v-tooltip top>
                    <template #activator="data">
                        <v-btn v-on="data.on" :disabled="isSending || cant_delete"
                               :loading="`${section}/deleting`|whoIsSending"
                               v-can.disabled="[`borrar-${section}`]" absolute fab left color="error"
                               @click="openModal">
                            <v-icon>
                                delete
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.delete')}}</span>
                </v-tooltip>
            </template>
        </base-form>
    </div>
</template>

<script>
    import BaseForm from '@/components/admin/formularios/base/BaseForm.vue'
    import CrudActions from '@/components/admin/CrudActions.vue'
    import ModalAction from '@/components/admin/ModalAction.vue'

    export default {
        name: "edit-form",
        extends: CrudActions,
        components: {
            BaseForm,
            ModalAction
        },
        props: {
            data: Object,
            section: String,
            title: String
        },
        created() {
            ['updated', 'deleted'].forEach((ev) => {
                this.$bus.$on(ev, () => {
                    setTimeout(() => {
                        this.$router.replace(`${this.routes.base.admin}/${this.section}`)
                    }, this.mixin.admin.notification.timeout)
                })

            });
            this.$bus.$on('cantDelete', () => this.cant_delete = true)
        },
        data() {
            return {
                modal: {
                    opened: false,
                    actions: {
                        cancel: () => {
                            this.modal.opened = false;
                        },
                        confirm: {}
                    }
                },
                repository: this.$repositories[this.section],
                cant_delete: false
            }
        },
        methods: {
            openModal() {
                this.modal.opened = true;
                this.modal.actions.confirm = this.destroy.bind(this, this.data.id)
            }
        }
    }
</script>

<style scoped>

</style>