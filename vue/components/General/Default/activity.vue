<template>
  <div>
    <section class="top_120 section">
      <div class="container">
        <div class="fire-spinner" v-if="shouldLoading(type)"></div>
        <div class="columns">
          <div class="column is-8 is-mobile">
            <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
            <hr>
            <div class="main_title">
              <h3>What we did</h3>
            </div>
            <div class="columns is-multiline">
              <div class="column is-4"
              v-for="(activity, idx) in postsData.activities.posts.data"
              :key="idx">
              <div class="image" @click="getDetail('activity', activity)">
                <img :src="`${baseUrl}${activity.image}`" :alt="activity.image">
              </div>
              <div class="contents">
                <a @click="getDetail('activity', activity)">
                  <h3 v-html="$utils.sub($utils.strip(activity.title),60)"></h3>
                </a>
                <div class="detail">
                  <p v-html="$utils.sub($utils.strip(activity.description), 120)"></p>
                  <p>
                    <span :datetime="activity.updated_at">{{activity.formatted_start_date}}</span>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-8" v-if="isNotFound()">
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
    <div class="column is-4">
      <div class="widget">
        <h4>Popular activity</h4>
        <hr>
        <ul class="recent_posts">
          <li class="activity" v-for="(activity, idx) in postsData.activities.mostViews" :key="idx">
            <div class="thum">
              <img :src="`${baseUrl}${activity.image}`" :alt="activity.image">
            </div>
            <div class="detail">
              <i class="icon-calendar-empty"></i><span>{{activity.post_updated}}</span>
              <div class="recent_post_title">
                <a @click="getDetail('activity', activity)" v-html="$utils.sub($utils.strip(activity.title), 85)"></a>
              </div>
            </div>
          </li>
        </ul>
      </div> 

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
<style type="text/css" media="screen">

.widget{
  /*float: right;*/
}
ul.recent_posts li.activity .thum img{
  width: 120px;
  height: auto;
  padding: 10px 10px 10px 0px;
  cursor: pointer;
  max-width: 200px;
  max-height: 150px;

}
ul .recent_post.thum img{
  width: 100px;
}
.detail{
  float: right;
} 
ul.recent_posts li.activity{
  display: flex;
  display: -webkit-box!important;
  display: -webkit-flex!important;
}
.detail span{
  font-size: 12px;
  font-weight: 400;

}
.recent_post_title a{
  color: #333;
  font-size: 15px;
  font-weight: 500;

}
.recent_post_title a:hover{
  color: #7461CF;
  transition: all 0.3s ease;

}

</style>