<template>
  <div class="general-from-container is-65 no-before">
    <div class="form-represent is-65" :style="`${isFullWidth ? 'width: 100%' : ''};`">
      <!--Progress bar-->
      <div class="form-represent-top-border form-represent-top-border-z-index" aria-hidden="true">
        <div
          class="form-represent-top-border-progress"
          :class="[validated().loading_send_contact ? '': 'is-hide']"
        >
          <div
            class="form-represent-top-border-progress-img form-represent-top-border-progress-bg-size"
          ></div>
          <div class="form-represent-top-border-progress-tran"></div>
          <div class="form-represent-top-border-progress-tran-anim is-anim">
            <span class="form-represent-top-border-progress-tran-anim-spot is-anim"></span>
          </div>
          <div class="form-represent-top-border-progress-tran-relay-anim is-anim">
            <span class="form-represent-top-border-progress-tran-anim-spot scale is-anim"></span>
          </div>
        </div>
      </div>
      <!-- Progress bar-->

      <div class="form-represent-form-container">
        <!--Elements-->
        <div class="form-represent-form-elements-container">
          <!--Header Caption -->
          <div class="form-represent-form-header-caption">
            <div class="header-text-centered">
              <h5 class="headingText">
                <content>{{ contactInfo.header_title}}</content>
              </h5>
            </div>
          </div>
          <!--Header Caption -->
          <div>
            <div class="form-represent-form-elements-container-inner">
              <div class="form-represent-form-input">
                <div>
                  <content>
                    <section class="section-input">
                      <div class="section-input-container-inner">
                        <div class="form-represent-form-container contact-form">
                          <!--Start input-->
                          <content>
                            <GeneralInput
                              v-model="contactInfo.name"
                              :inputType="'text'"
                              :labelText="'Name'"
                              :validate="{text: validated().name}"
                            />
                            <GeneralInput
                              v-model="contactInfo.email"
                              :inputType="'email'"
                              :labelText="'Email'"
                              :validate="{text: validated().email}"
                            />
                            <GeneralInput
                              v-model="contactInfo.subject"
                              :inputType="'text'"
                              :labelText="'Subject'"
                              :validate="{text: validated().subject}"
                            />
                            <div class="field">
                              <div class="singel-form form-group">
                                <textarea
                                  :class="[{'is-error':  validated().message}]"
                                  v-model="contactInfo.message"
                                  placeholder="Message"
                                ></textarea>
                              </div>
                              <div
                                v-if="validated().message"
                                class="general-input-validate-container"
                              >
                                <div class="general-input-validate-top"></div>
                                <div class="general-input-validate-text-container">
                                  <div class="inner">
                                    <span class="span-icon">
                                      <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="rgb(229, 28, 35)"
                                      >
                                        <path d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                          d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"
                                        ></path>
                                      </svg>
                                    </span>
                                    {{
                                    validated().message }}
                                  </div>
                                </div>
                              </div>
                            </div>
                          </content>
                          <!--End input-->
                          <button
                            @click="sendContact"
                            :disabled="validated().loading_send_contact"
                            class="main_b top_10"
                          >Contact Us</button>
                        </div>
                      </div>
                    </section>
                  </content>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Base from "@com/Bases/GeneralBase.js";
import { mapActions } from "vuex";

export default Base.extend({
  props: {
    isFullWidth: {
      default: false
    }
  },
  data: () => ({
    contactInfo: { header_title: "Contact Us Now" } //for contact form
  }),
  methods: {
    ...mapActions(["postSendContact"]),
    sendContact() {
      let c = this.contactInfo;
      if (this.validated().loading_send_contact) {
        c.header_title = "Sending Information...";
        return;
      }
      this.postSendContact(c)
        .then(res => {
          if (res.success) {
            this.contactInfo = { header_title: "Sent the information!" };
            setTimeout(() => {
              this.contactInfo = { header_title: "Contact Us Now" };
            }, 3500);
          }
        })
        .catch(err => {
          if (err && !err.errors) {
            c.header_title = "Failed to send the information!";
          }
        });
    }
  }
});
</script>

<style scoped>
/* .singel-form textarea {
    padding-top: 10px;
    height: 100px;
    resize: none;
        width: 100%;
    margin-bottom: 10px;
} */
.singel-form textarea {
  width: 100%;
  height: 150px;
  padding: 10px 20px;
  border: 1px solid #a1a1a1;
  border-radius: 5px;
  color: #8a8a8a;
  font-size: 15px;
  margin-bottom: 1px;
}
.contact-button {
  margin-top: 20px;
}
</style>
