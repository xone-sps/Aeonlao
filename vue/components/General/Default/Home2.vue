<template>
	<div>
	<section class="site-container">	
		<div class="top-graphic">
<!-- 			<div class="owl-carousel">

				<div class="item">
					<a href="#" class="link">
						<figure class="cover" style="background-image: url(image/upload/top-graphic.jpg)"></figure>
					</a>
				</div>
				<div class="item">
					<a href="#" class="link">
						<figure class="cover" style="background-image: url(image/upload/top-graphic.jpg)"></figure>
					</a>
				</div>
				<div class="item">
					<a href="#" class="link">
						<figure class="cover" style="background-image: url(image/upload/top-graphic.jpg)"></figure>
					</a>
				</div>

			</div> -->
			<carousels :items="homeData.banners"/>
		</div>
		
		<div class="wg-product">
			<div class="container">
				  <InstituteCategory/>
				<div class="row-table">
					<div class="col">
						<div class="whead">
							<div class="row-table">
								<div class="col-auto">
									<div class="wh-icon"><img src="image/icon/wh-icon1.png" alt="icon"></div>
								</div>
								<div class="col">
									<h4 class="title text-light">Pro<span class="color">duct</span></h4>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="btn-block">
							<a href="#" class="btn btn-secondary">VIEW ALL</a>
						</div>
					</div>
				</div>
				<div class="product-block">
					<div class="slick">

						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate1.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TELEPHONE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate2.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">ELECTRONIC</h6>
								</a>
							</div>
						</div>
						<div class="no-item">
							<div class="item">
								<figure class="cover" style="background-color:#55118F">
									<img src="image/cover/product-cover.png" alt="cover">
								</figure>
								<h6 class="title">NONE</h6>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate3.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">BAG</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate4.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TIRE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate5.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">sports equipment</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate1.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TELEPHONE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate2.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">ELECTRONIC</h6>
								</a>
							</div>
						</div>
						<div class="no-item">
							<div class="item">
								<figure class="cover" style="background-color:#55118F">
									<img src="image/cover/product-cover.png" alt="cover">
								</figure>
								<h6 class="title">NONE</h6>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate3.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">BAG</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate4.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TIRE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate5.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">sports equipment</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate1.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TELEPHONE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate2.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">ELECTRONIC</h6>
								</a>
							</div>
						</div>
						<div class="no-item">
							<div class="item">
								<figure class="cover" style="background-color:#55118F">
									<img src="image/cover/product-cover.png" alt="cover">
								</figure>
								<h6 class="title">NONE</h6>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate3.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">BAG</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate4.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">TIRE</h6>
								</a>
							</div>
						</div>
						<div>
							<div class="item">
								<a href="#" class="link">
									<figure class="cover" style="background-image: url(image/upload/product-cate5.jpg)">
										<img src="image/cover/product-cover.png" alt="cover">
									</figure>
									<h6 class="title">sports equipment</h6>
								</a>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- #promotion -->
		<div class="wg-promotion">
			<div class="container">
				<div class="row-table">
					<div class="column">
						<div class="whead">
							<div class="row-table">
								<div class="col-auto">
									<div class="wh-icon"><img src="image/icon/wh-icon2.png" alt="icon"></div>
								</div>
								<div class="col">
									<h4 class="title">Promo<span class="color">tion</span></h4>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="btn-block">
							<a href="#" class="btn btn-primary">VIEW ALL</a>
						</div>
					</div>
				</div>
				<div class="promotion-block">
					<promotion/>
					<div class="owl-carousel">
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide1.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide2.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide3.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide1.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide2.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide3.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide1.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide2.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
						<div class="item">
							<a href="#" class="link">
								<figure class="cover" style="background-image: url(image/upload/promotion-slide3.jpg)">
									<img src="image/cover/promotion-slide-cover.png" alt="cover">
								</figure>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="wg-service">
			<div class="container">
				<div class="row-table">
					<div class="col">
						<div class="whead">
							<div class="row-table">
								<div class="col-auto">
									<div class="wh-icon"><img src="image/icon/wh-icon3.png" alt="icon"></div>
								</div>
								<div class="col">
									<h4 class="title text-light">Ser<span class="color">vice</span></h4>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="btn-block">
							<a href="#" class="btn btn-secondary">VIEW ALL</a>
						</div>
					</div>
				</div>
				<div class="service-block">
					<div class="owl-carousel">
						<div class="item">
							<div class="row-table">
								<div class="col-auto">
									<div class="smartcard">
										<img src="image/upload/service-card.png" alt="service-card">
									</div>
								</div>
								<div class="col">
									<div class="content">
										<h6 class="title overflow-line-1">Membership card has special privileges on rates</h6>
										<p class="desc overflow-line-2">
											Interest on installments And discounts at stores Participating in the program when
											Interest on installments And discounts at stores Participating in the program when
										</p>
										<div class="btn-box">
											<a href="#" class="btn btn-light">Read More</a>
											<a href="#" class="btn btn-warning">Register</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="row-table">
								<div class="col-auto">
									<div class="smartcard">
										<img src="image/upload/service-card.png" alt="service-card">
									</div>
								</div>
								<div class="col">
									<div class="content">
										<h6 class="title overflow-line-1">Membership card has special privileges on rates</h6>
										<p class="desc overflow-line-2">
											Interest on installments And discounts at stores Participating in the program when
											Interest on installments And discounts at stores Participating in the program when
										</p>
										<div class="btn-box">
											<a href="#" class="btn btn-light">Read More</a>
											<a href="#" class="btn btn-warning">Register</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="row-table">
								<div class="col-auto">
									<div class="smartcard">
										<img src="image/upload/service-card.png" alt="service-card">
									</div>
								</div>
								<div class="col">
									<div class="content">
										<h6 class="title overflow-line-1">Membership card has special privileges on rates</h6>
										<p class="desc overflow-line-2">
											Interest on installments And discounts at stores Participating in the program when
											Interest on installments And discounts at stores Participating in the program when
										</p>
										<div class="btn-box">
											<a href="#" class="btn btn-light">Read More</a>
											<a href="#" class="btn btn-warning">Register</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="wg-benefits">
			<div class="container">
				<div class="row-table">
					<div class="col">
						<div class="whead">
							<div class="row-table">
								<div class="col-auto">
									<div class="wh-icon"><img src="image/icon/wh-icon4.png" alt="icon"></div>
								</div>
								<div class="col">
									<h4 class="title">Benefits from membership cards</h4>
									<h5 class="sub-title">ສິດຜົນປະໂຫຍດສຳລັບສະມາຊິກ AEON</h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="btn-block">
							<a href="#" class="btn btn-primary">VIEW ALL</a>
						</div>
					</div>
				</div>
				<div class="benefits-block">
					<div class="slider-nav">
						<div class="owl-carousel">
							<div>
								<a class="nav-item link active" data-toggle="tab" href="#tab-1">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon1.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">All</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-2">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon2.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Air Conditioner</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-3">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon3.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Audio</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-4">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon4.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Bag</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-5">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon5.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">CCTV</div>
								</a>
							</div>
							<div>
								<a class="nav-item link active" data-toggle="tab" href="#tab-1">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon1.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">All</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-2">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon2.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Air Conditioner</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-3">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon3.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Audio</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-4">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon4.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Bag</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-5">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon5.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">CCTV</div>
								</a>
							</div>
							<div>
								<a class="nav-item link active" data-toggle="tab" href="#tab-1">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon1.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">All</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-2">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon2.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Air Conditioner</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-3">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon3.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Audio</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-4">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon4.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">Bag</div>
								</a>
							</div>
							<div>
								<a class="nav-item link" data-toggle="tab" href="#tab-5">
									<figure class="cover" style="background-image: url(image/upload/benefits-navicon5.png)">
										<img src="image/cover/square-cover.png" alt="cover">
									</figure>
									<div class="title">CCTV</div>
								</a>
							</div>
						</div>
					</div>
					<div class="slider-for">
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab-1">
								<div class="owl-carousel">
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-2">
								<div class="owl-carousel">
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-3">
								<div class="owl-carousel">
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-4">
								<div class="owl-carousel">
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide3.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide4.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide1.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide2.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide3.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide4.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-5">
								<div class="owl-carousel">
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide1.jpg)">
												<img src="public/image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(public/image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide1.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide2.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide3.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
									<div class="item">
										<a href="#" class="link">
											<figure class="cover" style="background-image: url(image/upload/benefits-slide4.jpg)">
												<img src="image/cover/benefits-slide-cover.png" alt="cover">
											</figure>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="social-list">
							<ul class="item-list">
								<li class="facebook"><a href="#" target="_blank" class="link"><span class="fab fa-facebook-f"></span></a></li>
								<li class="google"><a href="#" target="_blank" class="link"><span class="fab fa-google-plus-g"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="wg-member-shop">
			<div class="container">
				<div class="whead">
					<div class="row-table">
						<div class="col">
							<h4 class="title"><strong>AEON</strong> Member shop</h4>
							<h5 class="sub-title">ຮ້ານຄ້າຕົວແທນອິອອນ</h5>
							<div class="desc">Interested in becoming a club shop representative</div>
							<div class="btn-box">
								<a href="#" class="btn btn-warning">Apply as an agent</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="wg-location">
			<div class="container">
				<div class="whead">
					<h4 class="title"><strong>AEON</strong> service network</h4>
					<h5 class="sub-title">ສຳນັກງານອິອອນ</h5>
					<div class="desc">Our service points will help you find the location of the automatic cash machine.</div>
					<div class="btn-box">
						<a href="#" class="btn btn-warning">Service network</a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="wg-dealer">
			<div class="container">
				<div class="whead">
					<h4 class="title"><strong>AEON</strong> Member shop</h4>
					<h5 class="sub-title">ຮ້ານຄ້າຕົວແທນອິອອນ</h5>
					<div class="desc">ສົນໃຈເຂົ້າຮ່ວມເປັນຕົວແທນກັບເຮົາ</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-4 col-sm-12 col-xs-12 col-vxs-12">
						<a href="#" class="link">
							<div class="banner-box" style="background-image: url(image/upload/dealer1.jpg)">
								<div class="title">
									<p>ຕົວແທນຮ້ານຄ້າທີ່ສາມາດ</p>
									<p style="color: #f9a61a;">ຜ່ອນອິອອນໄດ້</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 col-vxs-12">
						<a href="#" class="link">
							<div class="banner-box" style="background-image: url(image/upload/dealer2.jpg)">
								<div class="title">
									<strong>APPLY AS AN</strong><br>
									<strong style="color: #f9a61a;">AEON DEALER</strong>
									<p>ສະໝັກເປັນຮ້ານຄ້າຕົວແທນອິອອນ</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 col-vxs-12">
						<a href="#" class="link">
							<div class="banner-box" style="background-image: url(image/upload/dealer3.jpg)">
								<div class="title">
									<strong>Con<span style="color: #f9a61a;">tact</span></strong>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		
	</section>
				</div>
			</template>


			<script>
			import {Carousel, Slide} from "vue-carousel";
			import Carousels from "@com/General/Partial/Carousel.vue";
			import InstituteCategory from "@com/General/Default/instituteCategory.vue";
			import Promotion from "@com/General/Default/Slider/Promotion.vue"
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
					InstituteCategory,
					Promotion
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
			.slide-left {
				-webkit-animation: slide-left 0.9s cubic-bezier(0.250, 0.460, 0.450, 0.940) infinite alternate both;
				animation: slide-left 0.9s cubic-bezier(0.250, 0.460, 0.450, 0.940) infinite alternate both;
			}
