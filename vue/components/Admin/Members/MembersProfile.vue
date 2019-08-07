<template>
    <div class="no-tip no-border white-table-paginate">
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <MasterSingleDetailCard
                        :isLoading="validated().loading_members_profile_search"
                        @onBackButtonClick="onBackButtonClick"
                        :cell="'16'"
                        :header="{
                        title: 'Find Other Members Profile',
                        content: `You can search other member and see their profile, by enter his/her name, surname, email, phone number, year of graduated and so on.`}"
                        :menuItem="{ name: 'Members Profile', icon: 'account_circle'}">
                        <div class="admin-settings-cameo template-brand-settings">
                            <div class="settings-container no-margin-top no-border-left add-padding" border-bottom>
                                <div class="cameo-header">
                                    <i class="material-icons cameo-header-icon">filter_list</i>
                                    <span> Filters</span>
                                </div>
                                <div class="cameo-content">
                                    <div class="layout-align-space-around-start layout-row">
                                        <!-- Filter by Degree in Education-->
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Degree in Education</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.degree"
                                                         label="label" track-by="value"
                                                         placeholder="Select degree"
                                                         open-direction="bottom"
                                                         :options="options.educationDegrees"
                                                         :show-no-results="false"
                                                         :preserve-search="true"
                                                         :hide-selected="false"
                                                         @input="getItems">
                                            </multiselect>
                                        </div>
                                        <!-- Filter by Degree in Education -->
                                        <!--Filter by Marital Status -->
                                        <div class="form-multi-select-container flex is-second-input dense"
                                             full>
                                            <label>Marital Status</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.marital_status"
                                                         label="text" track-by="value"
                                                         placeholder="Select status"
                                                         open-direction="bottom"
                                                         :options="getMaritalStatusOption()"
                                                         :show-no-results="false"
                                                         :preserve-search="true"
                                                         :hide-selected="false"
                                                         @input="getItems">
                                            </multiselect>
                                        </div>
                                        <!-- Filter by Marital Status-->
                                    </div>
                                    <!-- Filter by Type of Organization -->
                                    <div class="layout-align-space-around-start layout-row">
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Type of Organization</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.type_of_organization"
                                                         label="label" track-by="value"
                                                         placeholder="Select type"
                                                         open-direction="bottom"
                                                         :options="options.organization"
                                                         :show-no-results="false"
                                                         :preserve-search="true"
                                                         :hide-selected="false"
                                                         @input="getItems">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <!-- Filter by Type of Organization-->
                                </div>
                            </div>
                        </div>
                        <!--Search Input-->
                        <SearchInput
                            v-model="query"
                            searchButtonText="Search"
                            searchPlaceholder="Search by member name, surname, phone number or member Email"
                            @onSearchActionButton="getItems"
                            @onSearchReLoadButtonClick="getItems"
                            @onSearchInputEnter="getItems"
                            @onSearchInputClose="getItems"
                            @onRemoveChipText="getItems"/>
                        <!--Search Input-->
                        <div class="details is-edit">

                            <div class="represent-search">
                                <div class="is-index">
                                    <div class="admin-card-list">

                                        <!--User Card -->
                                        <div class="link-card-container" v-for="(item, idx) in paginate.items">
                                            <div class="link-card admin-mat-card">
                                                <div class="is-card-header">
                                                    <img :alt="item.name" class="is-card-logo"
                                                         :src="`${baseUrl}${item.thumb_image}`">
                                                    <h1 class="is-card-logo-text">{{`${$utils.firstUpper(item.name)}
                                                        ${$utils.firstUpper(item.last_name)}`}}</h1>
                                                </div>
                                                <div class="is-link-card-content admin-mat-card-content">
                                                    <div class="is-card-body">
                                                        <div class="is-icon-description-row">
                                                            <span class="is-description">
                                                                Email: {{item.email}} <br>
                                                                Date of Birth: {{ item.date_of_birth ? $utils.formatTimestmp(item.date_of_birth, false) : emptyText}}<br>
                                                                Status: {{item.marital_status || emptyText}} <br>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <button @click="userProfileDetail(item.id)"
                                                            class="v-mat-button primary is-card-footer is-link">
                                                    <span class="v-mat-button-wrapper">
                                                        Detail
                                                        <i class="admin-mat-icon material-icons is-arrow-icon"
                                                           role="img" aria-hidden="true">arrow_forward</i></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--User Card -->

                                    </div>
                                </div>
                                <div
                                    v-if="!isNotFound() && !(paginate.current_page===1 && paginate.current_page===paginate.last_page) && !isFirstLoad"
                                    class="table-pagination members-card layout-align-end-center layout-row">
                                    <div class="layout-align-end-center layout-row">
                                        <button :disabled="paginate.current_page===1"
                                                @click="prevPage(paginate.current_page - 1)"
                                                class="v-md-button v-md-icon-button pag"><i
                                            class="material-icons">chevron_left</i></button>
                                        <button :disabled="paginate.current_page===paginate.last_page"
                                                @click="nextPage(paginate.current_page + 1)"
                                                class="v-md-button v-md-icon-button pag"><i
                                            class="material-icons">chevron_right</i></button>
                                    </div>
                                </div>

                                <div class="admin-card-content" v-if="isNotFound()">
                                    <div class="card-result-panel layout-align-center-center layout-column">
                                        <div class="card-result-heading layout-row"> Could not be found the
                                            information
                                        </div>
                                        <div class="card-result-heading layout-row">
                                            Please make sure you type or spell the member information correctly.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </MasterSingleDetailCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import UserBase from '@bases/UserBase.js'
    import SearchInput from '@cus-com/Admin/SearchInput.vue'

    export default UserBase.extend({
        name: "MembersProfile",
        components: {
            SearchInput
        },
        data: () => ({
            title: 'Members Profile',
            type: 'members',
            tabs: [{name: 'Members Profile'}],
            watchers: true,
            isFirstLoad: true,
        }),
        methods: {
            ...mapActions(['fetchSearchMembers']),
            onBackButtonClick() {
                this.Route({name: "dashboard"});
            },
            isNotFound() {
                return this.paginate.items.length <= 0 && this.isSearch;
            },
            paginateNavigator(p) {
                this.isNavigator = true; //set to true to tell the request this is navigator action
                this.paginate.current_page = p.pageNo; //set current page next or prev page for pagination
                this.getItems();
            },
            getItems() {//get all users
                //check if should reset current page when user have search not navigate data
                if (!this.isNavigator) {
                    this.paginate.current_page = 1;
                }
                this.isSearch = false;//set user searching to false
                this.fetchSearchMembers({
                    type: this.type, q: this.query, filters: this.filters,
                    limit: this.paginate.per_page, page: this.paginate.current_page
                })
            },
            prevPage(pageNo) {
                if (pageNo < 1) pageNo = 1;
                if (this.paginate.current_page === pageNo) return;
                this.paginateNavigator({pageNo, dr: 'prev'});
            },
            nextPage(pageNo) {
                if (pageNo > this.paginate.lastPage) pageNo = this.paginate.lastPage;
                if (this.paginate.current_page === pageNo) return;
                this.paginateNavigator({pageNo, dr: 'next'});
            },
            callbackSetItems() {
                this.paginate = this.searchesData[this.type];
                this.paginate.items = this.paginate.data;
                this.isNavigator = false; //set to false to tell the request this is not navigator action
                this.isFirstLoad = false;
            },
            userProfileDetail(id) {
                this.Route({name: 'member-profile', params: {user_id: id}})
            },
            setQueryFilters() {
                this.query = this.searchQuery.text;
                this.filters = this.searchQuery.filters;
            }
        },
        created() {
            //check if need to set old query and filters
            if (this.$route.params.setQueryFilters) {
                this.setQueryFilters();
            }
            this.paginate.per_page = 15;//override per page items
            this.getOptions();
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
