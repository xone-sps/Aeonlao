<template>
  <div>
    <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
    <div class="module_content layout-column">
      <div class="module_authentication">
        <div class="module-canvas emails-card-wrapper">
          <MasterSingleDetailCard
            :isLoading="about.loading"
            @onBackButtonClick="onBackButtonClick"
            :header="{ title: 'About Us', content: '<p> Changes own information.</p>'}"
            :menuItem="{ name: 'About Us', icon: 'account_circle'}"
          >
            <div class="details is-edit">
              <form @submit.prevent class="admin-form admin-template-form">
                <div class="layout-align-space-around-start layout-row">
                  <Editor id="about_jaol_editor" @editorMounted="(ed)=> editor = ed" v-model="about.description" :label="'About Us Description'"/>
                </div>
                <div class="actions">
                  <div class="layout-align-end-center layout-row">
                    <button @click="saveContent" class="v-md-button primary">Save Changes</button>
                  </div>
                </div>
              </form>
            </div>
          </MasterSingleDetailCard>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import AdminBase from "@bases/AdminBase.js";
import { mapActions } from "vuex";
export default AdminBase.extend({
  name: "AboutInfo",
  data: () => ({
    title: "About Us information",
    tabs: [{ name: "About Us" }],
    about: { loading: true },
    editor: null,
  }),
  methods: {
    ...mapActions(["postMangeAboutInfo", "fetchAboutInfo"]),
    onBackButtonClick() {
      this.Route({ name: "dashboard" });
    },
    getItems() {
      this.about.loading = true;
      this.fetchAboutInfo()
        .then(res => {
          if (!this.$utils.isEmptyVar(res.data) && res.success) {
            res.data.loading = false;
            this.about = res.data;
            this.editor.setEditorContent(this.about.description);
          }
          this.about.loading = false;
        })
        .catch(err => {
          this.about.loading = false;
        });
    },
    saveContent() {
      this.about.loading = true;
      this.postMangeAboutInfo(this.about)
        .then(res => {
          this.showInfoToast({
            msg: "The about information was successfully updated!",
            dt: 3500
          });
          this.getItems();
        })
        .catch(err => {
          this.showErrorToast({
            msg: "Cannot update the about information!",
            dt: 3500
          });
        });
    }
  },
  created() {
    this.getItems = this.debounce(this.getItems, 150);
    this.getItems();
  }
});
</script>
