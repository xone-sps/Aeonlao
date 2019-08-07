<template>
    <div>
        <div class="general-from-container is-65">
            <div class="form-represent is-65">
                <!--Progress bar-->
                <div class="form-represent-top-border form-represent-top-border-z-index" aria-hidden="true">
                    <div
                        class="form-represent-top-border-progress"
                        :class="[validated().loading ? '': 'is-hide']"
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
                                        <h1 class="headingText">
                                            <content>Create your {{$utils.firstUpper(type)}} Account</content>
                                        </h1>
                                        <div class="headingSubtext">
                                            <content>Continue to Ledu Members</content>
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
                                                        <div class="form-represent-input-group">
                                                            <div class="half-input-width">
                                                                <GeneralInput
                                                                    v-model="user.first_name"
                                                                    :inputType="'text'"
                                                                    :labelText="'Name'"
                                                                    :validate="{text: validated().first_name}"
                                                                    :isSmall="true"
                                                                />
                                                            </div>
                                                            <div class="half-input-width">
                                                                <GeneralInput
                                                                    v-model="user.last_name"
                                                                    :inputType="'text'"
                                                                    :labelText="'Surname'"
                                                                    :validate="{text: validated().last_name}"
                                                                    :isSmall="true"
                                                                />
                                                            </div>
                                                        </div>
                                                        <GeneralInput
                                                            v-model="user.email"
                                                            :inputType="'email'"
                                                            :labelText="'Email'"
                                                            :validate="{text:  validated().email}"
                                                            :adviceText="'You can use letters, numbers & periods'"
                                                            :isSmall="true"
                                                        />
                                                        <div class="general-input-spacing"></div>
                                                        <div
                                                            class="form-represent-input-group-three is-margin-bottom-less">
                                                            <div class="form-represent-input-group">
                                                                <div class="half-input-width no-order">
                                                                    <GeneralInput
                                                                        v-model="user.password"
                                                                        :inputType="'password'"
                                                                        :labelText="'Password'"
                                                                        :validate="{text: validated().password}"
                                                                        :isSmall="true"
                                                                    />
                                                                </div>
                                                                <div class="form-represent-input-advice-text">
                                                                    Use 6 or
                                                                    more characters with a mix of letters, numbers &
                                                                    symbols
                                                                </div>
                                                                <div class="half-input-width no-order">
                                                                    <GeneralInput
                                                                        v-model="user.password_confirmation"
                                                                        :inputType="'password'"
                                                                        :labelText="'Confirm'"
                                                                        :validate="{text: validated().password_confirmation}"
                                                                        :isSmall="true"
                                                                        @inputEnter="RegisterUser"
                                                                    />
                                                                </div>
                                                            </div>
                                                            <!-- Show password button-->
                                                            <!--Show password button-->
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
                                                    <div
                                                        @click="RegisterUser"
                                                        class="action-button-container action-button-container-main action-button-is-anim"
                                                    >
                                                        <div
                                                            class="action-button-bubble action-button-bubble-tran"
                                                            style="top: 14.5px; left: 39px; width: 88px; height: 88px;"
                                                        ></div>
                                                        <div class="action-button-inner-top"></div>
                                                        <content class="action-button-content">
                                                            <span class="action-button-text">Register</span>
                                                        </content>
                                                    </div>
                                                </div>
                                                <div class="action-text">
                                                    <div class="action-text-container" @click="Route({name: 'login'})">
                                                        <div
                                                            class="action-text-bubble action-text-bubble-tran"
                                                            style="top: 14.5px; left: 39px; width: 88px; height: 88px;"
                                                        ></div>
                                                        <div class="action-text-inner-top"></div>
                                                        <content class="action-text-content">
                                                            <span class="action-text-text">Sign in instead</span>
                                                        </content>
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
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "register",
        data() {
            return {
                ...mapGetters(["validated"]),
                user: {},
                type: 'field-inspector',
                allows: {'field-inspector': 'field_inspector', 'checker': 'checker'}
            };
        },
        methods: {
            ...mapActions(["register", "setPageTitle"]),
            RegisterUser() {
                this.user.type = this.type;
                this.user.url = this.$route.path.replace('/', '');
                this.register(this.user).then(res => {
                    this.Route({name: "registered"}, 1000);
                });
            }
        },
        mounted() {
            this.setPageTitle("Register - " + this.type);
        },
        created() {
            let qType = this.$route.query.type;
            if (qType && this.allows[qType]) {
                this.type = qType;
            }
        }
    };
</script>
