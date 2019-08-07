import Vue from 'vue'
import {mapActions, mapGetters, mapMutations, mapState} from 'vuex'

import Multiselect from 'vue-multiselect'
import Editor from '@cus-com/Admin/TinyEditor.vue'

const {Datetime} = VueDatetime;
const {Settings} = luxon;
Settings.defaultLocale = 'en';

export default Vue.extend({
    components: {
        Multiselect,
        Datetime,
        Editor
    },
    data() {
        return {
            ...mapGetters(['validated', 'succeeded', 'getSideBarWidthForTabs']),
            title: '',
            watchers: false,
            type: '',
            tabs: [],
            headers: [],
            models: {formTop: {}},
            modal: {data: {}, action: {}},
            options_request: {},
            query: '',
            filters: {},
            isSearch: false,
            formTopState: {show: false, loading: false, t: 0},
            paginate: {items: [], per_page: 10, current_page: 1, last_page: 0, total: 0},
            editingItems: {oldCount: 0, items: []},
            isNavigator: false,
            //for profile settings
            options: {},
            isLoading: false,
            userProfile: {
                profile_image_base64: '',
                founded: '',
                about: '',
            },
            emptyText: 'Not specified',
            //for profile settings
        }
    },
    computed: {
        ...mapState(['isMobile', 'searchQuery', 'searchesData', 'authUserInfo', 'dashboardData']),
    },
    watch: {
        //animate form
        'formTopState.show': function (n, o) {
            if (n) {
                let ft = this.formTopState;
                ft.loading = true;
                this.$utils.clearTOut(ft.t);
                ft.t = setTimeout(() => {
                    ft.loading = false;
                }, 500);
            }
        }
    },
    methods: {
        ...mapMutations(['setClearValidate', 'setClearSuccess']),
        ...mapActions(['setPageTitle', 'showErrorToast', 'showInfoToast', 'fetchSearches', 'fetchAuthUserInfo',
            'fetchOptionProfileData', 'fetchDashboardData', 'postAutoUserLogin']),
        registerWatches() {
            if (this.watchers) {
                this.$watch(`searchesData.${this.type}`, (n, o) => {
                    this.setItems();
                });
            }
        },
        paginateNavigator(p) {
            this.isNavigator = true; //set to true to tell the request this is navigator action
            this.paginate.current_page = p.pageNo; //set current page next or prev page for pagination
            this.getItems();
        },
        callbackBuildItem(data) {
        },
        callbackSetItems() {
        },
        setItems() {//set items for table pagination
            this.paginate = this.searchesData[this.type];
            this.paginate.items = [];//reset items
            //set items for table pagination
            let data = this.paginate.data;
            for (let i in data) {
                if (data.hasOwnProperty(i)) {
                    this.paginate.items.push(this.callbackBuildItem(data[i]));
                }
            }
            //callback hooker to extended component
            this.callbackSetItems();
            //set editing items for new items
            this.setEditingItems();
            //check if user search or not
            if (this.query !== '') {
                this.isSearch = true;
            }
            this.isNavigator = false; //set to false to tell the request this is not navigator action
        },
        initEditingItems() {
            this.editingItems.oldCount = this.paginate.total;//set count for editing items
            this.editingItems.items = this.paginate.items.filter((f, idx) => {
                f.row = idx;
                return f.rowContent.state && f.rowContent.state.active === true;
            });
        },
        setEditingItems() {
            let mData = this.paginate, diff = 0;
            diff = mData.total - this.editingItems.oldCount;
            if (diff >= 0) {
                this.editingItems.items.filter(f => {
                    let i = mData.items[f.row + diff], j = f.rowContent;
                    if (!this.$utils.isEmptyVar(i)) {
                        //this will use user edited data replace the latest data but not origin data that contains latest data
                        i.rowContent.options = j.options;
                        i.rowContent.state = j.state;
                        i.rowContent.data = j.data;
                    }
                });
            }
        },
        getItems(p = {}) {//get all users
            //check if should reset current page
            if (p.per_page > 0) {
                this.paginate.per_page = p.per_page;
            }
            if (p.reset) {
                this.paginate.current_page = 1;
            }
            //check if should reset current page when user have search not navigate data
            if (!this.isNavigator) {
                this.paginate.current_page = 1;
            }
            //get and set editing items if user edit them
            this.initEditingItems();
            //check if should reset current page
            this.isSearch = false;//set user searching to false
            this.fetchSearches({
                options: this.options_request,
                type: this.type, q: this.query,
                limit: this.paginate.per_page, page: this.paginate.current_page
            });
        },
        toggleFormTop(t) {
            let ft = this.formTopState;
            ft.show = t;
            if (t) {
                this.setClearValidate(this.models.formTop);
            }
        },
        toggleFormRowContent(fireEvent, position, state) {
            let rowContent = this.paginate.items[position.row].rowContent;
            if (!state.active) {
                //reset edited data with clone originData;
                rowContent.options = this.$utils.clone(rowContent.originData.options);
                rowContent.data = this.$utils.clone(rowContent.originData);
            }
            //reset validated
            rowContent.validated = {};
            //send event to table is required
            this.Event.fire(fireEvent, {state, position});
        },
        limitText(count) {
            return `and ${count} more.`
        },
        getOptions(nl = true) {
            if (nl)
                this.isLoading = true;
            this.fetchOptionProfileData()
                .then(res => {
                    let s = res.success, d = res.data, op = this.options;
                    if (s) {
                        if (!this.$utils.isEmptyVar(d.user_profile)) {
                            this.userProfile = d.user_profile;
                        }
                        this.fetchAuthUserInfo();
                    }
                    this.isLoading = false;
                })
                .catch(err => {
                    this.showErrorToast({msg: 'Failed to load your profile!', dt: 3500});
                    this.isLoading = false;
                })
        },
        //Posts Status
        manageStatus(fireEvent, data, position, postType, changeStatusTo) {
            let dt = 3500,
                v = this.paginate.items[position.row].rowContent;
            this.toggleFormRowContent(
                fireEvent,
                position,
                this.Event.loadingState().ActiveLoading
            );
            data.id = v.data.id;
            data.changeStatusTo = changeStatusTo;
            this.postManagePostsStatus(data)
                .then(r => {
                    if (r.success) {
                        this.showInfoToast({msg: `The ${postType} status was updated!`, dt});
                        this.getItems();
                    } else {
                        this.showErrorToast({
                            msg: `Failed to update the ${postType} status!`,
                            dt
                        });
                    }
                    this.toggleFormRowContent(
                        fireEvent,
                        position,
                        this.Event.loadingState().NorActiveLoading
                    );
                })
                .catch(err => {
                    console.log(err);
                    this.showErrorToast({
                        msg: `Failed to update the ${postType} status!`,
                        dt
                    });
                    this.toggleFormRowContent(
                        fireEvent,
                        position,
                        this.Event.loadingState().ActiveNotLoading
                    );
                });
        },
        //Posts Status
        downloadExportFile({id, data}) {
            this.postAutoUserLogin()
                .then(res => {
                    if (res.success) {
                        let req = `?redirect_url=${encodeURIComponent(`/users/me/download-files/export?type=${data.type_user}&id=${id}&user_id=${data.user_id}`)}`;
                        let url = res.data + req;
                        this.$utils.downloadURL(url, 'frame-download')
                    }
                })
                .catch(err => {
                    console.log(err);
                })
        }
    },
    created() {
        this.registerWatches();
        this.setPageTitle(this.title);
    }
})
;

