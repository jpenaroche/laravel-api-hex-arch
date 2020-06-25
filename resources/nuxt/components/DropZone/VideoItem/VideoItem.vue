<template>
    <div>
        <v-divider></v-divider>
        <v-layout row justify-end>
            <slot name="buttons-prepend"></slot>
            <v-tooltip left>
                <template #activator="data">
                    <v-btn flat small fab @click.stop="$emit('show_attr_dialog',video_info)">
                        <v-icon v-on="data.on">rate_review</v-icon>
                    </v-btn>
                </template>
                <span>{{$t('form.titles.video_attributes')}}</span>
            </v-tooltip>

            <slot name="buttons-append"></slot>
        </v-layout>
        <v-card>
            <v-layout row wrap>
                <v-flex xs5>
                    <v-img
                            :src="video_info.video_thumbnail"
                            contain
                    ></v-img>
                </v-flex>
                <v-flex xs7>
                    <v-card-title primary-title>
                        <div class="padding-5">
                            <div>{{$t('form.labels.video_code')}}{{video_info.video_id}}</div>
                            <div>{{$t('form.labels.video_url')}}{{video_info.video_url}}</div>
                        </div>
                    </v-card-title>
                </v-flex>
            </v-layout>
            <v-list v-if="Object.keys(video_info.attributes).length > 0 ">
                <v-list-tile v-for="(prop,index) of Object.entries(video_info.attributes)" :key="index">
                    <v-list-tile-content>
                        <v-list-tile-title>{{prop[0]}}</v-list-tile-title>
                        <v-list-tile-sub-title>{{prop[1]}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "video-item",
        props:{
            video_info:Object
        }
    }
</script>

<style scoped>

</style>