export default {
    install(component) {
        // ["Model", "Query", "Attribute", "Type", "Attr", "String", "Number", "Boolean", "Increment", "Relation",
        // "HasOne", "BelongsTo", "HasMany", "HasManyBy", "BelongsToMany", "HasManyThrough", "MorphTo", "MorphOne",
        // "MorphMany", "MorphToMany", "MorphedByMany", "Getters", "Actions", "RootGetters", "RootActions", "RootMutations"]
        const {
            Model,
            RootGetters,
            RootMutations,
            RootActions,
            Actions,
            Getters
        } = component;

        Model.sync = function (is_synced) {
            this.dispatch('synchronize', is_synced)
        };

        Actions.synchronize = function (context, is_synced) {
            const state = context.state;
            const module = state.$name;

            return context.dispatch(
                `${state.$connection}/synchronize`, {
                    module, is_synced
                }, {
                    root: true
                }
            );
        };

        Getters.isSynced = function (state, _getters, _rootState, rootGetters) {
            return rootGetters[`${state.$connection}/isSynced`](
                state.$name
            );
        };

        RootActions.synchronize = function (context, payload) {
            context.commit('setSync', payload)
        };

        RootMutations.setSync = function (state, {module, is_synced}) {
            state[module].synced = is_synced
        };

        RootGetters.isSynced = function (state) {
            return function (module) {
                return state[module].synced;
            };
        }
    }
}