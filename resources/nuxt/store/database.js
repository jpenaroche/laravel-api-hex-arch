import {Database} from '@vuex-orm/core'
import {User, users} from './modules/User'
import {Profile, profiles} from "./modules/Profile"
import {Role, Roleable, roles} from './modules/Role'
import {Permission, PermissionRole, Permissionable, permissions} from './modules/Permission'
import {Media, Mediable, medias} from './modules/Media'

const database = new Database();

database.register(User, users);
database.register(Profile, profiles);
database.register(Role, roles);
database.register(Roleable);
database.register(Permission, permissions);
database.register(Permissionable);
database.register(PermissionRole);
database.register(Media, medias);
database.register(Mediable);

export default database