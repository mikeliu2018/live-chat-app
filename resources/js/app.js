/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';
// import vuetify from './vuetify';
import store from './store'
import router from './router'
import api from './apis'
import sockets from './socket'

Vue.prototype.api = api

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('signin-component', require('./components/User/SigninComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    // vuetify,
    router,
    store,
    sockets,
    mounted() {
        
    },
    watch: {
        '$store.state.auth': function (newValue, oldValue){
            console.log('newValue: %o, oldValue: %o', newValue, oldValue)            
        }
    }  
    // data: {
    //     currentRoute: window.location.pathname
    // },
    // computed: {
    //     ViewComponent () {
    //         return routes[this.currentRoute] || NotFound
    //     }
    // },
    // render (h) { return h(this.ViewComponent) }
});