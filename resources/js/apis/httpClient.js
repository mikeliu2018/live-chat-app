import axios from "axios"
// import { config } from "vue/types/umd"
import router from "../router"
import store from "../store"


let instance = axios.create({
    baseURL: '/api'
})

instance.interceptors.request.use(config => {
    const token = store.state.auth.token
    token && (config.headers.Authorization = 'Bearer ' + token)
    return config
}, (error => {
    return Promise.reject(error)
}))

instance.interceptors.response.use(response => {
    return response
}, (error) => {
    const { response } = error

    if (response) {
        errorHandler(response.status, response.data.error)
        return Promise.reject(error)
    } else {
        if (!window.navigator.onLine){
            console.log('window.navigator.onLine:' + window.navigator.onLine)
        } else {
            return Promise.reject(error)
        }
    }
})

const errorHandler = (status, msg) => {
    switch(status) {
        case 400:
            console.info("status %d, errorMsg: %s.", status, msg)
            break
        case 401:            
            if (router.currentRoute == 'sign-in')
            {
                console.log('route to sign-in')
            }
            else
            {
                store.dispatch('auth/userAuth', {
                    token: "",
                    isAuth: false
                })
                
                console.info("status %d, errorMsg: %s.", status, msg)
                
                setTimeout(() => {
                    router.replace({
                        name: 'sign-in',
                        // query: {
                        //     redirect: router.currentRoute.fullPath
                        // }
                    })                    
                })
            }
            break
        case 403:
            router.replace({
                name: '403'
            })
            break
        case 404:
            console.info("status %d, errorMsg: %s.", status, msg)
            break
        default:
            console.info("status %d, errorMsg: %s.", status, msg)
            break
    }
}

export default function (method, url, data = null) {
    method = method.toUpperCase()

    switch (method)
    {
        case 'POST':
            return instance.post(url, data)            
        case 'GET':
            return instance.get(url, {params: data})
        case 'DELETE':
            return instance.delete(url, {params: data})
        case 'PUT':
            return instance.put(url, data)
        default:
            break
    }
}