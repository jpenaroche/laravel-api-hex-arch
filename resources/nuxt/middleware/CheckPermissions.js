export default ({app, route, error}) => {
    if(app.$auth.user.roles.findIndex(rol=>rol.name==='super-admin')===-1){
        let has =  app.$auth.user.permissions.some(p => p.indexOf(route.meta[0].permissions)!==1);
        if (!has)
            return error({
                statusCode: 401,
                message: 'Forbidden',
                to:route.meta[0].redirect || '/'
            })
    }
}