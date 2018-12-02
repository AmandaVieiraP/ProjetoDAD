/*jshint esversion: 6 */
import Vue from "vue";

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './stores/global-store';

import VueGoodTable from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css';
Vue.use(VueGoodTable); 

import item from './components/items/item.vue';
Vue.component('item', item);

import login from './components/login.vue';
Vue.component('login', login);

import logout from './components/logout.vue';
Vue.component('logout', logout);

//US4
import changePassword from './components/users/changePassword.vue';
Vue.component('changePassword', changePassword);

//US5
import showProfile from './components/users/userDetails.vue';
Vue.component('userDetails',showProfile);

//US6
import startQuitShift from './components/users/startQuitShift.vue';
Vue.component('start-quit',startQuitShift);

const routes = [
{ path: '/', redirect: '/items', name: 'root' },
{ path: '/items', component: item, name: 'items' },
{ path: '/login', component: login, name: 'login' },
{ path: '/logout', component: logout, name: 'logout' },
{ path: '/changePassword', component: changePassword, name: 'changePassword'},
    { path: '/profile', component: showProfile, name: 'userDetails'},
];
//o name pode ser qualquer coisa?
const router = new VueRouter({
    routes:routes
}); 

router.beforeEach((to, from, next) => {
    if ((to.name == 'logout')) {
        if (!store.state.user) {
            next("/login");
            return;
        }
    }
    next();
});

const app = new Vue({
    router, 
    data: {
        title: "Menu",
    },
    store,
    created() {
        console.log('-----');
        console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        console.log(this.$store.state.user);
    }
}).$mount('#app');
