<template>
    <v-card class="details-card">
        <v-card-title class="pink accent-2">
            <h3 class="header white--text text-uppercase">
                {{$t('form.titles.details')}}
            </h3>
        </v-card-title>
        <v-card-text class="fill-height">
            <v-list>
                <template v-for="(key, index) in only">
                    <v-list-tile
                            :key="index"
                    >
                        <v-list-tile-content>
                            <v-list-tile-sub-title> <small>{{ $t(key.split('.').pop()) }}</small></v-list-tile-sub-title>
                            <v-list-tile-title v-if="!Array.isArray(getFrom(data,key.split('.')))">{{getFrom(data,key.split('.'))}}</v-list-tile-title>
                            <!--<v-list-tile-title v-else>
                                <v-chip v-for="prop of getFrom(data,key.split('.'))" :key="prop.id" color="green" text-color="white">
                                    {{prop.name}}
                                </v-chip>
                            </v-list-tile-title>-->
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-divider
                            v-if="index + 1 < only.length"
                            :key="key"
                    ></v-divider>
                </template>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
    export default {
        name: "preview-card",
        props:{
            data:Object,
            only:Array,
            trans_path:String
        },
        data(){
            return {
                expanded:[]
            }
        },
        methods:{
            getFrom(obj, path){
                if (path.length === 1)
                    return obj[path];

                return this.getFrom(obj[path[0]], path.slice(1))
            }
        }
    }
</script>

<style scoped>
    .details-card > .v-card__text {
        min-height: 350px;
        height: 100%;
        z-index: 5;
    }
</style>