/* ----------------------------------------------
 * Generated by Animista on 2019-8-9 10:42:16
 * w: http://animista.net, t: @cssanimista
 * ---------------------------------------------- */

/**
 * ----------------------------------------
 * animation slide-left
 * ----------------------------------------
 */
 @-webkit-keyframes slide-left {
 	0% {
 		-webkit-transform: translateX(0);
 		transform: translateX(0);
 	}
 	100% {
 		-webkit-transform: translateX(-100px);
 		transform: translateX(-100px);
 	}
 }
 @keyframes slide-left {
 	0% {
 		-webkit-transform: translateX(0);
 		transform: translateX(0);
 	}
 	100% {
 		-webkit-transform: translateX(-100px);
 		transform: translateX(-100px);
 	}
 }


 .shadow-drop-center {
 	-webkit-animation: shadow-drop-center 2s cubic-bezier(0.250, 0.460, 0.450, 0.940) infinite both;
 	animation: shadow-drop-center 2s cubic-bezier(0.250, 0.460, 0.450, 0.940) infinite both;
 }

/* ----------------------------------------------
 * Generated by Animista on 2019-8-9 10:48:21
 * w: http://animista.net, t: @cssanimista
 * ---------------------------------------------- */

/**
 * ----------------------------------------
 * animation shadow-drop-center
 * ----------------------------------------
 */
 @-webkit-keyframes shadow-drop-center {
 	0% {
 		box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
 	}
 	100% {
 		box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.35);
 	}
 }
 @keyframes shadow-drop-center {
 	0% {
 		box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
 	}
 	100% {
 		box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.35);
 	}
 }

 .card.service{
 	padding: 24px;
 	color: rgba(3,27,78,.7);
 	border-radius: 4px;
 	background-color: #fff;
 	border: 1px solid #e5e8ed;
 	cursor: pointer;

 	-webkit-box-shadow: 0 2px 4px rgba(3,27,78,.06);
 	box-shadow: 0 2px 4px rgba(3,27,78,.06);
 	-webkit-transition: -webkit-box-shadow .25s linear;
 	transition: -webkit-box-shadow .25s linear;
 	transition: box-shadow .25s linear;
 	transition: box-shadow .25s linear,-webkit-box-shadow .25s linear;
 }
 .card.service:hover{
 	-webkit-box-shadow: 0 10px 20px rgba(3,27,78,.1);
 	box-shadow: 0 10px 20px rgba(3,27,78,.1);
 	color: rgba(3,27,78,.7);
 }
 .www-card-title{
 	color: #031b4e;
 	font-size: 18px;
 	margin: 0;
 }
 .www-card-detail{
 	margin: 0 0 16px;
 }
 img{
 	max-width: 100%;
 	height: auto;
 	border: 0;
 }
 .card-image{
 	margin-bottom: 16px;
 }

 </style>

