
require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import routes from './route';    // 路由配置文件
import store from './store/'; // vuex 数据存储所需对象

// 实例化路由
const router = new VueRouter({
	mode:'history',
    routes
})

var vm = new Vue({
    store,
    router
}).$mount('#app');