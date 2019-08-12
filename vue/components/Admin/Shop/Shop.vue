<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :searchPlaceholder="'Search by institute name, short name, or Email'"
                                       :searchButtonText="'Add Category'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the member information correctly.'"
                                       :isSearch="isSearch"
                                       :isLoading="validated().loading_searches"
                                       :formTopState="formTopState"
                                       @onItemPerPageClick="getItems"
                                       @onSearchActionButton="toggleFormTop(true)"
                                       @onSearchReLoadButtonClick="getItems"
                                       @onSearchInputEnter="getItems"
                                       @onSearchInputClose="getItems"
                                       @onRemoveChipText="getItems"
                                       :paginateData="paginate"
                                       @paginateNavigate="paginateNavigator"
                                       @onMenuContextClick="showModalAction">
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
                    <div class="form-label"> User Account</div>
                    <div class="form-input-static-value"> {{ modal.data.email }}</div>
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
                    <div class="form-label"> User Account</div>
                    <div class="form-input-static-value"> {{ modal.data.email }}</div>
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
        name: "all-institute",
        data() {
            return {
                title: 'ສະຖານການສຶກສາ',
                type: 'users_institute',
                watchers: true,
                tabs: [{name: 'Institutes'}],
                headers: [
                    {class: 'th-sortable', name: 'Name', width: '150'},
                    {class: 'hide-xs th-sortable', name: 'Short Name', width: '120'},
                    {class: 'hide-xs th-sortable', name: 'Category', width: '15%'},
                    {class: 'hide-xs hide-md th-sortable', name: 'Image', width: '10%'},
                    {class: 'hide-xs th-sortable', name: 'Status', width: '15%'},
                    {class: 'hide-xs th-sortable', name: 'Created At', width: '25%'},
                    {class: 'th-not-sortable', name: '', width: '80'},
                ],
            }
        },
        methods: {
            ...mapActions(['postChangeUserStatus', 'postDeleteUser', 'postRegisterUser', 'postSendUserResetPasswordLink']),
            callbackBuildItem(data) {
                let contextMenu, userStatusMenu;
                contextMenu = [
                    {
                        name: 'Reset password',
                        active: false,
                        type: 'info',
                        message: `<p> Send an email to get reset password link, <a>the email will send to the user email.</a></p>`,
                        action: {act: this.postSendUserResetPasswordLink, text: 'Send'},
                        data: data
                    },
                    {
                        name: 'Delete Account',
                        active: false,
                        type: 'warning',
                        message: `When you delete the account, the account will be permanently deleted and cannot be un-deleted.`,
                        action: {act: this.postDeleteUser, text: 'Delete'},
                        data: data,
                    }
                ];

                //set user status menu
                if (data.status === 'approved') {
                    userStatusMenu = {
                        name: 'Disable Account',
                        active: false,
                        type: 'warning',
                        message: `Users with disabled accounts cannot sign in.`,
                        action: {act: this.postChangeUserStatus, params: {status: 'disabled'}, text: 'Disable'},
                        data: data
                    }
                } else if (data.status === 'disabled') {
                    userStatusMenu = {
                        name: 'Enable Account',
                        active: false,
                        type: 'info',
                        message: `<p>Users with enabled accounts can sign in.</p>`,
                        action: {act: this.postChangeUserStatus, params: {status: 'approved'}, text: 'Enable'},
                        data: data

                    }
                } else {
                    userStatusMenu = {
                        name: 'Approve Account',
                        active: false,
                        type: 'info',
                        message: `<p>Approve the user account then the user can sign in and <a>user can start create profile.</p>`,
                        action: {act: this.postChangeUserStatus, params: {status: 'approved'}, text: 'Approve'},
                        data: data
                    }
                }
                //set user status menu
                //add user menu status
                contextMenu.splice(1, 0, userStatusMenu);//add item at second position
                //add user menu status
                return {
                    rowContent: {},
                    rows: [
                        {data: data.institute_name, type: 'text', class: 'user-email'},
                        {data: data.short_institute_name, type: 'text', class: 'hide-xs'},
                        {data: data.category, type: 'id', class: 'hide-xs'},
                        {
                            data: `${this.baseUrl}${data.image}`,
                            type: 'image',
                            class: 'hide-xs hide-md'
                        },
                        {
                            data: this.$utils.firstUpper(data.status),
                            type: 'text',
                            class: 'hide-xs',
                            textColor: data.statusColor,
                        },
                        {data: this.$utils.formatTimestmp(data.created_at), type: 'text', class: 'hide-xs'},
                        {
                            data: data.email, type: 'action', class: '',
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
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
