<template>
	<div>
		<div class="top_66">
			<carousels class="top-40" :items="homeData.banners"/>
		</div>
		<div id="get_quote">
			<section class="section">
				<div class="container">
					<div class="columns">
						<div class="column is-9">
							<h3>Looking for a Promotion service? </h3>
						</div>
						<div class="column is-3">
							<!-- 					<a href="quotation_wizard.html" class="btn_quote">Get Promotion</a> -->
							<div class="button_cont btn_quote" align="center">
								<a class="main_b" href="add-website-here" target="_blank">Get Promotion</a>
							</div>
						</div>
					</div><!-- End row -->
				</div><!-- End container -->
			</section>
		</div>

		<div id="feat_home">
			<section class="section">
				<div class="container margin_60_25">
					<div class="columns">
						<div class="column is-6">
							<div class="box_feat">
								<span><img src="/assets/images/Kredit-AEON.png" alt=""></span>
								<h3>Full Protection</h3>
								<p>Lorem ipsum dolor sit amet, no movet simul laoreet pri, aperiri fabulas expetenda ei pro. Sed vero assentior ad, est vide liber viris ne. Mea saperet evertitur intellegebat ei, an labore cetero eos.</p>
							</div>
						</div>
						<div class="column is-6">
							<div class="box_feat">
								<span><img src="/assets/images/credit.jpg" alt=""></span>
								<h3>Management via App</h3>
								<p>Mea saperet evertitur intellegebat ei, an labore cetero eos. Quo no facilisis contentiones, enim graeci vim ea, justo graecis ne qui. His tation theophrastus intellegebat ut, hinc fierent nam ea.</p>
							</div>
						</div>
					</div><!-- End row -->

				</div>
			</section>
		</div>
		<section class="section">
			<div class="container">
				<div>
					<h3>News</h3>
					<p>
						<a href="tips.html" class="link_normal" style="float:right; margin-top:-24px;">More  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
					</p>
				</div>
				<hr style="margin-top:2px;">
			</div>
		</section>
		<section class="section">
			<div class="container">
				<div class="columns">
					<div class="column is-4 news">
						<figure class="animated"><a href="service_1.html">
							<img src="/assets/images/posts/service.jpg" alt="" class="img-responsive"></a></figure>
							<h3>Wiring installation</h3>
							<p>
								Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset, doctus volumus.
							</p>
						</div>
						<div class="column is-4 news">
							<figure class="animated"><a href="service_2.html">
								<img src="/assets/images/posts/service2.jpg" alt="" class="img-responsive"></a></figure>
								<h3>Security Plan</h3>
								<p>
									Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset, doctus volumus.
								</p>
							</div>
							<div class="column is-4">
								<figure class="animated"><a href="service_3.html">
									<img src="/assets/images/posts/service3.jpg" alt="" class="img-responsive"></a></figure>
									<h3>Security Check</h3>
									<p>
										Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset, doctus volumus.
									</p>
								</div>	
							</div>
						</div>
					</section>
					<section class="section">
						<div class="container">
							<h3 style="margin-top:-24px;">Activity</h3>
							<p style="padding-bottom: 8px;margin-top: -20px;">
								<a href="tips.html" class="link_normal" style="float:right;">Read more activity <i class="fa fa-angle-right" aria-hidden="true"></i></a>
							</p>
						</div>
					</section>
					<hr>
					<div id="feat_home">
						<section class="section">
							<div class="container margin_60_25">
								<div class="columns">
									<div class="column is-6">
										<div class="box_feat">
											<span><img src="/assets/images/Kredit-AEON.png" alt=""></span>
											<h3>Full Protection</h3>
											<p>Lorem ipsum dolor sit amet, no movet simul laoreet pri, aperiri fabulas expetenda ei pro. Sed vero assentior ad, est vide liber viris ne. Mea saperet evertitur intellegebat ei, an labore cetero eos.</p>
										</div>
									</div>
									<div class="column is-6">
										<div class="box_feat">
											<span><img src="/assets/images/credit.jpg" alt=""></span>
											<h3>Management via App</h3>
											<p>Mea saperet evertitur intellegebat ei, an labore cetero eos. Quo no facilisis contentiones, enim graeci vim ea, justo graecis ne qui. His tation theophrastus intellegebat ut, hinc fierent nam ea.</p>
										</div>
									</div>
								</div><!-- End row -->

							</div>
						</section>
					</div>

				</div>
			</template>


			<script>
			import {Carousel, Slide} from "vue-carousel";
			import Carousels from "@com/General/Partial/Carousel.vue";
			import InstituteCategory from "@com/General/Default/instituteCategory.vue";
			import Sponsor from "@com/General/Default/Sponsor.vue";
			import ContactForm from "@com/General/ContactForm.vue";
			import Base from "@com/Bases/GeneralBase.js";
			import {mapActions} from "vuex";

			export default Base.extend({
				name: "Home",
				data: () => ({
            contactInfo: {header_title: "Contact Us Now"} //for contact form
        }),
				components: {
					Carousels,
					Carousel,
					Slide,
					ContactForm,
					Sponsor,
					InstituteCategory
				},
				created() {
					this.setPageTitle(`Home`);
				}
			});
			</script>

			<style type="text/css" media="screen">
			h2{
				margin-top: 100px;
				margin-bottom: 40px;
			}

			
			</style>