<template>
  <div>
          <div class="culumns">
            <div class="column is-12 is-mobile">
              <carousel
              :per-page="1"
              :mouse-drag="true"
              :autoplay="false"
              :autoplayHoverPause="false"
              :autoplayTimeout="9000"
              :paginationEnabled="true"
              :speed="900"
              :loop="true"
              :paginationPadding="1"
              :paginationSize="8"
              :navigationEnabled="true"
              :navigationPrevLabel='`<span class="prev"> <i class="fa fa-chevron-left aria-hidden="true""></i>
            </span>`'
            :navigationNextLabel='`<span class="next"><i class="fa fa-chevron-right" aria-hidden="true"></i</span>`'
              >
              <slide v-for="(service, index) in homeData.latest_scholarship" :key="index">
                    <div class="slider">
                        <div class="columns">
<div class="column is-6">
                            <img class="img" src="/assets/images/service-card.png" alt="">      
</div>
<div class="column is-6">
                          <div class="pro-service">
                             <a @click="getDetail('scholarship', scholarship)">
                        <h4 class="title" v-html="$utils.sub($utils.strip(service.title), 100)"></h4>
                            </a>
                            <p class="des" v-html="$utils.sub($utils.strip(service.description), 200)"></p>
                      </div>
                      <div class="btn-group">
                          <button class="button btn-light">Read more</button>
                              <button class="button btn-warning">Register</button>
                      </div>
</div>
                  </div>
</div>
                </slide>
              </carousel>
            </div>
          </div>
  </div>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";
import Base from "@com/Bases/GeneralBase.js";

export default Base.extend({
  name: "Service",

  components: {
    Carousel,
    Slide
  }
});
</script>

<style scope>
.slider{
  padding-right: 4px;
}
.slider_bck {
  height: 450px;
  background-size: cover !important;
  background-repeat: no-repeat !important;
}

.icon-pro img{
  width: 200px;
  height: 300px;
}
.pro-category{
  text-align: center;
}
.pro-name{
  font-size: 14px;
  font-weight: 400;
  color: #7461cf;
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
  .icon-pro img{
    width: 100px;
    height: 50px;
    width: 100px;
    height: 64px;
    padding: 0px 8px 4px 0px;
  }
}



.prev,
.next {
position: absolute;
    top: 30%;
    width: 30px;
    height: 30px;
    border: 2px solid #7461cf;
    color: #fff;
    border-radius: 50%;
    margin-top: -30px;
    margin-left: -36px;
    cursor: pointer;
    line-height: 26px;
    text-align: center;
    text-indent: -2px;
  transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.prev:hover,
.next:hover {
  background: #221e21;
  color: #fff;
  transform: scale(1.2);
}
.prev:active,
.next:active {
  transform: translate(0, 3px) scale(1.2);
}

.next {
  right: 0;
  margin-left: auto;
  margin-right: -28px;
  text-indent: 2px;
}
</style>
