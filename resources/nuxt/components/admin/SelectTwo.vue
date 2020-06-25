<template>
    <v-layout id="select_two" row wrap justify-center>
        <v-flex xs5 sm5>
            <v-layout>
                <v-flex>
                    <v-btn @click="toggle('selected')" class="mr-0 ml-0" flat icon color="teal">
                        <v-icon>{{picked.selected.length===this.items.selected.length && this.items.selected.length?'check_box':
                            !picked.selected.length?'check_box_outline_blank':'indeterminate_check_box'}}
                        </v-icon>
                    </v-btn>
                </v-flex>
                <v-flex xs11 sm11>
                    <v-text-field
                            box
                            v-model="needles.selected"
                            clearable
                            prepend-inner-icon="search"
                    />
                </v-flex>
            </v-layout>

            <v-divider/>
            <v-list v-for="item of items.filtered.selected" :key="item.id">
                <v-list-tile>
                    <v-list-tile-action>
                        <v-checkbox v-model="picked.selected" :value="item" :multiple="true"></v-checkbox>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        {{item.name}}
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-flex>
        <v-flex xs1 sm1 class="actions">
            <v-btn fab flat :disabled="!items.all.length" @click="add">
                <v-icon>
                    chevron_left
                </v-icon>
            </v-btn>
            <v-btn fab flat :disabled="!items.selected.length" @click="remove">
                <v-icon>
                    chevron_right
                </v-icon>
            </v-btn>
        </v-flex>
        <v-flex xs5 sm5>
            <v-layout>
                <v-flex>
                    <v-btn @click="toggle('all')" class="mr-0 ml-0" flat icon color="teal">
                        <v-icon>{{picked.all.length===this.items.all.length && this.items.all.length?'check_box':
                            !picked.all.length?'check_box_outline_blank':'indeterminate_check_box'}}
                        </v-icon>
                    </v-btn>
                </v-flex>
                <v-flex xs11 sm11>
                    <v-text-field
                            box
                            v-model="needles.all"
                            clearable
                            prepend-inner-icon="search"
                    />
                </v-flex>
            </v-layout>
            <v-divider/>
            <v-list v-for="item of items.filtered.all" :key="item.id">
                <v-list-tile>
                    <v-list-tile-action>
                        <v-checkbox v-model="picked.all" :value="item" :multiple="true"></v-checkbox>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        {{item.name}}
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "select-two",
        inject:['$validator'],
        props: {
            rules: String,
            tags: {
                type: Array,
                default: function(){
                    return []
                }
            },
            item_list: {
                type: Array,
                default: []
            },
            url: String
        },
        data() {
            return {
                items: {
                    all: [],
                    selected: [],
                    filtered: {
                        all: [],
                        selected: []
                    }
                },
                picked: {
                    all: [],
                    selected: []
                },
                needles: {
                    all: '',
                    selected: ''
                }
            }
        },
        mounted(){
          this.init();
        },
        methods: {
            init(){
                this.items.all = this.items.filtered.all = this.item_list;
                this.picked.all = this.tags;
                this.add();
            },
            add() {
                const union = this.items.selected.concat(this.picked.all);
                this.items.all = this.items.filtered.all = this.items.all.filter(item => this.picked.all.findIndex(i => i.id === item.id) === -1);
                this.items.selected = this.items.filtered.selected = union;
                this.picked.all = [];
                this.$emit('synced',this.items.selected);
            },
            remove() {
                const union = this.items.all.concat(this.picked.selected);
                this.items.selected = this.items.filtered.selected = this.items.selected.filter(item => this.picked.selected.findIndex(i => i.id === item.id) === -1);
                this.items.all = this.items.filtered.all = union;
                this.picked.selected = [];
                this.$emit('synced',this.items.selected);
            },
            toggle(who) {
                if(!this.picked[who].length || this.picked[who].length !== this.items.filtered[who].length){
                  this.picked[who] = this.items.filtered[who];
                  return
                }

                this.picked[who] = [];
            }
        },
        watch: {
            'needles.all'(newVal) {
                newVal = newVal === null ? '' : newVal;
                this.items.filtered.all = this.items.all.filter(item => item.name.includes(newVal));
            },
            'needles.selected'(newVal) {
                newVal = newVal === null ? '' : newVal;
                this.items.filtered.selected = this.items.selected.filter(item => item.name.includes(newVal));
            },
            item_list() {
                this.init();
            }
        }
    }
</script>

<style scoped>
    .actions {
        justify-content: center;
        margin: auto;
    }
    #select_two{
        min-height: 400px;
    }
</style>
