<template>
    <v-layout>
        <v-flex tag="h1" class="headline" sm5>
            DASHBOARD
            <loader></loader>
        </v-flex>
    </v-layout>
</template>

<script>
    import Loader from '@/components/admin/Loader/Loader'

    export default {
        name: "index",
        layout:'admin',
        components:{
            Loader
        }
    }
</script>

<style scoped>

</style>