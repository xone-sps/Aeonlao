<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate
                            v-model="query"
                            :searchPlaceholder="'Search by name, description'"
                            :searchButtonText="'Add '"
                            :headers="headers"
                            :notFoundText="'Please make sure you type or spell information correctly.'"
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
                        >
                            <!--Slot Form Top -->
                            <template slot="form-top" v-if="formTopState.show">
                                <form class="admin-form-card user-form" @submit.prevent>
                                    <div class="user-form-title">Create new Sponsor</div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.name"
                                            :focus="true"
                                            :validateText="validated().name"
                                            :label="'Sponsor name*'"
                                            :inputType="'text'"
                                        />
                                    </div>
                                     <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.link"
                                            :label="'Link'"
                                            :inputType="'text'"
                                            :placeholder="'https://www.google.com'"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.description"
                                            :label="'Description'"
                                            :inputType="'textarea'"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            @inputImageBase64="(d)=> models.formTop.imageSrc=d"
                                            @inputFile="(d)=> models.formTop.image=d"
                                            :validateText="validated().image"
                                            :label="'Feature Image*'"
                                            :inputType="'file'"
                                            placeholder="Choose feature image"
                                        ></AdminInput>
                                    </div>
                                    <div v-if="models.formTop.imageSrc"
                                         class="layout-align-space-around-start layout-row">
                                        <div class="box">
                                            <div class="media-centered">
                                                <figure class="image is-128x128">
                                                    <img class="sponsor" :src="models.formTop.imageSrc">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-form-action layout-align-end-center layout-row">
                                        <button
                                            @click="toggleFormTop(false)"
                                            class="v-md-button secondary theme-blue"
                                        >Cancel
                                        </button>
                                        <button @click="addSponsor" class="v-md-button primary theme-blue">Create</button>
                                    </div>
                                </form>
                            </template>
                            <!--Slot Form Top -->
                            <!--Slot Actions row -->
                            <template slot-scope="{fireEvent, position, data}" slot="action-row">
                                <button
                                    @click="toggleFormRowContent(fireEvent, position, {active: true})"
                                    class="v-md-button v-md-icon-button"
                                >
                                    <i class="material-icons v-icon">edit</i>
                                </button>
                                <button @click="deleteItem(data.column)" class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">delete</i>
                                </button>
                            </template>
                            <!--Slot Actions row-->
                            <!--Slot Row Detail Content-->
                            <template slot-scope="{fireEvent, position, rowContent}" slot="form-row-detail">
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.name"
                                        :validateText="rowContent.validated.name"
                                        :label="'Sponsor name'"
                                        :inputType="'text'"
                                        @onInputEnter="editSponsor(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                          <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="rowContent.data.link"
                                            :label="'Link'"
                                            :inputType="'text'"
                                            :placeholder="'https://www.google.com'"
                                            @onInputEnter="editSponsor(fireEvent, rowContent.data, position)"
                                        />
                                    </div>
                                <div class="layout-align-space-around-start layout-row">
                                            <AdminInput
                                            v-model="rowContent.data.description"
                                            :label="'Description'"
                                            :inputType="'textarea'"
                                        />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.filename"
                                        @inputImageBase64="(d)=> rowContent.options.imageSrc=d"
                                        @inputFile="(d) => rowContent.data.image = d"
                                        :label="'Feature Image'"
                                        :validateText="rowContent.validated.image"
                                        :inputType="'file'"
                                        placeholder="Choose feature image"
                                        @onInputEnter="editNews(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <div class="box">
                                        <div class="media-centered ">
                                            <figure class="image is-128x128">
                                                <img class="sponsor" v-if="rowContent.options.imageSrc"
                                                     :src="rowContent.options.imageSrc">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="user-form-action provider-list-actions layout-align-end-center layout-row">
                                    <button
                                        @click="toggleFormRowContent(fireEvent, position, {active: false})"
                                        class="v-md-button secondary theme-blue">Cancel
                                    </button>
                                    <button
                                        @click="editSponsor(fireEvent, rowContent.data, position)"
                                        class="v-md-button primary theme-blue">Save
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
            <template slot="title">{{modal.name}}</template>
            <div class="fb-dialog-body-section">
                <div class="body-message-container has-icon is-warning">
                    <div class="inner">
                        <i class="material-icons m-icon">warning</i>
                        <div class="admin-modal-message">{{ modal.message }}</div>
                    </div>
                </div>
                <div>
                    <div class="form-label">Sponsor Information</div>
                    <div class="form-input-static-value">
                        {{ `Name: ${modal.data.name}`
                        }}
                    </div>
                </div>
            </div>
            <template slot="actions">
                <button @click="positiveAction()" class="v-md-button warning">{{ modal.action.text }}</button>
            </template>
        </AdminModal>
        <!--warning -->
        <!--Modal -->
    </div>
