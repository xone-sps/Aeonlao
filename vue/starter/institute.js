import Vue from 'vue';
import App from './vue/AppInstitute.vue';
import store from '@store/instituteStore';
import VueRouter from 'vue-router';
import routes from '@route/routesInstitute';



import '@com/Admin/Assets/index.css'
import {
    Button,
    CheckboxGroup,
    Checkbox,
    Radio,
    Select,
    Dropdown,
    DropdownItem,
    DropdownMenu,
    Dialog,
    Option,
    Switch,
    TabPane,
    Message,
    MessageBox,
    Tooltip
} from 'element-ui';

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
// configure language
locale.use(lang);

import iconfont from '@com/Admin/Assets/iconfont/iconfont.js'

Vue.use(iconfont);
import TextAreaPlugin from '@vue/plugin/textarea-plugin.js'
Vue.use(TextAreaPlugin);
Vue.use(CheckboxGroup)
Vue.use(Checkbox)
Vue.use(Radio)
Vue.use(Button)
Vue.use(Select)
Vue.use(Dialog)
Vue.use(Option)
Vue.use(Switch)
Vue.use(TabPane)
Vue.use(TabPane)
Vue.use(Dropdown)
Vue.use(DropdownItem)
Vue.use(DropdownMenu)
Vue.use(Tooltip)

Vue.prototype.$message = Message
Vue.prototype.$confirm = MessageBox.confirm


Vue.use(VueRouter);
export const router = new VueRouter({
    mode: 'history',
    routes
});

/**
 * @Component load
 */
import SpinnerLoading from '@cus-com/Admin/SpinnerLoading.vue';
import MasterDetailCard from '@cus-com/Admin/MasterDetailCard/MasterDetailCard.vue';
import MasterDetailCardMenu from '@cus-com/Admin/MasterDetailCard/MasterDetailCardMenu.vue';
import MasterDetailCardItem from '@cus-com/Admin/MasterDetailCard/MasterDetailCardItem.vue';
import MasterSingleDetailCard from '@cus-com/Admin/MasterDetailCard/MasterSingleDetailCard.vue';

/**
 * @lazyLoad components
 */
const Tabs = () => import('@cus-com/Admin/Tabs.vue').then(a => a.default);
const TablePaginate = () => import('@cus-com/Admin/TablePaginate.vue').then(a => a.default);
const AdminModal = () => import('@cus-com/Admin/AdminModal.vue').then(a => a.default);
const AdminInput = () => import('@cus-com/Admin/AdminInput.vue').then(a => a.default);
const VTransclude = () => import('@cus-com/TranscludeTag.vue').then(a => a.default);
/**
 * @lazyLoad components
 */
Vue.component('SpinnerLoading', SpinnerLoading);
Vue.component('MasterDetailCard', MasterDetailCard);
Vue.component('MasterDetailCardMenu', MasterDetailCardMenu);
Vue.component('MasterDetailCardItem', MasterDetailCardItem);
Vue.component('MasterSingleDetailCard', MasterSingleDetailCard);
Vue.component('Tabs', Tabs);
Vue.component('TablePaginate', TablePaginate);
Vue.component('AdminModal', AdminModal);
Vue.component('AdminInput', AdminInput);
Vue.component('VTransclude', VTransclude);
/**
 * @Component load
 */

/**
 * @Route guard
 */
Vue.prototype.initRouter(router, store).StartRouteGuard();
/**
 *
 * @Route guard
 */

Vue.prototype.$context_name = "app_institute";
Vue.prototype.Route = Vue.prototype.initRouter(router, store).Route;
Vue.prototype.theme = {bgColor: 'linear-gradient(to right, rgb(92, 107, 192), rgb(121, 134, 203))'};

export const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
