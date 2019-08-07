<template>
  <div>
    <section class="section pb-60 gray-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="happy-student mt-25">
              <div class="happy-title">
                <h3>ເອກະສານຕ່າງໆ</h3>
              </div>
              <ul>
                <li>
                  <div
                    class="singel-document"
                    v-for="(file , index) in homeData.files"
                    :key="index"
                  >
                    <h4>{{file.fileName}}</h4>
                    <div class="icon-donwload">
                      <span>
                        <a :href="`${path}${file.filePath}`" class="v-md-button v-md-icon-button">
                          <i class="material-icons v-icon">cloud_download</i>
                        </a>
                      </span>
                      <!-- <span><a target="_blank" href="#" class="v-md-button v-md-icon-button"><i class="material-icons">visibility</i></a></span> -->
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- row -->
      </div>
    </section>
  </div>
</template>
<script>
import Base from "@com/Bases/GeneralBase.js";
export default Base.extend({
  data() {
    return {
      path: `${this.baseUrl}/assets/files/`
    };
  },
  created() {
    this.setPageTitle(`Donload Documents`);
  }
});
</script>