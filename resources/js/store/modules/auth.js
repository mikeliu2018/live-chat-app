export default {
    namespaced: true,
    state: {
        token: "",
        isAuth: false
    },
    mutations: {
        userAuth(state, payload) {
            state.token = payload.token
            state.isAuth = payload.isAuth
        }
    },
    actions: {
        userAuth(context, payload) {
            context.commit('userAuth', {
                token: payload.token,
                isAuth: payload.isAuth
            })
        }
    }
}