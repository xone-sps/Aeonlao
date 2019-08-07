<template>
    <div>
        <!--====== BLOG PART START ======-->
        <section id="blog-page" class="pb-20 gray-bg">
            <div class="container">
                <div class="fire-spinner" v-if="shouldLoading(type)"></div>
                <div class="row">
                    <div class="col-lg-8">
                        <div
                            class="singel-blog mt-10"
                            v-for="(scholarship, idx) in postsData.scholarships.posts.data"
                            :key="idx">
                            <div class="blog-thum" @click="getDetail('scholarship', scholarship)">
                                <img :src="`${baseUrl}${scholarship.image}`"
                                     :alt="scholarship.image">
                            </div>
                            <div class="blog-cont">
                                <a @click="getDetail('scholarship', scholarship)">
                                    <h3>{{scholarship.title}}</h3>
                                </a>
                                <ul>
                                    <li>
                                        <i class="fa fa-user"></i>
                                        {{scholarship.author}}
                                    </li>
                                    <li>
                                        <i class="fas fa-map-marker-alt check-in"></i>
                                        <strong
                                            class="f-title"
                                            v-html="$utils.sub($utils.strip(scholarship.place), 100)"
                                        ></strong>
                                    </li>
                                    <li>
                                    <span class="deadline-color">Deadline - {{ scholarship.formatted_deadline }} {{ scholarship.isClosed ? '(Closed)': '' }}
                                    </span>
                                    </li>
                                </ul>
                                <p v-html="$utils.sub($utils.strip(scholarship.description), 180)"></p>
                                <p>
                                    <span :datetime="scholarship.updated_at">{{scholarship.post_updated}}</span>
                                </p>
                            </div>
                        </div>
                        <!-- single blog -->
                        <!--Search Form-->
                        <div class="col-lg-8" v-if="isNotFound()">
                            <div class="devsite-article mt-20">
                                <h1 class="devsite-page-title">
                                    Search results for
                                    <span class="devsite-search-term">
                                      <span class="devsite-search-term">{{ query }}</span>
                                    </span>
                                </h1>
                            </div>
                            <div class="result-snippet">No Results</div>
                        </div>
                        <nav class="courses-pagination mt-20">
                            <ul class="pagination justify-content-lg-end justify-content-center">
                                <li class="page-item">
                                    <a
                                        :disabled="paginate.current_page===1"
                                        @click="prevPage(paginate.current_page - 1)"
                                        aria-label="Previous"
                                        class="active">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a
                                        :disabled="paginate.current_page===paginate.last_page"
                                        @click="nextPage(paginate.current_page + 1)"
                                        aria-label="Next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-4">
                        <div class="saidbar">
                            <div class="row">
                                <div class="col-lg-12 col-md-6">
                                    <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
                                </div>
                                <!-- search -->
                                <div class="col-lg-12 col-md-6">
                                    <div
                                        class="saidbar-post mt-10"
                                        v-if="postsData.scholarships.mostViews.length > 0">
                                        <h4>ທຶນການສຶກສາອື່ນໆ</h4>
                                        <ul>
                                            <li v-for="(scholarship, idx) in postsData.scholarships.mostViews"
                                                :key="idx">
                                                <a @click="getDetail('scholarship', scholarship)">
                                                    <div class="singel-post">
                                                        <div class="thum">
                                                            <img :src="`${baseUrl}${scholarship.image}`"
                                                                 :alt="scholarship.image">
                                                        </div>
                                                        <div class="cont"
                                                             @click="getDetail('scholarship', scholarship)">
                                                            <p v-html="$utils.sub($utils.strip(scholarship.title), 35)"></p>
                                                            <span>{{scholarship.post_updated}}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- side bar post -->
                                </div>
                            </div>
                            <!-- row -->
                        </div>
                        <!-- side bar -->
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </section>

        <!--====== BLOG PART ENDS ======-->
    </div>
</template>
<style scoped>
    .saidbar .saidbar-post ul li a .singel-post .thum img {
        width: 92px;
        height: 62px;
    }
</style>
<script>
    import Base from "@com/Bases/GeneralBase.js";

    export default Base.extend({
        data: () => ({
            type: "scholarships"
        }),
        created() {
            this.registerWatches();
            this.setPageTitle("Scholarships");
            this.getItems();
        }
    });
</script>

