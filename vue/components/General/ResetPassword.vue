<template>
    <div>
        <div class="general-from-container is-65">
            <div class="form-represent is-65">
                <!--Progress bar-->
                <div class="form-represent-top-border form-represent-top-border-z-index" aria-hidden="true">
                    <div class="form-represent-top-border-progress"
                         :class="[validated().loading_reset ? '': 'is-hide']">
                        <div
                            class="form-represent-top-border-progress-img form-represent-top-border-progress-bg-size"></div>
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
                <!--Start form register-->
                <div class="form-represent-form-container">
                    <!--Logo-->
                    <div class="form-represent-form is-full-height">
                        <div class="form-represent-form-logo-container">
                            <div @click="Route({name: 'home'})" class="form-represent-form-logo-inner is-cursor">
                                <img :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`">
                            </div>
                        </div>
                    </div>
                    <!--Logo-->
                    <!--Elements-->
                    <div class="form-represent-form-elements-container">
                        <div>
                            <div class="form-represent-form-elements-container-inner">
                                <!--Header Caption -->
                                <div class="form-represent-form-header-caption">
                                    <div class="header-text-centered">
                                        <h1 class="headingText ">
                                            <content>Reset your Jaol Password</content>
                                        </h1>
                                        <div class="headingSubtext ">
                                            <content>to Continue to Jaol Memebers</content>
                                        </div>
                                    </div>
                                </div>
                                <!--Header Caption -->
                                <!--Form input-->
                                <div class="form-represent-form-input">
                                    <div class="form-represent-form-input-inner">
                                        <!--Input element -->
                                        <content>
                                            <section class="section-input">
                                                <div class="section-input-container-inner">
                                                    <!--Start input-->
                                                    <content>
                                                        <GeneralInput v-model="user.email"
                                                                      :inputType="'email'"
                                                                      :labelText="'Email'"
                                                                      :isDisabled="true"
                                                                      :validate="{text:  validated().email}"
                                                                      :adviceText="'We will sign out your authenticated devices after finished the password reset action.'"
                                                                      :isSmall="true"
                                                        />
                                                        <!--Description-->
                                                        <content v-if="validated().error">
                                                            <div
                                                                class="general-input-spacing failed-description is-error ">
                                                                {{ validated().error}}
                                                            </div>
                                                        </content>
                                                        <!--Description-->
                                                        <div
                                                            class="form-represent-input-group-three is-margin-bottom-less">
                                                            <div class="form-represent-input-group">
                                                                <div class="half-input-width no-order">
                                                                    <GeneralInput v-model="user.password"
                                                                                  :inputType="'password'"
                                                                                  :labelText="'Password'"
                                                                                  :validate="{text: validated().password}"
                                                                                  :isSmall="true"
                                                                    />
                                                                </div>
                                                                <div class="form-represent-input-advice-text">Use 6 or
                                                                    more characters with a mix of letters, numbers &
                                                                    symbols
                                                                </div>
                                                                <div class="half-input-width no-order">
                                                                    <GeneralInput v-model="user.password_confirmation"
                                                                                  :inputType="'password'"
                                                                                  :labelText="'Confirm'"
                                                                                  :validate="{text: validated().password_confirmation}"
                                                                                  :isSmall="true"
                                                                                  @inputEnter="UserResetPassword"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!--Optional-->
                                                    </content>
                                                    <!--End input-->
                                                </div>
                                            </section>
                                        </content>
                                        <!--Input element -->
                                        <!--Action-->
                                        <div class="form-represent-form-action">
                                            <div class="inner">
                                                <div class="action-button">
                                                    <!--@CLick-->
                                                    <div
                                                        @click="UserResetPassword"
                                                        class="action-button-container action-button-container-main action-button-is-anim">
                                                        <div class="action-button-bubble action-button-bubble-tran"
                                                             style="top: 14.5px; left: 39px; width: 88px; height: 88px;"></div>
                                                        <div class="action-button-inner-top"></div>
                                                        <content class="action-button-content"><span
                                                            class="action-button-text">Reset Password</span></content>
                                                    </div>
                                                </div>
                                                <div class="action-text">
                                                    <div
                                                        class="action-text-container" @click="Route({name: 'login'})">
                                                        <div class="action-text-bubble action-text-bubble-tran"
                                                             style="top: 14.5px; left: 39px; width: 88px; height: 88px;"></div>
                                                        <div class="action-text-inner-top"></div>
                                                        <content class="action-text-content"><span
                                                            class="action-text-text">Sign in instead</span></content>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Action-->
                                    </div>
                                </div>
                                <!--Form input-->
                            </div>
                        </div>
                    </div>
                    <!--Elements-->
                </div>
                <!--End form register-->
                <div class="masked-blur-form"></div>
            </div>
        </div>
    </div>

</template>

<script>
    import {mapActions, mapGetters, mapMutations} from 'vuex'

    export default {
        name: "ResetPassword",
        data() {
            return {
                ...mapGetters(['validated']),
                user: {email: '...'}
            }
        },
        methods: {
            ...mapActions(['initResetForm', 'resetPassword']),
            ...mapMutations(['setValidated', 'setPageTitle']),
            UserResetPassword() {
                this.resetPassword(this.user)
                    .then(res => {
                        if (res.success) {
                            this.Route({name: 'finished-reset-password'}, 1000);
                        }
                    })
                    .catch(e => {

                    })
            },
            Init() {
                let token = this.$route.params.token;
                this.initResetForm(token)
                    .then(res => {
                        if (res.success) {
                            this.user.token_input = res.token;
                            this.user.email = decodeURIComponent(res.email);
                        } else {
                            this.setValidated({errors: {error: "Your reset password link has expired!. Redirect in 3.5"}});
                            this.Route({name: 'forgot-password'}, -1, 3500)
                        }
                    })
                    .catch(e => {

                    })
            }
        },
        mounted() {
            this.setPageTitle('Reset Password');
        },
        created() {
            this.Init();
        }
    }
</script>

<style scoped>

</style>
