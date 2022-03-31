import httpClient from "./httpClient";

const auth = {
    vendorSignin(params){
        return httpClient('POST', '/auth/vendor-signin', params)
    },
    refresh(){
        return httpClient('PUT', '/auth/refresh')
    },
    logout(){
        return httpClient('PUT', '/auth/logout')
    }
}

export default auth