<script>
    export default {
        name: "base-sync",
        asyncData({$axios, store, route, error}) {

            if (route.meta[0].hasOwnProperty('store_props')) {
                const {store_props, data_var} = route.meta[0];
                const {model, query, fetch, dependencies} = store_props;

                const notFound = () => {
                    return error({
                        statusCode: 404,
                        message: 'Not Found',
                        to: route.meta[0].redirect
                    })
                };

                const parseQuery = () => {
                    if (query) {
                        const params_regep = /(\w+)\((.*)\)/;
                        let value = model;
                        query.split(/\.(?=\w+\(.*\))/).forEach(q => {
                            const parsed = params_regep.exec(q);
                            value = value[parsed[1]](eval(parsed[2]));
                        });
                        if (value && data_var) return {[data_var]: value}

                        notFound();
                    }
                };

                if (dependencies)
                // Cargo todas las dependencias de la vista si no estan synced
                    Object.entries(dependencies).forEach(([module, fetch] = dep) => {
                        const endpoint = `entities/${module}`;
                        if (!store.getters[`${endpoint}/isSynced`]) {
                            $axios.get(`api/${fetch}`)
                                .then(({data}) => {
                                    store.dispatch('entities/insert', {entity: module, data:data.result})
                                        .then(() => {
                                            store.dispatch(`${endpoint}/synchronize`, true)
                                        })
                                        .catch(e => console.log(e))
                                })
                                .catch((e) => {
                                    console.log(e);
                                })
                        }
                    });

                if (model && !model.getters('isSynced')) {
                    return $axios.get(`api/${fetch}`)
                        .then(({data}) => {
                            model.insert({
                                data:data.result
                            });
                            model.sync(true);
                            return parseQuery();
                        })
                        .catch((e) => {
                            //TODO Hacer el componente 404
                            if (e.hasOwnProperty('response') && e.response.status === 404)
                                notFound();

                            console.log(e);
                        });
                } else
                    return parseQuery();
            }
        }
    }
</script>