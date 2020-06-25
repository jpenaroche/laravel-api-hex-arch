<template>
    <iframe :src="src" :width="width" :height="500" :frameborder="frameborder" :style="css" allowfullscreen>
    </iframe>
</template>

<script>
    export default {
        name: "iframe",
        props:{
            src:String,
            width:{
                String,
                default:'100%'
            },
            height:Number,
            frameborder:Number,
            css:Object
        }
    }
</script>

<style scoped>

</style>