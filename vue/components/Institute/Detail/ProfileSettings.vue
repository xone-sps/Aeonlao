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
                                    content: `<p> Changes your personal information such as First name, Last name, Profile picture, Major,  University of Graduation in Japan and so on.</p>`}'
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
                                                <div class="settings-container no-border-left" border-bottom>
                                                    <div class="cameo-header">
                                                        <i class="material-icons cameo-header-icon">business</i>
                                                        <span> Institute Information</span>
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
                                                            <AdminInput :label="'Institute name'"
                                                                        v-model="userProfile.institute_name"
                                                                        :validateText="validated().institute_name"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{ userProfile.institute_name ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                            <AdminInput :label="' Short name '"
                                                                        v-model="userProfile.short_institute_name"
                                                                        :validateText="validated().short_institute_name"
                                                                        :containerClass="'is-second-input dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{
                                                                    userProfile.short_institute_name ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="' Phone number '"
                                                                        v-model="userProfile.phone_number"
                                                                        :validateText="validated().phone_number"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{ userProfile.phone_number ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                            <div
                                                                class="form-input-container flex is-second-input dense"
                                                                full>
                                                                <label>Date of Founded</label>
                                                                <Datetime v-model="userProfile.founded"
                                                                          value-zone="Asia/Vientiane"
                                                                          zone="Asia/Vientiane"
                                                                          format="dd-MM-yyyy"
                                                                          input-id="dateOfFounded"
                                                                          :input-class="'admin-input-datepicker'"/>
                                                                <p class="template-tip" v-if="userProfile.founded">
                                                                    {{$utils.formatTimestmp(userProfile.founded,
                                                                    false)}}</p>
                                                                <p class="template-tip" v-else>{{emptyText }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="' Public Email '"
                                                                        v-model="userProfile.public_email"
                                                                        :validateText="validated().public_email"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'text'">
                                                                <p class="template-tip">{{ userProfile.public_email ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--Personal Info-->
                                            <!--Institute Category Info -->
                                            <div class="admin-settings-cameo template-brand-settings">
                                                <div class="settings-container no-border-left no-margin-top"
                                                     border-bottom>
                                                    <div class="cameo-header">
                                                        <i class="material-icons cameo-header-icon">category</i>
                                                        <span>Institute Category</span>
                                                    </div>
                                                    <div class="cameo-content">
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <div
                                                                class="form-multi-select-container flex dense"
                                                                full>
                                                                <multiselect class="select-multiple"
                                                                             label="name" track-by="id"
                                                                             v-model="userProfile.institute_category"
                                                                             placeholder="Select institute category"
                                                                             open-direction="bottom"
                                                                             :show-no-results="false"
                                                                             :preserve-search="true"
                                                                             :hide-selected="false"
                                                                             :options="options.institute_categories"
                                                                             @input="getParentCategories">
                                                                </multiselect>
                                                                <template v-if="validated().institute_category">
                                                                    <div class="form-input-container">
                                                                        <input v-show="false"/>
                                                                        <div admin-messages>
                                                                            <div admin-message
                                                                                 class="message-required ">
                                                                                {{ validated().institute_category }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                                <p class="template-tip">{{
                                                                    (userProfile.institute_category &&
                                                                    userProfile.institute_category.name) || emptyText
                                                                    }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <template
                                                        v-if="userProfile.institute_category && userProfile.institute_category.have_parent==='yes'">
                                                        <div class="cameo-header">
                                                            <i class="material-icons cameo-header-icon">supervised_user_circle</i>
                                                            <span>Parent Institute Category</span>
                                                        </div>
                                                        <div class="cameo-content">
                                                            <div class="layout-align-space-around-start layout-row">
                                                                <div
                                                                    class="form-multi-select-container flex dense"
                                                                    full>
                                                                    <multiselect class="select-multiple"
                                                                                 label="name" track-by="id"
                                                                                 v-model="userProfile.parent_institute_category"
                                                                                 placeholder="Select institute category"
                                                                                 open-direction="bottom"
                                                                                 :show-no-results="false"
                                                                                 :preserve-search="true"
                                                                                 :hide-selected="false"
                                                                                 :options="parentCategories">
                                                                    </multiselect>
                                                                    <template
                                                                        v-if="validated().parent_institute_category">
                                                                        <div class="form-input-container">
                                                                            <input v-show="false"/>
                                                                            <div admin-messages>
                                                                                <div admin-message
                                                                                     class="message-required ">
                                                                                    {{
                                                                                    validated().parent_institute_category
                                                                                    }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <p class="template-tip">{{
                                                                        (userProfile.parent_institute_category &&
                                                                        userProfile.parent_institute_category.name) ||
                                                                        emptyText }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>


                                                </div>
                                            </div>
                                            <!--Institute Category Info -->
                                            <!--Contact Info -->
                                            <div class="admin-settings-cameo template-brand-settings">
                                                <div class="settings-container no-border-left no-margin-top">
                                                    <div class="cameo-header">
                                                        <i class="material-icons cameo-header-icon">account_box</i>
                                                        <span> Contact Information & About</span>
                                                    </div>
                                                    <div class="cameo-content">
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput :label="' Address'"
                                                                        v-model="userProfile.address"
                                                                        :validateText="validated().address"
                                                                        :containerClass="'dense'"
                                                                        :inputType="'textarea'">
                                                                <p class="template-tip">{{ userProfile.address ||
                                                                    emptyText }}</p>
                                                            </AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput
                                                                :label="' Facebook '"
                                                                v-model="userProfile.facebook"
                                                                :containerClass="'dense'"
                                                                :inputType="'text'"><p class="template-tip">{{
                                                                userProfile.facebook ||
                                                                emptyText }}</p></AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput
                                                                :label="' Google Map '"
                                                                v-model="userProfile.googlemap"
                                                                :containerClass="'dense'"
                                                                :inputType="'text'"><p class="template-tip">{{
                                                                userProfile.googlemap ||
                                                                emptyText }}</p></AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row">
                                                            <AdminInput
                                                                :label="'Website'"
                                                                v-model="userProfile.website"
                                                                :containerClass="'dense'"
                                                                :inputType="'text'"><p class="template-tip">{{
                                                                userProfile.website ||
                                                                emptyText }}</p></AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row"
                                                             v-if="!isEdit">
                                                            <AdminInput
                                                                :label="'About Institute'"
                                                                :containerClass="'dense'"
                                                                :inputType="'textarea'">
                                                                <p class="template-tip"
                                                                   v-if="userProfile.about"
                                                                   v-html="userProfile.about"></p>
                                                                <p class="template-tip"
                                                                   v-else>{{ emptyText
                                                                    }}</p>
                                                            </AdminInput>
                                                        </div>
                                                        <div class="layout-align-space-around-start layout-row"
                                                             v-show="isEdit">
                                                            <Editor
                                                                id="description_jaol_editor"
                                                                v-model="userProfile.about"
                                                                :containerClass="'dense'"
                                                                label="Institute Description"
                                                                @editorMounted="(ed)=> editor = ed"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Address Info and Description-->
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
    import InstituteBase from '@bases/InstituteBase.js'

    export default InstituteBase.extend({
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
