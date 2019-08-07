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
                                    v-if="singlePostsData.scholarships.data.image"
                                    class="single-post-image"
                                    :src="`${baseUrl}${singlePostsData.scholarships.data.image}`"
                                    :alt="singlePostsData.scholarships.data.image"
                                >
                            </div>
                            <div class="cont" style="min-height: 499px;">
                                <div class="blog-header-title">
                                    <h1 v-if="shouldLoadingSingle(type)">
                                        <div class="loading-text"></div>
                                    </h1>
                                    <h4 v-else class="title single-blog-title">
                                        {{ singlePostsData.scholarships.data.title
                                        }}
                                    </h4>
                                </div>
                                <ul>
                                    <li>
                                        <i class="fa fa-calendar"></i>
                                        <time :datetime="singlePostsData.scholarships.data.updated_at">
                                            {{
                                            singlePostsData.scholarships.data.post_updated }}
                                        </time>
                                        <span
                                            class="agoTime"
                                            :title="singlePostsData.scholarships.data.post_updated_ago"
                                        ></span>
                                    </li>
                                    <li>
                                        <img
                                            v-if="shouldLoadingSingle(type)"
                                            :src="`${baseUrl}/assets/images/${s.website_logo}${s.fresh_version}`"
                                            class="avatar-image image-size50x50"
                                        >
                                        <img
                                            v-else-if="singlePostsData.scholarships.data.author_image"
                                            :src="`${baseUrl}${singlePostsData.scholarships.data.author_image}`"
                                            class="avatar-image image-size50x50"
                                        >
                                        <div class="author-caption">{{singlePostsData.scholarships.data.author}}</div>
                                    </li>
                                    <li><i class="fas fa-map-marker-alt check-in"></i>
                                        <span>{{ singlePostsData.scholarships.data.place }}</span>
                                    </li>
                                    <li>
                    <span
                        class="deadline-color"
                    >Deadline - {{singlePostsData.scholarships.data.formatted_deadline}}</span>
                                    </li>
                                </ul>
                                <div class="blog-content">
                                    <p class="content" v-html="singlePostsData.scholarships.data.description"></p>
                                </div>
                                <div v-if="singlePostsData.scholarships.data.id">
                                    <ul class="share">
                                        <li class="title">Share :</li>
                                        <li>
                                            <a
                                                target="_blank"
                                                :href="sharer('fb', type, singlePostsData.scholarships.data, link)"
                                            >
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                target="_blank"
                                                :href="sharer('twitter', type, singlePostsData.scholarships.data, link)"
                                            >
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <br>
                                <h4 class="text-center">ທຶນການສຶກສາອື່ນໆທີ່ນ່າສົນໃຈ</h4>
                            </div>
                            <!-- cont -->
                        </div>
                        <!-- blog details -->
                    </div>
                </div>
                <!-- Sigle blog footer -->
                <div class="row course-slied mt-10">
                    <div
                        class="col-lg-4 pb-10"
                        v-for="(item, idx) in singlePostsData.scholarships.others"
                        :key="idx"
                    >
                        <div class="singel-course card">
                            <div class="thum">
                                <div class="image img-card" @click="getDetail('scholarship', item)">
                                    <img :src="`${baseUrl}${item.image}`" :alt="item.image">
                                </div>
                            </div>
                            <div class="cont">
                                <a @click="getDetail('scholarship', item)">
                                    <p class="card-title" v-html="$utils.sub($utils.strip(item.title), 70)"></p>
                                </a>
                                <div class="content" v-html="$utils.sub($utils.strip(item.description), 180)"></div>

                                <div class="course-teacher">
                                    <p>
                                        <i class="fas fa-map-marker-alt check-in"></i>
                                        <strong class="f-title"
                                                v-html="$utils.sub($utils.strip(item.place), 40)"></strong>
                                    </p>
                                    <div class="admin">
                                        <p class="updated-date" :datetime="item.updated_at">
                                            <i class="fa fa-calendar"></i>
                                            {{item.post_updated}}
                                        </p>
                                    </div>
                                    <p>
                  <span
                      class="deadline-color"
                  >Deadline - {{ item.formatted_deadline }} {{ item.isClosed ? '(Closed)': '' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
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
            type: "scholarships",
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
            this.setPageTitle("Scholarship");
            this.singleId = this.$route.params.id;
            this.fetchSinglePostsData({type: this.type, id: this.singleId});
        }
    });
</script>



