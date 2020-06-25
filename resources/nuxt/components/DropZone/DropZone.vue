<template>
    <v-layout column>
        <v-flex>
            <v-alert
                    :value="max_files_reached"
                    color="warning"
                    icon="priority_high"
                    dismissible
            >
                {{$t('alerts.dropzone.max_files_reached')}}
            </v-alert>
            <vue-dropzone
                    v-show="hasFiles"
                    @vdropzone-sending="attach"
                    @vdropzone-max-files-reached="max_files_reached = true"
                    @vdropzone-complete-multiple="($event)=>files_processed.dropzone.push($event)"
                    @vdropzone-queue-complete="$emit('uploadComplete', files_processed.dropzone)"
                    ref="zone"
                    :disabled="true"
                    :id="id"
                    :options="drop_options"
                    :useCustomSlot=true
            >
                <div class="dropzone-custom-content">
                    <h3 class="dropzone-custom-title">{{$t('form.dropzone.title')}}</h3>
                    <div class="subtitle">{{$t('form.dropzone.subtitle')}}</div>
                </div>
            </vue-dropzone>
        </v-flex>
        <v-flex v-show="hasVideos">
            <!--<v-alert
                    :value="maxVideosReached"
                    type="warning"
                    color="yellow darken&#45;&#45;2"
                    transition="scale-transition"
            >
                {{$t('form.alert.max_video_files_reached')}}
            </v-alert>-->
            <v-layout row justify-end>
                <v-tooltip left>
                    <template #activator="data">
                        <v-btn
                                :disabled="maxVideosReached"
                                @mouseenter="show_video_limit = true"
                                @mouseleave="show_video_limit = false"
                                v-on="data.on" flat color="primary"
                                @click="dialogs.upload_video = true"
                        >
                            <v-badge right :color="maxVideosReached?'red':'blue'" :value="show_video_limit||maxVideosReached">
                                <template v-slot:badge>
                                    <span>{{files_processed.videos.length}}</span>
                                </template>
                                <v-icon class="mr-2">video_call</v-icon>
                            </v-badge>
                        </v-btn>
                    </template>
                    <span>{{$t('form.titles.upload_video')}}</span>
                </v-tooltip>
            </v-layout>
            <v-layout column v-if="files_processed.videos.length > 0">
                <v-divider></v-divider>
                <v-flex v-for="(file,index) of files_processed.videos" :key="index">
                    <video-item :video_info="file" @show_attr_dialog="show_attributes_dialog">
                        <template slot="buttons-append">
                            <v-tooltip top>
                                <template #activator="data">
                                    <v-btn v-on="data.on" flat small fab
                                           @click="files_processed.videos = files_processed.videos.filter(v => v.video_id!== file.video_id)"
                                    >
                                        <v-icon>
                                            delete
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>{{$t('buttons.delete')}} {{$t('keywords.video')}}</span>
                            </v-tooltip>

                        </template>
                    </video-item>
                </v-flex>
            </v-layout>
        </v-flex>

        <!--Dialog para url-->
        <upload-video
                :show="dialogs.upload_video"
                :attributes="preview_properties"
                @fetched="appendVideo"
                @close="dialogs.upload_video = false">
        </upload-video>

        <!--Dialog para attributos-->
        <attr-dialog
                :show="dialogs.attr.show"
                :target="dialogs.attr.video_info"
                :attributes="preview_properties"
                @save="save_video_attributes">
        </attr-dialog>
    </v-layout>
</template>

