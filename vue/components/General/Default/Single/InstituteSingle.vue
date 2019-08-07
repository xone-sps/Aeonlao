<template>
    <div>
        <!--====== COURSES SINGEl PART START ======-->
        <section id="corses-singel" class="pt-10 pb-20 gray-bg" style="min-height: 499px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="corses-singel-left mt-30" style="min-height: 499px;">
                            <div class="title">
                                <h3 style="padding-bottom: 10px;">
                                    {{singlePostsData.institutes.data.institute_name}}</h3>
                            </div>
                            <div class="sub-title">
                                <h4 style="padding-bottom: 10px;">Short name - {{
                                    singlePostsData.institutes.data.short_institute_name}}</h4>
                            </div>
                            <!-- title -->
                            <div class="course-terms">
                                <ul>
                                    <li>
                                        <div class="course-category">
                                            <div class="name">
                                                <span>Updated</span>
                                                <h6>{{ singlePostsData.institutes.data.formatted_updated }}</h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="course-category">
                                            <span>ໝວດໝູ່ສະຖານການສຶກສາ</span>
                                            <h6 v-if=" singlePostsData.institutes.data.category">{{
                                                singlePostsData.institutes.data.category.name }} {{
                                                singlePostsData.institutes.data.parent_category.name ? ' - ສັງກັດ ' +
                                                singlePostsData.institutes.data.parent_category.name : '' }}</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="course-category">
                                            <span>ວັນສ້າງຕັ້ງ</span>
                                            <h6>{{ singlePostsData.institutes.data.formatted_founded }}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- course terms -->
                            <div class="corses-singel-image pt-50">
                                <img v-if="singlePostsData.institutes.data.image"
                                     :src="`${baseUrl}${singlePostsData.institutes.data.image }`" alt="Institute Image">
                            </div>
                            <!-- corses singel image -->
                            <div class="corses-tab mt-30">
                                <ul class="nav nav-justified" id="myTab" role="tablist">
                                    <li class="nav-item institute">
                                        <a
                                            class="active"
                                            id="overview-tab"
                                            data-toggle="tab"
                                            href="#overview"
                                            role="tab"
                                            aria-controls="overview"
                                            aria-selected="true"
                                        >ລາຍລະອຽດກ່ຽວກັບສະຖານການສຶກສາ</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                      <a
                                        id="curriculam-tab"
                                        data-toggle="tab"
                                        href="#curriculam"
                                        role="tab"
                                        aria-controls="curriculam"
                                        aria-selected="false"
                                      >ຫຼັດສູດການສຶກສາ</a>
                                    </li>
                                    <li class="nav-item">
                                      <a
                                        id="instructor-tab"
                                        data-toggle="tab"
                                        href="#instructor"
                                        role="tab"
                                        aria-controls="instructor"
                                        aria-selected="false"
                                      >ຂໍ້ມູນຕິດຕໍ່</a>
                                    </li>-->
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div
                                        class="tab-pane fade show active"
                                        id="overview"
                                        role="tabpanel"
                                        aria-labelledby="overview-tab">
                                        <div class="overview-description"
                                             v-html="singlePostsData.institutes.data.about"></div>
                                        <!-- overview description -->
                                    </div>
                                </div>
                                <!-- tab content -->
                            </div>
                            <div>
                                <ul class="share">
                                    <li class="title">Share :</li>
                                    <li>
                                        <a target="_blank"
                                           :href="link">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- corses singel left -->
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12 col-md-6">
                                <div class="course-features mt-30">
                                    <h4>ຂໍ້ມູນຕິດຕໍ່</h4>
                                    <ul>
                                        <li>
                                            <div class="icon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <div class="cont">
                                                <a style="color: #000000;" target="_blank"
                                                   :href="`${singlePostsData.institutes.data.phone_number ? 'tel:'+singlePostsData.institutes.data.phone_number : '#'}`">
                                                    {{ singlePostsData.institutes.data.phone_number || 'N/A' }}
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <i class="far fa-envelope"></i>
                                            </div>
                                            <div class="cont">
                                                <a style="color: #000000;" target="_blank"
                                                   :href="`${singlePostsData.institutes.data.public_email ? 'mailto:'+singlePostsData.institutes.data.public_email : '#'}`">
                                                    {{ singlePostsData.institutes.data.public_email || 'N/A' }}
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <div class="cont">
                                                <p>{{ $utils.sub(singlePostsData.institutes.data.address, 400) || 'N/A'
                                                    }}</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <i class="fas fa-globe"></i>
                                            </div>
                                            <div class="cont">
                                                <a style="color: #000000;" target="_blank"
                                                   :href="`${singlePostsData.institutes.data.website ? singlePostsData.institutes.data.website : '#'}`">
                                                    {{ $utils.sub(singlePostsData.institutes.data.website, 30) || 'N/A'
                                                    }}
                                                </a>
                                            </div>
                                        </li>
                                        <li></li>
                                    </ul>
                                    <div>
                                        <ul class="share social">
                                            <li class="title">Social :</li>
                                            <li>
                                                <a target="_blank"
                                                   :href="`${singlePostsData.institutes.data.facebook ? singlePostsData.institutes.data.facebook : '#'}`">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- course features -->
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="You-makelike mt-30">
                                    <h4>ແຜນທີ່ສະຖານການສຶກສາ</h4>

                                    <div class="gmap mt-10">
                                        <iframe
                                            :src="singlePostsData.institutes.data.googlemap"
                                            frameborder="0"
                                            scrolling="no"
                                            marginheight="0"
                                            marginwidth="0"
                                            style="width: 100%;height: 228px;"></iframe>
                                        <!-- Example link: https://maps.google.com/maps?width=100%&amp;height=400&amp;hl=en&amp;q=108Hill-Office & Residenceຸ&amp;ie=UTF8&amp;t=&amp;z=13&amp;iwloc=B&amp;output=embed-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="releted-courses pt-45">
                            <div class="title">
                                <h3>ສະຖານການສຶກສາອື່ນໆທີ່ກຽ່ວຂ້ອງ</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-6" v-for="(item, idx) in singlePostsData.institutes.others"
                                     :key="idx">
                                    <div class="custom-card mt-10">
                                        <a class="grid-card card">
                                            <div class="media"
                                                 @click="Route({name: 'institute-single', params: {id: item.id}})">
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
                                                        <div @click="Route({name: 'institute-single', params: {id: item.id}})"
                                                             class="button secondary border -full">View more
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- singel course -->
                                </div>
                            </div>
                            <!-- row -->
                        </div>
                        <!-- releted courses -->
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </section>
        <!--====== COURSES SINGEl PART ENDS ======-->
    </div>
</template>
<script>
    import Base from "@com/Bases/GeneralBase.js";

    export default Base.extend({
        data: () => ({
            type: "institutes",
            link: ""
        }),
        watch: {
            "$route.params": function (n, o) {
                this.$utils.scrollToY("html,body", 10);
                this.singleId = n.id;
                this.link = this.baseUrl + this.$route.fullPath;
                this.fetchSinglePostsData({type: this.type, id: this.singleId});
            }
        },
        methods: {
            setItem(data) {
                this.setPageTitle(`${data.data.institute_name} - ${data.data.short_institute_name}`);
            }
        },
        created() {
            this.link = this.baseUrl + this.$route.fullPath;
            this.registerWatches();
            this.setPageTitle("Institute");
            this.singleId = this.$route.params.id;
            this.fetchSinglePostsData({type: this.type, id: this.singleId});
        }
    });
</script>

