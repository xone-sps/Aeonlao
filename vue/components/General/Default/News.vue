  <template>
    <div>

<!--         <section id="blog-page" class="pb-20 gray-bg">
        <div class="container">
            <div class="fire-spinner" v-if="shouldLoading(type)"></div>
           <div class="row">
               <div class="col-lg-8">

                   <div class="singel-blog mt-10" v-for="(news, idx) in postsData.news.posts.data" :key="idx">
                       <div class="blog-thum" @click="getDetail('news', news)">
                           <img :src="`${baseUrl}${news.image}`" :alt="news.image">
                       </div>
                       <div class="blog-cont">
                           <a @click="getDetail('news', news)">
                               <h3>{{news.title}}</h3>
                               </a>
                           <ul>
                            <li class="updated-date" :datetime="news.updated_at"><i class="fa fa-calendar"></i> {{news.post_updated}}</li>
                             <li>
                             </li>
                           </ul>
                         <div class="blog-content">
                             <p class="content" v-html="$utils.sub($utils.strip(news.description), 180)"></p>
                         </div>
                       </div>
                   </div> 

                                  <div class="col-lg-8" v-if="isNotFound()">
                                    <div class="devsite-article mt-20">
                                        <h1 class="devsite-page-title">
                                            Search results for <span class="devsite-search-term"><span
                                            class="devsite-search-term">{{ query }}</span></span>
                                          </h1>
                                    </div>
                                    <div class="result-snippet">No Results</div>
                                </div>

                   <nav class="courses-pagination mt-10">
                        <ul class="pagination justify-content-lg-end justify-content-center">
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
               <div class="col-lg-4">
                     <div class="saidbar">
                       <div class="row">
                           <div class="col-lg-12 col-md-6">
                                <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
                           </div>
                           <div class="col-lg-12 col-md-6">
                               <div class="saidbar-post mt-10">
                                   <h4>Popular Posts</h4>
                                   <ul>
                                       <li v-for="(news, idx) in postsData.news.mostViews" :key="idx">
                                            <a @click="getDetail('news', news)">
                                                <div class="singel-post">
                                                   <div class="thum">
                                                       <img :src="`${baseUrl}${news.image}`" :alt="news.image">
                                                   </div>
                                                   <div class="cont" @click="getDetail('news', news)" >
                                                       <p v-html="$utils.sub($utils.strip(news.title), 35)"></p>
                                                       <span> {{news.post_updated}}</span>
                                                   </div>
                                               </div> 

                                            </a>
                                       </li>
                                   </ul>
                               </div>
                           </div>
                       </div> 
                   </div> 
               </div>
           </div>
        </div> 
    </section> -->
    
    <!--====== BLOG PART ENDS ======-->


<main class="top_100">
        <div class="container margin_60_35">
            <div class="columns">
                <div class="column is-9">
                        <div class="post" v-for="(news, idx) in postsData.news.posts.data" :key="idx">
                        <figure class="animated"><a href="">
                          <img :src="`${baseUrl}${news.image}`" :alt="news.image" class="img-responsive"></a>
                        </figure>
                        <div class="post_info clearfix">
                            <div class="post-left">
                                <ul>
                                    <li><i class="fa fa-calendar"></i> On <span>12 Nov 2020</span></li>
                                    <li><i class="fa fa-tags"></i> Tags <a href="#">Works</a>, <a href="#">Personal</a></li>
                                </ul>
                            </div>
                            <div class="post-right"><i class="icon-comment"></i><a href="#">25 </a></div>
                        </div>
                        <h2 @click="getDetail('news', news)">{{news.title}}</h2>
                        <p v-html="$utils.sub($utils.strip(news.description), 180)">
                            
                        </p>
                        <a @click="getDetail('news', news)" class="main_b">Read more</a>
                    </div>
                    
                    <hr>
                    <div class="is-centered">
                        <ul class="pagination">
                            <li><a href="#">Prev</a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">Next</a></li>
                        </ul><!-- end pagination-->
                    </div>
                </div><!-- End col-md-9 -->
                
                <div class="column is-3">
                <div class="box_style_1">
                    <div class="widget">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search...">
          </div>
        </div><!-- End Search -->
                
                <hr>
                
        <div class="widget">
          <h5>Categories</h5>
          <ul id="cat_nav">
                      <li><a href="#">Security tips</a></li>
                        <li><a href="#">Office wiring</a></li>
                        <li><a href="#">Diagnosis</a></li>
                        <li><a href="#">Maintance</a></li>
                    </ul>
        </div><!-- End widget -->
              
               </div><!-- End box style 1 -->
            
        <div class="widget">
          <h4>Recent post</h4>
          <ul class="recent_post">
            <li>
            <i class="icon-calendar-empty"></i> 16th July, 2020
            <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
            </li>
            <li>
            <i class="icon-calendar-empty"></i> 16th July, 2020
            <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
            </li>
            <li>
            <i class="icon-calendar-empty"></i> 16th July, 2020
            <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
            </li>
          </ul>
        </div><!-- End widget -->
                <hr>
        <div class="widget tags">
          <h4>Tags</h4>
          <a href="#">Lorem ipsum</a>
          <a href="#">Dolor</a>
          <a href="#">Long established</a>
          <a href="#">Sit amet</a>
          <a href="#">Latin words</a>
          <a href="#">Excepteur sint</a>
        </div><!-- End widget -->
                <hr>
                <div class="widget archives">
          <h4>Archives</h4>
          <ul class="list_2">   
                        <li><a href="#">May 2015</a></li>
                        <li><a href="#">April 2015</a></li>
                        <li><a href="#">March 2015</a></li>
                        <li><a href="#">February 2015</a></li>
                        <li><a href="#">January 2015</a></li>
                    </ul>
        </div><!-- End widget -->
                    
                </div><!-- End col-md-3 -->
            </div><!-- End row -->
          </div>
            </main>
        </div><!-- End container -->
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