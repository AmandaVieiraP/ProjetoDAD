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

const routes = [
	{ path: '/', redirect: '/items', name: 'root' },
	{ path: '/items', component: item, name: 'items' },
	{ path: '/login', component: login, name: 'login' },
    { path: '/logout', component: logout, name: 'logout' }
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
