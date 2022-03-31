export default {
    namespaced: true,
    state: {
        user: {},        
        chatList: []
    },
    mutations: {        
        // socketUserEnter(state, payload) {
        //     state.user = payload.user
        // },
        socketChatFromServer(state, payload) {
            // console.log('mutations.socketChatFromServer', payload)
            state.chatList.push(payload)
        }        
    },
    actions: {        
        // socketUserEnter(context, payload) {
        //     context.commit('socketUserEnter', payload)            
        // },
        socketChatFromServer(context, payload) {
            // console.log('actions.socketChatFromServer', payload)
            context.commit('socketChatFromServer', payload)                       
        }
    }        
}