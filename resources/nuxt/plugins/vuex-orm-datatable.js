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

        const _saveGetFiedsMethod = Model.prototype.$fields;
        Model.prototype.$fields = function () {
            const existing = _saveGetFiedsMethod.call(this);
            return Object.assign({}, existing, {
                $to_sort: Model.attr(false),
                $pos: Model.attr(false)
            });
        };

        /*Metodos de Vuex-ORM para la interaccion de la variable $pos de cada modelo*/
        Model.setPos = function (pos) {
            this.dispatch('setPosition', pos)
        };

        Actions.setPosition = function (context, pos) {
            const state = context.state;
            const module = state.$name;

            return context.dispatch(
                `${state.$connection}/setPosition`, {
                    module, pos
                }, {
                    root: true
                }
            );
        };

        Getters.getPosition = function (state, _getters, _rootState, rootGetters) {
            return rootGetters[`${state.$connection}/getPosition`](
                state.$name
            );
        };

        RootActions.setPosition = function (context, payload) {
            context.commit('savePosition', payload)
        };

        RootMutations.savePosition = function (state, {module, pos}) {
            state[module].$pos = pos
        };

        RootGetters.getPosition = function (state) {
            return function (module) {
                return state[module].$pos;
            };
        }

        /*Metodos de Vuex-ORM para la interaccion de la variable $to_sort de cada modelo*/
        Actions.toSort = function (context, value) {
            const state = context.state;
            const entity = state.$name;

            return context.dispatch(
                `${state.$connection}/toSort`, {
                    entity, value
                }, {
                    root: true
                }
            );
        };

        RootActions.toSort = function (context, payload) {
            context.commit('setToSort', payload)
        };

        const _update = RootMutations.update;
        RootMutations.setToSort = function (state, { entity, where, value = true}) {
            if (where === 'all') {
                where = (record) => {
                    return record.hasOwnProperty('$to_sort')
                };
            }else if(typeof where !=='function' && isNaN(where)){
              throw new Error('Where param has to be a number, callback function or reserved key "all"')
            }

            _update.call(this, state, {
                entity,
                where,
                data: {$to_sort: value},
                result: {}
            });
        };

        RootGetters.toSort = function (state) {
            return function (entity) {
                if (entity) {
                    return new Query(state, entity)
                        .where(elt => elt.$to_sort)
                        .get();
                } else {
                    let result = [];
                    const allEntities = Model.database().entities;
                    allEntities.forEach(e => {
                        let elts = new Query(state, e.name)
                            .where(elt => elt.$to_sort)
                            .get();
                        result = result.concat(elts);
                    });
                    return result;
                }
            };
        };

        Getters.toSort = function (state, _getters, _rootState, rootGetters) {
            return function () {
                return rootGetters[`${state.$connection}/toSort`](
                    state.$name
                );
            };
        };
    }
}