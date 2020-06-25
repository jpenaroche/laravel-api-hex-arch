import Vue from 'vue'
import Vuex from 'vuex'
import VuexORM from '@vuex-orm/core'
import VuexORMSync from '@/plugins/vuex-orm-sync'
import VuexORMDatatable from '@/plugins/vuex-orm-datatable'
import VuexORMFilters from '@/plugins/vuex-orm-filters'
import database from './database'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)
VuexORM.use(VuexORMSync)
VuexORM.use(VuexORMDatatable)
VuexORM.use(VuexORMFilters)

export default () => {
    return new Vuex.Store({
        state: {
            locale: 'es'
        },
        mutations:{
            setLocale(state,locale){
                state.locale = locale;
            }
        },
        plugins: [
            VuexORM.install(database),
            // createPersistedState({storage: window.localStorage})
        ]
    })
}