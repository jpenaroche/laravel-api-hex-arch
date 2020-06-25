export default function ({app,redirect}) {
    if(app.$auth.loggedIn && app.$auth.user.is_admin===1)
        return redirect('/admin')
}