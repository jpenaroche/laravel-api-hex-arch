<template>
    <div>
        <!--<alert v-if="this.data.canMark" :type="'is-success'" :message="'Presione doble click para almacenar la posición recogida en el mapa'"></alert>-->
        <div v-if="search">
            <h2>{{$tc('keywords.map.place',1)}}</h2>
            <label>
                <gmap-autocomplete
                        @place_changed="setPlace">
                </gmap-autocomplete>
                <button @click.prevent="locatePlace">{{$t('keywords.map.locate')}}</button>
            </label>
            <br/>
        </div>
        <br>
        <gmap-map ref="map"
                  @dblclick="mark($event)"
                  :center="center"
                  :zoom="near"
                  :options="{
                      disableDoubleClickZoom: false,
                      scaleControl: false,
                      zoomControl: false,
                      gestureHandling:'cooperative',
                      mapTypeId:'roadmap',
                  }"
                  style="width:100%;  height: 400px;">
            <gmap-marker
                    :key="index"
                    v-for="(flag, index) in flags"
                    :position="flag.position"
                    @click="center=flag.position">
            </gmap-marker>
        </gmap-map>
    </div>
</template>

<script>
    import Vue from 'vue'
    import * as VueGoogleMaps from 'vue2-google-maps'

    Vue.use(VueGoogleMaps, {
        load: {
            key: 'AIzaSyA3g_id7WsXmQhbud6QozCNFlKQNH-vw_0',
            libraries: 'places',
        }
    });

    export default {
        name: "gmap",
        props: {
            position:{
                type:Object,
                default:function(){
                    return {
                        lat: -34.9011127,
                        lng: -56.16453139999999
                    }
                }
            },
            zoom:{
                type:Number,
                default:6
            },
            markers:{
                type:Array,
                default:function(){
                    return []
                }
            },
            can_mark:{
                type:Boolean,
                default:false
            },
            search:{
                type:Boolean,
                default:false
            }
        },
        data() {
            return {
                // default to Montreal to keep it simple
                // change this to whatever makes sense
                center: {lat: 0, lng: 0},
                flags: [],
                places: [],
                near: 12,
                currentPlace: null,
            };
        },
        mounted() {
            this.geolocate();
            this.center = this.position;
            this.near = this.zoom;
            this.flags = this.markers;
        },
        methods: {
            // receives a place object via the autocomplete component
            setPlace(place) {
                this.currentPlace = place;
            },
            mark(e) {
                if (this.can_mark) {
                    const pos = {lat: e.latLng.lat(), lng: e.latLng.lng()};
                    this.center = pos;
                    this.flags = [];
                    this.addMarker(pos);
                    this.$emit('geolocated', pos);
                }
            },
            locatePlace() {
                if (this.currentPlace) {
                    this.places.push(this.currentPlace);
                    this.addMarker(
                        {
                            lat: this.currentPlace.geometry.location.lat(),
                            lng: this.currentPlace.geometry.location.lng()
                        }
                    );
                    this.currentPlace = null;
                }
            },
            addMarker(position) {
                let {lat, lng} = position;
                const marker = {
                    lat: lat,
                    lng: lng
                };
                this.flags.push({position: marker});
                this.center = marker;
            },
            geolocate: function () {
                navigator.geolocation.getCurrentPosition(position => {
                    this.center = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                });
            }
        },
        watch: {
            'position'(newVal) {
                this.flags = [];
                this.flags.push({position: newVal});
                this.center = newVal;
            },
            'zoom'(newVal) {
                this.near = newVal;
            }
        }
    };
</script>
