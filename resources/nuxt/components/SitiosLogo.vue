<template>
    <img :style="{width:size}" class="SitiosLogo" src="~/static/img/sitios_logo/iso/sitios-iso_c_128.png" alt="">
</template>

<script>
    export default {
        name: "sitios-logo",
        props:{
            size:String
        }
    }
</script>

<style scoped>
   .SitiosLogo {
       transform: rotate(-180deg);
       animation: turn 3.5s ease-out forwards 1s;
   }

   @keyframes turn {
       100% {
           transform: rotate(0deg);
       }
   }
</style>