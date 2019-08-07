<template>
    <div>
        <!--POST CONTENT -->
        <section id="blog-singel" class="pt-10 pb-10 gray-bg" style="min-height: 499px;">
            <div class="container">
                <div class="fire-spinner" v-if="shouldLoadingSingle(type)"></div>
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="blog-details">
                            <div class="thum">
                                <div v-if="shouldLoadingSingle(type)" class="loading-image"></div>
                                <img
                                    v-if="singlePostsData.news.data.image"
                                    class="single-post-image"
                                    :src="`${baseUrl}${singlePostsData.news.data.image}`"
                                    :alt="singlePostsData.news.data.image"
                                >
                            </div>
                            <div class="cont" style="min-height: 499px;">
                                <div class="blog-header-title">
                                    <h1 v-if="shouldLoadingSingle(type)">
                                        <div class="loading-text"></div>
                                    </h1>
                                    <h4 v-else class="title single-blog-title">{{ singlePostsData.news.data.title
                                        }}</h4>
                                </div>
                                <ul>
                                    <li>
                                        <i class="fa fa-calendar"></i>
                                        <time :datetime="singlePostsData.news.data.updated_at">
                                            {{
                                            singlePostsData.news.data.post_updated }}
                                        </time>
                                        <span class="agoTime"
                                              :title="singlePostsData.news.data.post_updated_ago"></span>
                                    </li>
                                    <li>
                                        <div class="thum avatar">
                                            <img
                                                v-if="shouldLoadingSingle(type)"
                                                :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`"
                                                class="avatar-image image-size50x50"
                                            >
                                            <img
                                                v-else-if="singlePostsData.news.data.author_image"
                                                :src="`${baseUrl}${singlePostsData.news.data.author_image}`"
                                                class="avatar-image image-size50x50"
                                            >
                                            <span class="author-caption">{{singlePostsData.news.data.author}}</span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="blog-content">
                                    <p class="content" v-html="singlePostsData.news.data.description"></p>
                                </div>
                                <div v-if="singlePostsData.news.data.id">
                                    <ul class="share">
                                        <li class="title">Share :</li>
                                        <li>
                                            <a
                                                target="_blank"
                                                :href="sharer('fb', type, singlePostsData.news.data, link)"
                                            >
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                target="_blank"
                                                :href="sharer('twitter', type, singlePostsData.news.data, link)"
                                            >
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <br>
                                <h4 class="text-center">ຂ່າວອື່ນໆທີ່ນ່າສົນໃຈ</h4>
                            </div>
                            <!-- cont -->
                        </div>
                        <!-- blog details -->
                    </div>
                </div>
                <!-- Sigle blog footer -->
                <div class="row course-slied mt-10">
                    <div class="col-lg-4" v-for="(item, idx) in singlePostsData.news.others" :key="idx">
                        <div class="singel-course card">
                            <div class="thum">
                                <div class="image img-card" @click="getDetail('news', item)">
                                    <img :src="`${baseUrl}${item.image}`" :alt="item.image">
                                </div>
                            </div>
                            <div class="cont">
                                <a @click="getDetail('news', item)">
                                    <h6 class="card-title" v-html="$utils.sub($utils.strip(item.title),80)"></h6>
                                </a>
                                <div v-html="$utils.sub($utils.strip(item.description), 120)"></div>
                                <div class="course-teacher">
                                    <div class="thum">
                                        <img :src="`${baseUrl}${singlePostsData.news.data.author_image}`">
                                    </div>
                                    <div class="name auther">
                                        <div class="author-caption">{{singlePostsData.news.data.author}}</div>
                                    </div>
                                    <div class="date-post">
                                        <time class="updated-date" :datetime="item.updated_at">
                                            <i class="fa fa-calendar"></i>
                                            {{item.post_updated}}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- singel course -->
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import Base from "@com/Bases/GeneralBase.js";

    export default Base.extend({
        data: () => ({
            type: "news",
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
                this.setPageTitle(data.data.title);
            }
        },
        created() {
            this.link = this.baseUrl + this.$route.fullPath;
            this.registerWatches();
            this.setPageTitle("News");
            this.singleId = this.$route.params.id;
            this.fetchSinglePostsData({type: this.type, id: this.singleId});
        }
    });
</script>

