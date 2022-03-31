<template>
<!--     
    <v-container fill-height fluid style="height: 600px;">
        <v-row>
            <v-col justify="center" align="center">
                <v-card           
                    class="pa-10"         
                    max-width="50%"
                >
                    <v-card-title>Hello {{ userProfile.name }}</v-card-title>
                    <v-card-text>
                        <v-btn depressed v-on:click="signOut">
                            Sign out
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
-->
    
    <div class="container mx-auto p-4">        
        <div class="h-screen">
            <div class="mx-auto h-4/5">
                <h1 class="text-3xl font-bold underline">
                    Hello {{ userProfile.name }}
                </h1>                         
                <div class="mx-auto">
                    <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded" v-on:click="signOut">Sign out</button>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
    export default {
        name: 'UserProfile',
        data: () => {
            return {
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
                })                
            }            
        },
        methods: {            
            signOut() {                
                // console.info(gapi.auth2)                

                this.$root.api.auth.logout().then(response => {
                    console.info(response) 
                    
                    this.$store.dispatch('auth/userAuth', {
                        token: "",
                        isAuth: false
                    });

                    const auth2 = gapi.auth2.getAuthInstance();
                    
                    // auth2.signOut().then(function () {
                    //     console.log('User signed out.');                        
                    // });

                    auth2.signOut().then((response) => {
                        console.log('User signed out.');                        
                        this.$router.push('/sign-in');
                    });

                    if (this.$socket.connected === true) {
                        this.$socket.close()
                    }
                    
                })                
            }
        }
    }
</script>