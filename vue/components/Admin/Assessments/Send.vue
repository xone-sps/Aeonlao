<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <MasterSingleDetailCard
                        :isLoading="validated().loading_institute_search"
                        @onBackButtonClick="onBackButtonClick"
                        :cell="'16'"
                        :header="{
                        title: 'Select Institutes or Field Inspector',
                        content: `You can search users you need to send an assessment to, by enter the field inspector name, surname, institute name and so on.`}"
                        :menuItem="{ name: 'Send Assessment', icon: 'email'}">

                        <div class="admin-settings-cameo template-brand-settings">
                            <div class="settings-container no-border-left add-padding no-margin-top"
                                 border-bottom>
                                <div class="cameo-header">
                                    <i class="material-icons cameo-header-icon">face</i>
                                    <span>Assessments For Filed Inspectors</span>
                                </div>
                            </div>
                        </div>
                        <div class="admin-settings-cameo template-brand-settings">
                            <div class="settings-container no-margin-top no-border-left add-padding" border-bottom>
                                <div class="cameo-header">
                                    <i class="material-icons cameo-header-icon">filter_list</i>
                                    <span> Filters</span>
                                </div>
                                <div class="cameo-content">
                                    <div class="layout-align-space-around-start layout-row">
                                        <!-- Select Assessment -->
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Select assessments</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.field_inspector_assessments"
                                                         label="title" track-by="id"
                                                         placeholder="Select assessments"
                                                         open-direction="bottom"
                                                         :options="options.assessments"
                                                         :limit="15"
                                                         :limit-text="limitText"
                                                         :multiple="true"
                                                         :clear-on-select="false"
                                                         :close-on-select="false"
                                                         :preserve-search="true"
                                                         :hide-selected="true">
                                            </multiselect>
                                            <template v-if="validated().field_inspector_assessments">
                                                <div class="form-input-container" style="padding-bottom: 10px;">
                                                    <input v-show="false"/>
                                                    <div admin-messages>
                                                        <div admin-message class="message-required ">
                                                            Please choose at least one assessment.
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <!-- Select Field Inspector -->
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <!-- Select Field Inspector -->
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Field Inspectors</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.field_inspectors"
                                                         label="name" track-by="id"
                                                         placeholder="Select users"
                                                         open-direction="bottom"
                                                         :options="options.field_inspectors"
                                                         :limit="15"
                                                         :limit-text="limitText"
                                                         :multiple="true"
                                                         :clear-on-select="false"
                                                         :close-on-select="false"
                                                         :preserve-search="true"
                                                         :hide-selected="true">
                                            </multiselect>
                                            <template v-if="validated().field_inspectors">
                                                <div class="form-input-container" style="padding-bottom: 10px;">
                                                    <input v-show="false"/>
                                                    <div admin-messages>
                                                        <div admin-message class="message-required ">
                                                            Please choose at least one user.
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <!-- Select Field Inspector -->
                                    </div>

                                    <div class="layout-align-end-center layout-row" style="padding-bottom: 22px;">
                                        <button @click="sendAssessmentSelectedUsers('field_inspector')"
                                                class="v-md-button primary">{{ sentText.field_inspector }}
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="admin-settings-cameo template-brand-settings">
                            <div class="settings-container no-border-left add-padding no-margin-top"
                                 border-bottom>
                                <div class="cameo-header">
                                    <i class="material-icons cameo-header-icon">face</i>
                                    <span>Assessment For Institutes</span>
                                </div>
                            </div>
                        </div>

                        <div class="admin-settings-cameo template-brand-settings">
                            <div class="settings-container no-margin-top no-border-left add-padding" border-bottom>
                                <div class="cameo-header">
                                    <i class="material-icons cameo-header-icon">filter_list</i>
                                    <span> Filters</span>
                                </div>
                                <div class="cameo-content">
                                    <!-- Select Institutes -->
                                    <div class="layout-align-space-around-start layout-row">
                                        <!-- Select Assessment -->
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Select an Assessment</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.institute_assessment"
                                                         label="title" track-by="id"
                                                         placeholder="Select assessment"
                                                         open-direction="bottom"
                                                         :options="options.assessments"
                                                         :show-no-results="false"
                                                         :preserve-search="true"
                                                         :hide-selected="false">
                                            </multiselect>
                                            <template v-if="validated().institute_assessment">
                                                <div class="form-input-container" style="padding-bottom: 10px;">
                                                    <input v-show="false"/>
                                                    <div admin-messages>
                                                        <div admin-message class="message-required ">
                                                            Please choose an assessment
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <!-- Select Field Inspector -->
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Institutes (Parents & All Child)</label>
                                            <multiselect class="select-multiple"
                                                         v-model="filters.institutes"
                                                         label="institute_name" track-by="id"
                                                         placeholder="Select institutes"
                                                         open-direction="bottom"
                                                         :limit="15"
                                                         :limit-text="limitText"
                                                         :multiple="true"
                                                         :clear-on-select="false"
                                                         :close-on-select="true"
                                                         :preserve-search="true"
                                                         :hide-selected="true"
                                                         :options="options.institutes"
                                                         @input="getItems('change')">
                                            </multiselect>
                                            <template v-if="validated().institutes">
                                                <div class="form-input-container" style="padding-bottom: 10px;">
                                                    <input v-show="false"/>
                                                    <div admin-messages>
                                                        <div admin-message class="message-required ">
                                                            Please choose at least one institute
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <!--Select Institutes-->

                                    <div class="layout-align-end-center layout-row" style="padding-bottom: 22px;">
                                        <button @click="sendAssessmentSelectedUsers('institute')"
                                                class="v-md-button primary">{{ sentText.institute }}
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!--Search Input-->
                        <SearchInput
                            v-model="query"
                            searchButtonText="Search"
                            searchPlaceholder="Search by parent institute name, short name or institute Email"
                            @onSearchActionButton="getItems"
                            @onSearchReLoadButtonClick="getItems('reload')"
                            @onSearchInputEnter="getItems"
                            @onSearchInputClose="getItems"
                            @onRemoveChipText="getItems"/>
                        <!--Search Input-->


                        <div class="details is-edit"
                             style="min-height: 380px;padding-right: 0;padding-left: 0;padding-top: 2px;">

                            <div class="represent-search" v-if="isNotFound()">
                                <div class="admin-card-content">
                                    <div class="card-result-panel layout-align-center-center layout-column">
                                        <div class="card-result-heading layout-row"> Could not be found the
                                            information
                                        </div>
                                        <div class="card-result-heading layout-row" style="text-align: center;">
                                            Please make sure you type or spell the parent institute information
                                            correctly.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <template v-for="(parent, idx) in childInstitutes" v-if="parent.child.length">

                                <div class="admin-settings-cameo template-brand-settings" :key="`parent-${idx}`">
                                    <div class="settings-container no-border-left add-padding no-margin-top"
                                         border-bottom style="position: relative;">
                                        <div class="cameo-header">

                                            <i class="material-icons cameo-header-icon">face</i>
                                            <span>{{ parent.parent.institute_name }}</span>

                                            <div class="cameo-button-abs">
                                                <div @click="sendAssessmentChildInstitutes(parent.child)"
                                                     class="v-md-button v-md-icon-button">
                                                    <i class="material-icons cameo-header-icon">send</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="represent-search" style="padding-top: 2px;">
                                    <div class="is-index">
                                        <div class="admin-card-list">
                                            <!--User Card -->
                                            <div class="link-card-container" v-for="(item, jdx) in parent.child">
                                                <div class="link-card admin-mat-card" :key="`parent-child-${jdx}`">
                                                    <div class="is-card-header">
                                                        <img :alt="item.institute_name" class="is-card-logo"
                                                             :src="`${baseUrl}${item.image}`">
                                                        <h1 class="is-card-logo-text">{{
                                                            $utils.firstUpper(item.institute_name)}}</h1>
                                                    </div>
                                                    <div class="is-link-card-content admin-mat-card-content">
                                                        <div class="is-card-body">
                                                            <div class="is-icon-description-row">
                                                            <span class="is-description">
                                                                Short: {{item.short_institute_name || emptyText}} <br>
                                                                Email: {{item.public_email}} <br>
                                                                Founded: {{ item.founded ? $utils.formatTimestmp(item.founded, false) : emptyText}}<br>
                                                            </span>
                                                            </div>
                                                        </div>

                                                        <button @click="deleteChild(idx, jdx)"
                                                                class="v-mat-button warning is-card-footer is-link">
                                                                <span class="v-mat-button-wrapper">
                                                                    Delete
                                                                    <i class="admin-mat-icon material-icons is-arrow-icon"
                                                                       role="img" aria-hidden="true">close</i>
                                                                </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--User Card -->

                                        </div>
                                    </div>

                                </div>

                            </template>
                        </div>
                    </MasterSingleDetailCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserBase from '@bases/UserBase.js'
    import {mapActions} from 'vuex'
    import SearchInput from '@cus-com/Admin/SearchInput.vue'

    export default UserBase.extend({
        name: "SendAssessment",
        components: {
            SearchInput
        },
        data() {
            return {
                title: 'ສົ່ງບົດປະເມີນ',
                type: 'none',
                watchers: true,
                tabs: [{name: 'Send Assessment'}],
                isFirstLoad: true,
                sending: false,
                childInstitutes: [],
                deletedInstitutes: [],
                sentText: {institute: 'Send', field_inspector: 'Send'},
            }
        },
        methods: {
            ...mapActions(['postSendAssessmentForUsers']),
            onBackButtonClick() {
                this.Route({name: "assessment"});
            },
            isNotFound() {
                return this.childInstitutes.length <= 0 && this.isSearch;
            },
            getItems(t) {//get all users
                if (t === 'reload') {
                    this.deletedInstitutes = [];
                }
                this.isSearch = !this.$utils.isEmptyVar(this.query);
                this.childInstitutes = [];//clear data
                if (!(this.filters.institutes || []).length) {
                    this.deletedInstitutes = [];
                    return;
                }
                //assign child institutes
                this.filters.institutes.map((item) => {
                    if (!item.parent_institute_category_id) {
                        let child = this.options.institutes.filter((ch => {
                            return ch.parent_institute_category_id === item.institute_category_id;
                        }));
                        this.childInstitutes.push({
                            parent: item,
                            child
                        });
                        this.deletedInstitutes.map(item => {
                            let pIdx = this.childInstitutes.findIndex((p) => {
                                return p.parent.id === item.parent.parent.id;
                            });
                            if (pIdx !== -1) {
                                let parent = this.childInstitutes[pIdx];
                                let cIdx = parent.child.findIndex((c) => {
                                    return c.id === item.child.id;
                                });
                                if (cIdx !== -1) {
                                    this.childInstitutes[pIdx].child.splice(cIdx, 1);
                                }
                            }
                        });
                    }
                });
                //filter selected child
                this.filters.institutes.map((item) => {
                    this.childInstitutes.map((p) => {
                        p.child = p.child.filter(c => {
                            return !(c.id === item.id)
                        });
                    });
                });
                //filter by query parent data
                let query = this.$utils.escapeRegExp(this.query);
                let queryRegExp = new RegExp(query, 'i');
                this.childInstitutes = this.childInstitutes.filter((p) => {
                    return queryRegExp.test(p.parent.public_email) ||
                        queryRegExp.test(p.parent.institute_name) ||
                        queryRegExp.test(p.parent.short_institute_name) ||
                        queryRegExp.test(p.parent.phone_number);
                });

            },
            deleteChild(parent, child) {
                let mParent = this.childInstitutes[parent];
                this.deletedInstitutes.push({parent: mParent, child: mParent.child[child]});
                this.getItems();
            },
            setQueryFilters() {
                this.query = this.searchQuery.text;
                this.filters = this.searchQuery.filters;
            },
            sendAssessmentSelectedUsers(type) {
                this.sentText[type] = 'Sending...';
                if (this.sending) {
                    return;
                }
                this.sending = true;
                this.postSendAssessmentForUsers({type, ...this.filters})
                    .then((res) => {
                        if (res.success) {
                            this.showInfoToast({msg: 'Send successfully.', dt: 3500});
                        } else {
                            this.showInfoToast({msg: 'Maybe Assessments already send to selected users.', dt: 3500});
                        }
                        this.sending = false;
                        this.sentText[type] = 'Send';
                    }).catch((err) => {
                    //console.log(err);
                    this.sending = false;
                    this.sentText[type] = 'Send';
                })
            },
            sendAssessmentChildInstitutes(data) {
                let post = {
                    institute_assessment: this.filters.institute_assessment,
                    institutes: data
                };
                if (this.sending) {
                    return;
                }
                this.sending = true;
                this.postSendAssessmentForUsers({type: 'institute', ...post})
                    .then((res) => {
                        if (res.success) {
                            this.showInfoToast({msg: 'Send successfully.', dt: 3500});
                        } else {
                            this.showInfoToast({msg: 'Maybe Assessments already send to selected users.', dt: 3500});
                        }
                        this.sending = false;
                    }).catch((err) => {
                    //console.log(err);
                    this.sending = false;
                })
            }
        },
        created() {
            //check if need to set old query and filters
            if (this.$route.params.setQueryFilters) {
                this.setQueryFilters();
            }
            this.getOptions();
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
<style scoped>
    .admin-settings-cameo .multiselect {
        min-height: 43px;
    }
</style>
