<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">    
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="385630244132-q997nc8gc04ml3fmhgka4gm7d7btsdlf.apps.googleusercontent.com">
    {{-- <script src="https://apis.google.com/js/platform.js" async defer></script> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://apis.google.com/js/platform.js"></script>    
    <title>Live-chat-app</title>        
</head>
<body>    
    <div id="app">
        {{-- <v-app app>
            <v-container>
                <v-btn color="primary">
                    Primary
                </v-btn>
                <v-btn color="secondary">
                    Secondary
                </v-btn>
                <v-btn color="error">
                    Error
                </v-btn>
            </v-container>
        </v-app> --}}        
        <!-- route outlet -->
        <!-- component matched by the route will render here -->
        <router-view></router-view>
        
        {{-- <signin-component></signin-component> --}}
    </div>    
    {{-- <script src="https://apis.google.com/js/api:client.js"></script> --}}
    <script src="{{ mix('/js/app.js') }}"></script>    
</body>
</html>