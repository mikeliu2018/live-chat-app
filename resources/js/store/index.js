import Vue from "vue"
import Vuex from 'vuex'
import 'es6-promise/auto'
import authPresistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import user from './modules/user'
import socket from './modules/socket'

Vue.use(Vuex)

export default new Vuex.Store({    
    modules: {
        auth,
        user,
        socket
    },
    plugins: [
        authPresistedState({
            storage: window.localStorage,
            reducer(val) {
                return {
                    auth: val.auth,
                    user: val.user,
                    // socket: val.socket
                }
            }
        })
    ],
    getters: {
        chatList: state => {
            return state.socket.chatList
        }
    }    
});
