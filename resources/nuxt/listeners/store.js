/*
Listeners implementados tomando como dependencia vuex-orm
como manejador de vuex
*/

const fetch = (model)=>{
    return new Promise((resolve, reject) => {
        import ( `@/store/modules/${model}` )
            .then(r => resolve(r[model]))
            .catch(e => reject(e))
    })
};

//Canales
export const modelCreated = (app) => {
    app.$echo.channel('modelCreated')
        .listen('.model.created', (payload) => {
            fetch(payload.model).then((model) => {
                model.insert(payload);
            });
        });
};

export const modelUpdated = (app) => {
    app.$echo.channel('modelUpdated')
        .listen('.model.updated', ({model,id,data}) => {
            fetch(model).then((model) => {
                model.update({
                    where:id,
                    data:data
                });
            });
        });
};

export const modelDeleted = (app) => {
    app.$echo.channel('modelDeleted')
        .listen('.model.deleted', ({model,id}) => {
            fetch(model).then((model) => {
                model.delete(id);
            });
        });
};

export const modelToggled = (app) => {
    app.$echo.channel('modelToggled')
        .listen('.model.toggled', ({model,field,data}) => {
            fetch(model).then((model) => {
                Object.entries(data).forEach(value=>{
                    // value = [field_value,id]
                    model.update({
                        where:value[0],
                        data:{[field]:value[1]}
                    })
                })
            });
        });
};