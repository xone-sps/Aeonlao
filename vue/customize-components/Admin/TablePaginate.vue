<template>
    <div ad-cell="12" class="admin-card theme-blue">
        <!--Search input-->
        <div class="in-search-input zi">
            <div class="in search-input-toolbar" :class="[ isShowInputSearch ? 'editing': '']">
                <!--editing-->
                <div class="in-input-show layout-row">
                    <i @click="toggleShowInputSearch" class="material-icons in-input v-icon in-icon">search</i>
                    <span
                        @click="toggleShowInputSearch"
                        class="in-input-toggle in-placeholder"
                        :style="showChip ? 'display:none;': ''"
                    >{{ searchPlaceholder }}</span>
                    <!--chip -->
                    <div class="layout-align-start-center layout-row" :style="showChip ? '': 'display:none;'">
                        <div class="admin-chip has-action">
                            <div @click="toggleShowInputSearch" class="admin-chip-content">{{ searchInputText }}</div>
                            <button @click="removeChipText" class="v-md-button v-md-icon-button chip-action">
                                <i class="material-icons">cancel</i>
                            </button>
                        </div>
                    </div>
                    <!--end chip-->
                    <span @click="toggleShowInputSearch" class="in-input-toggle flex"></span>
                    <div class="input-actions layout-row layout-align-start-center">
                        <div class="button-wrapper" v-if="showSearchButton">
                            <button
                                @click="emitSearchActionButton"
                                class="v-md-button is-primary"
                            >{{ searchButtonText }}
                            </button>
                        </div>
                        <button @click="emitSearchReLoadButton" class="v-md-button v-md-icon-button">
                            <i class="material-icons">refresh</i>
                        </button>
                    </div>
                </div>
                <div class="in-input-edit layout-row">
                    <i class="material-icons in-input v-icon in-icon">search</i>
                    <input
                        @keyup.enter="emitSearchInputEnter"
                        ref="inputSearch"
                        v-model="searchInputText"
                        class="in-infield flex"
                        :placeholder="searchPlaceholder"
                    >
                </div>
            </div>
        </div>
        <!--Search input-->
        <div class="admin-divider"></div>
        <div class="admin-spinner-covered">
            <!--Spinner Loading-->
            <SpinnerLoading v-if="isLoading"/>
            <!--Spinner Loading-->
            <!--Table-->
            <table class="admin-table-elem admin-users-table" ref="table-paginate">
                <colgroup>
                    <col v-for="(h, i) in headers" :width="h.width" :class="h.class" :key="i">
                </colgroup>
                <!--Thead-->
                <thead class="thead-sort-container">
                <tr>
                    <template v-for="(h, i) in headers">
                        <th :class="h.class">{{h.name }}</th>
                    </template>
                </tr>
                </thead>
                <!--Thead-->
                <!--start Tbody-->
                <!--Table detail content-->
                <!-- <tbody>
                        <tr class="provider-list-row selected">
                           Remove all td but except first td
                            <td>
                                <div class="table-cell-wrapper">
                                    <div>
                                        <div class="layout-row layout-align-start-center">
                                            <div class="provider-list-row-icon">
                                                <img class="provider-icon"
                                                     ng-src="//www.gstatic.com/mobilesdk/171005_mobilesdk/auth_service_play_games.svg"
                                                     src="//www.gstatic.com/mobilesdk/171005_mobilesdk/auth_service_play_games.svg">
                                            </div>
                                            Play Games
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hide-xs">
                                <div class="table-cell-wrapper">
                                    <span> ปิดใช้แล้ว </span>
                                </div>
                            </td>
                            <td class="table-row-actions">
                                <div class="user-row-actions table-cell-wrapper layout-row">
                                    <i class="material-icons v-icon">edit</i>
                                </div>
                            </td>
                        </tr>
                        <tr class="admin-table-detail-content">
                            <td class="admin-table-detail-content-container" :colspan="headers.length">
                                <div class="table-cell-wrapper admin-table-detail-content-wrapper">
                                    <VTransclude :tag="'td'" :childClass="`admin-spinner-covered provider-list-edit-card`">
                                        <form class="admin-form" @submit.prevent>
                                            <fieldset class="provider-list-fieldset layout-column">
                                                <div class="provider-edit-inset-content layout-align-end-center layout-row">
                                                    <span style="margin: 16px 0;"></span>
                                                </div>
                                                <AdminInput :isDisabled="true" class="provider-edit-inset-content "
                                                            :validateText="''"
                                                            :label="'Client ID'"
                                                            :inputType="'text'"/>
                                                <AdminInput :isDisabled="true"
                                                            class="provider-edit-inset-content non-padding-bottom"
                                                            :validateText="''"
                                                            :label="'Client Secret'"
                                                            :inputType="'text'"/>
                                                <div
                                                    class="user-form-action provider-list-actions layout-align-end-center layout-row">
                                                    <button
                                                        class="v-md-button secondary theme-blue">
                                                        Cancel
                                                    </button>
                                                    <button class="v-md-button primary theme-blue"> Save</button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </VTransclude>
                                </div>
                                <div class="admin-table-detail-card"></div>
                            </td>
                        </tr>
                </tbody>-->
                <!--Table detail content -->
                <!--slot form top-->
                <tbody v-if="hasSlot('form-top')">
                <tr>
                    <td
                        class="admin-spinner-covered"
                        :colspan="$utils.getFirstTHeadColspan($refs['table-paginate'])">
                        <SpinnerLoading v-if="formTopState.loading" style="z-index: 46;"/>
                        <slot name="form-top"></slot>
                    </td>
                </tr>
                </tbody>
                <!--slot form top-->
                <tbody v-if="paginateData.items.length <= 0 && !isSearch">
                <tr>
                    <td v-for="(h, i) in headers" :class="h.class" :key="i"></td>
                </tr>
                </tbody>
                <tbody v-for="(tb, i) in paginateData.items" :key="i" v-else>
                <tr
                    :class="[{'provider-list-row' : isRowContentEditable(tb)}, {'selected': isSelectedRowContentState(tb, i)}]">
                    <template v-for="(td, j ) in tb.rows">
                        <td :key="j"
                            v-if="td.type==='id'"
                            class="user-email"
                            :class="[td.class]"
                            @click="openRowContentEditor(i)">
                            <div class="table-cell-wrapper">
                                <div>
                                    <div class="layout-row layout-align-space-between-center">
                                        <div class="layout-column user-identifier">
                                            <div
                                                :style="`color: ${td.textColor}`"
                                                class="user-email-content layout-row">{{ td.data }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td
                            :class="td.class"
                            v-else-if="td.type==='image-row-content'"
                            @click="openRowContentEditor(i)">
                            <div class="table-cell-wrapper">
                                <div>
                                    <div class="layout-row layout-align-start-center">
                                        <div class="provider-list-row-icon">
                                            <img class="provider-icon" :src="td.src">
                                        </div>
                                        {{ td.data }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td :class="td.class" v-else-if="td.type==='image'" @click="openRowContentEditor(i)">
                            <div class="table-cell-wrapper">
                                <div>
                                    <div class="layout-row">
                                      <span>
                                        <div>
                                          <img class="provider-icon" :src="td.data">
                                        </div>
                                      </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td :class="td.class" v-else-if="td.type==='text'" @click="openRowContentEditor(i)">
                            <div class="table-cell-wrapper">
                                <span :style="`color: ${td.textColor};`">{{td.data}}</span>
                            </div>
                        </td>
                        <td class="table-row-actions" :class="td.class" v-else-if="td.type==='action'">
                            <div class="user-row-actions layout-row">
                                <template v-if="hasSlot('action-row')">
                                    <slot
                                        name="action-row"
                                        :fireEvent="events.rowContentItemsClick"
                                        :position="{row: i, column: j}"
                                        :data="{row: tb.rowContent, column: td}"
                                    ></slot>
                                </template>
                                <template v-else>
                                    <template v-if="hasSlot('action-row-context')">
                                        <slot
                                            name="action-row-context"
                                            :fireEvent="events.rowContentItemsClick"
                                            :position="{row: i, column: j}"
                                            :data="{row: tb.rowContent, column: td}"
                                        ></slot>
                                    </template>
                                    <template v-else>
                                        <button @click="copyData(td.data)" class="v-md-button v-md-icon-button">
                                            <i class="material-icons v-icon">content_copy</i>
                                        </button>
                                    </template>
                                    <div class="table-action-menu">
                                        <button
                                            @click="setMenuContext({el: $event, menus: td.contextMenu, offsetX: 101, from: contextId })"
                                            class="v-md-button v-md-icon-button">
                                            <i class="material-icons v-icon">more_vert</i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="hide-xs" v-else>
                            <div
                                class="table-cell-wrapper"
                                style="color: #ff5252; white-space: normal;overflow: auto;display: flex;">
                                <span>The column type is invalid!, (Valid Column Types:['id', 'image-row-content', 'image', 'text', 'action'])</span>
                            </div>
                        </td>
                    </template>
                </tr>
                <!--Slot rowContent form detail -->
                <template v-if="hasSlot('form-row-detail')">
                    <tr class="admin-table-detail-content admin-spinner-covered"
                        v-if="isSelectedRowContentState(tb, i)">
                        <td
                            class="admin-table-detail-content-container"
                            :colspan="$utils.getFirstTHeadColspan($refs['table-paginate'])">
                            <SpinnerLoading v-if="rowContentControlState[i].state.loading"/>
                            <div class="table-cell-wrapper admin-table-detail-content-wrapper">
                                <VTransclude
                                    :tag="'td'"
                                    :childClass="`admin-spinner-covered provider-list-edit-card`">
                                    <form class="admin-form" @submit.prevent>
                                        <fieldset class="provider-list-fieldset layout-column">
                                            <div class="provider-edit-inset-content layout-align-end-center layout-row">
                                                <span style="margin: 16px 0;"></span>
                                            </div>
                                            <slot
                                                :fireEvent="events.rowContentItemsClick"
                                                :position="{row: i, column: -1}"
                                                :rowContent="tb.rowContent"
                                                name="form-row-detail"
                                            ></slot>
                                        </fieldset>
                                    </form>
                                </VTransclude>
                            </div>
                            <div class="admin-table-detail-card"></div>
                        </td>
                    </tr>
                </template>
                <!--Slot rowContent form detail -->
                </tbody>
                <!--end Tbody-->
            </table>
            <!--Table-->
            <div v-if="!isNotFound()" class="table-pagination layout-align-end-center layout-row">
                <div class="layout-align-end-center layout-row">
                    Row per page:
                    <div class="admin-select admin-select-rows">
                        <select v-model="per_page">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    {{paginateData.current_page}} - {{paginateData.last_page}} From {{paginateData.total}}
                    <button
                        :disabled="paginateData.current_page===1"
                        @click="prevPage(paginateData.current_page - 1)"
                        class="v-md-button v-md-icon-button pag">
                        <i class="material-icons">chevron_left</i>
                    </button>
                    <button
                        :disabled="paginateData.current_page===paginateData.last_page"
                        @click="nextPage(paginateData.current_page + 1)"
                        class="v-md-button v-md-icon-button pag">
                        <i class="material-icons">chevron_right</i>
                    </button>
                </div>
            </div>
            <!--Table result-->
            <div class="admin-card-content" v-if="isNotFound()">
                <div class="card-result-panel layout-align-center-center layout-column">
                    <div class="card-result-heading layout-row">
                        Could not be found
                        "{{ searchInputText }}"
                    </div>
                    <div class="card-result-heading layout-row">{{ notFoundText }}</div>
                </div>
            </div>
            <!-- Table result-->
        </div>
        <!--End table-->
    </div>
    <!--End card-->
</template>

<script>
    import {mapState, mapActions, mapMutations} from "vuex";

    export default {
        props: {
            searchPlaceholder: {
                type: String,
                default: ""
            },
            showSearchButton: {
                type: Boolean,
                default: true
            },
            searchButtonText: {
                type: String,
                default: ""
            },
            notFoundText: {
                type: String,
                default: ""
            },
            headers: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            formTopState: {
                required: false
            },
            isLoading: {
                type: Boolean,
                default: false
            },
            isSearch: {
                type: Boolean,
                default: false
            },
            paginateData: {
                type: Object,
                default: () => {
                    return {
                        items: [],
                        last_page: 0,
                        current_page: 0,
                        total: 0,
                        per_page: 10,
                    }
                }
            }
        },
        name: "table-paginate",
        data() {
            return {
                contextId: "tablePaginateMenuContextId",
                per_page: 0,
                searchInputText: "",
                isShowInputSearch: false,
                showChip: false,
                oldInput: "",
                events: {
                    rowContentItemsClick: "onRowContentItemsClick",
                },
                rowContentControlState: []
            };
        },
        watch: {
            searchInputText: function (n, o) {
                this.emit();
            },
            per_page: function (n, o) {
                this.emitItemPerPage();
            },
            menuContextItemClicked: function (n, o) {
                if (n.from === this.contextId) this.setOnMenuContextClick(n);
            },
            'paginateData.items': function (n, o) {
                //initialize row content control state
                this.rowContentControlState = []; //reset and add new
                n.forEach(f => {
                    this.rowContentControlState.push(f.rowContent);
                });
            }
        },
        computed: {
            ...mapState(["isMobile", "menuContextItemClicked"])
        },
        methods: {
            ...mapMutations(["setMenuContext"]),
            ...mapActions(["copyToClipboard"]),
            toggleShowInputSearch() {
                this.isShowInputSearch = !this.isShowInputSearch;
                if (this.isShowInputSearch) {
                    this.$refs["inputSearch"].focus();
                }
            },
            isNotFound() {
                return this.paginateData.items.length <= 0 && this.isSearch;
            },
            prevPage(pageNo) {
                if (pageNo < 1) pageNo = 1;
                if (this.paginateData.current_page === pageNo) return;
                this.$emit("paginateNavigate", {pageNo, dr: "prev"});
            },
            nextPage(pageNo) {
                let p = this.paginateData;
                if (pageNo > p.last_page) pageNo = p.last_page;
                if (p.current_page === pageNo) return;
                this.$emit("paginateNavigate", {pageNo, dr: "next"});
            },
            copyData(d) {
                this.copyToClipboard({text: d});
            },
            emit() {
                this.$emit("send", this.searchInputText);
                this.$emit("input", this.searchInputText);
            },
            removeChipText() {
                this.searchInputText = "";
                this.showChip = false;
                this.emit();
                this.$emit("onRemoveChipText");
                this.setInputState();
            },
            setChipShow() {
                this.showChip = this.searchInputText !== "";
            },
            setInputState() {
                this.oldInput = this.searchInputText;
            },
            emitSearchActionButton() {
                this.$emit("onSearchActionButton");
            },
            emitSearchInputEnter() {
                this.isShowInputSearch = false;
                this.setChipShow();
                if (this.searchInputText === this.oldInput) return;
                this.$emit("onSearchInputEnter", this.searchInputText);
                this.setInputState();
            },
            emitSearchReLoadButton() {
                this.$emit("onSearchReLoadButtonClick");
            },
            setBlurSearchInput() {
                this.isShowInputSearch = false;
                this.setChipShow();
                if (this.searchInputText === this.oldInput) return;
                this.$emit("onSearchInputClose", this.searchInputText);
                this.setInputState();
            },
            emitItemPerPage() {
                let r = this.paginateData.items.length < this.per_page && this.paginateData.current_page > 1; //check if should reset current page
                this.$emit("onItemPerPageClick", {per_page: this.per_page, reset: r});
            },
            setFocusSearchInput() {
                this.isShowInputSearch = true;
            },
            setOnMenuContextClick(m) {
                this.$emit("onMenuContextClick", m);
            },
            openRowContentEditor(i) {
                if (this.rowContentControlState[i].wholeEdit) {
                    this.setRowContentState(i, this.Event.loadingState().ActiveNotLoading);
                }
            },
            setRowContentState(i, state) {
                this.rowContentControlState[i].state = state;
                this.paginateData.items[i].rowContent.state = state;
            },
            isSelectedRowContentState(tb, i) {
                let state = this.rowContentControlState;
                return (this.isRowContentEditable(tb) && state[i] && state[i].state.active)
            },
            isRowContentEditable(tb) {
                return (this.rowContentControlState.length > 0 && tb.rowContent.wholeEdit)
            },
            registerEvents() {
                // listen to the event row content items click
                this.Event.offListen(this.events.rowContentItemsClick);
                this.Event.listen(this.events.rowContentItemsClick, data => {
                    this.setRowContentState(data.position.row, data.state);
                });
            }
        },
        mounted() {
            this.$refs["inputSearch"].onfocus = e => {
                this.setFocusSearchInput();
            };
            this.$refs["inputSearch"].onblur = e => {
                this.setBlurSearchInput();
            };
            //initialize data
            this.emit();
            this.emitItemPerPage();
        },
        created() {
            this.per_page = this.paginateData.per_page;
            this.registerEvents();
        },
    };
</script>
