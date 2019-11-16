<template>
    <div>
        <!--POST CONTENT -->
        <section class="top_120 section">
            <div class="container">
                <div v-if="shouldLoadingSingle(type)"></div>
                <div class="columns">
                    <div class="column is-9">
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
                                <div class="blog-header-title">
                                    <h1 v-if="shouldLoadingSingle(type)">
                                        <div class="loading-text"></div>
                                    </h1>
                                    <h2 v-else>{{ singlePostsData.news.data.title
                                    }}</h2>
                                </div>
                                <ul>
                                    <li>
                                        <div class="date-post-detail">
                                            <i class="fa fa-calendar"></i>
                                        <time :datetime="singlePostsData.news.data.updated_at">
                                            {{
                                                singlePostsData.news.data.post_updated }}
                                            </time>
                                            <span class="agoTime"
                                            :title="singlePostsData.news.data.post_updated_ago"></span>
                                        </div>
                                        </li>
                                    </ul>
                                    <div class="blog-content">
                                        <p class="content" v-html="singlePostsData.news.data.description"></p>
                                    </div>
                                    <hr>
                                    <div v-if="singlePostsData.news.data.id">
                                        <ul class="share">
                                            <li><h4> Share :</h4></li>
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
                    </div>
                </div>
                <div class="column is-3 course-slied mt-10">
                    <div><h3>Other news</h3></div>
                    <hr>
                    <div class="columns is-multiline">
                        <div class="column is-12" v-for="(item, idx) in singlePostsData.news.others" :key="idx">
                            <div class="card">        
                                    <div class="img-card" @click="getDetail('news', item)">
                                        <img :src="`${baseUrl}${item.image}`" :alt="item.image">
                                </div>
                                <div class="card-content">
                                    <a @click="getDetail('news', item)">
                                        <h6 class="card-title" v-html="$utils.sub($utils.strip(item.title),80)"></h6>
                                    </a>
<!--                                     <div class="card-detail">
                                        <p v-html="$utils.sub($utils.strip(item.description), 120)"></p>
                                    </div> -->
                                        <div class="date-post">
                                            <time class="updated-date" :datetime="item.updated_at">Updated - 
                                                {{item.post_updated}}
                                            </time>
                                        </div>
                                </div>
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

