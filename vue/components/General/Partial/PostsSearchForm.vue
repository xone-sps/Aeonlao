<template>
    <div class="saidbar-search">
  <form v-on:submit.prevent="searchEnter">
    <input type="text" class="input is-rounded" id="search-bar" placeholder="Search" @keyup.enter="triggerButton"
                v-model="inputText">
    <!-- <a @click="searchEnter"><i class="fa fa-search search-icon"></i></a> -->
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

<style type="text/css" scope>
    
.saidbar-search {
    position: relative;
}
input#search-bar {
  margin: 0 auto;
  width: 100%;
  height: 45px;
  padding: 0 20px;
  font-size: 1rem;
  border: 1px solid #7461cf;
  outline: none;
}
input#search-bar:focus {
  border: 1px solid #008abf;
  transition: 0.35s ease;
  color: #008abf;
}
input#search-bar:focus::-webkit-input-placeholder {
  transition: opacity 0.45s ease;
  opacity: 0;
}
input#search-bar:focus::-moz-placeholder {
  transition: opacity 0.45s ease;
  opacity: 0;
}
input#search-bar:focus:-ms-placeholder {
  transition: opacity 0.45s ease;
  opacity: 0;
}

.search-icon {
  position: relative;
  float: right;
    top: -31px;
    right: 16px;
    color: #7461cF;

}
</style>