import Vue from 'vue';
import VueSocketIO from 'vue-socket.io'
import SocketIO from 'socket.io-client'
import store from './store';

const sockets = {    
    connect(data) {
        if (data) {
            console.log('connect', data)                 
            // this.sockets.subscribe('room', (data) => {                    
            //     // console.log(data)
            //     // this.msg = data.message;                    
            // });                
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
    connection: SocketIO(`//${location.hostname}`, {
        autoConnect: false,                //启动自从自动连接
        secure: true,
        transports: ['websocket'],        // ['websocket', 'polling']
        reconnection: true,               //启动重新连接
        reconnectionAttempts: 5,          //最大重试连接次数
        reconnectionDelay: 2000,          //最初尝试新的重新连接等待时间
        reconnectionDelayMax: 10000,      //最大等待重新连接,之前的2倍增长
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