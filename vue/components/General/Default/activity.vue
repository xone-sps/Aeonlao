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
              v-for="(activity, idx) in postsData.activities.posts.data"
              :key="idx"
            >
              <div class="blog-thum" @click="getDetail('activity', activity)">
                <img :src="`${baseUrl}${activity.image}`" :alt="activity.image">
              </div>
              <div class="blog-cont">
                <a @click="getDetail('activity', activity)">
                  <h3>{{activity.title}}</h3>
                </a>
                <ul>
                  <li>
                    <i class="fa fa-user"></i>
                    {{activity.author}}
                  </li>
                </ul>
                <p v-html="$utils.sub($utils.strip(activity.description), 180)"></p>
                <p>
                  <span :datetime="activity.updated_at">{{activity.formatted_start_date}}</span>
                </p>
              </div>
            </div>
            <!-- singel blog -->
               <!-- Search Form -->
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
                    class="active"
                  >
                    <i class="fa fa-angle-left"></i>
                  </a>
                </li>
                <li class="page-item">
                  <a
                    :disabled="paginate.current_page===paginate.last_page"
                    @click="nextPage(paginate.current_page + 1)"
                    aria-label="Next"
                  >
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
                  <div class="saidbar-post mt-10">
                    <h4>ກິດຈະກຳອື່ນໆ</h4>
                    <ul>
                      <li v-for="(activity, idx) in postsData.activities.mostViews" :key="idx">
                        <a @click="getDetail('activity', activity)">
                          <div class="singel-post">
                            <div class="thum">
                              <img :src="`${baseUrl}${activity.image}`" :alt="activity.image">
                            </div>
                            <div class="cont" @click="getDetail('activity', activity)">
                              <p v-html="$utils.sub($utils.strip(activity.title), 35)"></p>
                              <span>{{activity.post_updated}}</span>
                            </div>
                          </div>
                          <!-- singel post -->
                        </a>
                      </li>
                    </ul>
                  </div>
                  <!-- saidbar post -->
                </div>
              </div>
              <!-- row -->
            </div>
            <!-- saidbar -->
          </div>
        </div>
        <!-- row -->
      </div>
      <!-- container -->
    </section>
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
    type: "activities"
  }),
  created() {
    this.registerWatches();
    this.setPageTitle("Activity");
    this.getItems();
  }
});
</script>
