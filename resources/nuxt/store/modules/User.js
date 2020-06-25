import {Model} from '@vuex-orm/core'
import {Profile} from "./Profile"
import {Role, Roleable} from "./Role"
import {Permission, Permissionable} from "./Permission";
import {Media, Mediable} from "./Media";

// model declaration
export class User extends Model {
    //El nombre de entidad debe coincidir con el nombre del atributo "type"
    // en las relaciones polimorficas que se setean en el AppServiceProvider de Laravel
    static entity = 'User'

    static fields() {
        return {
            id: this.attr(null),
            name: this.string(''),
            email: this.string(''),
            email_verified_at: this.attr(null),
            active: this.boolean(true),
            pos: this.number(0),
            is_admin: this.boolean(false),
            locale: this.string('es'),
            created_at: this.attr(''),
            updated_at: this.attr(''),
            remember_token: this.string(''),
            profile: this.hasOne(Profile, 'user_id'),
            //TODO Cambiar a uso del store cuando me den respuesta del issue de vuex-orm
            media: this.morphToMany(Media, Mediable,'media_id','mediable_id', 'mediable_type'),
            roles: this.morphToMany(Role, Roleable, 'role_id', 'model_id', 'model_type'),
            permissions: this.morphToMany(Permission, Permissionable, 'permission_id', 'model_id', 'model_type'),
        }
    }

    get fullname(){
        return `${this.name} ${this.profile.lastname}`
    }
}

//Module Options
export const users = {
    state: {
        synced: false,
        filters:{
            admin:{}
        }
    }
}