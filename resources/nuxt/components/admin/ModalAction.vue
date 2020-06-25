<template>
    <v-dialog
            persistent
            v-model="is_opened"
            width="350"
    >
        <v-card>
            <v-card-title class="headline">{{title}}</v-card-title>

            <v-card-text>
                <v-layout row>
                    <v-flex xs3 sm3>
                        <v-icon v-if="!is_sending"
                                large
                                color="yellow darken-3"
                        >
                            warning
                        </v-icon>

                        <v-progress-circular
                                v-else
                                :size="40"
                                color="amber"
                                indeterminate
                        ></v-progress-circular>
                    </v-flex>
                    <v-flex>
                        {{text}}
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn :disabled="is_sending"
                       color="green darken-1"
                       flat="flat"
                       @click.stop="actions.cancel()"
                >
                    {{$t('buttons.cancel')}}
                </v-btn>

                <v-btn :disabled="is_sending"
                       color="green darken-1"
                       flat="flat"
                       @click.stop="actions.confirm()"
                >
                    {{$t('buttons.confirm')}}

                </v-btn>

            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "modal-action",
        props:{
            title:String,
            text:String,
            actions:{
                type:Object,
                defaut:{
                    cancel:{},
                    confirm:{}
                }
            },
            is_persisted:{
                type:Boolean,
                default:true
            },
            is_opened:{
                type:Boolean,
                default:false
            },
            is_sending:{
                type:Boolean,
                default:false
            }
        }
    }
</script>

<style scoped>

</style>