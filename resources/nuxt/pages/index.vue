<template>
    <v-layout
            row
            justify-center
            fill-height
            align-center>
        <v-flex xs12 tag="h1" class="headline">Bienvenido a SitiosComUY Frontend Page <hr/> Nuxt + VuetifyJS</v-flex>
        <v-flex s3>
            <figure class="m-auto">
                <SitiosLogo/>
            </figure>
        </v-flex>
        <v-flex m-auto s1>
            <v-icon class="rotate" large>add_circle</v-icon>
        </v-flex>
        <v-flex s3 align-center>
            <figure class="m-auto">
                <Logo/>
            </figure>
        </v-flex>
        <v-flex m-auto s1>
            <v-icon class="rotate" large>add_circle</v-icon>
        </v-flex>
        <v-flex s3 align-center>
            <figure class="m-auto">
                <VuetifyLogo/>
            </figure>
        </v-flex>
    </v-layout>
</template>

<script>
    import Logo from '~/components/Logo.vue'
    import VuetifyLogo from '~/components/VuetifyLogo.vue'
    import SitiosLogo from '~/components/SitiosLogo.vue'

    export default {
        components: {
            Logo,
            VuetifyLogo,
            SitiosLogo
        },
        layout: 'default'
    }
</script>
<style scoped>
    .rotate {
        transform: rotate(90deg);
        transition: transform 1s linear;
    }

</style>