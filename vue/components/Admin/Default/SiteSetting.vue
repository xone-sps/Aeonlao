<template>
    <div>
        <Tabs :offsetLeft="getSideBarWidthForTabs()" :tabs="tabs" @ItemClick="(d)=> selectedTab = d"/>
        <div class="module_content layout-column">
            <div class="module_authentication">
                <div class="module-canvas emails-card-wrapper">
                    <!--First Tab -->
                    <template v-if="selectedTab.idx===0">
                        <MasterSingleDetailCard
                            :isLoading="isLoading"
                            @onBackButtonClick="onBackButtonClick"
                            :header="{ title: 'Site Infomation', content: '<p> Changes Logo, Favorite Icon, Site Name, etc..</p>'}"
                            :menuItem="{ name: 'Site Info', icon: 'account_circle'}">
                            <div class="details is-edit">
                                <form @submit.prevent class="admin-form admin-template-form">
                                    <div class="layout-align-space-around-start layout-row">
                                        <AdminInput :label="' Site Name '"
                                                    v-model="siteInfo.site_name"
                                                    :containerClass="'dense'"
                                                    :validateText="validated().site_name"
                                                    placeholder="Laoedu.com"
                                                    @onInputEnter="saveSiteInfo"
                                                    :inputType="'text'">
                                        </AdminInput>
                                    </div>
                                    <div class="admin-settings-cameo template-brand-settings">
                                        <div class="settings-container no-border-left">
                                            <div class="cameo-header">
                                                <i class="material-icons cameo-header-icon">perm_media</i>
                                                <span>Site Images</span>
                                            </div>
                                            <div class="cameo-content">
                                                <!--Website Logo-->
                                                <div class="layout-align-space-around-start layout-row">

                                                    <div class="form-input-container dense">
                                                        <label class="is-center"> Site Logo </label>
                                                        <div
                                                             @click="chooseImage('website_logo')">
                                                            <div title="Choose logo image">
                                                                <img style="width: 100%; height: 100%;cursor:pointer;"
                                                                     v-if="siteInfo.website_logo_base64"
                                                                     :src="siteInfo.website_logo_base64">
                                                                <img style="width: 100%; height: 100%;cursor:pointer;" v-else
                                                                     :src="`${baseUrl}${siteInfo.thumb_website_logo}`">
                                                            </div>
                                                        </div>
                                                        <AdminInput ref="website_logo" v-show="false"
                                                                    @inputImageBase64="(d)=>siteInfo.website_logo_base64=d"
                                                                    @inputFile="(d)=> siteInfo.website_logo=d"
                                                                    :inputType="'file'"/>
                                                        <div admin-messages v-if="validated().website_logo">
                                                            <div admin-message class="message-required ">
                                                                {{ validated().website_logo }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-input-container dense">
                                                        <label class="is-center"> Favorite Icon </label>
                                                        <div class="is-edit in-image-side-form"
                                                             @click="chooseImage('favorite_icon')">
                                                            <div title="Choose logo image">
                                                                <img style="width: 100%; height: 100%;cursor:pointer;"
                                                                     v-if="siteInfo.fav_icon_base64"
                                                                     :src="siteInfo.fav_icon_base64">
                                                                <img style="width: 100%; height: 100%;cursor:pointer;"
                                                                     v-else
                                                                     :src="`${baseUrl}${siteInfo.thumb_favorite_icon}`">
                                                            </div>
                                                        </div>
                                                        <AdminInput ref="favorite_icon" v-show="false"
                                                                    @inputImageBase64="(d)=>siteInfo.fav_icon_base64=d"
                                                                    @inputFile="(d)=> siteInfo.favorite_icon=d"
                                                                    :inputType="'file'"/>
                                                        <div admin-messages v-if="validated().favorite_icon">
                                                            <div admin-message class="message-required ">
                                                                {{ validated().favorite_icon }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Website Logo-->
                                                <!--Fav Icon-->

                                                <!--Fav Icon-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <div class="layout-align-end-center layout-row">
                                            <button @click="saveSiteInfo" class="v-md-button primary">Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </MasterSingleDetailCard>
                    </template>
                    <!--End  First Tab -->
                    <!--Second Tab -->
                    <template v-if="selectedTab.idx===1">
                        <div>
                            <Banner/>
                        </div>
                    </template>
                    <!--End  Second Tab -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AdminBase from "@bases/AdminBase.js";
    import Banner from "@com/Admin/Default/Banner.vue";
    import {mapActions} from "vuex";

    let siteLogo = `/assets/images/${settings.website_logo}${settings.fresh_version}`;
    export default AdminBase.extend({
        name: "site_settings",
        components: {
            Banner
        },
        data: () => ({
            title: "Site Settings",
            watchers: true,
            tabs: [{name: "Site Info", idx: 0}, {name: "Banner", idx: 1}],
            selectedTab: {},
            siteInfo: {
                thumb_website_logo: siteLogo,
                thumb_favorite_icon: siteLogo,
            },//site_name email_logo fav_icon website_logo
            isLoading: false,
        }),
        methods: {
            ...mapActions(["postManageSiteInfo", "getSiteInfo"]),
            saveSiteInfo() {
                this.isLoading = true;
                this.postManageSiteInfo(this.siteInfo)
                    .then(res => {
                        this.showInfoToast({msg: 'The site information was successfully updated!', dt: 3500});
                        this.getItems();
                    })
                    .catch(err => {
                        if (err && !err.errors) {
                            this.showErrorToast({msg: 'Failed to update site information!', dt: 3500})
                        }
                        this.isLoading = false;
                    })
            },
            onBackButtonClick() {
                this.Route({name: "dashboard"});
            },
            chooseImage(ref) {
                this.$refs[ref].triggerInputClick();
            },
            getItems() {
                this.isLoading = true;
                this.getSiteInfo()
                    .then(res => {
                        res.website_logo_base64 = null;
                        res.fav_icon_base64 = null;
                        res.thumb_website_logo = res.website_logo;
                        res.thumb_favorite_icon = res.fav_icon;
                        this.siteInfo = res;
                        this.siteInfo.website_logo = '';
                        this.isLoading = false;
                    }).catch(er => {
                    this.isLoading = false;
                })
            }
        },
        created() {
            this.getItems = this.debounce(this.getItems, 150);
            this.getItems();
        }
    });
</script>
<style>
    .admin-master-card-template
    .details
    .form-input-container
    .control
    input[type="file"] {
        display: none;
    }
</style>

