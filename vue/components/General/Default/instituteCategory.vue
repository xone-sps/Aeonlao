<template>
  <div>
    <section id="category-part">
      <div class="container">
        <div class="category pt-20 pb-20">
          <div class="row">
            <div class="col-lg-12">
              <div class="category-text text-center">
                <h2>ໝວດໝູ່ສະຖານການສຶກສາ</h2>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <carousel
                :per-page="4"
                :mouse-drag="true"
                :autoplay="true"
                :autoplayHoverPause="false"
                :autoplayTimeout="5000"
                :paginationEnabled="true"
                :speed="500"
                :loop="true"
              >
                <slide v-for="(cat, index) in homeData.instituteCategoriesHome" :key="index">
                  <a @click="Route({name: 'institute', query: {category_id: cat.id}})">
                    <span class="singel-category text-center color-1">
                      <span class="icon">
                        <img
                          :src="`${baseUrl}${baseRes}assets/images/all-icon/ctg-3.png`"
                          alt="Icon"
                        >
                      </span>
                      <span class="cont">
                        <span>{{cat.name}}</span>
                      </span>
                    </span>
                    <!-- single category -->
                  </a>
                </slide>
              </carousel>
              <!--====== SLIDER PART ENDS ======-->
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- category -->
      </div>
      <!-- container -->
    </section>
  </div>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";
import Base from "@com/Bases/GeneralBase.js";

export default Base.extend({
  name: "Category",

  components: {
    Carousel,
    Slide
  }
});
</script>

<style>
.slider_bck {
  height: 450px;
  background-size: cover !important;
  background-repeat: no-repeat !important;
}

@media only screen and (max-width: 1023px) and (min-width: 920px) {
  .slider_bck {
    height: 320px;
  }
}

.slide-text {
  padding-bottom: 8px;
  z-index: 5;
  text-align: center;
  margin-top: -178px;
  position: relative;
  top: -90px;
  transition-delay: 5s;
  transition: ease;
}

.slide-text h1 {
  padding-bottom: 10px;
  font-size: 40px;
  font-weight: 600;
  color: #fff;
}

.slide-text p {
  font-size: 24px;
  font-weight: 400;
  padding-bottom: 20px;
  color: #fff;
}

@media only screen and (max-width: 919px) {
  .slider_bck {
    height: 240px;
  }

  .slide-text {
    top: -50px;
    padding-bottom: 10px;
    z-index: 5;
    margin-top: -138px;
    text-align: center;
    position: relative;
  }

  .slide-text h1 {
    padding-bottom: 4px;
    font-size: 18px;
    font-weight: 600;
    color: #fff;
  }

  .slide-text p {
    font-size: 14px;
    font-weight: 400;
    padding-bottom: 20px;
    color: #fff;
  }

  .main-btn {
    padding: 0px 12px;
    font-size: 12px;
    line-height: 26px;
  }
}
</style>
