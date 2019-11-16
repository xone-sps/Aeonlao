<template>
  <div>
    <footer class="site-footer">
		<div class="top-footer">
<section class="section">
  			<div class="container">
				<div class="columns">
					<div class="column is-5">
						<div class="logo">
							<div class="row-table">
								<div class="col-auto">
									<div class="thumb">
                     <Router-link to="/">
                    <img
                      :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`" width="54" height="54"
                      alt="Logo"
                    >
                  </Router-link>
                    </div>
								</div>
								<div class="column" style="float:right;">
									<div class="content">
										<h6 class="title">ບໍລິສັດ ອິອອນ ບໍລິການເຊົ່າສິນເຊື່ອ (ລາວ) ຈຳກັດ</h6>
										<p class="desc">
											ຖະໜົນອາຊຽນ, ຊັ້ນ 1 ແລະ ຊັ້ນ 3, ອາຄານ S 16,ໂຄງການສະກາຍຊິດຕີ້
											ໜ່ວຍ 10, ບ້ານສີບຸນເຮືອງ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="our-office">
							<div class="h-title">Our Office</div>
							<div class="h-desc">Ban Sibunhueang, Chanthaboury District, Vientiane Capital</div>
						</div>
						<div class="call-center">
							<a href="tel:1433" class="link">Call Center. <strong>1433</strong></a>
						</div>
					</div>
					<div class="column is-7">
						<div class="site-block">
							<!-- <a href="javascript:void(0)" class="link btn-site">SITE MAP</a> -->
							<div class="row-table vlign-top">
                            <div class="columns">
  								<div class="column is-6">
									<div class="nav-footer">
										<div class="h-title">about us</div>
										<ul class="nav-list">
											<li><a href="#" class="link">ຂໍ້ມູນບໍລິສັດ</a></li>
											<li><a href="#" class="link">ກິດຈະກຳບໍລິສັດ</a></li>
											<li><a href="#" class="link">ກິດຈະກຳເພື່ອສັງຄົມ</a></li>
										</ul>
									</div>
								</div>
								<div class="column is-6">
									<div class="nav-footer">
										<div class="h-title">Service</div>
										<ul class="nav-list">
											<li><a href="#" class="link">ຜ່ອນສີນຄ້າທົ່ວໄປ</a></li>
											<li><a href="#" class="link">ຜ່ອນສີນຄ້າ ຄຳ</a></li>
											<li><a href="#" class="link">ຜ່ອນລົດຈັກ</a></li>
											<li><a href="#" class="link">ສະໝັກບັດສະມາຊິກ</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
            </div>
					</div>
				</div>
			</div>
</section>
		</div>
		<div class="bottom-footer">
<section class="section">
			<div class="container">
<div class="columns">
  				<div class="row-table">
					<div class="column is-6">
						<div class="title">Copyright 2019  AEON (Laos) Public Company Limited. All rights reserved.</div>
					</div>
					<div class="column is-6">
						<div class="social-list">
							<ul class="item-list">
								<li class="facebook"><a :href="s.facebook" target="_blank" class="link"><span class="fab fa-facebook-f"></span></a></li>
                <li class="twitter"><a v-if="!$utils.isEmptyVar(s.twitter)" :href="s.twitter" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li class="instagram"><a href="#0"><i class="fab fa-instagram"></i></a></li>
                <li class="youtube"><a href="#0"><i class="fab fa-youtube"></i></a></li>
								<!-- <li class="google"><a href="#" target="_blank" class="link"><span class="fab fa-google-plus-g"></span></a></li> -->
							</ul>
						</div>
					</div>
				</div>
</div>
			</div>
</section>
		</div>
    </footer>



<!-- <footer>
<section class="section">
      <div class="container">
      <div class="columns ">
        <div class="column is-4">
      <div class="logo">
                    <Router-link to="/">
                    <img
                      :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`" width="54" height="54"
                      alt="Logo"
                    >
                  </Router-link>
                </div>
          <h3>{{s.site_name}}</h3>
        </div>
        <div class="column is-4">
          <h3>Discover</h3>
          <ul>
            <li><a href="#0">About us</a></li>
            <li><a href="#0">FAQ</a></li>
            <li><a href="#0">Quotation</a></li>
            <li><a href="#0">Contacts</a></li>
            <li><a href="#0">Shop</a></li>
            <li><a href="#0">Terms and condition</a></li>
          </ul>
        </div>
        <div class="column is-4" id="contact_bg">
          <h3>Contacts</h3>
          <ul id="contact_details_footer" v-if="!$utils.isEmptyVar( s )">
            <li id="address_footer"><i class="fa fa-home icon aeon"></i>{{s.address}}</li>
            <li id="phone_footer"><i class="fa fa-phone icon aeon"></i><a href="tel:020 52202014">{{s.phone}}</a></li>
            <li id="email_footer"><i class="fa fa-envelope icon aeon"></i><a href="mailto:john@example.com">{{s.email}}</a></li>
          </ul>
        </div>
      </div>
      <div id="social_footer">
        <ul>
          <li><a :href="s.facebook" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
          <li><a v-if="!$utils.isEmptyVar(s.twitter)" :href="s.twitter" target="_blank"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#0"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#0"><i class="fab fa-youtube"></i></a></li>
        </ul>
      </div>
    </div>
