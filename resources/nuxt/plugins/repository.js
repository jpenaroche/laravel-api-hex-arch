const repository = (client, store) => (resource, endpoint) => ({
    getResource() {
        return resource
    },
    async synchronize(is_synced) {
        await store.dispatch(`entities/${resource}/synchronize`, is_synced)
    },
    get() {
        this.synchronize(false);
        return client.get(`/api/${endpoint}`)
            .then((response) => {
                const {data} = response;
                this.synchronize(true);
                store.dispatch('entities/create', {
                    entity: resource,
                    data: data.result
                });
                return response;
            }).catch(({response}) => {
                return response;
            })
    },
    update(id, data) {
        this.synchronize(false);
        return client.post(`/api/${endpoint}/${id}`, data)
            .then((response) => {
                const {data} = response;
                this.synchronize(true);
                store.dispatch('entities/update', {
                    entity: resource,
                    where: data.id,
                    data: data.result
                });
                return response;
            }).catch(({response}) => {
                return response;
            })
    },
    save(data) {
        this.synchronize(false);
        return client.post(`/api/${endpoint}`, data)
            .then((response) => {
                const {data} = response;
                this.synchronize(true);
                store.dispatch('entities/insert', {
                    entity: resource,
                    data: data.result
                });
                return response;
            }).catch(({response}) => {
                return response;
            })
    },
    delete(id) {
        this.synchronize(false);
        return client.delete(`/api/${endpoint}/${id}`)
            .then((response) => {
                this.synchronize(true);
                store.dispatch('entities/delete', {
                    entity: resource,
                    where: Array.isArray(id)
                        ? (record) => id.indexOf(record.id) !== -1
                        : id
                });
                return response;
            }).catch(({response}) => {
                return response;
            })
    },
    toggle(field, ids) {
        this.synchronize(false);
        return client.put(`/api/${endpoint}/toggle/${field}`, {
            data: ids
        })
            .then((response) => {
                const {data} = response;
                this.synchronize(true);
                Object.entries(data.result).forEach(value => {
                    // value = [field_value,id]
                    store.dispatch('entities/update', {
                        entity: resource,
                        where: value[0],
                        data: {[field]: value[1]}
                    });
                });
                return response;
            }).catch(({response}) => {
                return response;
            })
    },
    //TODO Cambiar a uso del store cuando me den respuesta del issue de vuex-orm
    async medias(id, tags, match = true) {
        return await client.get(`/api/${endpoint}/medias/${id}`, {
            params: {tags, match}
        }).then(({data}) => data.result)
            .catch(e => {
                console.log(e);
                return []
            });
    },
    saveOrder(ids) {
        return client.patch(`/api/${endpoint}/order`, {
            order: ids
        }).then((response) => {
            /*Object.entries(response.data.result).forEach(value => {
                store.dispatch('entities/update', {
                    entity: resource,
                    where: value[0], //id
                    pos: value[1] //pos
                });
            });*/
            return response;
        }).catch(({response}) => {
            return response;
        })
    }
});

export default ({app}, inject) => {
    const repositoryBase = repository(app.$axios, app.store);

    const repositories = {
        // TODO cambiar las rutas de los endpoints con el valor de base route admin en el mixin
        //Las llaves de los repositorios tienen q tener igual nombre q las secciones
        usuarios: repositoryBase('User', `admin/usuarios`),
        permisos: repositoryBase('Permission', `admin/permisos`),
        roles: repositoryBase('Role', `admin/roles`),
    };

    inject('repositories', repositories)
}