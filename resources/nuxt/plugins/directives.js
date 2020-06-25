import Vue from 'vue'

export default ({app}) => {
    Vue.directive('can', {
        // When the bound element is inserted into the DOM...
        inserted: function (el,{modifiers,value}) {
            if(app.$auth.user.roles.findIndex(rol=>rol.name==='super-admin')===-1){
                let has = app.$auth.user.permissions.map(p => p.name).some((p) => {
                    value.indexOf(p) !== -1
                });
                if (!has) {
                    if (Object.keys(modifiers).length) {
                        if (modifiers.hasOwnProperty('disable') && modifiers.disable) {
                            el.disabled = true;
                            el.style.pointerEvents = 'none';
                            el.classList.add('disabled v-icon--disabled');
                            return
                        }
                    }
                    el.parentNode.removeChild(el);
                }
            }
        }
    });

    //Directiva para eliminar|deshabilitar elemento del DOM cuando es sobre acciones
    // que el usuario autenticado no puede alterar de su propio modelo
    Vue.directive('itself', {
        // When the bound element is inserted into the DOM...
        inserted: function (el,{modifiers}) {
            if(app.$auth.user.id == app.context.params.id){
                if (Object.keys(modifiers).length) {
                    if (modifiers.hasOwnProperty('disable') && modifiers.disable) {
                        el.disabled = true;
                        el.style.pointerEvents = 'none';
                        el.classList.add('disabled v-icon--disabled');
                        return
                    }
                }
                el.parentNode.removeChild(el);
            }
        }
    })
}