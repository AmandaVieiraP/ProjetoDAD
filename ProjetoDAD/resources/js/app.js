/*jshint esversion: 6 */
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import store from './stores/global-store';

import VueGoodTable from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css';
Vue.use(VueGoodTable); 

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
    position: 'bottom-center',
    duration: 7000,
    type: 'info',
});

import VueSocketio from 'vue-socket.io';
Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://127.0.0.1:8080'
}));

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
Vue.component('userDetails', showProfile);

//US6
import startQuitShift from './components/users/startQuitShift.vue';
Vue.component('start-quit', startQuitShift);

//US7
import notifications from './components/users/notifications.vue';
Vue.component('notifications', notifications);

//US9
import cookOrders from './components/users/cooks/cookOrders.vue';
Vue.component('cookOrders', cookOrders);

//US12
import createNewMeal from './components/users/waiters/startNewMeal.vue';
Vue.component('startNewMeal', createNewMeal);

//US13
import createNewOrder from './components/users/waiters/createNewOrder.vue';
Vue.component('createNewOrder', createNewOrder);

//US14
import waiterOrders from './components/users/waiters/waiterOrders.vue';
Vue.component('waiterOrders', waiterOrders);

//US19
import mealsSummary from './components/users/waiters/mealsSummary.vue';
Vue.component('mealsSummary', mealsSummary);

//US22
import invoices from './components/users/invoice.vue';
Vue.component('invoices', invoices);

//US28
import managersTablesAndItems from './components/users/managers/tablesItems.vue';
Vue.component('managersTablesAndItems',managersTablesAndItems);

//US29
import allUsers from './components/users/managers/allUsers.vue';
Vue.component('allUsers',allUsers);



const routes = [
    { path: '/', redirect: '/items', name: 'root' },
    { path: '/items', component: item, name: 'items' },
    { path: '/login', component: login, name: 'login' },
    { path: '/logout', component: logout, name: 'logout' },
    { path: '/changePassword', component: changePassword, name: 'changePassword'},
    { path: '/profile', component: showProfile, name: 'userDetails'},
    { path: '/registerWorker', component: registerWorker, name: 'registerWorker' },
    { path: '/me/orders/', component: cookOrders, name: 'cookOrdersList'},
    { path: '/newMeal', component: createNewMeal, name: 'createNewMeal'},
    {path: '/newOrder', component: createNewOrder, name: 'createNewOrder'},
    {path: '/orders', component: waiterOrders, name: 'waiterOrders', props: true},
    {path: '/meals', component: mealsSummary, name: 'mealsSummary'},
    {path: '/invoices', component: invoices, name: 'invoices'},
    {path: '/tablesItems', component: managersTablesAndItems, name: 'managersTablesAndItems'},
    {path: '/workers', component: allUsers, name: 'allUsers'},
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
    }
    next();
});

const app = new Vue({
    router,
    store,
    created() {
        this.$store.commit('loadTokenAndUserFromSession');
    }, 
    sockets: {
        connect() {
            console.log('socket connect (socket ID = '+this.$socket.id+')');
            this.$socket.emit('user_enter', this.$store.state.user);
        }, 
    }
}).$mount('#app');
