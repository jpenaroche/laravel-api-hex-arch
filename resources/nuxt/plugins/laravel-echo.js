import Echo from "laravel-echo"
import * as store_listeners from '@/listeners/store'

export default ({app}, inject) => {
    const logged_strategy = app.$auth.$state.strategy;
    const token = app.$auth.getToken(logged_strategy);

    //laravel-echo-server config
    window.io = require('socket.io-client');
    
    inject('echo',new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001',
        key: 'f1682330e4fdf199',
        auth: {
            headers: {
                Authorization: token
            },
        }
    }));

    //Inicializo todos mis listeners a canales de laravel-echo
    Object.values(store_listeners).forEach(channel => channel(app))
}