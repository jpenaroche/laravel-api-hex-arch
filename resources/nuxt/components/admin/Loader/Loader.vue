<template lang="html">
    <div :class="{'is-active':loading}" class="pageloader">
        <div class="sitios-logo">
            <figure></figure>
        </div>
        <span v-if="show_title" class="title">Cargando</span>
    </div>
</template>
<script>
    import './style.styl'

    export default {
        props:{
            show_title:{
                type:Boolean,
                default:false
            }
        },
        data: () => ({
            loading: false
        }),
        methods: {
            start () {
                this.loading = true
            },
            finish () {
                this.loading = false
            }
        }
    };
</script>