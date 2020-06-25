<template>
  <v-app class="white">
      <v-container>
          <nuxt/>
      </v-container>
  </v-app>
</template>

<script>
    import MainComponent from '@/components/Base/MainComponent'

    export default {
        name: "default",
        extends:MainComponent
    }
</script>

<style scoped>

</style>