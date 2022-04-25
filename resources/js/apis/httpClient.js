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
    console.log(error)
    return Promise.reject(error)
}))

instance.interceptors.response.use(response => {
    return response
}, (error) => {
    const { message, response, request, config } = error
    // console.log('message', message)
    // console.log('response', response)
    // console.log('request', request)
    // console.log('config', config)

    if (response) {
        // errorHandler(response.status, response.data.error)
        switch(response.status) {
            case 400:
                console.info("status %d, errorMsg: %s.", response.status, response.data.error)
                break
            case 401:            
                console.info("status %d, errorMsg: %s.", response.status, response.data.error)    
                if (config.url === '/auth/refresh') {
                    store.dispatch('auth/userAuth', {
                        token: "",
                        isAuth: false
                    })
                    
                    setTimeout(() => {
                        router.replace({
                            name: 'sign-in',
                            // query: {
                            //     redirect: router.currentRoute.fullPath
                            // }
                        })                    
                    })
                } else {
                    return instance.put('/auth/refresh', {})
                    .then(response => {
                        console.log(response)
                        let res = response.data
                        if (res.status)
                        {
                            if (res.access_token) {
                                store.dispatch('auth/userAuth', {
                                    token: res.access_token,                                
                                    isAuth: true
                                });
                                console.log('instance.request(config)', config)
                                return instance.request(config);
                            }
                        }
                    })
                }
                break
            case 403:
                router.replace({
                    name: '403'
                })
                break
            case 404:
                console.info("status %d, errorMsg: %s.", response.status, response.data.error)
                break
            case 500:
                if (config.url === '/auth/refresh') {
                    store.dispatch('auth/userAuth', {
                        token: "",
                        isAuth: false
                    })
                    
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
            default:
                console.info("status %d, errorMsg: %s.", response.status, response.data.error)
                break
        }

        return Promise.reject(error)
    } else {
        if (!window.navigator.onLine){
            console.log('window.navigator.onLine:' + window.navigator.onLine)
        } else {
            return Promise.reject(error)
        }
    }
})

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