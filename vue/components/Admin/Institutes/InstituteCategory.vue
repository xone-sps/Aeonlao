<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :searchPlaceholder="'Search by category name'"
                                       :searchButtonText="'Add Category'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the category information correctly.'"
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
                                    <div class="user-form-title"> Create new category</div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput v-model="models.formTop.name"
                                                    :focus="true"
                                                    :validateText="validated().name"
                                                    :label="'Category Name'"
                                                    :inputType="'text'"
                                                    @onInputEnter="addItem"/>
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput v-model="models.formTop.have_parent"
                                                    :containerClass="'dense'"
                                                    :validateText="validated().have_parent"
                                                    :label="'Have Parent'"
                                                    :inputType="'checkbox'"
                                                    @onInputEnter="addItem">
                                        </AdminInput>
                                    </div>

                                    <div v-if="models.formTop.have_parent"
                                         class="layout-align-space-around-start layout-row"
                                         style="padding-bottom: 32px;">
                                        <div class="form-multi-select-container flex dense" full>
                                            <label>Parent Category</label>
                                            <multiselect class="select-multiple"
                                                         v-model="models.formTop.parent_categories"
                                                         label="name" track-by="id"
                                                         placeholder="Select parent categories"
                                                         open-direction="bottom"
                                                         :options="parentCategories"
                                                         :limit="10"
                                                         :limit-text="limitText"
                                                         :multiple="true"
                                                         :show-no-results="true"
                                                         :clear-on-select="false"
                                                         :close-on-select="false"
                                                         :preserve-search="true"
                                                         :hide-selected="true">
                                            </multiselect>

                                            <template v-if="validated().parent_categories">
                                                <div class="form-input-container">
                                                    <input v-show="false"/>
                                                    <div admin-messages>
                                                        <div admin-message
                                                             class="message-required ">
                                                            {{ validated().parent_categories }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                        </div>
                                    </div>

                                    <div class="user-form-action layout-align-end-center layout-row">
                                        <button @click="toggleFormTop(false)"
                                                class="v-md-button secondary theme-blue">
                                            Cancel
                                        </button>
                                        <button @click="addItem" class="v-md-button primary theme-blue"> Create
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
                                <button @click="deleteItem(data.column)" class="v-md-button v-md-icon-button"><i
                                    class="material-icons v-icon">delete</i></button>
                            </template>
                            <!--Slot Actions row-->
                            <!--Slot Row Detail Content-->
                            <template slot-scope="{fireEvent, position, rowContent}" slot="form-row-detail">
                                <AdminInput v-model="rowContent.data.name" class="provider-edit-inset-content"
                                            :validateText="rowContent.validated.category_name"
                                            :label="'Category Name'"
                                            :inputType="'text'"
                                            @onInputEnter="editItem(fireEvent, rowContent.data, position)"/>

                                <AdminInput v-model="rowContent.data.have_parent" class="provider-edit-inset-content"
                                            :containerClass="'dense'"
                                            :validateText="rowContent.validated.have_parent"
                                            :label="'Have Parent'"
                                            :inputType="'checkbox'"
                                            @onInputEnter="editItem(fireEvent, rowContent.data, position)"/>

                                <div v-if="rowContent.data.have_parent"
                                     style="padding-bottom: 32px;">
                                    <div class="form-multi-select-container flex dense provider-edit-inset-content" full >
                                        <label>Parent Category</label>
                                        <multiselect class="select-multiple"
                                                     v-model="rowContent.data.parent_categories"
                                                     label="name" track-by="id"
                                                     placeholder="Select parent categories"
                                                     open-direction="bottom"
                                                     :options="parentCategories.filter((i)=> {return i.id !== rowContent.data.id})"
                                                     :limit="10"
                                                     :limit-text="limitText"
                                                     :multiple="true"
                                                     :show-no-results="true"
                                                     :clear-on-select="false"
                                                     :close-on-select="false"
                                                     :preserve-search="true"
                                                     :hide-selected="true">
                                        </multiselect>

                                        <template v-if="rowContent.validated.parent_categories">
                                            <div class="form-input-container">
                                                <input v-show="false"/>
                                                <div admin-messages>
                                                    <div admin-message
                                                         class="message-required ">
                                                        {{ rowContent.validated.parent_categories }}
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                </div>



                                <div class="user-form-action provider-list-actions layout-align-end-center layout-row">
                                    <button
                                        @click="toggleFormRowContent(fireEvent, position, {active: false})"
                                        class="v-md-button secondary theme-blue">
                                        Cancel
                                    </button>
                                    <button @click="editItem(fireEvent, rowContent.data, position)"
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
                    <div class="form-label"> Category Name</div>
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
        name: "InstituteCategory",
        data: () => ({
            title: 'ໝວດໝູ່ສະຖານການສຶກສາ',
            type: 'institute_category',
            watchers: true,
            tabs: [{name: 'Institute Categories'}],
            headers: [
                {class: 'th-sortable', name: 'Category Name', width: '35%'},
                {class: "th-sortable", name: "Have Parent", width: "110"},
                {class: 'hide-xs th-sortable', name: 'Created At', width: '28%'},
                {class: 'hide-xs th-sortable', name: 'Updated At', width: '28%'},
                {class: 'th-not-sortable', name: '', width: '80'},
            ],
            models: {formTop: {have_parent: false}},
            parentCategories: [],
        }),
        methods: {
            ...mapActions(['postCreateInstituteCategory', 'postUpdateInstituteCategory', 'postDeleteInstituteCategory',
                'fetchInstituteCategories']),
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
                        {data: data.have_parent ? 'Yes' : 'No', type: "text"},
                        {data: this.$utils.formatTimestmp(data.created_at), type: 'text', class: 'hide-xs'},
                        {data: this.$utils.formatTimestmp(data.updated_at), type: 'text', class: 'hide-xs'},
                        {
                            data: data.name, type: 'action', class: '',
                            modal: {
                                name: 'Delete Category',
                                active: false,
                                type: 'warning',
                                message: `When you delete the category, the category will be permanently deleted and cannot be un-deleted.`,
                                action: {act: this.postDeleteInstituteCategory, text: 'Delete'},
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
                                this.showInfoToast({msg: 'The Category was deleted!', dt});
                                this.getItems();
                                this.getParentCategories();
                            } else {
                                this.showErrorToast({msg: 'Failed to delete the category!', dt});
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({msg: 'Failed to delete the category!', dt});
                        });
                }
            },
            addItem() {
                let ft = this.formTopState;
                ft.loading = true;
                this.postCreateInstituteCategory(this.models.formTop)
                    .then(r => {
                        if (r.success) {
                            this.getItems();
                            this.getParentCategories();
                            ft.show = false;
                            this.models.formTop = {imageSrc: null, have_parent: false};
                        }
                        ft.loading = false;
                    })
                    .catch(e => {
                        ft.loading = false;
                    })
            },
            editItem(fireEvent, data, position) {
                let dt = 3500, v = this.paginate.items[position.row].rowContent;
                data.category_name = data.name;
                this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().ActiveLoading);
                this.postUpdateInstituteCategory(data)
                    .then(r => {
                        if (r.success) {
                            this.showInfoToast({msg: 'The category was updated!', dt});
                            this.getItems();
                            this.getParentCategories();
                        } else {
                            this.showErrorToast({msg: 'Failed to update the category!', dt});
                        }
                        this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().NorActiveLoading);
                    })
                    .catch(err => {
                        v.validated = {};
                        if ((err && err.errors) || (err.response && err.response.data && err.response.data.errors)) {
                            this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().ActiveNotLoading);
                            v.validated = this.validated();
                        } else {
                            this.toggleFormRowContent(fireEvent, position, this.Event.loadingState().NorActiveLoading);
                            this.showErrorToast({msg: 'Failed to update the category!', dt});
                        }
                    });
            },
            deleteItem(data) {
                data.modal.active = true;
                this.modal = data.modal;
            },
            getParentCategories() {
                this.fetchInstituteCategories()
                    .then(res => {
                        this.parentCategories = res.data.filter((i) => {
                            return i.have_parent === 'no';
                        })
                    });
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
            this.getParentCategories();
        }
    })
</script>
