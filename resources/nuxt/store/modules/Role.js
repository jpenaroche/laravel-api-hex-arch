import {Model} from '@vuex-orm/core'
import {Permission, PermissionRole} from "./Permission"

// model declaration
export class Role extends Model {
    static entity = 'Role'

    static fields() {
        return {
            id: this.attr(null),
            name: this.string(''),
            description: this.string('').nullable(),
            guard_name: this.string(''),
            active: this.boolean(true),
            pos:this.number(0),
            created_at: this.attr(''),
            updated_at: this.attr('').nullable(),
            permissions: this.belongsToMany(
                Permission,
                PermissionRole,
                'role_id',
                'permission_id',
            )
        }
    }
}


export class Roleable extends Model {
    static entity = 'Roleable'

    static primaryKey = ['model_id','model_type','role_id']

    static fields() {
        return {
            role_id: this.attr(null),
            model_id: this.attr(null),
            model_type: this.attr(null),
        }
    }
}

//Module Options
export const roles = {
    state: {
        synced: false,
        filters:{
            admin:{}
        }
    }
}