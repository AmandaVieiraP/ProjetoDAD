/*jshint esversion: 6 */
import Vue from "vue";

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './stores/global-store';

import VueGoodTable from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'
Vue.use(VueGoodTable); 


import item from './components/items/item.vue';
Vue.component('item', item);
import login from './components/login.vue';
Vue.component('login', login);
import logout from './components/logout.vue';
Vue.component('logout', logout);

//US2
import registerWorker from './components/users/registerWorker.vue';
Vue.component('registerWorker', registerWorker);
import confirmRegistration from './components/users/confirmRegistration.vue';
Vue.component('confirm-registration', confirmRegistration);

//US4
import changePassword from './components/users/changePassword.vue';
Vue.component('changePassword', changePassword);

//US5
import showProfile from './components/users/userDetails.vue';
Vue.component('userDetails',showProfile);

//US6
import startQuitShift from './components/users/startQuitShift.vue';
Vue.component('start-quit',startQuitShift);

//US9
import cookOrdersList from './components/users/cooks/cookOrdersList.vue';
Vue.component('cookOrdersList',cookOrdersList);

const routes = [
{ path: '/', redirect: '/items', name: 'root' },
{ path: '/items', component: item, name: 'items' },
{ path: '/login', component: login, name: 'login' },
{ path: '/logout', component: logout, name: 'logout' },
{ path: '/changePassword', component: changePassword, name: 'changePassword'},
    { path: '/profile', component: showProfile, name: 'userDetails'},

    { path: '/registerWorker', component: registerWorker, name: 'registerWorker' },
    //US9
    { path: '/me/orders', component: cookOrdersList, name: 'cookOrdersList'},
];

const router = new VueRouter({
	routes:routes
}); 


router.beforeEach((to, from, next) => {
    if ((to.name == 'logout')) {
        if (!store.state.user) {
            next("/login");
            return;
        }
    } else if ((to.name == 'registerWorker')) {
        if (store.state.user.type != "manager") {
            // se não for manager volta para a pág inicial
            // corrigir pra mostrar uma página de não autorizado ou assim
            next('/items');
            return;
        }
    }
    next();
});

const app = new Vue({
	router,
    store,
    created() {
        console.log('-----');
        this.$store.commit('loadTokenAndUserFromSession');
    }
}).$mount('#app');
