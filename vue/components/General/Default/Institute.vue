<template>
    <div>
        <section id="courses-part" class="pt-20 pb-120 gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="courses-top-search">
                            <ul class="nav float-left" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <span>ສະແດງ  {{ paginate.data.length }} ທັງໝົດ {{ paginate.total }}</span>
                                </li>
                                <li>
                                    <div class="shop-select">
                                        <select class="select form-control" v-model="instituteCategory">
                                            <option value="">ທັງຫມົດໜວດໝູ່</option>
                                            <option v-for="cat in homeData.instituteCategoriesHome"
                                                    :value="cat.id">{{cat.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <!-- shop select -->
                                </li>
                            </ul>
                            <!-- nav -->

                            <div class="courses-search float-right">
                                <PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
                            </div>
                            <!-- courses search -->
                        </div>
                        <!-- courses top search -->
                    </div>
                </div>
                <!-- row -->
                <div class="tab-pane fade show active" id="courses-grid" role="tabpanel">
                    <div v-if="!paginate.data.length" class="row justify-content-md-center" style="min-height: 399px;">
                        <div class="col-md-auto">
                            <div class="alert alert-light mt-4" role="alert">
                                <div class="devsite-article">
                                    <h1 class="devsite-page-title">
                                        Search results for <span class="devsite-search-term"><span
                                        class="devsite-search-term">{{ query }}</span></span></h1>
                                </div>
                                <div class="result-snippet">{{ query==='' ? 'Results...' : 'No Results' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6" v-for="(item, idx) in postsData.institutes.posts.data"
                             :key="idx">
                            <div class="custom-card mt-10">
                                <a class="grid-card card">
                                    <div class="media"
                                         @click="Route({name: 'institute-single', params: {id: item.id}})">
                                        <img :src="`${baseUrl}${item.image}`" alt="Course">
                                    </div>
                                    <div class="card-body">
                                        <div class="media-block">
                                            <div class="body">
                                                <h3 @click="Route({name: 'institute-single', params: {id: item.id}})"
                                                    class="title">{{item.institute_name + ' ' + item.
                                                    short_institute_name}}</h3>
                                                <p class="content">
                                                    <i class="fas fa-calendar-check"></i>
                                                    <span>{{ item.formatted_founded}}</span>
                                                </p>
                                                <p class="conent">
                                                    <i class="fas fa-fw fa-envelope"></i>
                                                    <span>{{ item.public_email || 'N/A'}}</span>
                                                </p>
                                                <p class="content">
                                                    <i class="fas fa-fw fa-phone"></i>
                                                    <span>{{ item.phone_number || 'N/A'}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <div>
                                                <a @click="Route({name: 'institute', query: {category_id: item.category.id}})">
                                                    <h6>
                                                        <i class="fa fa-tags"></i> {{ item.category.name }}
                                                    </h6>
                                                </a>
                                                <div @click="Route({name: 'institute-single', params: {id: item.id}})"
                                                     class="button secondary border -full">View more
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- singel course -->
                        </div>
                        <!--<div class="col-lg-4 col-md-6">-->
                        <!--<div class="singel-course mt-30">-->
                        <!--<div class="thum">-->
                        <!--<div class="image">-->
                        <!--<img :src="`${baseUrl}${baseRes}/assets/images/course/cu-1.jpg`" alt="Course">-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--<div class="cont institute">-->
                        <!--<a href="courses-singel.html">-->
                        <!--<h4>ເສດຖະສາດແລະບໍລິຫານທຸລະກິດ</h4>-->
                        <!--</a>-->
                        <!--<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. In aliquid provident-->
                        <!--nesciunt inventore ipsa odit quisquam repellat praesentium?</p>-->
                        <!--<div class="course-teacher">-->
                        <!--<div class="name-course">-->
                        <!--<a href="#">-->
                        <!--<h6>-->
                        <!--<i class="fa fa-tags"></i> ມະຫາວິທະຍາໄລແຫ່ງຊາດ-->
                        <!--</h6>-->
                        <!--</a>-->
                        <!--</div>-->
                        <!--<div>-->
                        <!--<ul>-->
                        <!--<li>-->
                        <!--<span>Updated 22/4/2019</span>-->
                        <!--</li>-->
                        <!--</ul>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--&lt;!&ndash; singel course &ndash;&gt;-->
                        <!--</div>-->
                    </div>
                    <!-- row -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="courses-pagination mt-50">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a :disabled="paginate.current_page===1"
                                       @click="prevPage(paginate.current_page - 1)" aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>
                                <li class="page-item" v-for="p in paginate.last_page">
                                    <a :class="{'active': p===paginate.current_page }"
                                       @click="paginateNavigator({pageNo: p})">{{p}}</a>
                                </li>
                                <li class="page-item">
                                    <a :disabled="paginate.current_page===paginate.last_page"
                                       @click="nextPage(paginate.current_page + 1)" aria-label="Next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- courses pagination -->
                    </div>
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </section>

        <!--====== COURSES PART ENDS ======-->
    </div>
</template>
<script>
    import Base from "@com/Bases/GeneralBase.js";

    export default Base.extend({
        data: () => ({
            type: "institutes",
            instituteCategory: '',
        }),
        watch: {
            instituteCategory: function (n) {
                this.$router.replace({name: 'institute', query: {category_id: n || ''}});
            },
            '$route.query': function (n) {
                this.instituteCategory = n.category_id;
                this.options_request.category_id = n.category_id;
                this.getItems();
            }
        },
        created() {
            this.registerWatches();
            this.setPageTitle("Institutes");
            this.paginate.per_page = 6;
            this.options_request.category_id = this.$route.query.category_id || '';
            this.instituteCategory = this.$route.query.category_id || '';
            this.getItems();
        }
    });
</script>
<style scoped>

    .devsite-article {
        display: block;
        width: 100%;
        height: auto;
    }

    .devsite-page-title {
        font: 500 28px/40px Google Sans, sans-serif;
        letter-spacing: 0;
        margin: 8px 0 24px;
    }

    .devsite-search-term {
        color: #757575;
        font-weight: 400;
    }

    .result-snippet {
        display: block;
        width: 100%;
        background: 0;
        border: 0;
        color: #212121;
    }

</style>

