<template>
    <div class="saidbar-search mt-10">
        <form v-on:submit.prevent="searchEnter">
            <input
                @keyup.enter="triggerButton"
                v-model="inputText"
                class="input is-medium"
                type="text"
                placeholder="ຄົ້ນຫາ"
            >
            <button ref="search-button" @click="searchEnter" type="button">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
    <!-- saidbar search -->
</template>

<script>
    export default {
        name: "PostsSearchForm",
        data: () => ({
            inputText: ""
        }),
        props: {
            value: {}
        },
        watch: {
            inputText: function (n, o) {
                this.emits();
            }
        },
        methods: {
            emits() {
                this.$emit("send", this.inputText);
                this.$emit("input", this.inputText);
            },
            searchEnter() {
                this.$emit("onSearchEnter", this.inputText);
            },
            triggerButton() {
                this.$refs["search-button"].click();
                this.$utils.hideKeyboard(this.$refs["search-button"]);
            }
        },
        mounted() {
            this.inputText = this.value;
        }
    };
</script>

<style scoped>
    .saidbar-search {
        position: relative;
    }
</style>
