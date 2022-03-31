<!--
<template>
    <v-container fill-height fluid style="height: 600px;">
        <v-row>
            <v-col justify="center" align="center">
                <v-card           
                    class="pa-10"         
                    max-width="50%"
                >
                    <v-card-title>登入 Live Chat App</v-card-title>
                    <v-card-text>
                        <div id="google-signin-button"></div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>    
</template>
-->

<template>
    <div class="container mx-auto p-4">        
        <div class="mx-auto h-screen">            
            <h1 class="text-3xl font-bold underline">
                登入 Live Chat App
            </h1>
            <div id="google-signin-button"></div>
        </div>
    </div>    
</template>

<script>    
    export default {
        name: 'signin',
        mounted() {
            console.info("Component %s mounted.", this.$options.name)

            if (this.$store.state.auth.isAuth === true) {                
                console.info("user already sign-in. token: %s, ", this.$store.state.auth.token)
                this.$router.replace({
                    name: 'user-profile'
                })                
            } else {
                gapi.signin2.render('google-signin-button', {
                    scope: 'profile email',
                    width: 240,
                    height: 50,
                    longtitle: true,
                    theme: 'dark',
                    onsuccess: this.onSignIn,
                    onfailure: this.onSignInError
                })
            }

        },
        methods: {
            onSignIn (user) {
                const profile = user.getBasicProfile()
                console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
                console.log('Name: ' + profile.getName());
                console.log('Image URL: ' + profile.getImageUrl());
                console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
                console.log(user.getAuthResponse().id_token);

                this.$root.api.auth.vendorSignin({
                    token: user.getAuthResponse().id_token,
                    vendor: 'google'
                }).then(response => {
                    console.log(response)
                    let res = response.data

                    if (res.status)
                    {
                        if (res.access_token) {

                            this.$store.dispatch('auth/userAuth', {
                                token: res.access_token,                                
                                isAuth: true
                            });
                            
                            if (res.userProfile) {
                                console.log(res.userProfile)
                                this.$store.dispatch('user/userProfile', {
                                    id: res.userProfile.id,
                                    name: res.userProfile.name,
                                    email: res.userProfile.email,
                                    vendor: res.userProfile.vendor,
                                    vendor_image_url: res.userProfile.vendor_image_url,
                                    updated_at: res.userProfile.updated_at
                                })
                            }                            

                            this.$router.push('/chat/room');                            
                        }                        
                    }
                })


            },
            onSignInError (error) {
                console.error(error)
            }            
        }
    }
</script>
