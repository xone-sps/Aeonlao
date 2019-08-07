<template>
    <div>
        <carousels :items="homeData.banners"/>
        <!--====== SLIDER PART ENDS ======-->

        <!--====== CATEGORY PART START ======-->
        <InstituteCategory/>
        <!--====== CATEGORY PART ENDS ======-->

        <!--====== ABOUT PART START ======-->

        <section id="about-part">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="section-title mt-50">
                            <h2>ກ່ຽວກັບສູນ</h2>
                            <h5>ຍິນດີຕ້ອນຮັບສູ່ ສປຄ</h5>
                        </div>
                        <!-- section title -->
                        <div class="about-cont">
                            <div class="about-cont" v-if="!$utils.isEmptyVar(s.description)">
                                <p v-html="$utils.sub($utils.strip(s.description),800)"></p>
                            </div>
                            <router-link :to="{name: 'about'}" class="main-btn mt-55">Learn More</router-link>
                        </div>
                    </div>
                    <!-- about cont -->
                    <div class="col-lg-6 offset-lg-1" v-if="homeData.latest_scholarship.length > 0">
                        <div class="about-event mt-50">
                            <div class="event-title">
                                <h3>ທຶນການສຶກສາ</h3>
                            </div>
                            <!-- event title -->
                            <ul>
                                <li v-for="(scholarship, index) in homeData.latest_scholarship" :key="index">
                                    <div class="singel-event">
                                        <span>
                                          <i class="fa fa-calendar"></i>
                                          {{scholarship.formatted_updated_at}}
                                        </span>
                                        <a @click="getDetail('scholarship', scholarship)">
                                            <h4 v-html="$utils.sub($utils.strip(scholarship.title), 100)"></h4>
                                        </a>
                                        <span>
                                          <i class="fa fa-clock-o"></i>
                                          <strong>Deadline:</strong>
                                          {{scholarship.formatted_deadline}}
                                        </span>
                                        <span>
                                          <i class="fa fa-map-marker"></i>
                                          {{scholarship.place}}
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="singel-event">
                                        <router-link :to="{name:'scholarships'}"
                                                     class="main-btn mt-15">ອ່ານເພີ່ມ
                                        </router-link>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- about event -->
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
            <div class="about-bg">
                <img :src="`${baseUrl}${baseRes}/assets/images/about/bg-1.png`" alt="About">
            </div>
        </section>

        <!--====== ABOUT PART ENDS ======-->

        <!--====== APPLY PART START ======-->

        <section id="course-part" class="pt-35 pb-30 gray-bg" v-if="homeData.latest_institutes.length">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-title pb-15">
                            <h3>ສະຖານການສຶກສາ</h3>
                            <h5>ສະຖານການສຶກສາທີ່ຜ່ານການປະເມີນຈາກສູນປະກັນຄຸນນະພາບການສຶກສາ</h5>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-5">
                        <div class="products-btn text-right pb-60">
                            <router-link :to="{name: 'institute'}" class="main-btn">ສະຖານສຶກສາທັງໝົດ</router-link>
                        </div>
                        <!-- products btn -->
                    </div>
                </div>
                <!-- row -->
                <div class="row course-slied mt-30">
                    <div class="col-lg-4" v-for="(item, idx) in homeData.latest_institutes" :key="idx">
                        <div class="custom-card">
                            <a class="grid-card card">
                                <div class="media">
                                    <img :src="`${baseUrl}${item.image}`" alt="Course">
                                </div>
                                <div class="card-body">
                                    <div class="media-block">
                                        <div class="body">
                                            <h3 @click="Route({name: 'institute-single', params: {id: item.id}})"
                                                class="title">{{item.institute_name + ' ' + item.
                                                short_institute_name}}</h3>
                                            <p class="content">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>{{ item.formatted_founded}}</span>
                                            </p>
                                            <p class="conent">
                                                <i class="fas fa-fw fa-envelope"></i>
                                                <span>{{ item.public_email || 'N/A'}}</span>
                                            </p>
                                            <p class="content">
                                                <i class="fas fa-fw fa-phone"></i>
                                                <span>{{ item.phone_number || 'N/A'}}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <div>
                                            <a @click="Route({name: 'institute', query: {category_id: item.category.id}})">
                                                <h6>
                                                    <i class="fa fa-tags"></i> {{ item.category.name }}
                                                </h6>
                                            </a>
                                            <div @click="Route({name: 'institute-single', params: {id: item.id}})"  class="button secondary border -full">View more</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- single course -->
                    </div>
                </div>
                <!-- course slide -->
            </div>
            <!-- container -->
        </section>

        <!--====== NEWS PART START ======-->
        <section id="news-part" class="pt-50 pb-50" v-if="homeData.latest_news.length > 0">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-6 col-md-8 col-sm-7">
                        <div class="section-title pb-30">
                            <h3>ຂ່າວສານ</h3>
                            <h5>ຕິດຕາມຂໍ້ມູນຂ່າວສານ ແລະສາລະໜ້າຮູ້ຕ່າງໆໄດ້</h5>
                        </div>
                        <!-- section title -->
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-5">
                        <div class="products-btn text-right pb-60">
                            <router-link :to="{name: 'news'}" class="main-btn">ຂ່າວທັງໝົດ</router-link>
                        </div>
                        <!-- products btn -->
                    </div>
                </div>
                <!-- row -->
                <div class="row" v-if="homeData.latest_news.length > 0">
                    <div class="col-lg-6">
                        <div class="singel-news mt-30">
                            <div class="news-thum pb-25" @click="getDetail('news', homeData.latest_news[0])">
                                <img :src="homeData.latest_news[0].image" alt="News">
                            </div>
                            <div class="news-cont">
                                <ul>
                                    <li>
                                        <i class="fa fa-calendar"></i>
                                        {{homeData.latest_news[0].formatted_updated_at}}
                                    </li>
                                    <li>
                                        <span>By</span>
                                        {{homeData.latest_news[0].author}}
                                    </li>
                                </ul>
                                <a @click="getDetail('news', homeData.latest_news[0])">
                                    <h3 v-html="$utils.sub($utils.strip(homeData.latest_news[0].title), 55)"></h3>
                                </a>
                                <p v-html="$utils.sub($utils.strip(homeData.latest_news[0].description), 150)"></p>
                            </div>
                        </div>
                        <!-- singel news -->
                    </div>
                    <div class="col-lg-6">
                        <template v-for="(news, idx) in homeData.latest_news">
                            <div class="singel-news news-list singel-course" :key="idx" v-if="idx!==0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="news-thum mt-30">
                                            <div class="image img-card" @click="getDetail('news', news)">
                                                <img :src="news.image" :alt="news.image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="news-cont mt-30">
                                            <ul>
                                                <li>
                                                    <i class="fa fa-calendar"></i>
                                                    {{news.formatted_updated_at}}
                                                </li>
                                                <li>
                                                    <span>By</span>
                                                    {{news.author}}
                                                </li>
                                            </ul>
                                            <a @click="getDetail('news', news)">
                                                <h3 v-html="$utils.sub($utils.strip(news.title), 45)"></h3>
                                            </a>
                                            <p v-html="$utils.sub($utils.strip(news.description), 80)"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- row -->
                            </div>
                        </template>
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </section>

        <!--====== NEWS PART ENDS ======-->
        <!--====== ACTIVITY PART START ======-->
        <section
            id="publication-part"
            class="pt-25 pb-30 gray-bg"
            v-if="homeData.latest_activity.length > 0">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-6 col-md-8 col-sm-7">
                        <div class="section-title pb-10">
                            <h3>ກິດຈະກຳ</h3>
                            <h5>ການເຄື່ອນໄຫຼວກິດຈະກຳຕ່າງໆ</h5>
                        </div>
                        <!-- section title -->
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-5">
                        <div class="products-btn text-right pb-10">
                            <a @click="Route({name: 'activities'})" class="main-btn">ກິດຈະກຳທັງໝົດ</a>
                        </div>
                        <!-- products btn -->
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div
                        class="col-lg-4 col-md-6 col-sm-8"
                        v-for="(activity,index) in (homeData.latest_activity)"
                        :key="index">
                        <div class="custom-card">
                            <a @click="getDetail('activity', activity)" class="grid-card card">
                                <div class="media">
                                    <img :src="activity.image" :alt="activity.image">
                                </div>
                                <div class="card-body">
                                    <div class="media-block">
                                        <div class="body">
                                            <h3 class="title"
                                                v-html="$utils.sub($utils.strip(activity.title), 45)"></h3>
                                            <p class="content">By {{activity.author}}</p>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <div>
                                            <span>{{activity.formatted_updated_at}}</span>
                                            <div class="button secondary border -full">Read more</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </section>
    </div>
</template>
<script>
    import {Carousel, Slide} from "vue-carousel";
    import Carousels from "@com/General/Partial/Carousel.vue";
    import InstituteCategory from "@com/General/Default/instituteCategory.vue";
    import Sponsor from "@com/General/Default/Sponsor.vue";
    import ContactForm from "@com/General/ContactForm.vue";
    import Base from "@com/Bases/GeneralBase.js";
    import {mapActions} from "vuex";

    export default Base.extend({
        name: "Home",
        data: () => ({
            contactInfo: {header_title: "Contact Us Now"} //for contact form
        }),
        components: {
            Carousels,
            Carousel,
            Slide,
            ContactForm,
            Sponsor,
            InstituteCategory
        },
        created() {
            this.setPageTitle(`Home`);
        }
    });
</script>
