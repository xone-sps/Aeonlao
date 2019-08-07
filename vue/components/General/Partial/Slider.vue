<template>
    <div>
        <carousel
            :per-page="1"
            :mouse-drag="true"
            :autoplay="true"
            :autoplayHoverPause="false"
            :autoplayTimeout="8000"
            :paginationEnabled="false"
            :speed="500"
            :loop="true"
        >
            <slide v-for="(slide, index) in items" :key="index" @slideclick="openLink(slide.link)">
                <div
                    class="slider_bck"
                    :style="`background:url('${path}/${slide.image}');cursor: ${hasLink(slide.link) ? 'pointer;' : ''}`"
                ></div>
                <div class="container slide-text">
                    <h1 style="transition:ease;" v-html="$utils.sub($utils.strip(slide.name), 60)"></h1>
                    <p v-html="$utils.sub($utils.strip(slide.description), 100)"></p>
                    <ul>
                        <li @slideclick="openLink(slide.link)">
                            <a :style="`display: ${noLink(slide.link) ? 'none;' : ''}`" data-animation="fadeInUp"
                               data-delay="1.6s" class="main-btn">Read more</a>
                        </li>
                    </ul>
                </div>

            </slide>
        </carousel>
        <!--====== SLIDER PART ENDS ======-->
    </div>
</template>

<script>
    import {Carousel, Slide} from "vue-carousel";

    export default {
        props: ["items"],
        data() {
            return {
                path: `${this.baseUrl}/assets/images/banners`
            };
        },
        components: {
            Carousel,
            Slide
        },
        methods: {
            hasLink(d) {
                return !this.$utils.isEmptyVar(d);
            },
            noLink(d) {
                return this.$utils.isEmptyVar(d);
            },
            openLink(link) {
                if (this.hasLink(link)) {
                    window.open(link, "_blank");
                }
            }
        }
    };
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
                .main-btn{
   padding: 0px 12px;
    font-size: 12px;
    line-height: 26px;
}
    }
</style>
