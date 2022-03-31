export default {
    namespaced: true,
    state: {
        id: "",
        name: "",
        email: "",
        vendor: "",
        vendor_image_url: "",
        updated_at: ""
    },
    mutations: {
        userProfile(state, payload) {
            state.id = payload.id
            state.name = payload.name
            state.email = payload.email
            state.vendor = payload.vendor
            state.vendor_image_url = payload.vendor_image_url
            state.updated_at = payload.updated_at
        }
    },
    actions: {
        userProfile(context, payload) {
            context.commit('userProfile', {
                id: payload.id,
                name: payload.name,
                email: payload.email,
                vendor: payload.vendor,
                vendor_image_url: payload.vendor_image_url,
                updated_at: payload.updated_at
            })
        }
    }
}