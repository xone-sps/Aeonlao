<template>
    <div class="app-sidebar">
        <div class="nav">
            <button @click="GoToHomePage()" class="button v-md-button app-sidebar-left-top-menu flex-left">
                <div class="app-sidebar-logo-lockup">
                    <img class="app-sidebar-logo-lockup-icon"
                         :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`"
                         alt="Admin Icon Logo"
                    >
                    <img class="app-sidebar-logo-lockup-logotype logotype-white"
                         :src="`${baseUrl}/assets/images/admin-logo.svg${s.fresh_version}`"
                         alt="Admin Text Logo">
                </div>
            </button>
            <div class="app-sidebar-container-nav">
                <div class="app-sidebar-content-container-root side-group">
                    <div class="app-sidebar-overview">
                        <a class="app-sidebar-item" @click="GoToOverview"
                           :class="[{'selected-entry' : $route.name==='dashboard'}]">
                            <div class="app-sidebar-item-lockup">
                                <i class="material-icons selected-icon">home</i>
                                <span class="app-sidebar-entry-displayname">Overview</span>
                            </div>
                        </a>
                    </div>

                    <div class="app-sidebar-item-settings">
                        <button @click="GoToProfileSetting"
                                class="button v-md-button v-md-icon-button app-sidebar-item-settings-button">
                            <i class="material-icons app-sidebar-item-settings-icon">settings</i>
                            <i class="material-icons app-sidebar-item-settings-arrow-icon">arrow_drop_down</i>
                        </button>
                    </div>

                    <div class="app-sidebar-item-settings-collapsed">
                        <button @click="GoToProfileSetting" class="button v-md-button app-sidebar-item-settings-button">
                            <i class="material-icons app-sidebar-item-settings-icon">settings</i>
                            <i class="material-icons app-sidebar-item-settings-arrow-icon">arrow_drop_down</i>
                        </button>
                    </div>

                </div>
                <!--@Start Sidebar content menu-->
                <div class="sidebar-tree">
                    <SidebarItem @onItemClick="itemAction"
                                 v-for="(side, index) in sideItems"
                                 :contentHeader="side.contentHeader" :items="side.items" :key="index"/>
                </div>
                <!--@End Sidebar content menu-->
            </div>
            <!--@Start Footer Sidebar -->
            <button @click="setSidebarCollapsed" class="button v-md-button app-sidebar-item-expando">
                <i class="material-icons">chevron_left</i>
            </button>

            <button class="button v-md-button app-sidebar-item-expando is-mobile">
            </button>
            <!--@End Footer Sidebar -->
        </div>
    </div>
</template>

<script>
    import {mapMutations, mapActions, mapState} from 'vuex'
    import SidebarItem from '@com/Institute/Default/SidebarItem.vue'

    export default {
        name: "sidebar",
        data() {
            return {
                sideItems: [
                    {
                        contentHeader: {
                            expanded: true,
                            name: 'Institute Info',
                            description: 'Manage, Authentication',
                            icon: 'keyboard_arrow_up',
                        }, items: [
                            {
                                name: 'My Profile Settings',//required
                                icon: 'group',//required
                                action: this.Route,//required
                                params: {name: 'profile-settings'},//required
                            },
                        ]
                    },
                    {
                        contentHeader: {
                            expanded: true,
                            name: "My Assessments",
                            description: "Manage my assessments information",
                            icon: "keyboard_arrow_up"
                        },
                        items: [
                            {
                                name: "Assessments", //required
                                icon: "collections_bookmark", //required
                                action: this.Route, //required
                                params: {name: "check-assessments"} //required
                            }
                        ]
                    }
                ]
            }
        },
        components: {
            SidebarItem,
        },
        methods: {
            ...mapActions([]),
            ...mapMutations(['setSidebarCollapsed', 'setSelectedSidebarItem']),
            itemAction(i) {
                if (i.action) {
                    i.action(i.params);
                }
            },
            routeActions(data) {
                this.Route({name: data.params.name});
                this.setSelectedSidebarItem(data);
            },
            GoToOverview() {
                let i = {name: 'Dashboard', params: {name: 'dashboard'}};
                this.routeActions(i);
            },
            GoToProfileSetting() {
                let i = {name: 'My Profile Settings', params: {name: 'profile-settings'}};
                this.routeActions(i);
            },
            GoToHomePage() {
                this.$utils.Location('/');
            }
        }
    }
</script>

<style scoped>

</style>
