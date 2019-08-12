  <template>
    <div>
      <section class="section">
        <main class="top_120">
          <div class="container">
            <div class="fire-spinner" v-if="shouldLoading(type)"></div>
            <div class="columns">
              <div class="column is-8">
                <div class="column is-12">
                  <div class="box_style_1">
                   <div class="widget">
                    <div class="form-group">
                      <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4" v-for="(news, idx) in postsData.news.posts.data" :key="idx">
                  <div class="card">
                    <div class="img-card">
                      <figure class="animated">
                        <a  @click="getDetail('news', news)" >
                          <img :src="`${baseUrl}${news.image}`" :alt="news.image" class="img-responsive">
                        </a>
                      </figure>
                    </div>
                    <div class="post_info clearfix">
                      <div class="post-left">
                        <ul>
                          <li><i class="fa fa-calendar"></i> On <span> {{news.post_updated}}</span></li>
                          <!--                         <li><i class="fa fa-tags"></i> Tags <a href="#">Works</a>, <a href="#">Personal</a></li> -->
                        </ul>
                      </div>
                      <!--                     <div class="post-right"><i class="icon-comment"></i><a href="#">25 </a></div> -->
                    </div>
                    <div class="card-content">
                      <div class="post-title">
                        <a  @click="getDetail('news', news)">
                          <h3 v-html="$utils.sub($utils.strip(news.title),60)"></h3>
                        </a>
                      </div>
                      <div class="content">
                        <p v-html="$utils.sub($utils.strip(news.description), 120)">

                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Search not found -->
              <div class="column is-8" v-if="isNotFound()">
                <div class="">
                  <h1 class="devsite-page-title">
                    Search results for <span class="devsite-search-term"><span
                      class="devsite-search-term">{{ query }}</span></span>
                    </h1>
                  </div>
                  <div class="result-snippet">No Results</div>
                </div>

                <hr>
                <div class="is-centered">
                  <nav class="news-pagination">
                    <ul class="pagination">
                      <li class="page-item">
                        <a :disabled="paginate.current_page===1" @click="prevPage(paginate.current_page - 1)"
                        aria-label="Previous" class="active">
                        <i class="fa fa-angle-left"></i>
                      </a>
                    </li>
                    <li class="page-item">
                      <a :disabled="paginate.current_page===paginate.last_page"
                      @click="nextPage(paginate.current_page + 1)"
                      aria-label="Next">
                      <i class="fa fa-angle-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="column is-4">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="widget">
                  <h4>Popular post</h4>
                  <hr>
                  <ul class="recent_post">
                    <li v-for="(news, idx) in postsData.news.mostViews" :key="idx">
                      <i class="icon-calendar-empty"></i><span> {{news.post_updated}}</span>
                      <div class="recent_post_title">
                        <a @click="getDetail('news', news)" v-html="$utils.sub($utils.strip(news.title), 75)"></a>
                      </div>
                    </li>
                  </ul>
                </div> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </section>
</div>
</template>

<script>
import Base from '@com/Bases/GeneralBase.js'

export default Base.extend({
  data: () => ({
    type: 'news',
  }),
  created() {
    this.registerWatches();
    this.setPageTitle('News');
    this.getItems();
  }
});
</script>


<style type="text/css" media="screen" scope>
.post-title a h3{
  color: #333;
  font-weight: 600;
  font-size: 16px !important;
}
.post-title{
 display: inline-block;
}
</style>