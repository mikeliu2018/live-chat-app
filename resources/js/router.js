import Vue from 'vue';
import VueRouter from 'vue-router';
import SigninComponent from './components/User/SigninComponent.vue'
import UserProfileComponent from './components/User/UserProfileComponent.vue'
import RoomComponent from './components/Chat/RoomComponent.vue'

Vue.use(VueRouter)

const NotFound = { template: '<p>Page not found</p>' }
const Home = { template: '<p>home page</p>' }

const routes = [
    { path: '/', component: RoomComponent, name: 'home' },
    { path: '/sign-in', component: SigninComponent, name: 'sign-in' },
    { path: '/user/profile', component: UserProfileComponent, name: 'user-profile' },    
    { path: '/chat/room', component: RoomComponent, name: 'chat-room'}

]

// const router = new VueRouter({
//     routes // short for `routes: routes`
// })

export default new VueRouter({
    routes // short for `routes: routes`
})