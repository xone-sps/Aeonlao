import Vue from 'vue';
import App from './vue/AppGeneral.vue';
import store from '@store/generalStore';
import VueRouter from 'vue-router';
import routes from '@route/routesGeneral';
import VueCarousel from 'vue-carousel';
import PostsSearchForm from '@com/General/Partial/PostsSearchForm.vue'

/**
 * @Component load
 */
import GeneralInput from '@cus-com/GeneralInput.vue';
Vue.component('PostsSearchForm', PostsSearchForm);
Vue.component('GeneralInput', GeneralInput);
/**
 * @Component load
 */

Vue.use(VueCarousel);
Vue.use(VueRouter);
export const router = new VueRouter({
    mode: 'history',
    routes
});
/**
 * @Route guard
 */
Vue.prototype.initRouter(router, store).StartRouteGuard();
/**
 *
 * @Route guard
 */
Vue.prototype.$context_name = "app_general";
Vue.prototype.Route = Vue.prototype.initRouter(router, store).Route;
Vue.prototype.$utils.onWindowNewTap((info) => {
    store.commit('setWindowState', {WindowState: info});
});

export const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
