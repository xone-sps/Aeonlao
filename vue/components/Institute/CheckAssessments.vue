<template>
    <div>
        <Tabs :bgColor="theme.bgColor" :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas">
                    <div class="md-single-grid provider-list">
                        <!--Table card-->
                        <TablePaginate v-model="query"
                                       :showSearchButton="false"
                                       :searchPlaceholder="'Search by title, or year'"
                                       :headers="headers"
                                       :notFoundText="'Please make sure you type or spell the assessment information correctly.'"
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
                            <!--Slot Actions row context-->
                            <template slot-scope="{fireEvent, position, data}" slot="action-row">
                                <button
                                    @click="Route({name: 'check-assessment-single', params: { check_assessment_id: data.row.data.id } })"
                                    class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">description</i>
                                </button>

                                <button
                                    @click="downloadExportFile(data.row.data.id)"
                                    class="v-md-button v-md-icon-button">
                                    <i class="material-icons v-icon">save_alt</i>
                                </button>

                            </template>
                            <!--Slot Actions row context-->
                        </TablePaginate>
                        <!--Table card-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InstituteBase from '@bases/InstituteBase.js'
    import {mapActions} from 'vuex'

    export default InstituteBase.extend({
        name: "CheckAssessments",
        data() {
            return {
                title: 'My Assessments',
                type: 'check_assessments',
                watchers: true,
                tabs: [{name: 'My Assessments'}],
                headers: [
                    {class: 'th-sortable', name: 'Title', width: '200'},
                    {class: 'hide-xs th-sortable', name: 'Status', width: '15%'},
                    {class: 'hide-xs th-sortable', name: 'Sent At', width: '25%'},
                    {class: 'hide-xs th-sortable', name: 'Updated At', width: '25%'},
                    {class: 'th-not-sortable', name: '', width: '80'},
                ],
            }
        },
        methods: {
            ...mapActions(['postUpdateStatusCheckAssessment']),
            callbackBuildItem(data) {
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
                            data: data.title, type: 'action', class: '', contextMenu: []
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
