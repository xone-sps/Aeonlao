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
                            :searchPlaceholder="'Search by title, description'"
                            :searchButtonText="'Add Scholarship'"
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
                            @paginateNavigate="paginateNavigator">
                            <!--Slot Form Top -->
                            <template slot="form-top" v-if="formTopState.show">
                                <form class="admin-form-card user-form" @submit.prevent>
                                    <div class="user-form-title">Create new Scholarship</div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.title"
                                            :focus="true"
                                            :validateText="validated().title"
                                            :label="'Scholarship name *'"
                                            :inputType="'text'"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.place"
                                            :validateText="validated().place"
                                            :label="'Scholarship place *'"
                                            :inputType="'text'"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            v-model="models.formTop.scholarship_type"
                                            :validateText="validated().scholarship_type"
                                            :label="'Scholarship type *'"
                                            :inputType="'select'"
                                            :options="scholarshipTypes"
                                        />
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <div class="form-input-container flex" full>
                                            <label>Deadline *</label>
                                            <Datetime
                                                @onOpenPopup="onDateTimeOpen"
                                                @close="onDateTimeClose"
                                                format="dd-MM-yyyy HH:mm"
                                                type="datetime"
                                                value-zone="Asia/Vientiane"
                                                zone="Asia/Vientiane"
                                                v-model="models.formTop.deadline"
                                                input-id="activity_date"
                                                :input-class="'admin-input-datepicker'"
                                            />
                                            <template v-if="validated().deadline">
                                                <div admin-messages>
                                                    <div admin-message class="message-required">{{ validated().deadline
                                                        }}
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput
                                            @inputImageBase64="(d)=> models.formTop.imageSrc=d"
                                            @inputFile="(d)=> models.formTop.image=d"
                                            :validateText="validated().image"
                                            :label="'Feature Image *'"
                                            :inputType="'file'"
                                            placeholder="Choose feature image"
                                        ></AdminInput>
                                    </div>
                                    <div v-if="models.formTop.imageSrc"
                                         class="layout-align-space-around-start layout-row">
                                        <div class="box">
                                            <div class="media-centered">
                                                <figure class="image is-128x128">
                                                    <img :src="models.formTop.imageSrc">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layout-align-space-around-start layout-row">
                                        <Editor
                                            v-model="models.formTop.description"
                                            style="padding-bottom: 24px;"
                                            :label="'Scholarship description *'"
                                            :validateText="validated().description"
                                        />
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
                                        v-model="rowContent.data.title"
                                        :validateText="rowContent.validated.title"
                                        :label="'Scholarship name *'"
                                        :inputType="'text'"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.place"
                                        :validateText="rowContent.validated.place"
                                        :label="'Scholarship Place *'"
                                        :inputType="'text'"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.scholarship_type"
                                        :validateText="rowContent.validated.scholarship_type"
                                        :label="'Scholarship type *'"
                                        :inputType="'select'"
                                        :options="scholarshipTypes"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <div class="form-input-container flex" full>
                                        <label>Deadline *</label>
                                        <Datetime
                                            v-model="rowContent.data.scholarship_deadline"
                                            @onOpenPopup="onDateTimeOpen"
                                            @close="onDateTimeClose"
                                            value-zone="Asia/Vientiane"
                                            zone="Asia/Vientiane"
                                            format="dd-MM-yyyy HH:mm"
                                            type="datetime"
                                            :input-id="`activity-date${rowContent.data.id}`"
                                            :input-class="'admin-input-datepicker'"
                                        />
                                        <template v-if="rowContent.validated.scholarship_deadline">
                                            <div admin-messages>
                                                <div
                                                    admin-message
                                                    class="message-required"
                                                >{{ rowContent.validated.scholarship_deadline }}
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <AdminInput
                                        v-model="rowContent.data.filename"
                                        @inputImageBase64="(d)=> rowContent.options.imageSrc=d"
                                        @inputFile="(d) => rowContent.data.image = d"
                                        :label="'Feature Image *'"
                                        :validateText="rowContent.validated.image"
                                        :inputType="'file'"
                                        placeholder="Choose feature image"
                                        @onInputEnter="edit(fireEvent, rowContent.data, position)"
                                    />
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <div class="box">
                                        <div class="media-centered">
                                            <figure class="image is-128x128">
                                                <img v-if="rowContent.options.imageSrc"
                                                     :src="rowContent.options.imageSrc">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <div class="layout-align-space-around-start layout-row">
                                    <Editor
                                        :id="`editor_news_${rowContent.data.id}`"
                                        v-model="rowContent.data.description"
                                        style="padding-bottom:24px;"
                                        :label="'Event description *'"
                                        @editorMounted="(ed)=> ed.setEditorContent(rowContent.data.description)"
                                        :validateText="rowContent.validated.description"
                                    />
                                </div>

                                <div
                                    class="user-form-action provider-list-actions layout-align-end-center layout-row">
                                    <button
                                        @click="toggleFormRowContent(fireEvent, position, {active: false})"
                                        class="v-md-button secondary theme-blue">Cancel
                                    </button>
                                    <!--Status Manage-->
                                    <button v-if="rowContent.data.status === 'open'"
                                            @click="manageStatus(fireEvent, rowContent.data, position, 'scholarship', 'close')"
                                            class="v-md-button warning theme-blue">Close
                                    </button>
                                    <!--Status Manage-->
                                    <button
                                        @click="edit(fireEvent, rowContent.data, position)"
                                        class="v-md-button primary theme-blue">Save
                                    </button>
                                    <!--Status Manage-->
                                    <button v-if="rowContent.data.status === 'pending'"
                                            @click="manageStatus(fireEvent, rowContent.data, position, 'scholarship', 'approve')"
                                            class="v-md-button primary theme-blue">Approve
                                    </button>
                                    <button v-else-if="rowContent.data.status === 'close'"
                                            @click="manageStatus(fireEvent, rowContent.data, position, 'scholarship', 'open')"
                                            class="v-md-button primary theme-blue">Open
                                    </button>
                                    <!--Status Manage-->
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
                    <div class="form-label">Scholarship Information</div>
                    <div
                        class="form-input-static-value"
                    >{{ `Title: ${modal.data.title}, Author: ${modal.data.author}`}}
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
        name: "Scholarship",
        data: () => ({
            title: "Scholarship",
            type: "scholarship",
            watchers: true,
            tabs: [{name: "Scholarship"}],
            headers: [
                {class: "th-sortable", name: "Scholarship name", width: "50%"},
                {class: "hide-xs th-sortable", name: "Deadline", width: "20%"},
                {class: "hide-xs th-sortable", name: "Image", width: "10%"},
                {class: "th-sortable", name: "Status", width: "100"},
                {class: "hide-xs th-sortable", name: "Created", width: "20%"},
                {class: "th-not-sortable", name: "", width: "80"}
            ],
            models: {formTop: {imageSrc: null}},//override base data
            scholarshipTypes: [{text: 'Partial', value: 'partial'}, {text: 'Full', value: 'full'}]
        }),
        methods: {
            ...mapActions([
                "postCreateScholarship",
                "postUpdateScholarship",
                "postDeleteScholarship"
            ]),
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
                        {data: data.title, type: "id", class: "user-email"},
                        {
                            data: data.deadline,
                            type: "text",
                            class: "hide-xs"
                        },
                        {
                            data: `${this.baseUrl}${data.image}`,
                            type: "image",
                            class: "hide-xs"
                        },
                        {
                            data: this.$utils.firstUpper(data.status),
                            type: "text",
                            textColor: data.statusColor,
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
                                name: "Delete scholarship",
                                active: false,
                                type: "warning",
                                message: `When you delete the scholarship, the scholarship will be permanently deleted and cannot be un-deleted.`,
                                action: {act: this.postDeleteScholarship, text: "Delete"},
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
                                this.showInfoToast({msg: "The scholarship was deleted!", dt});
                                this.getItems();
                            } else {
                                this.showErrorToast({
                                    msg: "Failed to delete the scholarship!",
                                    dt
                                });
                            }
                        })
                        .catch(e => {
                            this.showErrorToast({
                                msg: "Failed to delete the scholarship!",
                                dt
                            });
                        });
                }
            },
            add() {
                let ft = this.formTopState;
                ft.loading = true;
                this.postCreateScholarship(this.models.formTop)
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
                this.postUpdateScholarship(data)
                    .then(r => {
                        if (r.success) {
                            this.showInfoToast({msg: "The scholarship was updated!", dt});
                            this.getItems();
                        } else {
                            this.showErrorToast({
                                msg: "Failed to update the scholarship!",
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
                                msg: "Failed to update the scholarship!",
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
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>

