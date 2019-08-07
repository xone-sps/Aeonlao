<template>
    <div>
        <header>
            <!-- Tob bar -->
            <div class="tobbar">
                <div class="container">
                    <div class="columns is-mobile">
                        <div class="column is-6">
                            <span class="tag_line">"Experts since 1998"</span>
                        </div>
                        <div class="column is-6">
                            <ul id="top_links navbar-end">
                                <li><a href="tel:020 52202014" id="phone_top"><i class="fa fa-phone icon aeon"></i>0045 043204434</a><i class="fab fa-clock-o" aria-hidden="true"></i><span id="opening">Mon - Sat 8.00/18.00</span></li>

                            </ul>
                        </div>
                    </div><!-- End row -->
                </div><!-- End container-->
            </div> <!--End Tob bar-->

            <nav class="navbar" role="navigation" aria-label="main navigation">
                <div class="container">
                  <div class="navbar-brand">
                      <router-link class="navbar-item logo" to="/">
                        <img
                        class="logoImage"
                        :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`" width="48" height="48"
                        >
                        <span>Aeon Leasing Service Laos Co. ,Ltd</span>
                    </router-link>

                    <a role="button" v-if="isMobile" class="navbar-burger burger" @click="showMobileNav()">
                      <span aria-hidden="true"></span>
                      <span aria-hidden="true"></span>
                      <span aria-hidden="true"></span>
                  </a>
              </div>

              <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <router-link to="/" active-class="active" class="navbar-item menu" exact>Home</router-link>

                    <router-link :to="{name: 'promotion'}" class="navbar-item" active-class="active" exact>
                        Promotion
                    </router-link>
                    <router-link :to="{name: 'service'}" class="navbar-item" active-class="active" exact>
                        Service
                    </router-link>
                    <router-link :to="{name: 'shop'}" class="navbar-item" active-class="active" exact>
                        Shop
                    </router-link>
                    <router-link :to="{name: 'news'}" active-class="active" class="navbar-item" exact>News
                    </router-link>
                    <router-link :to="{name: 'activities'}" active-class="active" class="navbar-item" exact>
                        Activity
                    </router-link>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                          More
                      </a>

                      <div class="navbar-dropdown">       
                          <router-link :to="{name: 'document'}" active-class="active" class="navbar-item" exact>
                              Job recruitment
                          </router-link>
                          <a class="navbar-item">
                            Payment Channel
                        </a>
                                                <hr class="navbar-divider">
                        <router-link to="/about" active-class="active" class="navbar-item" exact>About
                        </router-link>
                        <router-link to="/contact" active-class="active" class="navbar-item" exact>Contact
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
              <div class="navbar-item">
                <div class="buttons">
                  <a class="button is-primary">
                    <strong>Sign up</strong>
                </a>
                <template v-if="!LoggedIn()">
                    <a @click="Route({name: 'login'})" class="button main-btn">
                     <strong> Log in</strong>
                 </a>
             </template>
             <template v-else>
                <a @click="gotoDashBoard()" class="main-btn">
                    <strong>Dashboard</strong>
                </a>

                <a
                @click="Logout()"
                class="main-btn"
                :class="[ validated().loading ? 'is-loading' : '']">
                <strong>Sign out</strong>
            </a>
        </template>
    </div>
</div>
</div>
</div>
</div>
</nav>

</header>



<!--====== HEADER PART START ======-->
<!--         <header id="header-part"> -->

<!--     <div class="header-logo-support pt-30 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="logo">
                        <router-link class="logo" to="/">
                            <img
                            class="logoImage"
                            :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`"
                            >
                        </router-link>
                        <span class="site-title">ສູນປະກັນຄຸນນະພາບການສຶກສາລາວ</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="support-button float-right d-none d-md-block">
                        <div class="support float-left">
                            <div class="icon">
                                <img
                                :src="`${baseUrl}${baseRes}assets/images/all-icon/support.png`"
                                alt="icon"
                                >
                            </div>
                            <div class="cont" v-if="!$utils.isEmptyVar( s.phone )">
                                <p>Need Help? call us free</p>
                                <span>{{s.phone}}</span>
                            </div>
                        </div>
                        <div class="button float-left">
                            <template v-if="!LoggedIn()">

                                <a @click="Route({name: 'login'})" class="main-btn">
                                    <strong>ເຂົ້າໃຊ້ລະບົບ</strong>
                                </a>
                            </template>
                            <template v-else>
                                <a @click="gotoDashBoard()" class="main-btn">
                                    <strong>Dashboard</strong>
                                </a>

                                <a
                                @click="Logout()"
                                class="main-btn"
                                :class="[ validated().loading ? 'is-loading' : '']">
                                <strong>Sign out</strong>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> -->

<!--====== SEARCH BOX PART ENDS ======-->
</div>
<!--====== HEADER PART ENDS ======-->
</template>

<script>
import {mapActions, mapGetters, mapState, mapMutations} from "vuex";
import Base from "@com/Bases/GeneralBase.js";

export default Base.extend({
    data() {
        return {
            ...mapGetters(["LoggedIn", "validated"]),
            isActive: ""
        };
    },
    watch: {
        isSidebar: function (n, o) {
            this.isActive = n === "left-side" ? "" : "is-active";
        }
    },
    computed: {
        ...mapState(["isMobile", "isSidebar", "authUserInfo"])
    },
    methods: {
        ...mapMutations(["setSidebar"]),
        ...mapActions(["Logout"]),
        showMobileNav() {
            this.isActive = this.isActive === "is-active" ? "" : "is-active";
            this.setSidebar({isSidebar: this.isActive});
        },
        gotoDashBoard() {
            let type = this.authUserInfo.decodedType;
            if (!this.LoggedIn()) {
                this.$utils.Location('/');
                return;
            }
            console.log(type);
            if (type === 'admin'
                || type === 'super_admin') {
                this.$utils.Location('/admin/me');
        } else if (type === 'field_inspector') {
            this.$utils.Location('/field-inspector/me');
        }else {
            this.$utils.Location(`/${type}/me`);
        }
    }
}
});
</script>

<style scoped>
header {
    width: 100%;
    padding: 0;
    background-color: rgba(255,255,255,1);
    z-index: 900;
    position: fixed;
    left: 0;
    top: 0;
    -moz-box-shadow: 0 0 5px rgba(0,0,0,.3);
    -webkit-box-shadow: 0 0 5px rgba(0,0,0,.3);
    box-shadow: 0 0 5px rgba(0,0,0,.3);
    margin-bottom: 40px;
}
</style>

