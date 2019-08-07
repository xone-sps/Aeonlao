<template>
    <div class="nav-container" :style="`background: ${hasHeaderTransparent ? '': getBgColor()};`">
        <nav class="admin-navbar app-bar" :style="`background: ${scrollNavBgColor};`" :class="scrollNavShadow">
            <!--@Mobile -->
            <div class="navbar-brand" v-if="isMobile" :style="isMobile ? 'width: 100%;': ''">
                <!--Start Menu on mobile here-->
                <!--End Start  Menu on mobile here-->
                <div class="navbar-burger burger is-marginless">
                    <div class="app-bar-left-top-menu-container">
                        <button @click="setSidebarMobileOpen({isOpen: true})" class="button v-md-button left-top-menu">
                            <i class="material-icons">menu</i>
                        </button>
                    </div>
                </div>
                <!--End Menu on mobile here-->
                <a @click="Route({name:'dashboard'})" class="navbar-item is-bold-nav-item is-left-start">Dashboard</a>

                <div class="nav-and-page-menu">
                    <a @click="scrollToTop()" class="nav-bar-page-menu" :class="[isHiddenPageItem]">
                        {{ selectedSidebarItem.name }}
                    </a>
                </div>

                <div class="end-of-mobile">
                    <!--<a class="nav-bar-link-menu">-->
                    <!--Go to Doc-->
                    <!--</a>-->
                    <div class="navbar-alert-menu">
                        <!--<button class="navbar-alert-menu-button navbar-alert-menu-button-bell navbar-menu-button">-->
                        <!--<span class="navbar-menu-button-wrapper">-->
                        <!--<i class="material-icons">notifications</i>-->
                        <!--</span>-->
                        <!--<div-->
                        <!--class="navbar-menu-button-ripple navbar-menu-button-round navbar-menu-button-ripple"></div>-->
                        <!--<div class="navbar-menu-button-focus"></div>-->
                        <!--</button>-->
                    </div>
                    <div class="navbar-overflow-menu">
                        <!--<button-->
                        <!--class="v-md-button navbar-menu-button-icon navbar-overflow-menu-button navbar-men-button-no-style">-->
                        <!--<i class="material-icons">more_vert</i>-->
                        <!--</button>-->
                    </div>
                    <div class="navbar-user-menu">
                        <div class="navbar-user-menu-visible">
                            <div class="navbar-user-menu-container is-small">
                                <div class="navbar-user-menu-container-picture">
                                    <div class="navbar-user-menu-container-picture-relative">
                                        <div class="navbar-user-menu-container-picture-info">
                                            <div class="navbar-user-menu-picture-info">
                                                <a @click="toggleUserPanel"
                                                   :aria-expanded="isUserPanelExpanded? 'true':'false'"
                                                   class="navbar-user-menu-picture-info-circle">
                                                    <span
                                                        class="navbar-user-menu-picture-circle-src-container">
                                                        <img :style="`${getImageStyleProfile()}`"
                                                             :src="`${baseUrl}${authUserInfo.thumb_image}`"/>
                                                    </span>
                                                </a>
                                            </div>
                                            <div @click="panelClick" aria-label="account info" aria-hidden="false"
                                                 class="navbar-user-menu-picture-info-panel"
                                                 :class="isUserPanelExpanded">
                                                <div class="user-square-top-container">
                                                    <a class="user-picture-link">
                                                        <div class="user-picture-link-inner">
                                                            <div class="user-picture-link-inner-title"
                                                                 title="profile">
                                                                <img :style="`${getImageStyleProfile()}`"
                                                                     :src="`${baseUrl}${authUserInfo.thumb_image}`"/>
                                                            </div>
                                                            <!--<span-->
                                                            <!--class="user-picture-link-change is-black">Change</span>-->
                                                        </div>
                                                    </a>
                                                    <div class="user-square-top-right">
                                                        <div class="user-square-top-right-name">{{ `${authUserInfo.name}
                                                            ${authUserInfo.last_name}` }}
                                                        </div>
                                                        <div class="user-square-top-right-email">
                                                            {{ authUserInfo.email }}
                                                        </div>
                                                        <!--<div class="user-square-top-right-link-action-container">-->
                                                        <!--<a href="#" target="_blank">Profile-->
                                                        <!--Google+</a><span aria-hidden="true">–</span><a-->
                                                        <!--href="#"-->
                                                        <!--target="_blank">Privacy</a></div>-->
                                                        <a @click="goTo($event, 'profile-settings')"
                                                           class="user-square-top-right-account">My Account</a>
                                                    </div>
                                                </div>
                                                <div class="splitter-loading"
                                                     :class="validated().loading ? '' : 'not-show'">
                                                    <div class="splitter-loading-inner"></div>
                                                </div>
                                                <div class="user-square-footer-container">
                                                    <div>
                                                        <a class="user-square-footer-button"
                                                           @click="goTo($event,'profile-settings')">Profile
                                                            Settings</a>
                                                    </div>
                                                    <div>
                                                        <a @click="Logout()" class="user-square-footer-button"
                                                           target="_top">Sign out</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--End Start  Menu on mobile here-->
            </div>
            <!--@End Mobile -->

            <!-- @Start Desktop -->
            <div class="navbar-menu">

                <div class="navbar-start app-bar-left-top-start-spaced">

                    <a @click="Route({name:'dashboard'})" class="navbar-item is-bold-nav-item">
                        Dashboard
                    </a>
                    <div class="nav-and-page-menu">
                        <a @click="scrollToTop()" class="nav-bar-page-menu" :class="[isHiddenPageItem]">
                            {{ selectedSidebarItem.name }}
                        </a>
                    </div>

                </div><!-- nav start -->
                <div class="navbar-end">
                    <!--<a class="nav-bar-link-menu">-->
                    <!--Go to Doc-->
                    <!--</a>-->
                    <div class="navbar-alert-menu">
                        <!--<button class="navbar-alert-menu-button navbar-alert-menu-button-bell navbar-menu-button">-->
                        <!--<span class="navbar-menu-button-wrapper">-->
                        <!--<i class="material-icons">notifications</i>-->
                        <!--</span>-->
                        <!--<div-->
                        <!--class="navbar-menu-button-ripple navbar-menu-button-round navbar-menu-button-ripple"></div>-->
                        <!--<div class="navbar-menu-button-focus"></div>-->
                        <!--</button>-->
                    </div>
                    <div class="navbar-user-menu">
                        <div class="navbar-user-menu-visible">
                            <div class="navbar-user-menu-container is-small">
                                <div class="navbar-user-menu-container-picture">
                                    <div class="navbar-user-menu-container-picture-relative">
                                        <div class="navbar-user-menu-container-picture-info">
                                            <div class="navbar-user-menu-picture-info">
                                                <a @click="toggleUserPanel"
                                                   :aria-expanded="isUserPanelExpanded? 'true':'false'"
                                                   class="navbar-user-menu-picture-info-circle">
                                                    <span
                                                        class="navbar-user-menu-picture-circle-src-container">
                                                        <img :style="`${getImageStyleProfile()}`"
                                                             :src="`${baseUrl}${authUserInfo.thumb_image}`"/>
                                                    </span>
                                                </a>
                                            </div>
                                            <div @click="panelClick" aria-label="account info" aria-hidden="false"
                                                 class="navbar-user-menu-picture-info-panel"
                                                 :class="isUserPanelExpanded">
                                                <div class="user-square-top-container">
                                                    <a class="user-picture-link">
                                                        <div class="user-picture-link-inner">
                                                            <div class="user-picture-link-inner-title" title="profile">
                                                                <img :style="`${getImageStyleProfile()}`"
                                                                     :src="`${baseUrl}${authUserInfo.thumb_image}`"/>
                                                            </div>
                                                            <!--<span-->
                                                            <!--class="user-picture-link-change is-black">Change</span>-->
                                                        </div>
                                                    </a>
                                                    <div class="user-square-top-right">
                                                        <div class="user-square-top-right-name">{{ `${authUserInfo.name}
                                                            ${authUserInfo.last_name}` }}
                                                        </div>
                                                        <div class="user-square-top-right-email">
                                                            {{ authUserInfo.email }}
                                                        </div>
                                                        <!--<div class="user-square-top-right-link-action-container">-->
                                                        <!--<a href="#" target="_blank">Profile-->
                                                        <!--Google+</a><span aria-hidden="true">–</span><a-->
                                                        <!--href="#"-->
                                                        <!--target="_blank">Privacy</a></div>-->
                                                        <a @click="goTo($event,'profile-settings')"
                                                           class="user-square-top-right-account">My Account</a>

                                                    </div>
                                                </div>
                                                <div class="splitter-loading"
                                                     :class="validated().loading ? '' : 'not-show'">
                                                    <div class="splitter-loading-inner"></div>
                                                </div>
                                                <div class="user-square-footer-container">
                                                    <div><a class="user-square-footer-button"
                                                            @click="goTo($event,'profile-settings')">Profile
                                                        Settings</a>
                                                    </div>
                                                    <div><a @click="Logout()" class="user-square-footer-button"
                                                            target="_top">Sign out</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--@End Desktop-->
            </div>
        </nav>
        <!--  nav container -->
    </div>
