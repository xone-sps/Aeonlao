<template>
    <div class="no-tip">
        <Tabs :bgColor="theme.bgColor" :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <div class="md-single-grid">
                        <div ad-cell="12">
                            <div class="admin-canvas-bar">
                                <div class="admin-canvas-bar-container">
                                    <div class="admin-canvas-bar-section"> Profile Settings and Credential</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <MasterDetailCard :isLoading="isLoading" @onStateChanged="stateChanged">
                        <template slot="details">
                            <div class="details"
                                 :class="[{'is-edit': isEdit}]">
                                <MasterDetailCardItem :header='{ title: " Profile Settings " }'>
                                    <!--Profile settings start-->
                                    <MasterDetailCardMenu
                                        :selected="true"
                                        :header='{
                                    title: "My Profile",
                                    content: `<p> Changes your personal information such as First name, Last name.</p>`}'
                                        :menuItem='{name: "My Profile", icon: "account_circle", selected: true}'>
                                        <form @submit.prevent class="admin-form admin-template-form">
                                            <!--Personal Info -->
                                            <button v-if="!isEdit" @click="isEdit=true"
                                                    class="v-md-button v-md-icon-button edit-button"><i
                                                class="material-icons">edit</i></button>
                                            <button v-if="isEdit" @click="isEdit=false"
                                                    class="v-md-button v-md-icon-button edit-button"><i
                                                class="material-icons">close</i></button>
                                            <div class="admin-settings-cameo template-brand-settings">
                                                <div class="settings-container no-border-left">
                                                    <div class="cameo-header">
                                                        <i class="material-icons cameo-header-icon">business</i>
                                                        <span> Field Inspector Information</span>
                                                    </div>
                                                    <div class="cameo-content" style="padding-bottom: 14px;">
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <div class="form-input-container dense">
                                                                <label class="is-center"> Profile Picture </label>
                                                                <div class="user-picture-link-inner in-image-side-form"
                                                                     :class="[{'is-edit':isEdit }]"
                                                                     @click="chooseProfileImage">
                                                                    <div title="profile"
                                                                         class="user-picture-link-inner-title">
                                                                        <img style="width: 100%; height: 100%;"
                                                                             v-if="userProfile.profile_image_base64!==''"
                                                                             :src="`${userProfile.profile_image_base64}`">
                                                                        <img v-else
                                                                             :style="`${getImageStyleProfile()}`"
                                                                             :src="`${baseUrl}${authUserInfo.thumb_image}`">
                                                                    </div>
                                                                    <span v-if="isEdit"
                                                                          class="user-picture-link-change is-black">Change</span>
                                                                </div>
                                                                <AdminInput ref="profile-image" v-show="false"
                                                                            @inputImageBase64="(d)=>userProfile.profile_image_base64=d"
                                                                            @inputFile="(d)=> userProfile.profile_image=d"
                                                                            :inputType="'file'"/>
                                                                <div admin-messages
                                                                     v-if="validated().profile_image">
                                                                    <div admin-message class="message-required ">
                                                                        {{ validated().profile_image }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'First name'"
                                                                        v-model="userProfile.first_name"
                                                                        :validateText="validated().first_name"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{ userProfile.first_name ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                            <AdminInput :label="' Last name '"
                                                                        v-model="userProfile.last_name"
                                                                        :validateText="validated().last_name"
                                                                        :containerClass="'is-second-input dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{
                                                                    userProfile.last_name ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Personal Info-->
                                            <!--Actions Profile-->
                                            <div class="actions" v-if="isEdit">
                                                <div class="layout-align-end-center layout-row">
                                                    <button @click="isEdit=false"
                                                            class="v-md-button secondary">
                                                        Cancel
                                                    </button>
                                                    <button @click="saveProfileSettings" class="v-md-button primary">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                            <!--Actions Profile-->
                                        </form>
                                    </MasterDetailCardMenu>
                                    <!--Profile settings end-->

                                    <!--Credentials Start-->
                                    <MasterDetailCardMenu
                                        :header='{
                                    title: "Credentials",
                                    content: `<p> You can change your password or even your email address, both use to login to Jaol site, <strong>When you have changed your credentials please use the new credentials to login to this site</strong>.<br><strong>Note: Please use truly email address</strong>.</p>`}'
                                        :menuItem='{name: "Credentials", icon: "email", selected: false}'>
                                        <form @submit.prevent class="admin-form admin-template-form" novalidate>

                                            <button v-if="!isEdit" @click="isEdit=true"
                                                    class="v-md-button v-md-icon-button edit-button"><i
                                                class="material-icons">edit</i></button>
                                            <button v-if="isEdit" @click="isEdit=false"
                                                    class="v-md-button v-md-icon-button edit-button"><i
                                                class="material-icons">close</i></button>

                                            <div class="admin-settings-cameo template-brand-settings">
                                                <div class="settings-container add-padding">
                                                    <div class="cameo-header">
                                                        <i class="material-icons cameo-header-icon">settings</i>
                                                        <span> Credentials Information</span>
                                                    </div>
                                                    <div class="cameo-content">
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'New Email '"
                                                                        :validateText="validated().new_email"
                                                                        v-model="userCredential.new_email"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'email'">
                                                                <p class="template-tip">New Email</p>
                                                            </AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'New Password '"
                                                                        :validateText="validated().new_password"
                                                                        v-model="userCredential.new_password"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'password'">
                                                                <p class="template-tip">New Password</p>
                                                            </AdminInput>
                                                        </div>
                                                        <div
                                                            v-if="isEdit && !$utils.isEmptyVar(userCredential.new_password)"
                                                            class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'New Password Confirmation'"
                                                                        :validateText="validated().new_password_confirmation"
                                                                        v-model="userCredential.new_password_confirmation"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'password'">
                                                            </AdminInput>
                                                        </div>
                                                        <div v-if="getStateCredentialsChanges()"
                                                             class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'Current Password '"
                                                                        :validateText="validated().current_password"
                                                                        v-model="userCredential.current_password"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'password'">
                                                            </AdminInput>
                                                        </div>
                                                        <div v-if="getStateCredentialsChanges()"
                                                             class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="'Logout from all other devices'"
                                                                        :containerClass="'dense'"
                                                                        v-model="userCredential.logout_from_all_other_devices"
                                                                        :inputType="'checkbox'">
                                                            </AdminInput>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="actions"
                                                 v-if="getStateCredentialsChanges()">
                                                <div class="layout-align-end-center layout-row">
                                                    <button @click="isEdit=false"
                                                            class="v-md-button secondary">
                                                        Cancel
                                                    </button>
                                                    <button @click="saveUserCredentials" class="v-md-button primary">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </MasterDetailCardMenu>
                                    <!--Reset Password end-->
                                </MasterDetailCardItem>
                            </div>
                        </template>
                    </MasterDetailCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import FieldInspectorBase from '@bases/FieldInspectorBase.js'

    export default FieldInspectorBase.extend({
        name: "ProfileSettings",
        data: () => ({
            title: 'My Profile Settings',
            tabs: [{name: 'Profile Settings'}],
            userCredential: {},
            isEdit: false,
            editor: null,
            parentCategories: [],
        }),
        watch: {
            isEdit: function (n, o) {
                if (!n) {
                    this.resetMemberData();
                }
            },
            userProfile: function (n) {
                this.setHeightAdminCard();
            }
        },
        methods: {
            ...mapActions(['postManageUserProfile', 'postChangeCredentialsUser', 'fetchInstituteParentCategories']),
            resetMemberData() {
                this.setClearValidate(this.userProfile);
                this.setClearValidate(this.userCredential);
                this.getOptions(false);
                this.userCredential = {};
            },
            stateChanged() {
                this.isEdit = false;
                this.resetMemberData();
            },
            getStateCredentialsChanges() {
                return this.isEdit && (!this.$utils.isEmptyVar(this.userCredential.new_email) || !this.$utils.isEmptyVar(this.userCredential.new_password))
            },
            getImageStyleProfile() {
                return (this.getImageProfileExt() === 'svg' || this.getImageProfileExt() === 'gif') ? `width: 100%; height: 100%` : ''
            },
            getImageProfileExt() {
                return this.$utils.getFileExtension(this.authUserInfo.thumb_image);
            },
            chooseProfileImage() {
                if (this.isEdit) {
                    this.$refs['profile-image'].triggerInputClick();
                }
            },
            saveProfileSettings() {
                let dt = 3500;
                this.isLoading = true;
                this.postManageUserProfile(this.userProfile)
                    .then(res => {
                        if (res.success) {
                            this.showInfoToast({msg: 'Your profile was updated!', dt});
                            this.getOptions();
                            this.isEdit = false;//collapsed editing
                        }
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Failed to update your profile!', dt});
                        this.isLoading = false;
                    })
            },
            saveUserCredentials() {
                let dt = 3500;
                this.isLoading = true;
                this.postChangeCredentialsUser(this.userCredential)
                    .then(res => {
                        if (res.success) {
                            this.getOptions();
                            this.userCredential = {};
                            this.isEdit = false;
                            this.showInfoToast({msg: 'Your credentials was updated!', dt});
                        } else {
                            this.showErrorToast({msg: 'Failed to update your credentials!', dt});
                        }
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.showErrorToast({msg: 'Failed to update your credentials!', dt});
                        this.isLoading = false;
                    });
            },
            getParentCategories() {
                if (!this.userProfile.institute_category) {
                    this.parentCategories = [];
                    return;
                }
                this.fetchInstituteParentCategories(this.userProfile.institute_category.id)
                    .then(res => {
                        this.parentCategories = res;
                    }).catch(() => {
                })
            },
            setHeightAdminCard() {
                let card = this.jq('.admin-master-card');
                if ((card.get(0) || {}).clientHeight > 600) {
                    card.get(0).style.height = '100vh';
                    setTimeout(() => {
                        card.get(0).style.height = 'auto';
                    }, 800)
                }
            },
        },
        created() {
            this.getOptions();
            this.setHeightAdminCard = this.debounce(this.setHeightAdminCard, 200);
        },
    });
</script>
