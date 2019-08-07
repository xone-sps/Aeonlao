<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :searchPlaceholder="'Search by title, or year'"
                                       :searchButtonText="'Add Assessment'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the assessment information correctly.'"
                                       :isSearch="isSearch"
                                       :isLoading="validated().loading_searches"
                                       :formTopState="formTopState"
                                       @onItemPerPageClick="getItems"
                                       @onSearchActionButton="Route({name: 'create-assessment'})"
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
                                    @click="Route({name: 'create-assessment', query: { assessment_id: data.row.data.id } })"
                                    class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">edit</i>
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
        name: "AllAssessments",
        data() {
            return {
                title: 'ບົດປະເມີນທັງໝົດ',
                type: 'assessments',
                watchers: true,
                tabs: [{name: 'Assessments'}],
                headers: [
                    {class: 'th-sortable', name: 'Title', width: '200'},
                    {class: 'hide-xs th-sortable', name: 'Status', width: '15%'},
                    {class: 'hide-xs th-sortable', name: 'Created At', width: '25%'},
                    {class: 'hide-xs th-sortable', name: 'Updated At', width: '25%'},
                    {class: 'th-not-sortable', name: '', width: '80'},
                ],
            }
        },
        methods: {
            ...mapActions(['postDeleteAssessment', 'postUpdateStatusAssessment']),
            callbackBuildItem(data) {
                let contextMenu, itemStatusMenu;
                contextMenu = [
                    {
                        name: 'Delete Assessment',
                        active: false,
                        type: 'warning',
                        message: `When you delete the assessment, the assessment will be permanently deleted and cannot be un-deleted.`,
                        action: {act: this.postDeleteAssessment, text: 'Delete'},
                        data: data,
                    }
                ];
                //set user status menu
                if (data.status === 'open' || data.status === 'opening') {
                    itemStatusMenu = {
                        name: 'Close Assessment',
                        active: false,
                        type: 'warning',
                        message: `Assessments with closed status, other users cannot checking them.`,
                        action: {act: this.postUpdateStatusAssessment, params: {status: 'close'}, text: 'Close'},
                        data: data
                    }
                } else if (data.status === 'close') {
                    itemStatusMenu = {
                        name: 'Open Assessment',
                        active: false,
                        type: 'info',
                        message: `Assessments with opened status, other users can checking them.`,
                        action: {act: this.postUpdateStatusAssessment, params: {status: 'open'}, text: 'Open'},
                        data: data
                    }
                }
                //set item status menu
                //add item menu status
                if (itemStatusMenu) {
                    contextMenu.splice(0, 0, itemStatusMenu);//add item at second position
                    if (data.status === 'opening') {
                        itemStatusMenu = {
                            name: 'Make Success',
                            active: false,
                            type: 'info',
                            message: `Assessments with success status, other users cannot checking them and means it's successfully.`,
                            action: {act: this.postUpdateStatusAssessment, params: {status: 'success'}, text: 'Success'},
                            data: data
                        };
                        contextMenu.splice(0, 0, itemStatusMenu);//add item at second position
                    }
                }
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
                        {data: this.$utils.formatTimestmp(data.created_at), type: 'text', class: 'hide-xs'},
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
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
