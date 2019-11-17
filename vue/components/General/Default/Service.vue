<template>
    <div>
        <!--====== BLOG PART START ======-->
        <section id="blog-page" class="top_120">
            <div class="container">
                <div class="fire-spinner" v-if="shouldLoading(type)"></div>
                <div class="columns is-multiline">
                    <div class="column is-8">
                <div class="columns is-multiline">
                    <div class="column is-4" v-for="(scholarship, idx) in postsData.scholarships.posts.data"
                            :key="idx">
                                <div  class="card">
                            <div class="blog-thum img-card" @click="getDetail('service', scholarship)">
                                <img :src="`${baseUrl}${scholarship.image}`"
                                     :alt="scholarship.image">
                            </div>
                            <div class="card-content">
                                <a @click="getDetail('service', scholarship)">
                                    <h2>{{scholarship.title}}</h2>
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
                                            v-html="$utils.sub($utils.strip(scholarship.place), 70)"
                                        ></strong>
                                    </li>
                                    <li>
                                    <span class="deadline-color">Deadline - {{ scholarship.formatted_deadline }} {{ scholarship.isClosed ? '(Closed)': '' }}
                                    </span>
                                    </li>
                                </ul>
                                <p>
                                    <span :datetime="scholarship.updated_at">{{scholarship.post_updated}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="column is-4">
                        <div class="saidbar">
                            <div class="columns is-multiline">
                                <div class="column is-12 is-mobile">
                                    <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
                                </div>
                                <!-- search -->
                                <div class="column is-12 is-mobile">
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
                                                        <div class="contets"
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
            this.setPageTitle("Services");
            this.getItems();
        }
    });
</script>

