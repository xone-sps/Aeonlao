<template>
    <div class="app-admin-container layout-column flex-100">
        <div v-if="LoggedIn()" class="layout-row flex" :class="[isSidebarCollapsed, isSidebarMobileOpen]">
            <Sidebar/>
            <div class="right-container layout-column flex">
                <div class="main-app-nav-bar">
                    <Nav :hasHeaderTransparent="hasHeaderTransparent"/>
                </div>
                <div id="main-container" class="modulehost-content layout-column flex admin-spinner-covered">

                    <div class="layout-fill" :class="[{'transclude': addTranscludeClass}]">
                        <!--FB Bar -->
                        <div v-if="showFeatureBar">
                            <div class="admin-fb-featurebar">
                                <div class="admin-fb-featurebar-main">
                                    <div class="admin-fb-featurebar-title-lockup admin-fb-featurebar-space-consumer">
                                        <div class="admin-fb-featurebar-title">{{ selectedSidebarItem.name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FB Bar -->
                        <router-view></router-view>
                    </div>

                </div>
            </div>
            <div @click="setSidebarMobileOpen({isOpen: false})" class="mobile-sidebar-bg-on-open"></div>
        </div>
        <!--Menu context-->
        <MenuContext/>
        <!--Menu context-->
        <!--Spinner For Whole Page, If user is not logged in -->
        <SpinnerLoading v-if="!LoggedIn()"/>
        <!--Spinner For Whole Page, If user is not logged in -->
        <!--Frame download-->
        <iframe v-show="false" id="frame-download"></iframe>
        <!--Frame download-->
    </div>
</template>
<script>
    import {mapState, mapGetters, mapActions, mapMutations} from 'vuex'
    import Sidebar from '@com/Admin/Default/Sidebar.vue'
    import Nav from '@com/Admin/Default/Nav.vue'
    import MenuContext from '@cus-com/Admin/MenuContext.vue'

    export default {
        name: 'app-admin',
        components: {
            Sidebar,
            Nav,
            MenuContext
        },
        data() {
            return {
                ...mapGetters(['LoggedIn']),
                breakPoint: 0,
                windowHeight: 0,
                limitBreakPoint: 1023,
                showFeatureBar: true,
                addTranscludeClass: false,
                hasHeaderTransparent: false,
            }
        },
        computed: {
            ...mapState(['isMobile', 'isSidebarCollapsed', 'isSidebarMobileOpen', 'selectedSidebarItem', 'authUserInfo']),
        },
        watch: {
            'authUserInfo': function (n, o) {
                if (this.$route.name && !(this.$route.meta.allows && this.$route.meta.allows.includes(n.decodedType))) {
                    this.$utils.Location(`/${String(n.decodedType).replace(/_/g, '-')}/me`);
                }
            }
        },
        methods: {
            ...mapActions(['fetchAuthUserInfo']),
            ...mapMutations(['setMobile', 'setSidebarMobileOpen']),
            getClient: (e, context) => {
                context.breakPoint = e.currentTarget.innerWidth;
                context.windowHeight = e.currentTarget.innerHeight;
                context.isBreak(context.limitBreakPoint);
            },
            isBreak(break_point) {
                if (this.breakPoint <= break_point) {
                    this.setMobile({isMobile: true, currentWidth: this.breakPoint, currentHeight: this.windowHeight});
                } else {
                    this.setMobile({isMobile: false, currentWidth: this.breakPoint, currentHeight: this.windowHeight});
                }
            }
        },
        mounted() {
            this.breakPoint = this.$el.clientWidth;
            this.windowHeight = this.$el.clientHeight;
            window.addEventListener('resize', (e) => {
                this.getClient(e, this);
            });
            this.isBreak(this.limitBreakPoint);
        },
        created() {
            this.fetchAuthUserInfo();//get first user data
        },
        beforeDestroy() {
            if (window.removeEventListener) {
                window.removeEventListener('resize', (e) => {
                    this.getClient(e, this)
                })
            }
        }
    }
</script>
<style scoped>
</style>
