<template>
  <div>

<footer>
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
          <h3>Aeon Leasing Service (Laos) Co. , Ltd</h3>
          <p>Id pri consul aeterno petentium. Vivendo abhorreant et vim, et quot persecuti mel. Libris hendrerit ex sea. Duo legere evertitur an, pri hinc doctus definitiones an, vix id dicam putent. Ius ornatus instructior in.</p>
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
          <ul id="contact_details_footer">
            <li id="address_footer"><i class="fa fa-home icon aeon"></i>4 West 31st Street New York, New York - 10001 United States</li>
            <li id="phone_footer"><i class="fa fa-phone icon aeon"></i><a href="tel:020 52202014">+45 423 445 99</a></li>
            <li id="email_footer"><i class="fa fa-envelope icon aeon"></i><a href="mailto:john@example.com">info@gmail.com</a></li>
          </ul>
        </div>
      </div><!-- End row -->  
      <div id="social_footer">
        <ul>
          <li><a href="#0"><i class="fab fa-facebook"></i></a></li>
          <li><a href="#0"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#0"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#0"><i class="fab fa-youtube"></i></a></li>
        </ul>
      </div>
    </div><!-- End container -->
</section>
    <div id="copy">
      <div class="container">
       <div class="is-centered">
          <p>Copy Right 2019</p>
       </div>
      </div>
    </div><!-- End copy -->
  </footer>


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
