import Vue from 'vue'

export default {
    install(component) {
        /*["Model", "Query", "Attribute", "Type", "Attr", "String", "Number", "Boolean", "Increment", "Relation",
        "HasOne", "BelongsTo", "HasMany", "HasManyBy", "BelongsToMany", "HasManyThrough", "MorphTo", "MorphOne",
        "MorphMany", "MorphToMany", "MorphedByMany", "Getters", "Actions", "RootGetters", "RootActions", "RootMutations"]*/
        const {
            Model,
            RootGetters,
            RootMutations,
            RootActions,
            Actions,
            Query,
            Getters
        } = component;

        /*Asigno los atributos, $to_sort para hacer la consulta por vuex-orm de los elementos dentro del panel temporal
        y el atributo $pos para guardar el order de en la tabla*/


        Actions.setFilter = function (context, params) {
            const state = context.state;
            const entity = state.$name;

            return context.dispatch(
                `${state.$connection}/setFilter`, {
                    entity, params
                }, {
                    root: true
                }
            );
        };

        Actions.setFilters = function (context, params) {
            const state = context.state;
            const entity = state.$name;

            return context.dispatch(
                `${state.$connection}/setFilters`, {
                    entity, params
                }, {
                    root: true
                }
            );
        };


        RootActions.setFilters = function (context, payload) {
            context.commit('saveFilters', payload)
        };

        RootActions.setFilter = function (context, payload) {
            context.commit('saveFilter', payload)
        };

        RootActions.delFilter = function (context, payload) {
            context.commit('removeFilter', payload)
        };

        RootMutations.saveFilters = function (state, {entity, section, filters}) {
            try {
                Vue.set(state[entity].filters, section, filters);
            }
            catch (e) {
                throw new Error('You have to set filters section on your Store Model')
            }
        };

        RootMutations.saveFilter = function (state, {entity, field, value, section}) {
            try {
                Vue.set(state[entity].filters[section], field, value);
            }
            catch (e) {
                throw new Error('You have to set a filter section key on your Store Model')
            }
        };

        RootMutations.removeFilter = function (state, {entity, section, field}) {
            try {
                delete state[entity].filters[section][field];
            }
            catch (e) {
                throw new Error('You have to set a filter section key on your Store Model')
            }
        };

        RootGetters.getFilter = function (state) {
            return function ({entity, section, field}) {
                return state[entity].filters[section][field];
            };
        };

        RootGetters.getFilters = function (state) {
            return function (entity, {section}) {
                return state[entity].filters[section];
            };
        };

        Getters.getFilter = function (state, _getters, _rootState, rootGetters) {
            return function (params) {
                return rootGetters[`${state.$connection}/getFilter`](
                    params
                );
            };
        };

        Getters.getFilters = function (state, _getters, _rootState, rootGetters) {
            return function (params) {
                return rootGetters[`${state.$connection}/getFilters`](
                    params
                );
            };
        };
    }
}