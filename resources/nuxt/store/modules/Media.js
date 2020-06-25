import {Model} from '@vuex-orm/core'

// model declaration
export class Media extends Model {
    static entity = 'Media'

    static fields() {
        return {
            id: this.attr(null),
            disk: this.string(''),
            directory: this.string(''),
            filename: this.string(''),
            extension: this.string(''),
            mime_type: this.string(''),
            attributes: this.string(''),
            aggregate_type: this.string(''),
            size: this.number(0),
            video_id:this.attr(null),
            video_url:this.attr(null),
            video_thumbnail:this.attr(null),
            active:this.boolean(true),
            created_at: this.attr(null),
            updated_at: this.attr(null),
            pivot:this.attr(null),//Capturo los datos del pivot y los inserto en la tabla
            mediable:this.hasOne(Mediable,'media_id')
        }
    }

    get path(){
        return `/api/storage/${this.directory}/${this.filename}.${this.extension}`
    }

    get parsed_attr(){
        return JSON.parse(this.attributes)
    }
}

export class Mediable extends Model {
    static entity = 'Mediable'

    static primaryKey = ['mediable_id','mediable_type','media_id']

    static fields() {
        return {
            media_id: this.attr(null),
            mediable_id: this.attr(null),
            mediable_type: this.attr(null),
            tag: this.attr(null),
            order: this.attr(null)
        }
    }

     static beforeCreate(model)
     {
        // Create the pivot model manually if it exists on the Media model
        const {tag,order} = Media.find(model.media_id).pivot;
        model.tag = tag;
        model.order = order;
    }
}

//Module Options
export const medias = {
    state: {
        synced: false
    }
}