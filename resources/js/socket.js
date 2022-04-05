import Vue from 'vue';
import VueSocketIO from 'vue-socket.io'
import SocketIO from 'socket.io-client'
import store from './store';

const sockets = {    
    connect(data) {
        if (data) {
            console.log('connect', data)                 
        }
    },
    reconnect(data) {
        console.log('reconnect', data)
        this.$socket.emit('connect', data)
    },
    disconnect(data) {
        console.log('disconnect', data)
        this.$socket.emit('reconnect', data)
    }    
}

Vue.use(new VueSocketIO({
    debug: true,    
    connection: SocketIO(`${location.protocol}//${location.hostname}:${location.port}`, {
        autoConnect: false,                
        secure: true,
        transports: ['websocket'],        
        reconnection: true,               
        reconnectionAttempts: 5,          
        reconnectionDelay: 2000,          
        reconnectionDelayMax: 10000,
        timeout: 20000,
        extraHeaders: {
            "Authorization": ""
        }
    }),
    vuex: {
        store,
        actionPrefix: "socket",
        // mutationPrefix: "socket"
    },    
}))

export default sockets