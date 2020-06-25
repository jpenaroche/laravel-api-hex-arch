<template>
  <v-layout>
    <v-flex text-xs-center>
      <v-img :aspect-ratio="16/9" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
      </v-img>
      <img
        src="/v.png"
        alt="Vuetify.js"
        class="mb-5">
      <blockquote class="blockquote">
        &#8220;First, solve the problem. Then, write the code.&#8221;
        <footer>
          <small>
            <em>&mdash;John Johnson</em>
          </small>
        </footer>
      </blockquote>
    </v-flex>
  </v-layout>
</template>
<script>
    export default{
        name:'inspire',
        layout:'admin',
    }
</script>
