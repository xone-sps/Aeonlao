<template>
    <div class="single-card-action">
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <MasterSingleDetailCard
                        :isLoading="isLoading"
                        @onBackButtonClick="onBackButtonClick"
                        :cell="'16'"
                        :header="{title: '', content: ''}"
                        :menuItem="{ name: 'Members Profile', icon: 'account_circle'}">
                        <div class="details">
                            <form @submit.prevent class="admin-form admin-template-form">
                                <!--Personal Info -->
                                <div class="admin-settings-cameo template-brand-settings">
                                    <div class="settings-container no-border-left" border-bottom>
                                        <div class="cameo-header">
                                            <i class="material-icons cameo-header-icon">account_box</i>
                                            <span> Personal Information</span>
                                        </div>
                                        <div class="cameo-content">
                                            <div class="layout-align-space-around-start layout-row">
                                                <div class="form-input-container dense">
                                                    <label class="is-center"> Profile Picture </label>
                                                    <div class="user-picture-link-inner is-edit"
                                                         @click="showFullImage(user_auth_info.image)">
                                                        <div title="Show full profile image"
                                                             class="user-picture-link-inner-title">
                                                            <img v-if="user_auth_info.thumb_image" :src="`${baseUrl}${user_auth_info.thumb_image}`">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput :label="' Email '"
                                                            v-model="user_auth_info.email"
                                                            :containerClass="'dense'"
                                                            :inputType="'text'">
                                                    <p class="template-tip">{{ user_auth_info.email ||
                                                        emptyText }}</p>
                                                </AdminInput>
                                            </div>
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput :label="'First name'"
                                                            v-model="userProfile.first_name"
                                                            :containerClass="'dense'"
                                                            :inputType="'text'">
                                                    <p class="template-tip">{{ userProfile.first_name ||
                                                        emptyText }}</p>
                                                </AdminInput>
                                                <AdminInput :label="' Last name '"
                                                            v-model="userProfile.last_name"
                                                            :containerClass="'is-second-input dense'"
                                                            :inputType="'text'">
                                                    <p class="template-tip">{{ userProfile.last_name ||
                                                        emptyText }}</p>
                                                </AdminInput>
                                            </div>
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput :label="' Phone number '"
                                                            v-model="userProfile.phone_number"
                                                            :containerClass="'dense'"
                                                            :inputType="'text'">
                                                    <p class="template-tip">{{ userProfile.phone_number ||
                                                        emptyText }}</p>
                                                </AdminInput>
                                                <div
                                                    class="form-input-container flex is-second-input dense"
                                                    full>
                                                    <label>Date of Birth</label>
                                                    <p class="template-tip" v-if="userProfile.dateOfBirth">
                                                        {{$utils.formatTimestmp(userProfile.dateOfBirth,
                                                        false)}}</p>
                                                    <p class="template-tip" v-else>{{ emptyText }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Personal Info-->

                                <!--Marital Info -->
                                <div class="admin-settings-cameo template-brand-settings">
                                    <div class="settings-container no-border-left no-margin-top"
                                         border-bottom>
                                        <div class="cameo-header">
                                            <i class="material-icons cameo-header-icon">favorite</i>
                                            <span> Marital Status</span>
                                        </div>
                                        <div class="cameo-content">
                                            <div class="layout-align-space-around-start layout-row">
                                                <div
                                                    class="form-multi-select-container flex dense"
                                                    full>
                                                    <label>Current Status</label>
                                                    <p class="template-tip">{{ userProfile.marital_status ?
                                                        userProfile.marital_status.text : emptyText }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Marital Info -->

                                <!--Education Info -->
                                <EducationProfile :isDisplay="true" :emptyText="emptyText" :member_educations="userProfile.member_educations"/>
                                <!--Education Info-->

                                <!--Career Info -->
                                <CareerProfile :isDisplay="true" :emptyText="emptyText" :member_careers="userProfile.member_careers"/>
                                <!--Career Info-->

                                <!--Address Info  and Description-->
                                <div class="admin-settings-cameo template-brand-settings">
                                    <div class="settings-container no-border-left no-margin-top">
                                        <div class="cameo-header">
                                            <i class="material-icons cameo-header-icon">business</i>
                                            <span> Address Information & Description</span>
                                        </div>
                                        <div class="cameo-content">
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput :label="' Place of Birth '"
                                                            v-model="userProfile.placeOfBirth"
                                                            :containerClass="'dense'"
                                                            :inputType="'text'">
                                                    <p class="template-tip">{{ userProfile.placeOfBirth || emptyText
                                                        }}</p>
                                                </AdminInput>
                                            </div>
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput
                                                    :label="' Place of Resident '"
                                                    v-model="userProfile.placeOfResident"
                                                    :containerClass="'dense'"
                                                    :inputType="'text'"><p class="template-tip">{{
                                                    userProfile.placeOfResident || emptyText }}</p></AdminInput>
                                            </div>
                                            <div class="layout-align-space-around-start layout-row">
                                                <AdminInput
                                                    :label="'Personal Description'"
                                                    :containerClass="'dense'"
                                                    :inputType="'textarea'">
                                                    <p class="template-tip"
                                                       v-if="userProfile.personalDescription"
                                                       v-html="userProfile.personalDescription"></p>
                                                    <p class="template-tip"
                                                       v-else>{{ emptyText }}</p>
                                                </AdminInput>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Address Info and Description-->
                            </form>
                        </div>
                    </MasterSingleDetailCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserBase from '@bases/UserBase.js'

    import EducationProfile from '@com/User/Member/EducationProfile.vue'
    import CareerProfile from '@com/User/Member/CareerProfile.vue'

    import {mapActions} from 'vuex'

    export default UserBase.extend({
        name: "SingleMemberProfile",
        data: () => ({
            title: 'Member Profile',
            tabs: [{name: 'Member Profile'}],
            user_auth_info: {},
        }),
        components: {
            EducationProfile,
            CareerProfile
        },
        methods: {
            ...mapActions(['fetchMemberProfile']),
            onBackButtonClick() {
                this.Route({name: 'members-profile', params: {setQueryFilters: true}});
            },
            getUserProfile(user_id) {
                this.isLoading = true;
                this.fetchMemberProfile(user_id)
                    .then(res => {
                        let s = res.success, d = res.data, op = this.options;
                        if (s) {
                            op.organization = d.organizes;
                            op.workCategories = d.departments;
                            if (!this.$utils.isEmptyVar(d.user_profile)) {
                                this.userProfile = d.user_profile;
                                this.user_auth_info = d.user_profile.user_auth_info;
                            }
                        }
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Failed to load member profile!', dt: 3500});
                        this.isLoading = false;
                    })
            },
            showFullImage(imgUrl) {
                window.open(this.baseUrl + imgUrl, '_blank');
            }
        },
        created() {
            this.getUserProfile(this.$route.params.user_id);
        }
    });
</script>
