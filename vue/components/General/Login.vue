<template>
    <div>
        <div class="general-from-container is-65">
            <div class="form-represent is-65">
                <!--Progress bar-->
                <div class="form-represent-top-border form-represent-top-border-z-index" aria-hidden="true">
                    <div class="form-represent-top-border-progress" :class="[validated().loading ? '': 'is-hide']">
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
                <!--Start form login-->
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
                                        <h1 class="headingText">
                                            <content>Sign in</content>
                                        </h1>
                                        <div class="headingSubtext">
                                            <content>to continue to ESQAC Members</content>
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
                                                                      :validate="{text: validated().email}"
                                                        />
                                                        <div class="general-input-spacing"></div>
                                                        <GeneralInput @inputEnter="Login({userInfo: user})"
                                                                      v-model="user.password"
                                                                      :inputType="'password'"
                                                                      :labelText="'Password'"
                                                                      :validate="{text: validated().password}"
                                                                      :showPasswordButton="true"
                                                                      :autoComplete="'current-password'"
                                                        />
                                                        <!--Optional-->
                                                        <!--Description-->
                                                        <content v-if="validated().auth_failed">
                                                            <div
                                                                class="general-input-spacing failed-description is-error ">
                                                                {{ validated().auth_failed}}
                                                            </div>
                                                        </content>
                                                        <!--Description-->
                                                        <!--Forgot password-->
                                                        <!--<div class="general-input-spacing">-->
                                                        <!--<button type="button" class="general-input-button-text">-->
                                                        <!--Forgot Password-->
                                                        <!--</button>-->
                                                        <!--</div>-->
                                                        <!--Forgot password-->
                                                        <!--Optional-->
                                                    </content>
                                                    <!--End input-->
                                                </div>
                                            </section>
                                        </content>
                                        <!--Input element -->
                                        <!--Description-->
                                        <content>
                                            <div class="general-input-description">
                                                <div class="general-input-spacing">
                                                    if you have a problem to login
                                                    please contact us to
                                                    <a @click="Route({name: 'contact'})" class="general-input-link-text">Get help</a>
                                                </div>
                                            </div>
                                        </content>
                                        <!--Description-->
                                        <!--Action-->
                                        <div class="form-represent-form-action">
                                            <div class="inner">
                                                <div class="action-button">
                                                    <div
                                                        @click="Login({userInfo: user})"
                                                        class="action-button-container action-button-container-main action-button-is-anim">
                                                        <div class="action-button-bubble action-button-bubble-tran"
                                                             style="top: 14.5px; left: 39px; width: 88px; height: 88px;"></div>
                                                        <div class="action-button-inner-top"></div>
                                                        <content class="action-button-content"><span
                                                            class="action-button-text">Login</span></content>
                                                    </div>
                                                </div>
                                                <div class="action-text">
                                                    <div
                                                        @click="Route({name: 'home'})"
                                                        class="action-text-container">
                                                        <!--<div class="action-text-bubble action-text-bubble-tran"-->
                                                             <!--style="top: 14.5px; left: 39px; width: 88px; height: 88px;"></div>-->
                                                        <div
                                                            class="action-text-inner-top"></div>
                                                        <content class="action-text-content"><span
                                                            class="action-text-text">Go Back</span></content>
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
                <!--End form login-->
                <div class="masked-blur-form"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'

    export default {
        name: "login",
        data() {
            return {
                ...mapGetters(['validated']),
                user: {}
            }
        },
        methods: {
            ...mapActions(['Login', 'setPageTitle']),
        },
        mounted() {
            this.setPageTitle('Sign In');
        },
    }
</script>

<style scoped>

</style>