</section>
    <div id="copy">
      <div class="container">
       <div class="is-centered">
          <p>Copy Right 2019</p>
       </div>
      </div>
    </div>
  </footer> -->


<!-- 

    <footer id="footer-part">
      <div class="footer-top pt-10 pb-40">
        <div class="container">
          <div class="row mobile-left">
            <div class="col-lg-4 col-md-6">
              <div class="footer-about mt-40">
                <div class="logo">
                  <Router-link to="/">
                    <img
                      :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`"
                      alt="Logo"
                    >
                  </Router-link>
                </div>
                <p>ສູນປະກັນຄຸນນະພາບການສຶກສາລາວ</p>
                <ul class="mt-20">
                  <li>
                    <a :href="s.facebook" target="_blank" >
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li>
                    <a v-if="!$utils.isEmptyVar(s.twitter)" :href="s.twitter" target="_blank">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                </ul>
              </div>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="footer-link mt-40">
                <div class="footer-title pb-20">
                  <h6>Sitemap</h6>
                </div>
                <ul>
                  <li>
                    <router-link :to="{name:'home'}">
                      <i class="fa fa-angle-right"></i>ໜ້າຫຼັກ
                    </router-link>
                  </li>

                  <li>
                    <Router-link :to="{name:'institute'}">
                      <i class="fa fa-angle-right"></i>ສະຖານການສຶກສາ
                    </Router-link>
                  </li>
                  <li>
                    <router-link :to="{name:'news'}">
                      <i class="fa fa-angle-right"></i>ຂ່າວສານ
                    </router-link>
                  </li>
                     <li>
                   <router-link :to="{name:'scholarships'}">
                      <i class="fa fa-angle-right"></i>ທຶນການສຶກສາ
                   </router-link>
                  </li> <li>
                   <router-link :to="{name:'activities'}">
                      <i class="fa fa-angle-right"></i>ກິດຈະກຳ
                   </router-link>
                  </li>
                  <li>
                   <router-link :to="{name:'document'}">
                      <i class="fa fa-angle-right"></i>ເອກະສານ
                   </router-link>
                  </li>
                  <li>
                  <router-link :to="{name:'contact'}">
                      <i class="fa fa-angle-right"></i>ຂໍ້ມູນຕິດຕໍ່
                  </router-link>
                  </li>
                  <li>
                    <router-link :to="{name:'about'}">
                      <i class="fa fa-angle-right"></i>ກ່ຽວກັບສູນ
                    </router-link>
                  </li>
                </ul>
              </div>

            </div>
            <div class="col-lg-4 col-md-6">
              <div class="footer-address mt-40">
                <div class="footer-title pb-20">
                  <h6>Contact Us</h6>
                </div>
                <ul v-if="!$utils.isEmptyVar( s )">
                  <li>
                    <div class="icon">
                      <i class="fa fa-home"></i>
                    </div>
                    <div class="cont">
                      <p>{{s.address}}</p>
                    </div>
                  </li>
                  <li>
                    <div class="icon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <div class="cont">
                      <p>{{s.phone}}</p>
                    </div>
                  </li>
                  <li>
                    <div class="icon">
                      <i class="far fa-envelope"></i>
                    </div>
                    <div class="cont">
                      <p>{{s.email}}</p>
                    </div>
                  </li>
                </ul>
              </div>

            </div>
          </div>

        </div>

      </div>


      <div class="footer-copyright pt-10 pb-25">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <div class="copyright text-center pt-15">
                <p>
                  Copyright ©
                  {{$utils.getDateTime(new Date()).years}} All rights reserved | Powered by
                  <a
                    href="https://www.mekong.digital"
                    target="_blank"
                  >Mekong Digital Works</a>
                </p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="copyright text-md-right text-center pt-15"></div>
            </div>
          </div>

        </div>

      </div>

    </footer> -->

  </div>
</template>

<style>
</style>
<script>
    import Base from "@com/Bases/GeneralBase.js";
    export default Base.extend({});
</script>
<style scoped>
.top-footer .logo .content {
    padding-left: 10px;
}
.top-footer .logo {
    display: block;
}
.top-footer .logo .content .desc {
    font-family: 'PhetsarathOT';
    font-size: 15px;
    color: #fff;
    margin-bottom: 0;
}
.top-footer .logo .content .title {
    font-family: 'PhetsarathOT';
    font-size: 18px;
    color: #fff;
    margin-bottom: 5px;
}
  .top-footer {
    background-color: #7b4dc5;
    position: relative;
    padding: 65px 0;
}
.top-footer .our-office {
    padding-top: 30px;
}
.top-footer .call-center {
    padding-top: 40px;
}
.top-footer .call-center .link {
    font-size: 30px;
    color: #ffffff;
}
.top-footer .call-center .link strong {
    font-size: 56px;
    color: #f9a61a;
}
.top-footer:before {
    content: '';
    display: block;
    position: absolute;
    z-index: 0;
    width: 45%;
    height: 100%;
    left: 0;
    top: 0;
    bottom: 0;
    background-color: #7f4fcd;
}
.bottom-footer {
    background-color: #6e46ae;
    border-top: 10px solid #f9a61a;
    padding: 30px 0;
}
.row-table {
    display: table;
    width: 100%;
}
.row-table .col-auto {
    width: 1%;
}
.top-footer .our-office {
    padding-top: 30px;
}
.top-footer .h-title {
    font-size: 36px;
    color: #f9a61a;
    text-transform: uppercase;
}
.top-footer .our-office .h-title {
    margin-bottom: 20px;
}
footer .h-desc {
    font-size: 21px;
}
.top-footer .logo .thumb {
    width: 80px;
    border: 3px solid #fff;
    border-radius: 5px;
}
.top-footer .site-block {
    margin-top: 35px;
    transition: all 200ms ease-out;
}
.item-list {
    margin: 0;
    padding: 0;
}
.bottom-footer .title {
    font-size: 14px;
    color: #fff;
}
.row-table > div {
    display: table-cell;
    vertical-align: middle;
    position: relative;
}
.bottom-footer .social-list li {
    float: none;
    display: inline-block;
    vertical-align: middle;
}
.bottom-footer .social-list {
    text-align: right;
}
.social-list .facebook,.twitter,.instagram,.youtube .link {
    margin-right: 10px;
}
.social-list .google .link {
    background-color: #dc4e41;
}
@media (max-width: 1366px){
.top-footer .logo .thumb {
    width: 50px;
    border: 2px solid #fff;
}
.top-footer .logo .content .title {
    font-size: 16px;
}
footer .logo .content .desc {
    font-size: 14px;
}
.top-footer .site-block .nav-footer {
    padding-left: 85px;
    padding-right: 20px;
}
}

</style>