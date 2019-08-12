<template>
  <div>
    <section class="section top_120">
      <div class="container">
        <div class="fire-spinner" v-if="shouldLoadingSingle(type)"></div>
        <div class="columns">
          <div class="column is-10 mx-auto">
            <div class="blog-details">
              <div class="thum">
                <div v-if="shouldLoadingSingle(type)" class="loading-image"></div>
<div class="imgage">
                  <img
                v-if="singlePostsData.activities.data.image"
                class="single-post-image"
                :src="`${baseUrl}${singlePostsData.activities.data.image}`"
                :alt="singlePostsData.activities.data.image"
                >
</div>
              </div>
              <div>
                <div class="blog-header-title">
                  <h1 v-if="shouldLoadingSingle(type)">
                    <div class="loading-text"></div>
                  </h1>
                  <h2 v-else class="title single-blog-title">{{ singlePostsData.activities.data.title
                  }}</h2>
                </div>
                <ul>
                  <li>
                    <i class="fa fa-calendar"></i>
                    <time :datetime="singlePostsData.activities.data.updated_at">
                      {{
                        singlePostsData.activities.data.post_updated }}
                      </time>
                    </li>
                  </ul>
                  <div class="blog-content">
                    <p class="content" v-html="singlePostsData.activities.data.description"></p>
                  </div>
                  <hr>
                  <div v-if="singlePostsData.activities.data.id">
                    <ul class="share">
                      <li><h4> Share :</h4></li>
                      <li>
                        <a
                        target="_blank"
                        :href="sharer('fb', type, singlePostsData.activities.data, link)"
                        >
                        <i class="fab fa-facebook-f"></i>
                      </a>
                    </li>
                    <li>
                      <a
                      target="_blank"
                      :href="sharer('twitter', type, singlePostsData.activities.data, link)"
                      >
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                </ul>

              </div>
              <br>
              <h3 class="text-center">Other activities</h3>
            </div>
            <!-- cont -->
          </div>
          <!-- blog details -->
        </div>
      </div>
      <!-- Sigle blog footer -->
      <div class="columns multiline course-slied mt-10">
        <div class="column is-4 pb-10" v-for="(item, idx) in singlePostsData.activities.others" :key="idx">
          <div class="singel-course card">
            <div class="thum">
              <div class="img-card" @click="getDetail('activity', item)">
                <img :src="`${baseUrl}${item.image}`"
                :alt="item.image">
              </div>
            </div>
            <div class="card-content">
              <a @click="getDetail('activity', item)">
                <h6 class="card-title" v-html="$utils.sub($utils.strip(item.title),80)"></h6>
              </a>
              <div v-html="$utils.sub($utils.strip(item.description), 120)"></div>
                 <time class="updated-date" :datetime="item.updated_at"><i class="fa fa-calendar"></i> {{item.post_updated}}</time>
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
    type: "activities",
    link: ""
  }),
  watch: {
    "$route.params": function(n, o) {
      this.$utils.scrollToY("html,body", 10);
      this.singleId = n.id;
      this.link = this.baseUrl + this.$route.fullPath;
      this.fetchSinglePostsData({ type: this.type, id: this.singleId });
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
    this.setPageTitle("Activity");
    this.singleId = this.$route.params.id;
    this.fetchSinglePostsData({ type: this.type, id: this.singleId });
  }
});
</script>

