<template>
  <div>
    <!--====== COURSES SINGEl PART START ======-->
    <section id="corses-singel" class="pt-40 pb-60 gray-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="corses-singel-left mt-30">
              <div class="title">
                <h3>ສະຖາບັນການເງິນ-ການທະນາຄານ</h3>
              </div>
              <!-- title -->
              <div class="course-terms">
                <ul>
                  <li>
                    <div class="teacher-name">
                      <div class="thum">
                        <img
                          :src="`${baseUrl}${baseRes}/assets/images/course/teacher/t-1.jpg`"
                          alt="Teacher"
                        >
                      </div>
                      <div class="name">
                        <span>Teacher</span>
                        <h6>Mark anthem</h6>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="course-category">
                      <span>ໝວດໝູ່ສະຖານການສຶກສາ</span>
                      <h6>ພາກລັດ</h6>
                    </div>
                  </li>
                </ul>
              </div>
              <!-- course terms -->

              <div class="corses-singel-image pt-50">
                <img :src="`${baseUrl}${baseRes}/assets/images/course/cu-1.jpg`" alt="Courses">
              </div>
              <!-- corses singel image -->

              <div class="corses-tab mt-30">
                <ul class="nav nav-justified" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a
                      class="active"
                      id="overview-tab"
                      data-toggle="tab"
                      href="#overview"
                      role="tab"
                      aria-controls="overview"
                      aria-selected="true"
                    >ກ່ຽວກັບສະຖານການສຶກສາ</a>
                  </li>
                  <li class="nav-item">
                    <a
                      id="curriculam-tab"
                      data-toggle="tab"
                      href="#curriculam"
                      role="tab"
                      aria-controls="curriculam"
                      aria-selected="false"
                    >ຫຼັດສູດການສຶກສາ</a>
                  </li>
                  <li class="nav-item">
                    <a
                      id="instructor-tab"
                      data-toggle="tab"
                      href="#instructor"
                      role="tab"
                      aria-controls="instructor"
                      aria-selected="false"
                    >ຂໍ້ມູນຕິດຕໍ່</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div
                    class="tab-pane fade show active"
                    id="overview"
                    role="tabpanel"
                    aria-labelledby="overview-tab"
                  >
                    <div class="overview-description">
                      <div class="singel-description pt-40">
                        <h6>ກ່ຽວກັບສະຖາບັນ</h6>
                        <p>Lorem ipsum gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus .</p>
                      </div>
                      <div class="singel-description pt-40">
                        <h6>ຫຼັດສູດການສຶກສາ</h6>
                        <p>Lorem ipsum gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus .</p>
                      </div>
                    </div>
                    <!-- overview description -->
                  </div>
                  <div
                    class="tab-pane fade"
                    id="curriculam"
                    role="tabpanel"
                    aria-labelledby="curriculam-tab"
                  >
                    <div class="curriculam-cont">
                      <div class="title">
                        <h6>Learn basis javascirpt Lecture Started</h6>
                      </div>
                      <div class="accordion" id="accordionExample">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <a
                              href="#"
                              data-toggle="collapse"
                              data-target="#collapseOne"
                              aria-expanded="true"
                              aria-controls="collapseOne"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.1</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>

                          <div
                            id="collapseOne"
                            class="collapse show"
                            aria-labelledby="headingOne"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingTow">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseTow"
                              aria-expanded="true"
                              aria-controls="collapseTow"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.2</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>

                          <div
                            id="collapseTow"
                            class="collapse"
                            aria-labelledby="headingTow"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingThree">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseThree"
                              aria-expanded="false"
                              aria-controls="collapseThree"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.3</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>
                          <div
                            id="collapseThree"
                            class="collapse"
                            aria-labelledby="headingThree"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingFore">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseFore"
                              aria-expanded="false"
                              aria-controls="collapseFore"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.4</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>
                          <div
                            id="collapseFore"
                            class="collapse"
                            aria-labelledby="headingFore"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingFive">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseFive"
                              aria-expanded="false"
                              aria-controls="collapseFive"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.5</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>
                          <div
                            id="collapseFive"
                            class="collapse"
                            aria-labelledby="headingFive"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingSix">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseSix"
                              aria-expanded="false"
                              aria-controls="collapseSix"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.6</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>
                          <div
                            id="collapseSix"
                            class="collapse"
                            aria-labelledby="headingSix"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header" id="headingSeven">
                            <a
                              href="#"
                              data-toggle="collapse"
                              class="collapsed"
                              data-target="#collapseSeven"
                              aria-expanded="false"
                              aria-controls="collapseSeven"
                            >
                              <ul>
                                <li>
                                  <i class="fa fa-file-o"></i>
                                </li>
                                <li>
                                  <span class="lecture">Lecture 1.7</span>
                                </li>
                                <li>
                                  <span class="head">What is javascirpt</span>
                                </li>
                                <li>
                                  <span class="time d-none d-md-block">
                                    <i class="fa fa-clock-o"></i>
                                    <span>00.30.00</span>
                                  </span>
                                </li>
                              </ul>
                            </a>
                          </div>
                          <div
                            id="collapseSeven"
                            class="collapse"
                            aria-labelledby="headingSeven"
                            data-parent="#accordionExample"
                          >
                            <div class="card-body">
                              <p>Ut quis scelerisque risus, et viverra nisi. Phasellus ultricies luctus augue, eget maximus felis laoreet quis. Maecenasbibendum tempor eros.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- curriculam cont -->
                  </div>
                  <div
                    class="tab-pane fade"
                    id="instructor"
                    role="tabpanel"
                    aria-labelledby="instructor-tab"
                  >
                    <div class="instructor-cont">
                      <div class="instructor-description pt-25">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus fuga ratione molestiae unde provident quibusdam sunt, doloremque. Error omnis mollitia, ex dolor sequi. Et, quibusdam excepturi. Animi assumenda, consequuntur dolorum odio sit inventore aliquid, optio fugiat alias. Veritatis minima, dicta quam repudiandae repellat non sit, distinctio, impedit, expedita tempora numquam?</p>
                      </div>
                    </div>
                    <!-- instructor cont -->
                  </div>
                </div>
                <!-- tab content -->
              </div>
              <div>
                <ul class="share">
                  <li class="title">Share :</li>
                  <li>
                    <a target="_blank" href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li>
                    <a target="_blank" href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- corses singel left -->
          </div>
          <div class="col-lg-4">
            <div class="row">
              <div class="col-lg-12 col-md-6">
                <div class="course-features mt-30">
                  <h4>Institute Features</h4>
                  <ul>
                    <li>
                      <i class="fa fa-clock-o"></i>ອາຈານທັງໝົດ :
                      <span>50 ຄົນ</span>
                    </li>
                    <li>
                      <i class="fa fa-clone"></i>ປອ :
                      <span>09</span>
                    </li>
                    <li>
                      <i class="fa fa-beer"></i>ປທ :
                      <span>11</span>
                    </li>
                    <li>
                      <i class="fa fa-beer"></i>ປຕ :
                      <span>25</span>
                    </li>
                    <li>
                      <i class="fa fa-beer"></i>ຊັ້ນກາງ :
                      <span>05</span>
                    </li>
                    <li>
                      <i class="fa fa-user-o"></i>ນັກສຶກສາທັງໝົດ :
                      <span>1000</span>
                    </li>
                  </ul>
                  <div class="price-button pt-10">
                    <span>
                      Price :
                      <b>$25</b>
                    </span>
                    <a href="#" class="main-btn">Enroll Now</a>
                  </div>
                </div>
                <!-- course features -->
              </div>
              <div class="col-lg-12 col-md-6">
                <div class="You-makelike mt-30">
                  <h4>ສະຖານສຶກສາທີ່ທ່ານອາດມັກ</h4>
                  <div class="singel-makelike mt-20">
                    <div class="image">
                      <img
                        :src="`${baseUrl}${baseRes}/assets/images/your-make/y-1.jpg`"
                        alt="Image"
                      >
                    </div>
                    <div class="cont">
                      <a href="#">
                        <h4>Introduction to machine languages</h4>
                      </a>
                      <ul>
                        <li>
                          <a href="#">
                            <i class="fa fa-user"></i>31
                          </a>
                        </li>
                        <li>$50</li>
                      </ul>
                    </div>
                  </div>
                  <div class="singel-makelike mt-20">
                    <div class="image">
                      <img
                        :src="`${baseUrl}${baseRes}/assets/images/your-make/y-1.jpg`"
                        alt="Image"
                      >
                    </div>
                    <div class="cont">
                      <a href="#">
                        <h4>How to build a basis game with java</h4>
                      </a>
                      <ul>
                        <li>
                          <a href="#">
                            <i class="fa fa-user"></i>31
                          </a>
                        </li>
                        <li>$50</li>
                      </ul>
                    </div>
                  </div>
                  <div class="singel-makelike mt-20">
                    <div class="image">
                      <img
                        :src="`${baseUrl}${baseRes}/assets/images/your-make/y-1.jpg`"
                        alt="Image"
                      >
                    </div>
                    <div class="cont">
                      <a href="#">
                        <h4>Basic accounting from primary</h4>
                      </a>
                      <ul>
                        <li>
                          <a href="#">
                            <i class="fa fa-user"></i>31
                          </a>
                        </li>
                        <li>$50</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="releted-courses pt-45">
              <div class="title">
                <h3>ສະຖານການສຶກສາອື່ນໆທີ່ກຽ່ວຂ້ອງ</h3>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="singel-course mt-30">
                    <div class="thum">
                      <div class="image">
                        <img
                          :src="`${baseUrl}${baseRes}/assets/images/course/cu-2.jpg`"
                          alt="Course"
                        >
                      </div>
                    </div>
                    <div class="cont">
                      <a href="courses-singel.html">
                        <h4>ສະຖາບັນຂົງຈື້</h4>
                      </a>
                      <div class="course-teacher">
                        <div class="name-course">
                          <a href="#">
                            <h6>
                              <i class="fa fa-tags"></i> ມະຫາວິທະຍາໄລແຫ່ງຊາດ
                            </h6>
                          </a>
                        </div>
                        <div class="student">
                          <ul>
                            <li>
                              <i class="fa fa-user"></i>
                              <span>ອາຈານ 300</span>
                            </li>
                            <li>
                              <i class="fa fa-heart"></i>
                              <span>ນັກສຶກສາ 7000</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- singel course -->
                </div>
                <div class="col-md-4">
                  <div class="singel-course mt-30">
                    <div class="thum">
                      <div class="image">
                        <img
                          :src="`${baseUrl}${baseRes}/assets/images/course/cu-1.jpg`"
                          alt="Course"
                        >
                      </div>
                    </div>
                    <div class="cont">
                      <a href="courses-singel.html">
                        <h4>ສະຖາບັນລາວ-ເກົາຫຼີ</h4>
                      </a>
                      <div class="course-teacher">
                        <div class="name-course">
                          <a href="#">
                            <h6>
                              <i class="fa fa-tags"></i> ມະຫາວິທະຍາໄລແຫ່ງຊາດ
                            </h6>
                          </a>
                        </div>
                        <div class="student">
                          <ul>
                            <li>
                              <i class="fa fa-user"></i>
                              <span>ອາຈານ 300</span>
                            </li>
                            <li>
                              <i class="fa fa-heart"></i>
                              <span>ນັກສຶກສາ 7000</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- singel course -->
                </div>
                <div class="col-md-4">
                  <div class="singel-course mt-30">
                    <div class="thum">
                      <div class="image">
                        <img
                          :src="`${baseUrl}${baseRes}/assets/images/course/cu-1.jpg`"
                          alt="Course"
                        >
                      </div>
                    </div>
                    <div class="cont">
                      <a href="courses-singel.html">
                        <h4>ສະຖາບັນລາວ-ເກົາຫຼີ</h4>
                      </a>
                      <div class="course-teacher">
                        <div class="name-course">
                          <a href="#">
                            <h6>
                              <i class="fa fa-tags"></i> ມະຫາວິທະຍາໄລແຫ່ງຊາດ
                            </h6>
                          </a>
                        </div>
                        <div class="student">
                          <ul>
                            <li>
                              <i class="fa fa-user"></i>
                              <span>ອາຈານ 300</span>
                            </li>
                            <li>
                              <i class="fa fa-heart"></i>
                              <span>ນັກສຶກສາ 7000</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- singel course -->
                </div>
              </div>
              <!-- row -->
            </div>
            <!-- releted courses -->
          </div>
        </div>
        <!-- row -->
      </div>
      <!-- container -->
    </section>
    <!--====== COURSES SINGEl PART ENDS ======-->
  </div>
</template>
<script>
export default {
  data: {}
};
</script>
