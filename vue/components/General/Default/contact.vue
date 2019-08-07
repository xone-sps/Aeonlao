<template>
  <div>
    <!--====== CONTACT PART START ======-->
    <section id="contact-page" class="top_120">
      <div class="container">
        <div class="columns">
          <div class="column is-7">
            <ContactForm :isFullWidth="true"/>
            <!--  contact from -->
          </div>
      <div class="column is-5">
          <section class="section">
              <div class="contact-address">
                <ul v-if="!$utils.isEmptyVar( s )">
                  <li id="address_footer">
                   <i class="fa fa-home icon aeon"></i>
                   {{s.address}}
                   <!-- singel address -->
                 </li>
                 <hr>
                 <li id="phone_footer">
                  <i class="fa fa-phone icon aeon"></i>
                  <a href="tel:020 52202014">{{s.phone}}</a>
                  <!-- singel address -->
                </li>
                <hr>
                <li>
                  <i class="fas fa-envelope icon aeon"></i>
                  <a href="mailto:john@example.com">{{s.email}}</a>
                  <!-- singel address -->
                </li>
              </ul>
            </div>
            <hr>
            <h4>Get Direction</h4>
            <br>
            <div class="gmap top-10">
              <iframe
              src="https://maps.google.com/maps?width=100%&amp;height=450&amp;hl=en&amp;q=ກະຊວງສຶກສາເຈົ້າອານຸ&amp;ie=UTF8&amp;t=&amp;z=13&amp;iwloc=B&amp;output=embed"
              frameborder="0"
              scrolling="no"
              marginheight="0"
              marginwidth="0"
              style="width: 100%;
              height: 250px;"
              ></iframe>
            </div>
        </section>
                  </div>
      </div>
      <!-- row -->
    </div>
    <!-- container -->
  </section>
  <!--====== CONTACT PART ENDS ======-->
</div>
</template>
<script>
import Base from "@com/Bases/GeneralBase.js";
import ContactForm from "@com/General/ContactForm.vue";

export default Base.extend({
  components: {
    ContactForm
  },
  created() {
    this.setPageTitle(`Contact Us`);
  }
});
</script>
<style lang="less" scoped>
.gmap {
  padding: 6px 6px 0;
  -webkit-border-radius: 8px;
  border-radius: 8px;
  border: 1px solid #dadce0;
  background-color: #ffffff;
}
</style>


