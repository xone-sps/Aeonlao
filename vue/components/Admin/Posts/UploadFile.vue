<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <TablePaginate
                            v-model="query"
                            :searchPlaceholder="'Search by file name'"
                            :searchButtonText="'Add File'"
                            :headers="headers"
                            :notFoundText="'Please make sure you type or spell the information correctly.'"
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
                                    <div class="user-form-title">Add File</div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.fileName"
                                            :validateText="validated().fileName"
                                            :focus="true"
                                            :label="'File name*'"
                                            :inputType="'text'"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            @inputImageBase64="(d)=> models.formTop.imageSrc=d"
                                            @inputFile="(d)=> models.formTop.file=d"
                                            :validateText="validated().file"
                                            :label="'File *'"
                                            :inputType="'file'"
                                            placeholder="Choose file type"
                                        ></AdminInput>
                                    </div>
                                    <div class="user-form-action layout-align-end-center layout-row">
                                        <button
                                            @click="toggleFormTop(false)"
                                            class="v-md-button secondary theme-blue"
                                        >Cancel
                                        </button>
                                        <button @click="add" class="v-md-button primary theme-blue">Create</button>
                                    </div>
                                </form>
                            </template>
                            <!--Slot Form Top -->
                            <!--Slot Actions row -->
                            <template slot-scope="{fireEvent, position, data}" slot="action-row">
                                <button
                                    @click="downloadFile(data.column)"
                                    class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">cloud_download</i>
                                </button>
                                <button
                                    @click="toggleFormRowContent(fireEvent, position, {active: true})"
                                    class="v-md-button v-md-icon-button">
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
                                        v-model="rowContent.data.fileName"
                                        :validateText="rowContent.validated.fileName"
                                        :label="'File name*'"
                                        :inputType="'text'"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.filePath"
                                        @inputFile="(d) => rowContent.data.file= d"
                                        :label="'File *'"
                                        :validateText="rowContent.validated.file"
                                        :inputType="'file'"
                                        placeholder="Choose file type"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div
                                    class="user-form-action provider-list-actions layout-align-end-center layout-row"
                                >
                                    <button
                                        @click="toggleFormRowContent(fireEvent, position, {active: false})"
                                        class="v-md-button secondary theme-blue"
                                    >Cancel
                                    </button>
                                    <button
                                        @click="edit(fireEvent, rowContent.data, position)"
                                        class="v-md-button primary theme-blue"
                                    >Save
                                    </button>
                                </div>
                            </template>
                            <!--Slot Row Detail Content-->
                        </TablePaginate>
                        <!--Modal-->
                        <!--warning-->
                        <AdminModal
                            :isActive="modal.type==='warning' && modal.active"
                            @close="modal.active=false">
                            <template slot="title">{{modal.name}}</template>
                            <div class="fb-dialog-body-section">
                                <div class="body-message-container has-icon is-warning">
                                    <div class="inner">
                                        <i class="material-icons m-icon">warning</i>
                                        <div class="admin-modal-message">{{ modal.message }}</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-label">File Information</div>
                                    <div
                                        class="form-input-static-value"
                                    >{{ `File Name: ${modal.data.fileName}`}}
                                    </div>
                                </div>
                            </div>
                            <template slot="actions">
                                <button
                                    @click="positiveAction()"
                                    class="v-md-button warning"
                                >{{ modal.action.text }}
                                </button>
                            </template>
                        </AdminModal>
                        <!--warning -->
                        <!--Modal -->
                    </div>
                </div>
            </div>
        </div>
        <iframe id="file_download" v-show="false"></iframe>
    </div>
</template>
<script>
    import AdminBase from "@bases/AdminBase.js";
    import {mapActions} from "vuex";

    export default AdminBase.extend({
        name: "UploadFile",
        data: () => ({
            title: "Upload File",
            type: "file",
            tabs: [{name: "Upload Files"}],
            watchers: true,
            headers: [
                {class: "th-sortable", name: "File name", width: "50%"},
                {class: "hide-xs th-sortable", name: "Uploaded", width: "20%"},
                {class: "th-not-sortable", name: "", width: "110"}
            ],
        }),
        methods: {
            ...mapActions(["postCreateFile", "postUpdateFile", "postDeleteFile"]),
            callbackBuildItem(data) {
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
                        {data: data.fileName, type: "id", class: "user-email"},
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
                                name: "Delete Banner",
                                active: false,
                                type: "warning",
                                message: `When you delete the file, the file will be permanently deleted and cannot be un-deleted.`,
                                action: {
                                    act: this.postDeleteFile,
                                    text: "Delete"
                                },
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
                                this.showInfoToast({msg: "The file was deleted!", dt});
                                this.getItems();
                            } else {
                                this.showErrorToast({
                                    msg: "Failed to delete the file!",
                                    dt
                                });
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({
                                msg: "Failed to delete the file!",
                                dt
                            });
                        });
                }
            },
            add() {
                let ft = this.formTopState;
                ft.loading = true;
                this.postCreateFile(this.models.formTop)
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
            edit(fireEvent, data, position) {
                let dt = 3500,
                    v = this.paginate.items[position.row].rowContent;
                this.toggleFormRowContent(
                    fireEvent,
                    position,
                    this.Event.loadingState().ActiveLoading
                );
                data.id = v.data.id;
                this.postUpdateFile(data)
                    .then(r => {
                        if (r.success) {
                            this.showInfoToast({msg: "The file was updated!", dt});
                            this.getItems();
                        } else {
                            this.showErrorToast({
                                msg: "Failed to update the file!",
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
                                msg: "Failed to update the file!",
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
            downloadFile(data) {
                let file = data.modal.data , url = `${this.baseUrl}${file.folderPath}${file.realfilePath}`;
                this.$utils.downloadURL(url, 'file_download');
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