</template>

<script>
    import AdminBase from "@bases/AdminBase.js";
    import {mapActions} from "vuex";

    export default AdminBase.extend({
        name: "sponsor",
        data: () => ({
            title: "Sponsor",
            type: "sponsor",
            watchers: true,
            tabs: [{name: "Sponsor"}],
            headers: [
                {class: "th-sortable", name: "Sponsor name", width: "40%"},
                {class: "hide-xs th-sortable", name: "Image", width: "10%"},
                {class: "hide-xs th-sortable", name: "Created", width: "20%"},
                {class: "th-not-sortable", name: "", width: "80"}
            ],
            models: {formTop: {imageSrc: null}},//override base data
        }),
        methods: {
            ...mapActions(["postCreateSponsor", "postUpdateSponsor", "postDeleteSponsor"]),
            callbackBuildItem(data) {
                //options data
                data.options = {imageSrc: `${this.baseUrl}${data.image}`};
                //options data
                return {
                    rowContent: {
                        validated: {},
                        state: {active: false, loading: false},
                        wholeEdit: true,
                        options: this.$utils.clone(data.options),
                        data: data,
                        originData: this.$utils.clone(data), //clone to separate data for object
                    },
                    rows: [
                        {data: data.name, type: "id", class: "user-email"},
                        {
                            data: `${this.baseUrl}${data.image}`,
                            type: "image",
                            class: "hide-xs"
                        },
                        {
                            data: data.created_at,
                            type: "text",
                            class: "hide-xs"
                        },
                        {
                            data: "",
                            type: "action",
                            class: "",
                            modal: {
                                name: "Delete Sponsor",
                                active: false,
                                type: "warning",
                                message: `When you delete the sponsor, the sponsor will be permanently deleted and cannot be un-deleted.`,
                                action: {act: this.postDeleteSponsor, text: "Delete"},
                                data: data
                            }
                        }
                    ]
                }
            },
            //positive action for modal buttons
            positiveAction() {
                this.modal.active = false; //close modal on positive button clicked
                let action = this.modal.action,
                    dt = 3500, //dt is toasted show length in time
                    data = this.modal.data; //set data from modal
                if (action.act) {
                    //@important action.act must non native functions
                    action
                        .act({id: data.id})
                        .then(r => {
                            if (r.success) {
                                this.showInfoToast({msg: "The sponsor was deleted!", dt});
                                this.getItems();
                            } else {
                                this.showErrorToast({
                                    msg: "Failed to delete the sponsor!",
                                    dt
                                });
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({
                                msg: "Failed to delete the sponsor!",
                                dt
                            });
                        });
                }
            },
            addSponsor() {
                let ft = this.formTopState;
                ft.loading = true;
                this.postCreateSponsor(this.models.formTop)
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
                    });
            },
            editSponsor(fireEvent, data, position) {
                let dt = 3500,
                    v = this.paginate.items[position.row].rowContent;
                this.toggleFormRowContent(
                    fireEvent,
                    position,
                    this.Event.loadingState().ActiveLoading
                );
                data.id = v.data.id;
                this.postUpdateSponsor(data)
                    .then(r => {
                        if (r.success) {
                            this.showInfoToast({msg: "The sponsor was updated!", dt});
                            this.getItems();
                        } else {
                            this.showErrorToast({
                                msg: "Failed to update the sponsor!",
                                dt
                            });
                        }
                        this.toggleFormRowContent(
                            fireEvent,
                            position,
                            this.Event.loadingState().NorActiveLoading
                        );
                    })
                    .catch(err => {
                        v.validated = {};
                        if (err && err.errors) {
                            this.toggleFormRowContent(
                                fireEvent,
                                position,
                                this.Event.loadingState().ActiveNotLoading
                            );
                            v.validated = this.validated();
                            this.setClearMsg();//clear validated
                            this.showInvalidFormValidation();
                        } else {
                            this.showErrorToast({
                                msg: "Failed to update the sponsor!",
                                dt
                            });
                            this.toggleFormRowContent(
                                fireEvent,
                                position,
                                this.Event.loadingState().NorActiveLoading
                            );
                        }
                    });
            },
            deleteItem(data) {
                data.modal.active = true;
                this.modal = data.modal;
            },
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>