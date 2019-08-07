<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :searchPlaceholder="'Search by organize name'"
                                       :searchButtonText="'Add Organize'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the organize information correctly.'"
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
                                       @paginateNavigate="paginateNavigator">
                            <!--Slot Form Top -->
                            <template slot="form-top" v-if="formTopState.show">
                                <form class="admin-form-card user-form" @submit.prevent>
                                    <div class="user-form-title"> Create new organize</div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput v-model="models.formTop.name"
                                                    :focus="true"
                                                    :validateText="validated().name"
                                                    :label="'Organize Name'"
                                                    :inputType="'text'"
                                                    @onInputEnter="addOrganize"/>
                                    </div>

                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput v-model="models.formTop.government_organize"
                                                    :containerClass="'dense'"
                                                    :validateText="validated().government_organize"
                                                    :label="'Government Organization'"
                                                    :inputType="'checkbox'"
                                                    @onInputEnter="addOrganize">
                                        </AdminInput>
                                    </div>

                                    <div class="user-form-action layout-align-end-center layout-row">
                                        <button @click="toggleFormTop(false)"
                                                class="v-md-button secondary theme-blue">
                                            Cancel
                                        </button>
                                        <button @click="addOrganize" class="v-md-button primary theme-blue"> Create
                                        </button>
                                    </div>
                                </form>
                            </template>
                            <!--Slot Form Top -->
                            <!--Slot Actions row -->
                            <template slot-scope="{fireEvent, position, data}" slot="action-row">
                                <button @click="toggleFormRowContent(fireEvent, position, {active: true})"
                                        class="v-md-button v-md-icon-button"><i
                                    class="material-icons v-icon">edit</i></button>
                                <button @click="deleteOrganize(data.column)" class="v-md-button v-md-icon-button"><i
                                    class="material-icons v-icon">delete</i></button>
                            </template>
                            <!--Slot Actions row-->
                            <!--Slot Row Detail Content-->
                            <template slot-scope="{fireEvent, position, rowContent}" slot="form-row-detail">
                                    <AdminInput v-model="rowContent.data.name"
                                                :validateText="rowContent.validated.organize_name"
                                                :label="'Organize Name'"
                                                :focus="true"
                                                :inputType="'text'"
                                                @onInputEnter="editOrganize(fireEvent, rowContent.data, position)"/>

                                    <AdminInput v-model="rowContent.data.government_organize"
                                                :containerClass="'dense'"
                                                :validateText="rowContent.validated.government_organize"
                                                :label="'Government Organization'"
                                                :inputType="'checkbox'"
                                                @onInputEnter="editOrganize(fireEvent, rowContent.data, position)" />

                                <div class="user-form-action provider-list-actions layout-align-end-center layout-row">
                                    <button
                                        @click="toggleFormRowContent(fireEvent, position, {active: false})"
                                        class="v-md-button secondary theme-blue">
                                        Cancel
                                    </button>
                                    <button @click="editOrganize(fireEvent, rowContent.data, position)"
                                            class="v-md-button primary theme-blue">
                                        Save
                                    </button>
                                </div>
                            </template>
                            <!--Slot Row Detail Content-->
                        </TablePaginate>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal-->
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
                    <div class="form-label"> Organize Name</div>
                    <div class="form-input-static-value"> {{ modal.data.name }}</div>
                </div>
            </div>
            <template slot="actions">
                <button @click="positiveAction()" class="v-md-button warning"> {{ modal.action.text }}</button>
            </template>
        </AdminModal>
        <!--warning -->
        <!--Modal -->
    </div>
</template>

<script>
    import AdminBase from '@bases/AdminBase.js'
    import {mapActions} from 'vuex'

    export default AdminBase.extend({
        name: "organize",
        data: () => ({
            title: 'Organizations',
            type: 'organizes',
            watchers: true,
            tabs: [{name: 'Organizations'}],
            headers: [
                {class: 'th-sortable', name: 'Organize Name', width: '35%'},
                {class: "th-sortable", name: "Government Organization", width: "100"},
                {class: 'hide-xs th-sortable', name: 'Created At', width: '28%'},
                {class: 'hide-xs th-sortable', name: 'Updated At', width: '28%'},
                {class: 'th-not-sortable', name: '', width: '80'},
            ],
            models: {formTop: {government_organize: false}},
        }),
        methods: {
            ...mapActions(['postCreateOrganize', 'postUpdateOrganize', 'postDeleteOrganize']),
            callbackBuildItem(data) {
                return {
                    rowContent: {
                        validated: {},
                        state: {active: false, loading: false},
                        originData: this.$utils.clone(data), //clone to separate data for object
                        data: data,
                        wholeEdit: true
                    },
                    rows: [
                        {data: data.name, type: 'id', class: 'user-email'},
                        {data: data.government_organize ? 'Yes':'No', type: "text"},
                        {data: this.$utils.formatTimestmp(data.created_at), type: 'text', class: 'hide-xs'},
                        {data: this.$utils.formatTimestmp(data.updated_at), type: 'text', class: 'hide-xs'},
                        {
                            data: data.name, type: 'action', class: '',
                            modal: {
                                name: 'Delete Organize',
                                active: false,
                                type: 'warning',
                                message: `When you delete the organize, the organize will be permanently deleted and cannot be un-deleted.`,
                                action: {act: this.postDeleteOrganize, text: 'Delete'},
                                data: data,
                            }
                        },
                    ]
                }
            },
            //positive action for modal buttons
            positiveAction() {
                this.modal.active = false;//close modal on positive button clicked
                let action = this.modal.action, dt = 3500, //dt is toasted show length in time
                    data = this.modal.data; //set data from modal
                if (action.act) {//@important action.act must non native functions
                    action.act({id: data.id})
                        .then(r => {
                            if (r.success) {
                                this.showInfoToast({msg: 'The organize was deleted!', dt});
                                this.getItems();
                            } else {
                                this.showErrorToast({msg: 'Failed to delete the organize!', dt});
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({msg: 'Failed to delete the organize!', dt});
                        });
                }
            },
            addOrganize() {
                let ft = this.formTopState;
                ft.loading = true;
                this.postCreateOrganize(this.models.formTop)
                    .then(r => {
                        if (r.success) {
                            this.getItems();
                            ft.show = false;
                            this.models.formTop = {imageSrc: null};
                        }
                        ft.loading = false;
                    })
                    .catch(e => {
                        ft.loading = false;
                    })
            },
            editOrganize(fireEvent, data, position) {
                let dt = 3500, v = this.paginate.items[position.row].rowContent;
                data.organize_name = data.name;
                this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().ActiveLoading);
                this.postUpdateOrganize(data)
                    .then(r => {
                        if (r.success) {
                            this.showInfoToast({msg: 'The organize was updated!', dt});
                            this.getItems();
                        } else {
                            this.showErrorToast({msg: 'Failed to update the organize!', dt});
                        }
                        this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().NorActiveLoading);
                    })
                    .catch(err => {
                        v.validated = {};
                        if ((err && err.errors) || (err.response && err.response.data && err.response.data.errors)) {
                            this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().ActiveNotLoading);
                            v.validated = this.validated();//get validated data from state mutation
                        } else {
                            this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().NorActiveLoading);
                            this.showErrorToast({msg: 'Failed to update the organize!', dt});
                        }
                    });
            },
            deleteOrganize(data) {
                data.modal.active = true;
                this.modal = data.modal;
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