<script>
    import VueDropzone from "vue2-dropzone"
    import UploadVideo from "./UploadVideoDialog/UploadVideoDialog"
    import MIME_TYPES from './mime_types'
    import './style.css'
    import Jimp from 'jimp'
    import AttrDialog from './VideoItem/AttrDialog/AttrDialog'
    import VideoItem from './VideoItem/VideoItem'

    export default {
        name: "drop-zone",
        components: {
            VueDropzone,
            UploadVideo,
            AttrDialog,
            VideoItem
        },
        props: {
            id: {
                type: String,
                default: 'my_drop-zone'
            },
            subtitle: String,
            options: {
                type: Object,
                required: false,
                validator: function (value) {
                    if (value.hasOwnProperty('videos') && value.videos.hasOwnProperty('uploaded'))
                        if (!Array.isArray(value.videos.uploaded))
                            return false;
                    if (value.hasOwnProperty('uploaded') && !Array.isArray(value.uploaded))
                        return false;

                    return true;
                }
            },
            only: {
                type: Array,
                default: function () {
                    return ['files', 'videos']
                },
                validator: function (values) {
                    return values.length > 0 && values.every(v => v === 'files' || v === 'videos')
                }
            },
            types: {
                type: Array,
                default: function () {
                    return []
                },
                validator: function (values) {
                    return values.every(v => Object.keys(MIME_TYPES).indexOf(v) !== -1)
                }
            },
            mimes: {
                type: Array,
                default: function () {
                    return []
                },
                validator: function (values) {
                    return values.every(v => Object.values(MIME_TYPES).indexOf(v) !== -1)
                }
            },
            preview_properties: {
                type: Array,
                default: function () {
                    return ['title']
                }
            },
            group: {
                type: String,
                default: 'gallery'
            }
        },
        data() {
            return {
                drop_options: {
                    addRemoveLinks: true,
                    previewTemplate: this.defaultTemplate(),
                    uploadMultiple: true,
                    transformFile: this.optimize,
                    duplicateCheck: true,
                    dictInvalidFileType: this.$t('alerts.dropzone.invalid_type'),
                    dictFileTooBig: this.$t('alerts.dropzone.file_too_big'),
                    dictResponseError: this.$t('alerts.dropzone.response_error'),
                    dictRemoveFile: this.$t('form.dropzone.remove'),
                    dictMaxFilesExceeded: this.$t('alerts.dropzone.max_files_exceeded'),
                },
                passed_mimes: this.mimes,
                files_processed: {
                    dropzone: [],
                    videos: []
                },
                max_files_reached: false,
                show_video_limit: true,
                //Objeto temporal para almacenar recurso optimizado
                optimized: {},
                dialogs: {
                    upload_video: false,
                    video_preview: false,
                    attr: {
                        show: false,
                        video_info: {}
                    }
                }
            }
        },
        created() {
            Object.assign(this.drop_options, this.options);
            if (this.mimes.length > 0) {
                this.drop_options.acceptedFiles = this.mimes.join(',')
            } else if (this.types.length > 0) {
                this.passed_mimes = this.types.map(t => MIME_TYPES[t]).reduce((acc, m) => acc.concat(m));
                this.drop_options.acceptedFiles = this.passed_mimes.join(',');
            }
        },
        mounted() {
            this.init();
        },
        methods: {
            appendVideo(v) {
                const i = this.files_processed.videos.findIndex(video => video.video_id === v.video_id);
                if (i === -1) {
                    this.files_processed.videos.push(v);
                    return
                }
                this.files_processed.videos[i] = v;
            },
            show_attributes_dialog(e) {
                this.dialogs.attr.video_info = e;
                this.dialogs.attr.show = true;
            },
            save_video_attributes(obj) {
                const i = this.files_processed.videos.findIndex(v => v.video_id === obj.video_id);
                if (i !== -1)
                    this.files_processed.videos[i] = obj
                this.dialogs.attr.show = false;
            },
            putInZone(file) {
                //TODO Cambiar a uso del store cuando me den respuesta del issue de vuex-orm
                const attributes = JSON.parse(file.attributes);
                this.$refs.zone.manuallyAddFile({
                    size: file.size,
                    id: file.id,
                    filename: file.filename,
                    name: attributes.title,
                    attributes: attributes,
                    type: file.mime_type,
                }, this.$options.filters.filePath(file))
            },
            init() {
                //cargo los archivos cargados en DB
                if (this.drop_options.uploaded)
                    this.drop_options.uploaded.forEach(this.putInZone);

                //Inicializo los inputs con los attributos de cada media cargada
                this.getAddedFiles().forEach(({attributes, previewElement}) => {
                    this.preview_properties.forEach(attr => {
                        previewElement.querySelector(`input[name="${attr}"]`).value = attributes[attr]
                    })
                });

                //cargo los archivos cargados en DB de tipo url para los videos
                if (this.hasVideos)
                    if (this.drop_options.videos.uploaded)
                        this.files_processed.videos = this.drop_options.videos.uploaded.map(v => {
                            v.attributes = JSON.parse(v.attributes);
                            return v;
                        })

            },
            parseTemplate(obj, file) {
                this.preview_properties.forEach(prop => {
                    Object.assign(obj, {
                        [prop]: file.previewElement.querySelector(`input[name="${prop}"]`).value || file.name
                    })
                });
                return obj;
            },
            build(file) {
                const obj = {};
                this.parseTemplate(obj, file);
                obj.file = file;
                return obj
            },
            attach(file, xhr, formData) {
                const parsed_file = this.build(file);
                formData.append(`medias[${this.group}][files][]`, parsed_file.file);
                delete parsed_file.file;
                formData.append(`medias[${this.group}][mimes]`, JSON.stringify(parsed_file.mimes));
                delete parsed_file.mimes;
                formData.append(`medias[${this.group}][attributes][]`, JSON.stringify(parsed_file));
            },
            processFiles() {
                const data = {
                    group: this.group,
                    mimes: this.passed_mimes,
                    uploaded: [],
                    medias: []
                };

                if (this.hasVideos) {
                    //Construyo los ficheros de tipo video - url y los guardo en medias
                    const uploaded = [],
                        to_upload = [];

                    //Extraigo ficheros subidos y ficheros nuevos
                    this.files_processed.videos.forEach(v => {
                        if(v.hasOwnProperty('id')){
                            uploaded.push(v);
                            return
                        }
                        to_upload.push(v)
                    });
                    to_upload.forEach(v => {
                            const {attributes} = v;
                            data.medias.push(Object.assign({file: JSON.stringify(v)},attributes))
                        });

                    uploaded.forEach(file => {
                        data.uploaded.push({
                            id: file.id,
                            filename: file.filename,
                            attributes: JSON.stringify(file.attributes)
                        })
                    })
                }

                if (this.hasFiles) {
                    //Construyo los ficheros cargados de DB con sus atributos dentro de cada preview
                    this.getAddedFiles().forEach(file => {
                        data.uploaded.push({
                            id: file.id,
                            filename: file.filename,
                            attributes: JSON.stringify(this.parseTemplate({}, file))
                        })
                    });

                    //Construyo los ficheros subidos con sus atributos dentro de cada preview
                    this.getAcceptedFiles().forEach((file) => {
                        if (file.size > 2000000 && file.type.includes('image')) {
                            this.optimize(file).then((optimized) => {
                                data.medias.push(this.build(optimized))
                            });
                            return
                        }

                        data.medias.push(this.build(file))
                    });
                }

                return data;
            },
            optimize(file, done) {
                return new Promise((resolve, reject) => {
                    let reader = new FileReader();

                    reader.readAsArrayBuffer(file);

                    reader.onloadend = () => {
                        const original = reader.result;
                        Jimp.read(original)
                            .then((img) => {
                                img.scaleToFit(1920, 1080).quality(80).getBuffer(file.type, (err, buff) => {
                                    const opt = new File([buff], file.name, {
                                        type: file.type
                                    });

                                    opt.accepted = file.accepted;
                                    opt.dataURL = file.dataURL;
                                    opt.upload = file.upload;
                                    opt.previewElement = file.previewElement;
                                    opt.previewTemplate = file.previewTemplate;
                                    opt.status = file.status;
                                    opt._removeLink = file._removeLink;

                                    if (typeof  done === 'function') {
                                        done(opt)
                                        return
                                    }
                                    resolve(opt)
                                });
                            }).catch((e) => {
                            reject(e);
                        })
                    };
                });

            },
            getAcceptedFiles() {
                return this.$refs.zone.getAcceptedFiles();
            },
            getAddedFiles() {
                return this.$refs.zone.getFilesWithStatus();
            },
            upload() {
                this.$refs.zone.processQueue();
            },
            defaultTemplate: function () {
                return `
                  <div class="dz-preview dz-file-preview">
                      <div class="dz-image" style="width: 200px;height: 200px">
                          <img data-dz-thumbnail />
                      </div>
                      <div class="dz-details">
                        <div class="dz-size"><span data-dz-size></span></div>
                        <div class="dz-filename"><span data-dz-name></span></div>
                        <div class="dz-metas">
                            ${this.preview_properties.map(prop => `<input type="text" placeholder="${prop}" name="${prop}" value="">`).join('<br/>')}
                        </div>
                      </div>
                      <div style="display:${this.options.autoProcessQueue ? 'block' : 'none'}" class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                      <div class="dz-error-message"><span data-dz-errormessage></span></div>
                      <div class="dz-success-mark"><svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">      <title>Check</title>      <defs></defs>      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>      </g>    </svg>  </div>
                      <div class="dz-error-mark"><svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">      <title>Error</title>      <defs></defs>      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">          <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>        </g>      </g>    </svg>  </div>
                  </div>
              `;
            }
        },
        computed: {
            hasVideos() {
                return this.only.indexOf('videos') !== -1;
            },
            hasFiles() {
                return this.only.indexOf('files') !== -1;
            },
            maxVideosReached() {
                if (this.drop_options.hasOwnProperty('videos') && this.drop_options.videos.maxFiles)
                    return this.files_processed.videos.length >= this.drop_options.videos.maxFiles;
                return false
            }
        }
    }
</script>

<style scoped>

</style>