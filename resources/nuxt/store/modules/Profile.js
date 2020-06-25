import {Model} from '@vuex-orm/core'
import {User} from "./User"

// model declaration
export class Profile extends Model {
    static entity = 'Profile'

    static fields() {
        return {
            id: this.attr(null),
            user_id: this.attr(null),
            lastname:this.attr(null),
            address:this.attr(null),
            mobile:this.attr(null),
            phone:this.attr(null),
            created_at:this.attr(null),
            updated_at:this.attr(null),
            user:this.belongsTo(User,'user_id')
            // medias: this.morphToMany(Media, Mediable, 'media_id', 'mediable_id', 'mediable_type')
            //Other attributes here
        }
    }
}

//Module Options
export const profiles = {
    state: {
        synced: false
    }
}