<template>
	<div>
		<section class="section top_100">
			<div class="container">
				<div class="columns">
					<div class="column top_10">
						<PostsSearchForm v-model="query" @onSearchEnter="getItems('click')"/>
					</div>
				</div>
				<hr>
				<div class="main_title">
					<h3>Promotions</h3>
				</div>
				<div class="columns is-multiline is-mobile">
					<div class="column is-3 is-desktop">
						<div class="card">
							<div class="card-image">
								<figure class="image is-4by3">
									<img src="https://versions.bulma.io/0.7.0/images/placeholders/1280x960.png" alt="Placeholder image">
								</figure>
							</div>
							<div class="pro-date">
								<span>start date</span> <span>end date</span>
							</div>
							<div class="card-content pro">
								<div class="card-title">
									<a>Hello world</a>
								</div>
								<div class="content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit.
									Phasellus nec iaculis mauris. <a>@bulmaio</a>.
									<a href="#">#css</a> <a href="#">#responsive</a>
									<br>
									<time datetime="2016-1-1">Published 11:09 PM - 1 Jan 2016</time>
								</div>
							</div>
						</div>
					</div>
					<div class="column is-3">
						<div class="card">
							<div class="card-image">
								<figure class="image is-4by3">
									<img src="https://versions.bulma.io/0.7.0/images/placeholders/1280x960.png" alt="Placeholder image">
								</figure>
							</div>
							<div class="pro-date">
								<span>start date</span> <span>end date</span>
							</div>
							<div class="card-content pro">
								<div class="card-title">
									<a>Hello world</a>
								</div>
								<div class="content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit.
									Phasellus nec iaculis mauris. <a>@bulmaio</a>.
									<a href="#">#css</a> <a href="#">#responsive</a>
									<br>
									<time datetime="2016-1-1">Published 11:09 PM - 1 Jan 2016</time>
								</div>
							</div>
						</div>
					</div>
					<div class="column is-3">
						<div class="card">
							<div class="card-image">
								<figure class="image is-4by3">
									<img src="https://versions.bulma.io/0.7.0/images/placeholders/1280x960.png" alt="Placeholder image">
								</figure>
							</div>
							<div class="pro-date">
								<span>start date</span> <span>end date</span>
							</div>
							<div class="card-content pro">
								<div class="card-title">
									<a>Hello world</a>
								</div>
								<div class="content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit.
									Phasellus nec iaculis mauris. <a>@bulmaio</a>.
									<a href="#">#css</a> <a href="#">#responsive</a>
									<br>
									<time datetime="2016-1-1">Published 11:09 PM - 1 Jan 2016</time>
								</div>
							</div>
						</div>
					</div>
					<div class="column is-3">
						<div class="card">
							<div class="card-image">
								<figure class="image is-4by3">
									<img src="https://versions.bulma.io/0.7.0/images/placeholders/1280x960.png" alt="Placeholder image">
								</figure>
							</div>
							<div class="pro-date">
								<span>start date</span> <span>end date</span>
							</div>
							<div class="card-content pro">
								<div class="card-title">
									<a>Hello world</a>
								</div>
								<div class="content">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit.
									Phasellus nec iaculis mauris. <a>@bulmaio</a>.
									<a href="#">#css</a> <a href="#">#responsive</a>
									<br>
									<time datetime="2016-1-1">Published 11:09 PM - 1 Jan 2016</time>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
			
		</section>
	</div>
</template>

<style type="text/css" media="screen">
.card-title{
	font-size: 18px;
	font-weight: 600;
}
.pro-date{
	padding: 4px 8px 4px 8px;
	font-size: 12px;
}
.card-content.pro{
	padding-top: 4px;
}
.card-content.pro time{
	font-size: 13px;
}
.card-image img{
	cursor: pointer;
}
</style>