<template>
    <!--Search input-->
    <div class="in-search-input zi">
        <div class="in search-input-toolbar" :class="[ isShowInputSearch ? 'editing': '']"><!--editing-->
            <div class="in-input-show layout-row">
                <i
                    @click="toggleShowInputSearch"
                    class="material-icons in-input v-icon in-icon">search</i>
                <span
                    @click="toggleShowInputSearch"
                    class="in-input-toggle in-placeholder" :style="showChip ? 'display:none;': ''">{{ searchPlaceholder }}</span>
                <!--chip -->
                <div class="layout-align-start-center layout-row" :style="showChip ? '': 'display:none;'">
                    <div class="admin-chip has-action">
                        <div @click="toggleShowInputSearch" class="admin-chip-content">{{ searchInputText }}</div>
                        <button @click="removeChipText" class="v-md-button v-md-icon-button chip-action"><i
                            class="material-icons">cancel</i></button>
                    </div>
                </div>
                <!--end chip-->
                <span @click="toggleShowInputSearch" class="in-input-toggle flex"></span>
                <div class=" input-actions layout-row layout-align-start-center">
                    <div class="button-wrapper">
                        <button @click="emitSearchActionButton" class="v-md-button is-primary">{{searchButtonText}}
                        </button>
                    </div>
                    <button @click="emitSearchReLoadButton" class="v-md-button v-md-icon-button"><i
                        class="material-icons">refresh</i></button>
                </div>
            </div>
            <div class="in-input-edit layout-row">
                <i
                    class="material-icons in-input v-icon in-icon">search</i>
                <input @keyup.enter="emitSearchInputEnter" ref="inputSearch" v-model="searchInputText"
                       class="in-infield flex"
                       :placeholder="searchPlaceholder"
                >
            </div>
        </div>
    </div>
    <!--Search input-->
</template>

<script>
    export default {
        name: "SearchInput",
        props: {
            searchPlaceholder: {
                type: String,
                default: ''
            },
            searchButtonText: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            searchInputText: '',
            isShowInputSearch: false,
            showChip: false,
            oldInput: '',
        }),
        watch: {
            'searchInputText': function (n, o) {
                this.emit();
            },
        },
        methods: {
            toggleShowInputSearch() {
                this.isShowInputSearch = !this.isShowInputSearch;
                if (this.isShowInputSearch)
                    this.$refs['inputSearch'].focus();
            },
            emit() {
                this.$emit('send', this.searchInputText);
                this.$emit('input', this.searchInputText);
            },
            removeChipText() {
                this.searchInputText = '';
                this.showChip = false;
                this.emit();
                this.$emit('onRemoveChipText');
                this.setInputState();
            },
            setChipShow() {
                this.showChip = this.searchInputText !== '';
            },
            setInputState() {
                this.oldInput = this.searchInputText;
            },
            emitSearchActionButton() {
                this.$emit('onSearchActionButton');
            },
            emitSearchInputEnter() {
                this.isShowInputSearch = false;
                this.setChipShow();
                if (this.searchInputText === this.oldInput) return;
                this.$emit('onSearchInputEnter', this.searchInputText);
                this.setInputState();
            },
            emitSearchReLoadButton() {
                this.$emit('onSearchReLoadButtonClick');
            },
            setBlurSearchInput() {
                this.isShowInputSearch = false;
                this.setChipShow();
                if (this.searchInputText === this.oldInput) return;
                this.$emit('onSearchInputClose', this.searchInputText);
                this.setInputState();
            },
            setFocusSearchInput() {
                this.isShowInputSearch = true;
            },
        },
        mounted() {
            this.$refs['inputSearch'].onfocus = (e) => {
                this.setFocusSearchInput();
            };
            this.$refs['inputSearch'].onblur = (e) => {
                this.setBlurSearchInput();
            };
            //initialize data
            this.emit();
        },
    }
</script>

<style scoped>

</style>
