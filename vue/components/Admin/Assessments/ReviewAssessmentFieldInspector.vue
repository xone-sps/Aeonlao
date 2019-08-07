<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :searchButtonText="'Go Back'"
                                       :searchPlaceholder="'Search by title, or year'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the assessment information correctly.'"
                                       :isSearch="isSearch"
                                       :isLoading="validated().loading_searches"
                                       :formTopState="formTopState"
                                       @onItemPerPageClick="getItems"
                                       @onSearchActionButton="Route({name: 'review-assessment'})"
                                       @onSearchReLoadButtonClick="getItems"
                                       @onSearchInputEnter="getItems"
                                       @onSearchInputClose="getItems"
                                       @onRemoveChipText="getItems"
                                       :paginateData="paginate"
                                       @paginateNavigate="paginateNavigator"
                                       @onMenuContextClick="showModalAction">
                            <!--Slot Actions row context-->
                            <template slot-scope="{fireEvent, position, data}" slot="action-row-context">
                                <button
                                    @click="Route({name: 'review-assessments-field-inspector-single', params: { check_assessment_id: data.row.data.id }, query: {user_id: data.row.data.user_id, institute_id: data.row.data.check_user_id, check_assessment_id: $route.query.check_assessment_id} })"
                                    class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">description</i>
                                </button>
                            </template>
                            <!--Slot Actions row context-->
                        </TablePaginate>
                        <!--Table card-->
                    </div>
                </div>
            </div>
        </div>
        <!--Modals -->
        <!--info-->
        <AdminModal :isActive="modal.type==='info' && modal.active" @close="modal.active=false">
            <template slot="title"> {{modal.name}}</template>
            <div class="fb-dialog-body-section">
                <div v-html="modal.message"></div>
                <div>
                    <div class="form-label"> Assessment Info</div>
                    <div class="form-input-static-value"> {{ modal.data.title }}</div>
                </div>
            </div>
            <template slot="actions">
                <button @click="positiveAction()" class="v-md-button primary"> {{modal.action.text}}</button>
            </template>
        </AdminModal>
        <!--info -->
        <!--warning-->
        <AdminModal :isActive="modal.type==='warning' && modal.active" @close="modal.active=false">
            <template slot="title"> {{modal.name}}</template>
            <div class="fb-dialog-body-section">
                <div class="body-message-container has-icon is-warning">
                    <div class="inner">
                        <i class="material-icons m-icon">warning</i>
                        <div class="admin-modal-message">{{ modal.message }}</div>
                    </div>
                </div>
                <div>
                    <div class="form-label"> Assessment Info</div>
                    <div class="form-input-static-value"> {{ modal.data.title }}</div>
                </div>
            </div>
            <template slot="actions">
                <button @click="positiveAction()" class="v-md-button warning"> {{ modal.action.text }}</button>
            </template>
        </AdminModal>
        <!--warning -->
        <!--Modals -->
    </div>
</template>

<script>
    import AdminBase from '@bases/AdminBase.js'
    import {mapActions} from 'vuex'

    export default AdminBase.extend({
        name: "ReviewAssessmentsFieldInspector",
        data() {
            return {
                title: 'Review Assessments Field Inspector',
                type: 'check_assessments_field_inspector',
                watchers: true,
                tabs: [{name: 'Review Assessments Field Inspector'}],
                headers: [
                    {class: 'th-sortable', name: 'Title', width: '180'},
                    {class: 'hide-xs th-sortable', name: 'Status', width: '10%'},
                    {class: 'hide-xs th-sortable', name: 'Institute', width: '160'},
                    {class: 'hide-xs th-sortable', name: 'Updated At', width: '15%'},
                    {class: 'th-not-sortable', name: '', width: '80'},
                ],
            }
        },
        methods: {
            ...mapActions(['postUpdateStatusCheckAssessment']),
            callbackBuildItem(data) {
                let contextMenu, itemStatusMenu;
                contextMenu = [];
                //set user status menu
                if (data.status === 'checking') {
                    itemStatusMenu = {
                        name: 'Make as Close',
                        active: false,
                        type: 'warning',
                        message: `Check Assessments with closed status, other users cannot checking them.`,
                        action: {
                            act: this.postUpdateStatusCheckAssessment,
                            params: {status: 'close'},
                            text: 'Close'
                        },
                        data: data
                    }
                } else if (data.status === 'close' || data.status === 'success') {
                    itemStatusMenu = {
                        name: 'Make as Checking',
                        active: false,
                        type: 'info',
                        message: `Check Assessments with checking status, other users can checking them.`,
                        action: {
                            act: this.postUpdateStatusCheckAssessment,
                            params: {status: 'checking'},
                            text: 'Check'
                        },
                        data: data
                    }
                }
                //set item status menu
                //add item menu status
                if (itemStatusMenu) {
                    contextMenu.splice(0, 0, itemStatusMenu);//add item at second position
                    if (data.status === 'checking') {
                        itemStatusMenu = {
                            name: 'Make as Success',
                            active: false,
                            type: 'info',
                            message: `Check Assessments with success status, other users can see them and means it's successfully.`,
                            action: {
                                act: this.postUpdateStatusCheckAssessment,
                                params: {status: 'success'},
                                text: 'Success'
                            },
                            data: data
                        };
                        contextMenu.splice(0, 0, itemStatusMenu);//add item at second position
                    }
                }
                contextMenu.splice(contextMenu.length, 0, {
                    name: 'Export Check Assessment',
                    active: false,
                    type: 'info',
                    message: `Save as Microsoft Office Word File.`,
                    action: {act: this.downloadExportFile, params: {}, text: 'Export'},
                    data: data
                });
                //add item menu status
                return {
                    rowContent: {
                        data: data,
                    },
                    rows: [
                        {data: data.title, type: 'id', class: 'user-email'},
                        {
                            data: this.$utils.firstUpper(data.status),
                            type: 'text',
                            class: 'hide-xs',
                            textColor: data.statusColor,
                        },
                        {data: data.check_institute_name, type: 'text', class: 'hide-xs'},
                        {data: this.$utils.formatTimestmp(data.updated_at), type: 'text', class: 'hide-xs'},
                        {
                            data: data.title, type: 'action', class: '',
                            contextMenu
                        },
                    ]
                }
            },
            showModalAction(m) {
                m.active = true; //close modal on menu context clicked
                this.modal = m;
            },
            //positive action for modal menu context
            positiveAction() {
                this.modal.active = false;//close modal on positive button clicked
                let action = this.modal.action, dt = 3500, //dt is toasted show length in time
                    data = {...this.modal.data, ...action.params}; //set data from modal
                if (action.act) {//@important action.act must non native functions
                    if (!(action.act instanceof Promise)) {
                        action.act({id: data.id, data});
                        return;
                    }
                    action.act({id: data.id, data})
                        .then(r => {
                            if (r.success) {
                                this.showInfoToast({msg: r.message, dt});
                                this.getItems();
                            } else {
                                this.showErrorToast({msg: r.message, dt});
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({msg: 'The action failed!', dt});
                        });
                }
            },
        },
        created() {
            this.options_request.check_assessment_id = this.$route.query.check_assessment_id;
            this.options_request.field_inspector_id = this.$route.query.field_inspector_id;
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
