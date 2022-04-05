<template>
    <div class="container mx-auto p-4 h-screen">
        <h1 class="text-3xl font-bold">
            歡迎光臨隨便聊
        </h1>            

        <div class="grid grid-cols-12 h-4/5">
            <div class="col-span-10 overflow-scroll">
                <ul>
                    <li v-for="(item, index) in messageList" v-bind:key="index">
                        <div class="p-1">
                            <span class="font-bold text-red-400">{{ item.from }}</span>
                            <span class="font-base">{{ item.text }}</span>
                            <span class="font-light ml-2.5">{{ item.created_at }}</span>
                        </div>
                    </li>
                </ul>                            
            </div>
            <div class="col-span-2 overflow-scroll">
                <h3 class="font-bold">
                    在線用戶
                </h3>        
                <ul>
                    <li v-for="(item, index) in userList" v-bind:key="index">
                        <div class="p-1">
                            <span class="font-bold text-red-400">{{ item.name }}</span>                        
                        </div>
                    </li>
                </ul>
            </div>            
        </div>    

        <div class="mx-auto my-2">
            <input type="text" class="mx-2 border border-2 border-gray-400" v-model="text" placeholder="說點什麼">
            <button class="mx-2 py-2 px-4 text-white font-bold bg-blue-500 hover:bg-blue-700 rounded" v-on:click="sendMessage">發送信息</button>
        </div>                     
        
    </div>    
</template>


<script>
    export default {
        sockets: {                    
            message (data) {
                console.log('Room receive message', data)
            },
            // socketUserEnter(data) {
            //     console.log('socketUserEnter', data)  
            //     this.$store.dispatch('socket/socketUserEnter', data);
            // }
            UserList (data) {
                console.log('UserList receive message', data)
                this.userList = data
            }
        },     
        name: 'Room',
        data() {
            return {
                // socket,  
                text: '', 
                messageList: this.$store.state.socket.chatList,
                userList: [],
                userProfile: {}                           
            }            
        },
        mounted() {
            console.info('Component %s mounted.', this.$options.name)            

            gapi.load('auth2', function() {
                gapi.auth2.init();
            });
            
            if (this.$store.state.auth.isAuth === false) {
                this.$router.replace({
                    name: 'sign-in'                    
                })              
            } else {
                this.$root.api.user.profile()
                .then(response => {
                    console.log(response)
                    let res = response.data
                    this.userProfile = res.userProfile                                        
                    console.info(this.userProfile) 
                    
                    // if user login and socket ready do connecting
                    if (this.$socket && this.$socket.connected === false && this.$store.state.auth.isAuth === true) {
                        console.log('token', this.$store.state.auth.token)

                        // Because websocket not support the Authorization header. We set query string provide server transfor
                        this.$socket.io.uri = `${location.protocol}//${location.hostname}:${location.port}?token=${this.$store.state.auth.token}`
                        // websocket connecting 
                        this.$socket.open()
                        console.log('websocket connecting')                    
                        
                        this.logChatList()                    
                    }

                })
                .catch(error => {
                    console.log(error)                    
                })

                // this.messages = Array.from({ length: this.length }, (k, v) => v + 1)

                // this.userProfile.id = this.$store.state.user.id
                // this.userProfile.email = this.$store.state.user.email
                // this.userProfile.vendor = this.$store.state.user.vendor
                // this.userProfile.vendor_image_url = this.$store.state.user.vendor_image_url
                // this.userProfile.updated_at = this.$store.state.user.updated_at            
                
                
            }            
        },        

        methods: {  
            logChatList() {                     
                console.log(this.$store.state.socket.chatList)                
            },            

            sendMessage() {
                console.log('sendMessage click') 
                // console.log(this.$socket.connected) 
                if (this.$socket && this.$store.state.auth.isAuth === true && this.text !== '') {
                    // this.$socket.emit('ChatFromClient', this.text) 
                    this.$socket.emit('ChatFromClient', {
                        text: this.text
                    }) 
                    this.text = ''
                    console.log(this.messageList)
                }
            },       
            signOut() {                
                // console.info(gapi.auth2)                

                this.$root.api.auth.logout().then(response => {
                    console.info(response) 
                    
                    this.$store.dispatch('auth/userAuth', {
                        token: "",
                        isAuth: false
                    });

                    const auth2 = gapi.auth2.getAuthInstance();                                    

                    auth2.signOut().then((response) => {
                        console.log('User signed out.');                        
                        this.$router.push('/sign-in');
                    });
                    
                })                
            }
        }
    }
</script>
