<template>
    <div>
        <v-dialog :value="show" max-width="500px" persistent>
            <v-card>
                <v-card-title class="text-capitalize">
                    {{$t('form.titles.upload_video')}}
                </v-card-title>
                <v-card-text>
                    <v-alert
                            :value="dirty_url"
                            type="error"
                            color="red accent--1"
                            transition="scale-transition"
                    >
                        {{$t('form.alerts.wrong_url_video')}}
                    </v-alert>
                    <v-text-field
                            @click:clear="clear"
                            v-debounce:1s="processUrl"
                            v-model="url"
                            label="url"
                            clearable
                            prepend-inner-icon="http"
                    ></v-text-field>

                    <video-item
                            v-if="fetched"
                            :video_info="video_info"
                            @show_attr_dialog="show_attributes_dialog = true">
                    </video-item>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="loading" color="primary" flat
                           @click="close">
                        {{$t('buttons.close')}}
                    </v-btn>
                    <v-btn :loading="loading" :disabled="!fetched" color="primary" flat
                           @click="save">
                        <v-icon>file_upload</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <attr-dialog :target="video_info" :show="show_attributes_dialog" :attributes="attributes"  @save="save_attr"></attr-dialog>
    </div>
</template>

<script>
    import axios from 'axios'
    import Vue from 'vue'
    import VueDebounce from 'vue-debounce'
    import AttrDialog from '../VideoItem/AttrDialog/AttrDialog'
    import VideoItem from '../VideoItem/VideoItem'

    Vue.use(VueDebounce)
    export default {
        name: "upload-video-dialog",
        components: {
            VideoItem,
            AttrDialog
        },
        props: {
            show: Boolean,
            attributes: {
                type: Array,
                default: function () {
                    return ['title']
                }
            },
        },
        data() {
            return {
                loading: false,
                dirty_url: false,
                color: '',
                url: '',
                video_info: {},
                show_attributes_dialog:false
            }
        },
        mounted() {
            this.color = this.randomColor();
        },
        methods: {
            clear() {
                this.video_info = {};
                this.url = '';
                this.dirty_url = false;
                this.loading = false;
            },
            close() {
                this.clear();
                this.$emit('close', false)
            },
            save() {
                this.$emit('fetched', this.video_info);
                this.close();
            },
            save_attr(obj){
                this.video_info = obj;
                this.show_attributes_dialog = false;
            },
            processUrl() {
                this.loading = true;
                this.dirty_url = false;
                this.video_info = {};
                const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = regExp.exec(this.url);
                if (match && match[2].length == 11) {
                    let thumbnail = "http://i.ytimg.com/vi/" + match[2] + "/1.jpg";
                    this.video_info = {
                        video_id: match[2],
                        video_url: this.url,
                        video_thumbnail: thumbnail,
                        extension: 'YT',
                        type: 'video',
                        size:0,
                        mime_type:'text/uri-list',
                        attributes: {}
                    };
                    this.loading = false;
                } else {
                    const regExpVimeo = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
                    const matchVimeo = regExpVimeo.exec(this.url);
                    axios.get(`https://vimeo.com/api/oembed.json?url=${this.url}`)
                        .then((response) => {
                            this.video_info = {
                                video_id: matchVimeo[3],
                                video_url: this.url,
                                video_thumbnail: response.data.thumbnail_url,
                                extension: 'VM',
                                type: 'video',
                                size:0,
                                attributes: {}

                            };
                        })
                        .catch((response) => {
                            this.dirty_url = true;
                            console.log(response);
                        })
                        .finally(() => {
                            this.loading = false;
                        })
                }
            }
        },
        computed: {
            fetched() {
                return this.video_info.hasOwnProperty('video_id')
            }
        }
    }
</script>

<style scoped>
    .attributes input {
        height: 25px;
        width: inherit;
    }
</style>