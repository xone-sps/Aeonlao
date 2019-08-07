<template>
  <div>
    <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
    <div class="module_content layout-column">
      <div class="module_authentication">
        <div class="module-canvas emails-card-wrapper">
          <MasterSingleDetailCard
           :isLoading="contactInfo.loading"
            @onBackButtonClick="onBackButtonClick"
            :header="{ title: 'Contact Info', content: '<p> Changes Contact Info.</p>'}"
            :menuItem="{ name: 'Contact Info', icon: 'account_circle'}"
          >
            <div class="details is-edit">
              <form @submit.prevent class="admin-form admin-template-form">
                <div class="layout-align-space-around-start layout-row">
                  <AdminInput
                   v-model="contactInfo.phone"
                   :validateText="validated().phone"
                    :label="'Phone number'"
                    :placeholder="'+856 12345678'"
                    :inputType="'text'"
                  />
                </div>
                <div class="layout-align-space-around-start layout-row">
                  <AdminInput
                  v-model="contactInfo.email"
                  :validateText="validated().email"
                    :label="'Email Address'"
                     :placeholder="'example@gmail.com'"
                    :inputType="'email'"
                  />
                </div>
                <div class="layout-align-space-around-start layout-row">
                  <AdminInput :label="'Address'" 
                  v-model="contactInfo.address"
                  :validateText="validated().address"
                  :inputType="'textarea'"/>
                </div>

                <div class="admin-settings-cameo template-brand-settings">
                  <div class="settings-container no-border-left is-white">
                    <div class="cameo-header">
                      <i class="material-icons cameo-header-icon">account_box</i>
                      <span>Social Media</span>
                    </div>
                    <div class="cameo-content">
                      <div class="layout-align-space-around-start layout-row">
                        <AdminInput
                          :label="'Facebook'"
                          v-model="contactInfo.facebook"
                          :containerClass="'dense'"
                          :inputType="'text'"
                        >
                        </AdminInput>
                      </div>
                      <div class="layout-align-space-around-start layout-row">
                        <AdminInput
                        v-model="contactInfo.twitter"
                          :label="' Twitter '"
                          :containerClass="'dense'"
                          :inputType="'text'"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="actions">
                  <div class="layout-align-end-center layout-row">
                    <button
                      @click="saveContactInfo"
                      class="v-md-button primary"
                    >Save Changes</button>
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
  name: "ContactInfo",
  data: () => ({
    title: "Contact Info",
    contactInfo: {loading: true},
    tabs: [{ name: "Contact Info" }]
  }),
  methods: {
    ...mapActions(["postMangeContactInfo", "fetchContactInfo"]),
    onBackButtonClick() {
        this.Route({ name: 'dashboard' });
    },
    getItems() {
      this.contactInfo.loading = true;
      this.fetchContactInfo()
      .then(res => {
          if(!this.$utils.isEmptyVar(res.data) && res.success){
            res.data.loading = false;
            this.contactInfo = res.data;
          }
        this.contactInfo.loading = false;
      })
      .catch(err=> {
        this.contactInfo.loading = false;
      });
    },
    saveContactInfo() {
      this.postMangeContactInfo(this.contactInfo).
      then(res=> {
          this.showInfoToast({ msg: "The contact information was successfully updated!", dt: 3500 });
          this.getItems();
      })
      .catch(err=> {
          this.showErrorToast({ msg: "Cannot update the contact information!", dt: 3500  });
      })
    }
  },
  created() {
    this.getItems = this.debounce(this.getItems, 150);
    this.getItems();
  }
});
</script>
