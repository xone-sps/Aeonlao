import {createActions, axiosClient} from "./actions/checkerActions";
import {
    createInit,
    defaultStates,
    defaultGetters,
    defaultMutations,
    defaultActions
} from '../initial/initializer';

/**
 * @initialize
 */
let {Vue, Vuex, $utils, debounce, initRouter} = createInit();
let addedActions = createActions($utils);
let {client, ajaxConfig, apiUrl} = axiosClient();
//@start set vue prototype
Vue.prototype.initRouter = initRouter;
Vue.prototype.apiUrl = apiUrl;
Vue.prototype.ajaxConfig = ajaxConfig;
Vue.prototype.client = client;
//@end set vue prototype

export default new Vuex.Store({
    state: {
        ...defaultStates,
        isSidebarCollapsed: '',
        isSidebarMobileOpen: '',
        userImage: 'user.png' + settings.fresh_version,
        authInfo: {img: 'admin.png' + settings.fresh_version},
        sidebarWidth: {normal: 256, collapsed: 68},
        isSidebar: '',
        selectedSidebarItem: {},
        menuContext: {menus: []},
        menuContextItemClicked: {},
        searchQuery: {text: '', filters: {}},
        dashboardData: {
            latest_checker_count: 0,
            assessments: {checking: 0, all: 0, success: 0},
        },
        searchesData: {
            check_assessments: {},
             check_assessments_field_inspector: {},
        },
        searchesAllowed: {
            check_assessments: true,
            check_assessments_field_inspector: true,
        },
        mSectionsAssessmentAnswer: [],
        checkAssessmentComments: [],
    },
    getters: {
        ...defaultGetters,
        getSideBarWidthForTabs(s) {
            return s.isSidebarCollapsed !== '' ? s.sidebarWidth.collapsed : s.sidebarWidth.normal;
        }
    },
    mutations: {
        ...defaultMutations,
        setSidebar(s, p) {
            s.isSidebar = p.isSidebar;
        },
        setSidebarCollapsed(s, p) {
            s.isSidebarCollapsed = s.isSidebarCollapsed === 'app-sidebar-collapsed' ? '' : 'app-sidebar-collapsed'
        },
        setSelectedSidebarItem(s, p) {
            s.selectedSidebarItem = p;
            if (s.isMobile)
                s.isSidebarMobileOpen = '';
            $utils.setWindowTitle(`${p.name} | ${settings.site_name}`);
        },
        setSidebarMobileOpen(s, p) {
            s.isSidebarMobileOpen = p.isOpen ? 'mobile-nav-open' : ''
        },
        setMenuContext(s, p) {
            if (p.el)
                p.el.stopPropagation();
            s.menuContext = p;
        },
        setOnMenuContextItemClick(s, p) {
            s.menuContextItemClicked = {};
            s.menuContextItemClicked = p;
        },
        setSearchesData(s, p) {
            if (!!s.searchesAllowed[p.type]) {
                s.searchesData[p.type] = p.data;
                s.searchesData[p.type].items = [];
            }
        },
        setSearchQuery(s, p) {
            s.searchQuery.text = p.text;
            s.searchQuery.filters = p.filters;
        },
        setDashboardData(s, p) {
            s.dashboardData = p;
        },
        setSectionsAssessmentAnswer(s, p) {
            s.mSectionsAssessmentAnswer = p;
        },
        setCheckAssessmentComments(s, p) {
            if (p.position === 'top') {
                s.checkAssessmentComments.unshift(p.data);
                s.checkAssessmentComments.pop();
            } else if(p.position === 'reset') {
                s.checkAssessmentComments = p.data;
            } else {
                s.checkAssessmentComments = s.checkAssessmentComments.concat(p.data);
            }
        }
    },
    actions: {
        ...defaultActions(axiosClient()),
        ...addedActions,
        setPageTitle(c, n) {
            if (n !== c.state.selectedSidebarItem.name)
                c.commit('setSelectedSidebarItem', {name: n});
        },
        showErrorToast(c, data) {
            Vue.toasted.error(data.msg, {
                duration: data.dt,
                action: {
                    text: 'Close',
                    onClick: (e, t) => {
                        t.goAway(0);
                    }
                }
            });
        },
        showInfoToast(c, data) {
            Vue.toasted.show(data.msg, {
                duration: data.dt,
                action: {
                    text: 'Close',
                    onClick: (e, t) => {
                        t.goAway(0);
                    }
                }
            });
        },
    } //end actions
});
