<template>
    <v-dialog :value="show" persistent max-width="300px">
        <v-card>
            <v-card-title>{{$t('form.titles.video_attributes')}}</v-card-title>
            <v-card-text>
                <v-text-field
                        v-for="(prop,index) of attributes"
                        :key="index"
                        outline
                        v-model="value[prop]"
                        :label="$t('form.labels.'+prop)"
                        clearable
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="teal" @click.stop="save">
                    <v-icon>save</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "attr-dialog",
        props:{
            show:Boolean,
            attributes:Array,
            target:Object
        },
        data(){
            return {
                value:{}
            }
        },
        updated(){
            if(this.target.hasOwnProperty('attributes'))
                this.value = this.target.attributes;
        },
        methods:{
            save(){
                const tmp = Object.assign({},this.target);
                tmp.attributes = this.value;
                this.$emit('save',tmp);
            }
        }
    }
</script>

<style scoped>

</style>