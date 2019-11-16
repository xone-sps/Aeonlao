<template>
    <div>
    <!-- <header class="site-header">
        <div class="top-header">
            <div class="container">
                <div class="row-table">
                    <div class="col-auto">
                        <a href="index.html" class="link logo">
                            <div class="row-table">
                                <div class="col-auto">
                                    <div class="thumb"><img src="image/asset/logo.png" alt="Aeon LEASING Service (LAO) Co., Ltd."></div>
                                </div>
                                <div class="col">
                                    <div class="content">
                                        <h4 class="title">Aeon LEASING Service (LAO) Co., Ltd.</h4>
                                        <h4 class="sub-title">ບໍລິສັດ ອິອອນ ບໍລິການເຊົ່າສິນເຊື່ອ (ລາວ) ຈຳກັດ</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <div class="menu-mobile-btn">
                            <a role="button" v-if="isMobile" @click="showMobileNav()" class="btn-mobile">
                                <span class="bar active"></span>
                                <span class="bar active"></span>
                                <span class="bar active"></span>
                                <span class="bar active"></span>
                            </a>
                        </div>

                        <nav class="nav-menu">
                            <ul class="nav-list">
                                <li class="active">
                                    <router-link to="/" active-class="active" class="link" exact>Home</router-link>
                                </li>
                                <li>
            <router-link :to="{name: 'promotion'}" active-class="active" class="link" exact>
                        Promotion
                    </router-link>
                                    </li>
                                <li>
                    <router-link :to="{name: 'service'}" active-class="active" class="link" exact>
                        Service
                    </router-link>
                                </li>
                                <li><a class="link" href="#">AEON Express Card</a></li>
                                <li>
                <router-link :to="{name: 'shop'}" active-class="active" class="link" exact>
                        Shop
                    </router-link>
                                </li>
                    <li><router-link :to="{name: 'news'}" active-class="active" class="link" exact>News
                    </router-link></li>
                    <li><router-link :to="{name: 'activities'}" active-class="active" class="link" exact>
                        Activity
                    </router-link></li>
                    <li>
                        <router-link to="/about" active-class="active" class="link" exact>About
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/contact" active-class="active" class="link" exact>Contact
                        </router-link>
                    </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header> -->

        <header>
            <div class="tobbar is-hidden-mobile">
                <div class="container">
                    <div class="columns">
                        <div class="column is-6">

                        </div>
                        <div class="column is-6">
                            <ul id="top_links navbar-end">
                            </ul>
                        </div>
                    </div>
                </div>
            </div> 

            <nav class="navbar" role="navigation" aria-label="main navigation">
                <div class="container">
                  <div class="navbar-brand">
                      <router-link class="navbar-item logo" to="/">
                        <img
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
<ul class="nav-list">
 <li> <router-link to="/" active-class="active" class="navbar-item menu" exact>Home</router-link></li>
<li><router-link :to="{name: 'promotion'}" class="navbar-item" active-class="active" exact>
                        Promotion
                    </router-link></li>
<li>          <router-link :to="{name: 'service'}" class="navbar-item" active-class="active" exact>
                        Service
                    </router-link></li>
<li>                    <router-link :to="{name: 'shop'}" class="navbar-item" active-class="active" exact>
                        Shop
                    </router-link></li>
<li>                    <router-link :to="{name: 'news'}" active-class="active" class="navbar-item" exact>News
                    </router-link></li>
<li>                    <router-link :to="{name: 'activities'}" active-class="active" class="navbar-item" exact>
                        Activity
                    </router-link></li>
<li>
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
                        <li><router-link to="/about" active-class="active" class="navbar-item" exact>About
                        </router-link></li>
                        <li><router-link to="/contact" active-class="active" class="navbar-item" exact>Contact
                        </router-link></li>
                    </div>
                </div>
</li>
</ul>
            </div>

            <!-- <div class="navbar-end">
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
</div> -->

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
/* .navbar-brand a{
    margin-left: 40px;
} */
.navbar-brand .logo img{
    display: block;
    width: 50px;
    height: 50px;
}
.navbar-menu .navbar-start ul li{
    display: inline-block;
    
}
/* .nav-list>li>a{
    padding: 22px 0px 22px 0xp;
} */
.nav-list>li + li:before {
    content: '';
    width: 2px;
    height: 12px;
    background-color: #d9d9d9;
    border-right: 1px solid #ededed;
    margin: 0 15px;
    display: inline-block;
    vertical-align: middle;
}
.nav-list>li .navbar-item {
    font-weight: normal;
    text-transform: uppercase;
    display: inline-block;
    vertical-align: middle;
        padding: 22px 0px 22px 0xp;

}
/* .navbar-item, .navbar-link{
         color: #afafaf;
} */
.navbar-item .active{
          color: #7461CF;
}
.nav-list > li > .active:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 17%;
    right: 0;
    width: 70%;
    height: 4px;
    color: #7461CF;
    background-color: #f4a10e;
}
.nav-list > li > .active:before {
    content: '';
    position: absolute;
    bottom: 3px;
    left: 0;
    right: 0;
    margin: auto;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 8px 8px 8px;
    color: #7461CF;
    border-color: transparent transparent #f4a10e transparent;
}
@media (max-width: 1366px){
.top-header .logo {
    width: 330px;
}}
</style>

