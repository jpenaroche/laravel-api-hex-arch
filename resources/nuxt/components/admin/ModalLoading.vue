<template>
    <v-dialog
            v-model="is_sending"
            persistent
            width="300"
    >
        <v-card
                color="green accent-4"
                dark
        >
            <v-card-text>
                {{$t('dialogs.sending')}}
                <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "modal-loading",
        props:{
            is_sending:Boolean
        }
    }
</script>

<style scoped>

</style>