import httpClient from "./httpClient";

const user = {
    profile(){
        return httpClient('GET', '/user/profile')
    }
}

export default user