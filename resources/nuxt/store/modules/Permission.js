import {Model} from '@vuex-orm/core'
import {Role} from "./Role"

// model declaration
export class Permission extends Model {
    static entity = 'Permission'

    static fields () {
        return {
            id: this.attr(null),
            name:this.string(''),
            guard_name:this.string(''),
            active:this.boolean(true),
            pos:this.number(0),
            created_at:this.attr(''),
            updated_at:this.attr('').nullable(),
            roles: this.belongsToMany(Role, PermissionRole, 'role_id', 'permission_id')
        }
    }
}

export class Permissionable extends Model {
    static entity = 'Permissionable'

    static fields () {
        return {
            permission_id: this.attr(null),
            model_id: this.attr(null),
            model_type: this.attr(null)
        }
    }
}

export class PermissionRole extends Model {
    static entity = 'PermissionRole'

    static primaryKey = ['permission_id', 'role_id']

    static fields () {
        return {
            permission_id: this.attr(null),
            role_id: this.attr(null)
        }
    }
}


//Module Options
export const permissions = {
    state:{
        synced:false,
        filters:{
            admin:{}
        }
    }
}