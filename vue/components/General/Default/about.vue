<template>
  <div>
    <!--====== ABOUT PART START ======-->
    <section id="about-page" class="top_100">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="section-title mt-30">
              <h5>About us</h5>
              <h2>{{s.site_name}}</h2>
            </div>
               <div class="about-cont" v-if="!$utils.isEmptyVar(s)">
                <p v-html="s.description"></p>
            </div>
          </div>
        </div>
        <!-- row -->
        <!-- about items -->
      </div>
      <!-- container -->
    </section>
  </div>
</template>
<script>
import Base from "@com/Bases/GeneralBase.js";
export default Base.extend({
  created() {
    this.setPageTitle(`About us`);
  }
});
</script>