</template>

<script>
    import {mapState, mapGetters, mapMutations, mapActions} from 'vuex'

    export default {
        name: "nav-admin-user",
        props: {
            bgColor: {
                required: false,
            },
            hasHeaderTransparent: {
                default: false,
            }
        },
        data() {
            return {
                ...mapGetters(['validated']),
                scrollNavBgColor: 'rgba(3, 155, 229, 0);',
                scrollNavShadow: '',
                lastScrollTop: 0,
                navbarHeight: 48,
                isHiddenPageItem: 'is-hidden-nav',
                isUserPanelExpanded: '',
                el: null,
            }
        },
        computed: {
            ...mapState(['authUserInfo', 'isMobile', 'isSidebar', 'selectedSidebarItem']),
        },
        methods: {
            ...mapActions(['Logout']),
            ...mapMutations(['setSidebarMobileOpen']),
            getImageStyleProfile() {
                return (this.getImageProfileExt() === 'svg' || this.getImageProfileExt() === 'gif') ? `width: 100%; height: 100%` : ''
            },
            getImageProfileExt() {
                return this.$utils.getFileExtension(this.authUserInfo.thumb_image);
            },
            getBgColor() {
                return `${this.$utils.isEmptyVar(this.bgColor) ? 'rgb(3, 155, 229)' : this.bgColor}`;
            },
            toggleUserPanel(e) {
                e.stopPropagation();
                this.isUserPanelExpanded = this.isUserPanelExpanded ? '' : 'is-expanded';
            },
            outsideClick(e) {
                if (this.isUserPanelExpanded && !this.validated().loading) {
                    this.isUserPanelExpanded = ''
                }
            },
            panelClick(e) {
                e.stopPropagation();
            },
            goTo(e, name, options = {}) {
                this.toggleUserPanel(e);
                this.Route({name})
            },
            setAlphaRatio(alphaRatio, dr) {
                let isEmitted = false;
                if (dr === 0) {
                    if (alphaRatio <= 1) {
                        this.scrollNavBgColor = this.hasHeaderTransparent ? this.$utils.getARGB(this.getBgColor(), alphaRatio) : this.getBgColor();
                    }
                    if (alphaRatio >= 1) {
                        this.scrollNavShadow = 'has-visible-shadow';
                        this.scrollNavBgColor = `${this.getBgColor()};`;
                        if (!isEmitted) {
                            this.$emit('addScrollHasShadow');
                            isEmitted = true;
                        }
                    }
                } else {
                    if (alphaRatio <= 1) {
                        this.scrollNavBgColor = this.hasHeaderTransparent ? this.$utils.getARGB(this.getBgColor(), alphaRatio) : this.getBgColor();
                        if (alphaRatio <= 0.5) {
                            this.scrollNavShadow = '';
                            this.$emit('removeScrollHasShadow');
                            isEmitted = false;
                        }
                    }
                    if (alphaRatio >= 1) {
                        this.scrollNavBgColor = `${this.getBgColor()};`;
                    }
                }
                this.Event.fire('scrolling', this.el.scrollTop());
            },
            scrollNavHandler() {
                this.el = this.jq('#main-container');
                this.el.scroll(() => {
                    let st = this.el.scrollTop(), alphaRatio;
                    alphaRatio = st / this.navbarHeight;
                    if (st > this.lastScrollTop) {
                        // Scroll Down
                        this.setAlphaRatio(alphaRatio, 0);
                    } else {
                        // Scroll Up
                        this.setAlphaRatio(alphaRatio, 1);
                    }
                    this.lastScrollTop = st;
                });//
            },
            removeHandlers() {
                document.removeEventListener('click', this.outsideClick);
                if (this.el)
                    this.el.off('scroll');
            },
            registerHandlers() {
                document.addEventListener('click', this.outsideClick);
                this.$off('removeScrollHasShadow');
                this.$on('removeScrollHasShadow', () => {
                    this.isHiddenPageItem = 'is-hidden-nav'
                });
                this.$off('addScrollHasShadow');
                this.$on('addScrollHasShadow', () => {
                    this.isHiddenPageItem = ''
                })
            },
            scrollToTop() {
                this.$utils.animateScrollToY('main-container', 2);
            }
        },
        created() {
            this.registerHandlers();
        },
        mounted() {
            this.scrollNavHandler();
        },
        beforeDestroy() {
            this.removeHandlers();
        }
    }
</script>
