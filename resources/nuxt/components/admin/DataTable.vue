<template>
    <div>
        <nuxt-link v-if="has_new" :to="`${routes.base.admin}/${section}/create`">
            <v-btn flat large color="teal">
                {{$t('buttons.new')}}
                <v-icon>add</v-icon>
            </v-btn>
        </nuxt-link>
        <v-toolbar flat color="white" pb-20>
            <v-toolbar-title class="text-capitalize">{{title}}</v-toolbar-title>
            <v-divider
                    class="mx-2"
                    inset
                    vertical
            ></v-divider>
            <div v-if="is_draggable">
                <v-tooltip right>
                    <template #activator="data">
                        <v-btn :disabled="isOrderSaved || toSort.length > 0" v-on="data.on"
                               @click.stop="openModal(saveOrder,'order')" flat
                               color="blue">
                            <v-icon>save</v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.save_order')}}</span>
                </v-tooltip>
                <v-tooltip right>
                    <template #activator="data">
                        <v-btn :disabled="isOrderSaved" v-on="data.on" @click.stop="openModal(resetOrder,'order_reset')"
                               flat
                               color="blue">
                            <v-icon>replay</v-icon>
                        </v-btn>
                    </template>
                    <span>{{$t('buttons.tooltips.reset_order')}}</span>
                </v-tooltip>
                <v-tooltip right>
                    <template #activator="data">
                        <v-btn v-on="data.on" @click.stop="opened.panel = !opened.panel" flat
                               color="blue">
                            <v-icon>storage</v-icon>
                        </v-btn>
                    </template>
                    <span v-if="!opened.panel">{{$t('buttons.tooltips.row_drawer.open')}}</span>
                    <span v-else>{{$t('buttons.tooltips.row_drawer.close')}}</span>
                </v-tooltip>
            </div>
            <v-spacer></v-spacer>
            <v-text-field v-if="searchable"
                          :value="search"
                          v-debounce:250ms="e => search = e"
                          append-icon="search"
                          :label="$t('keywords.search')"
                          single-line
                          hide-details
            ></v-text-field>
        </v-toolbar>
        <slot name="filters"></slot>
        <v-data-table ref="table" style="position: relative"
                      :search="search"
                      :disable-initial-sort="true"
                      @update:pagination="putOrderInStore"
                      v-model="selected.list"
                      :headers="headers"
                      :loading="loading"
                      :items="toTable"
                      item-key="id"
                      select-all
                      :pagination.sync="pagination"
                      class="elevation-1"
                      :rows-per-page-items="[5,10,25,50,{'text':'all',value:-1}]"
        >
            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
            <template slot="items" slot-scope="props">
                <tr class="data-row" :active="props.selected" @click="props.selected = !props.selected"
                    :data-id="props.item[id_key]">
                    <td class="showable"
                        onmouseenter="event.currentTarget.querySelector('.handle').classList.add('show')"
                        onmouseleave="event.currentTarget.querySelector('.handle').classList.remove('show')"
                    >
                        <div v-if="is_draggable" class="handle">
                            <v-icon>more_vert</v-icon>
                        </div>
                        <v-checkbox
                                :input-value="props.selected"
                                primary
                                hide-details
                        ></v-checkbox>
                    </td>
                    <td v-for="(header,index) of headers.filter(h=>h.value)" :key="index">
                        <v-icon v-if="header.icon">
                            {{header.icon[props.item[header.value]]}}
                        </v-icon>
                        <p v-else>{{ props.item[header.value]}}</p>
                    </td>

                    <td class="justify-center layout px-0">
                        <!--Prepended actions-->
                        <v-icon v-can.disabled="action.permissions || []"
                                v-for="(action,index) of (actions.table.plus || []).filter(x => x.prepend)"
                                :key="index"
                                class="mr-2"
                                small
                                @click.stop="$emit(action.name,props.item)"
                        >
                            {{action.icon}}
                        </v-icon>

                        <nuxt-link v-can.disabled="['show-'+section]" v-if="has('show')" class="v-icon"
                                   :to="`${routes.base.admin}/${section}/${props.item[id_key]}`">
                            <v-icon
                                    small
                                    class="mr-2"
                            >
                                remove_red_eye
                            </v-icon>

                        </nuxt-link>

                        <nuxt-link v-can.disabled="['edit-'+section]" v-if="has('edit')" class="v-icon"
                                   :to="`${routes.base.admin}/${section}/edit/${props.item[id_key]}`">
                            <v-icon
                                    small
                                    class="mr-2"
                            >
                                edit
                            </v-icon>

                        </nuxt-link>

                        <v-icon v-can.disabled="['delete-'+section]" v-if="has('delete')"
                                small
                                @click.stop="selected.row=props.item; openModal(rowFunctions().destroy)"
                        >
                            delete
                        </v-icon>

                        <!--After actions-->
                        <v-icon v-can.disabled="action.permissions"
                                v-for="(action,index) of (actions.table.plus || []).filter(x => !x.prepend)"
                                :key="index"
                                class="ml-2"
                                small
                                @click.stop="$emit(action.name,props.item)"
                        >
                            {{action.icon}}
                        </v-icon>
                    </td>
                </tr>
            </template>


            <!--<template slot="no-data">-->
            <!--<v-btn color="primary" @click="initialize">Reset</v-btn>-->
            <!--</template>-->
            <template slot="actions-prepend">

                <v-fab-transition>
                    <v-speed-dial
                            v-show="selected.list.length > 0"
                            v-model="opened.actions"
                            :open-on-hover="true"
                            class="actions white--text"
                            transition="slide-y-reverse-transition"
                    >
                        <v-btn
                                :loading="'actions'|whoIsSending"
                                slot="activator"
                                v-model="opened.actions"
                                color="teal lighten-2"
                                fab
                        >
                            <v-icon>settings</v-icon>
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-btn v-if="has('active','footer')"
                               :disabled="isSending"
                               fab
                               small
                               color="green"
                               @click.stop="selectedFunctions().active()"
                        >
                            <v-tooltip right>
                                <template #activator="data">
                                    <v-icon v-on="data.on">check</v-icon>
                                </template>
                                <span>{{$t('buttons.activate')}}/{{$t('buttons.deactivate')}}</span>
                            </v-tooltip>
                        </v-btn>
                        <v-btn v-for="(action,index) of actions.footer.plus" :key="index"
                               :disabled="isSending"
                               fab
                               small
                               :color="action.color"
                               @click.stop="$emit(action.name,selected.list)"
                        >
                            <v-tooltip v-if="action.tooltip" right>
                                <template #activator="data">
                                    <v-icon v-on="data.on">{{action.icon}}</v-icon>
                                </template>
                                <span>{{action.tooltip}}</span>
                            </v-tooltip>

                            <v-icon v-else>{{action.icon}}</v-icon>

                        </v-btn>

                        <v-btn v-if="has('delete','footer')"
                               fab
                               small
                               color="red"
                               :disabled="isSending"
                               @click.stop="openModal(selectedFunctions().destroy)"
                        >
                            <v-tooltip right>
                                <template #activator="data">
                                    <v-icon v-on="data.on">delete</v-icon>
                                </template>
                                <span>{{$t('buttons.delete')}}</span>
                            </v-tooltip>
                        </v-btn>
                    </v-speed-dial>
                </v-fab-transition>
            </template>

        </v-data-table>
        <div v-if="pages > 1" class="text-xs-center pt-2">
            <v-pagination color="teal" :circle="true" v-model="pagination.page" :length="pages"></v-pagination>
        </div>

        <modal-action :actions="modal.actions" :is_opened="modal.opened"
                      :title="$t(modal.title)"
                      :text="$tc(modal.text,selected.list.length)"
                      :is_sending="'modal'|whoIsSending"/>

        <!--Espacio temporal de elementos a ordenar-->
        <v-navigation-drawer @dragover.native="openDrawer"
                             class="bucket"
                             v-model="opened.panel"
                             absolute
                             :clipped="true"
                             :hide-overlay="true"
                             :right="true"
        >
            <div class="trigger"></div>
            <v-list class="pa-1">
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <v-icon>storage</v-icon>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title class="text-capitalize">{{$t('keywords.datatable.drop_drawer')}}
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>

            <v-list class="pt-0 fill-height" dense>
                <v-divider></v-divider>
                <div ref="bucket" class="fill-height">
                    <div :data-id="item[id_key]" v-for="item of toSort" :key="item[id_key]">
                        <v-list-tile class="tile">
                            <v-list-tile-content>
                                <v-list-tile-title class="text-center">{{item|name(drawer_keys)}}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </div>
                </div>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
    import ModalAction from '@/components/admin/ModalAction.vue'
    import Vue from 'vue'
    import VueDebounce from 'vue-debounce'
    import Sortable from '@/plugins/sortable'

    Vue.use(VueDebounce)

    export default {
        name: "data-table",
        components: {
            ModalAction
        },
        props: {
            title: String,
            searchable: {
                type: Boolean,
                default: true
            },
            is_draggable: {
                type: Boolean,
                default: true
            },
            has_new: {
                type: Boolean,
                default: true
            },
            filters_section: {
                type: String,
                default: 'admin'
            },
            sort_key: {
                type: String,
                default: 'pos'
            },
            section: String,
            items: Array,
            headers: Array,
            id_key: {
                type: String,
                default: 'id'
            },
            actions: {
                type: Object,
                default: function () {
                    {
                        return {
                            table: {
                                plus: []
                            },
                            footer: {
                                plus: []
                            }
                        }

                    }
                }
            },
        },
        data() {
            return {
                modal: {
                    opened: false,
                    title: '',
                    text: '',
                    actions: {
                        cancel: () => {
                            this.modal.opened = false;
                        },
                        confirm: {}
                    }
                },
                loading: false,
                // search: '',
                selected: {
                    list: [],
                    single: {}
                },
                opened: {
                    actions: false,
                    panel: false
                },
                saved_order: '',
                pagination: {},
                drawer_keys: [],
                resource: this.$repositories[this.section].getResource()
            }
        },
        beforeCreate() {
            const {section, headers} = this.$options.propsData;

            headers.map(h =>
                h.text = this.$t(`${section}.form.fields.${h.value}`));

            headers.push({
                    text: this.$t('keywords.actions'),
                    align: 'center',
                    sortable: false
                }
            );
        },
        created() {
            this.drawer_keys = this.headers.filter(h => h.in_drawer).map(h => h.value);

            //Guardo el orden inicial en el store
            if (!this.orderInStore)
                this.putInFilters({
                    field: 'order',
                    value: {
                        initial: this.idList
                    }
                });
        },
        mounted() {
            let over, prev, pos = 0;
            if (this.is_draggable) {
                const table = new Sortable(this.$refs.table.$el, {
                    child: 'tbody',
                    handle: '.handle',
                    group: 'Datatable',
                    onDragStart: (evt) => {
                        prev = evt.el.dataset.id;
                    },
                    onDragOver(evt) {
                        if (evt.el.dataset.id)
                            over = evt.el.dataset.id;
                    },
                    onDrop: (evt) => {
                        pos = evt.el.dataset.id;

                        if (evt.from === 'DIV') {
                            // const toMount = this.toSort.find(item => item[this.id_key] == over);
                            const index = this.items.findIndex(item => item[this.id_key] == over);
                            this.updatePosition(pos, this.items[index].pos);
                            let sum = this.items[index].pos;
                            this.items.splice(index, this.toTable.length).forEach(item => {
                                this.updatePosition(item[this.id_key], ++sum);
                            });
                            this.$store.dispatch('entities/toSort', {
                                entity: this.resource,
                                where: pos,
                                value: false
                            });
                            return
                        }

                        if (prev !== pos) {
                            const $prev_position = this.items.find(item => item[this.id_key] == prev).pos;
                            const $pos_position = this.items.find(item => item[this.id_key] == pos).pos;
                            this.updatePosition(prev, $pos_position);
                            this.updatePosition(pos, $prev_position);
                        }
                    }
                });

                const bucket = new Sortable(this.$refs.bucket, {
                    group: 'Datatable',
                    onDrop: (evt) => {
                        if (evt.from === 'TBODY')
                            this.$store.dispatch('entities/toSort', {
                                entity: this.resource,
                                where: evt.el.dataset.id
                            })
                    }
                })
            }
        },
        methods: {
            openDrawer() {
                if (!this.opened.panel)
                    this.opened.panel = true;
            },
            updatePosition(id, pos) {
                this.$store.dispatch('entities/update', {
                    entity: this.resource,
                    where: id,
                    data: {pos: pos}
                });
            },
            sort(sortBy, descending) {
                if (descending) {
                    this.items.sort((a, b) => {
                        return (b[sortBy] > a[sortBy]) - (b[sortBy] < a[sortBy])
                    });
                    return
                }

                this.items.sort((a, b) => {
                    return (a[sortBy] > b[sortBy]) - (a[sortBy] < b[sortBy])
                });
            },
            putOrderInStore({descending, sortBy}) {
                if (descending !== null && sortBy !== null) {
                    this.sort(sortBy, descending);
                    let pos = 0;
                    this.items.forEach((item) => {
                        this.$store.dispatch('entities/update', {
                            entity: this.resource,
                            where: item[this.id_key],
                            data: {pos: ++pos}
                        })
                    });
                }
            },
            resetOrder() {
                let pos = 0;
                this.$store.dispatch('entities/toSort', {
                    entity: this.resource,
                    where: 'all',
                    value: false
                });

                this.orderInStore.initial.forEach(id => {
                    this.updatePosition(id, ++pos);
                });

                this.modal.opened = false;
            },
            saveOrder() {
                this.mixin.sending_now = 'modal';
                this.$repositories[this.section].saveOrder(this.idList)
                    .then((response) => {
                        this.$emit('tableUpdated');
                        this.parseResponse(response);
                        this.putInFilters({
                            field: 'order',
                            value: {
                                initial: this.idList
                            }
                        });
                    })
                    .catch(({response}) => {
                        this.parseResponse(response);
                    })
                    .finally(() => {
                        this.mixin.sending_now = '';
                        this.modal.opened = false;
                    });
            },
            has(method, section = 'table') {
                if (this.actions[section].hasOwnProperty('default'))
                    return this.actions[section].default.indexOf(method) !== -1;

                return true;
            },
            openModal(action, keyword = 'delete') {
                this.modal.opened = true;
                this.modal.actions.confirm = action;
                this.modal.title = `${this.section}.dialogs.${keyword}.title`;
                this.modal.text = `${this.section}.dialogs.${keyword}.text`;
            },
            putInFilters({field, value}) {
                this.$store.dispatch('entities/setFilter', {
                    entity: this.resource,
                    section: this.filters_section,
                    field: field,
                    value: value
                })
            },
            rowFunctions() {
                const _this = this;
                return {
                    destroy() {
                        _this.mixin.sending_now = 'modal';
                        _this.$repositories[_this.section].delete(_this.selected.row.id)
                            .then((response) => {
                                _this.$emit('tableUpdated');
                                _this.parseResponse(response);
                            })
                            .catch(({response}) => {
                                _this.parseResponse(response);
                            })
                            .finally(() => {
                                _this.selected.list = [];
                                _this.mixin.sending_now = '';
                                _this.modal.opened = false;
                            });
                    }
                }
            },
            selectedFunctions() {
                const _this = this;
                const ids = this.selected.list.map(item => item[this.id_key]);
                return {
                    destroy() {
                        _this.mixin.sending_now = 'modal';
                        _this.$repositories[_this.section].delete(ids)
                            .then((response) => {
                                _this.$emit('tableUpdated');
                                _this.parseResponse(response);
                            })
                            .catch(({response}) => {
                                _this.parseResponse(response);
                            })
                            .finally(() => {
                                _this.selected.list = [];
                                _this.mixin.sending_now = '';
                                _this.modal.opened = false;
                            });
                    },

                    active() {
                        _this.mixin.sending_now = 'actions';
                        _this.$repositories[_this.section].toggle('active', ids)
                            .then((response) => {
                                _this.$emit('tableUpdated');
                                _this.parseResponse(response);
                            })
                            .catch(({response}) => {
                                _this.parseResponse(response);
                            })
                            .finally(() => {
                                _this.selected.list = [];
                                _this.mixin.sending_now = '';
                                _this.modal.opened = false;
                            });
                    }
                }
            }
        },
        computed: {
            pages() {
                if (this.pagination.rowsPerPage == null ||
                    this.pagination.totalItems == null
                ) return 0

                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
            },
            search: {
                set(v) {
                    this.putInFilters({
                        field: 'search',
                        value: v
                    })
                },
                get() {
                    return this.$store.getters['entities/getFilter']({
                        entity: this.resource,
                        section: this.filters_section,
                        field: 'search'
                    })
                }
            },
            orderInStore() {
                return this.$store.getters['entities/getFilter']({
                    entity: this.resource,
                    section: this.filters_section,
                    field: 'order'
                });
            },
            isOrderSaved() {
                if (this.orderInStore)
                    return this.orderInStore.initial.toString() === this.idList.toString();

                return true;
            },
            idList() {
                return this.items.map(item => item[this.id_key])
            },
            toSort() {
                return this.items.filter(item => item.$to_sort)
            },
            toTable() {
                return this.items.filter(item => !item.$to_sort)
            }
        },
        watch: {
            '$store.state.locale'() {
                const i = this.headers.findIndex(h => !h.value);
                if (i !== -1)
                    this.headers[i].text = this.$t('keywords.actions');

                this.headers.filter(h => h.value).map(h =>
                    h.text = this.$t(`${this.section}.form.fields.${h.value}`));
            }
        },
        filters: {
            name(obj, keys) {
                return keys.map(k => obj[k]).join(' ')
            }
        }
    }
</script>

<style scoped>
    .actions {
        position: absolute;
        left: 0;
        margin-left: 10px;
    }

    tr {
        cursor: pointer;
    }

    tr.active {
        background: lightgray;
    }

    .handle {
        position: absolute;
        left: 0;
        cursor: grab;
        max-width: 50px;
        display: none;
        opacity: 0;
        transition: opacity .25s ease-out;
    }

    .handle.show {
        display: block;
        opacity: 1;
    }

    .tile > td:not(.showable), .tile .v-input {
        display: none;
    }

    tbody .tile {
        width: inherit;
    }

    .tile {
        background: lightcoral;
        box-shadow: none;
        color: white;
        padding: 10px;
        margin-bottom:1px;
        cursor: move;
    }

    .tile:hover{
        background: #f0a4a2;
        transition: background-color .25s linear;
    }

    .tile td {
        padding: 6px;
    }

    .bucket {
        width: 308px !important;
    }

    div.trigger {
        width: 20px;
        left: -10px;
        background: lightcoral;
        position: absolute;
        height: inherit;
    }
</style>