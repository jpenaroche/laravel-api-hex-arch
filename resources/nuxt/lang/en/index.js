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
      sending:'sending...'
    },
    keywords: {
        edit: 'edit',
        create:'create',
        actions:'actions',
        dashboard:'dashboard',
        video:'video',
        background_photo:'background photo',
        search:'search',
        map:{
            locate:'locate',
            place:'place'
        },
        datatable:{
            drop_drawer:'temporary space'
        }
    },
    form: {
        labels:{
            title:'title',
            video_code: 'video code',
            video_url: 'video url'
        },
        titles: {
            details: 'details',
            video_attributes:'video attributes',
            upload_video:'upload video'
        },
        fields: {
            active: 'active'
        },
        dropzone:{
            title:'upload files',
            subtitle:'drag here the files to upload',
            remove:'remove'
        }
    },
    //section keys
    usuarios: Usuarios,
    roles: Roles,
    permisos: Permisos
}