import validations from './validations'
import notifications from './notifications'
import alerts from './alerts'
import buttons from './buttons'
import Usuarios from './sections/usuarios'
import Roles from './sections/roles'
import Permisos from './sections/permisos'

export default {
    validations: validations,

    //general keys
    notifications: notifications,
    buttons: buttons,
    alerts:alerts,
    dialogs:{
        sending:'enviando...'
    },
    keywords: {
        edit: 'editar',
        create: 'crear',
        actions: 'acciones',
        dashboard: 'dashboard',
        video:'vídeo',
        background_photo: 'imagen de fondo',
        search:'buscar',
        map: {
            locate: 'localizar',
            place: 'lugar'
        },
        datatable:{
            drop_drawer:'espacio temporal'
        }
    },
    form: {
        labels: {
            title: 'título',
            video_code: 'código de video',
            video_url: 'url de video'
        },
        titles: {
            details: 'detalles',
            video_attributes: 'atributos del video',
            upload_video:'subir vídeo'
        },
        fields: {
            active: 'activo'
        },
        dropzone:{
            title:'subir ficheros',
            subtitle:'arrastre hasta aquí los ficheros a subir',
            remove:'eliminar'
        }
    },
    //section keys
    usuarios: Usuarios,
    roles: Roles,
    permisos: Permisos
}