$(document).ready(function () { 
    $('.select-control').select2({
        minimumResultsForSearch: Infinity
    });
    $('.select-control.search').select2({});


    $('.tab-collapse').tabCollapse();
    $('.tab-collapse-sm').tabCollapse({
        tabsClass: 'visible-lg visible-md hidden-sm',
        accordionClass: 'visible-sm visible-xs'
    });


    $("[data-fancybox]").fancybox({
        thumbs     : false,
        slideShow  : false,
        fullScreen : false
    });


    $(".mcscroll").mCustomScrollbar({
        axis : "y",
        scrollButtons: {
            enable: true
        }
    });

    $('.top-graphic .owl-carousel').owlCarousel({
        loop: true,
        margin:0,
        items:1,
        navText:['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        smartSpeed: 1200,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        mouseDrag:true,
		nav:false,
		dots: true,
        responsive:{
            0:{
            },
            767:{
            },
            768:{
            }
        }
    });
	

	$('.product-block .slick').slick({
		infinite: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		arrows: false,
		focusOnSelect: true,
		rows: 2,
		slidesPerRow:3,
		responsive: [
			{
				breakpoint: 575,
				settings: {
//					variableWidth: true,
//					dots: false,
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow:1
				}
			},
			{
				breakpoint: 767,
				settings: {
//					variableWidth: true,
//					dots: false,
					slidesToShow: 2,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow:1
				}
			},
			{
				breakpoint: 1366,
				settings: {
					rows: 2,
					slidesPerRow:3
				}
			}
		]
	});
	
	
	var PMBSItems = $('.promotion-block .owl-carousel .item').length;
    $('.promotion-block .owl-carousel').owlCarousel({
        loop: PMBSItems > 3,
        margin:20,
        items:3,
        navText:['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        smartSpeed: 1200,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        mouseDrag:true,
		nav:false,
		dots: true,
        responsive:{
            0:{
				loop: false,
				items:1,
				margin:0
            },
            576:{
				loop: PMBSItems > 2,
				items:2,
				margin:10
            },
            768:{
            }
        }
    });
	
    $('.service-block .owl-carousel').owlCarousel({
        loop: true,
        margin:0,
        items:1,
        navText:['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        smartSpeed: 1200,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        mouseDrag:true,
		nav:false,
		dots: true
    });
	
	var BNFSNItems = $('.benefits-block .slider-nav .owl-carousel .item').length;
	$('.benefits-block .slider-nav .owl-carousel').owlCarousel({
        loop: BNFSNItems > 5,
        margin:0,
        items:5,
        navText:['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        smartSpeed: 1200,
        autoplay: false,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        mouseDrag:true,
		nav:true,
		dots: false,
        responsive:{
            0:{
				loop: BNFSNItems > 2,
				items:2,
				nav:false,
				dots: true
            },
			575:{
				loop: BNFSNItems > 3,
				items:3,
				nav:false,
				dots: true
            },
            767:{
				loop: BNFSNItems > 4,
				items:4,
				nav:false,
				dots: true
            },
            991:{
				nav:false,
				dots: true
            },
			1200:{
			}
        }
    });
	
	var BNFSFItems = $('.benefits-block .slider-for .owl-carousel .item').length;
	$('.benefits-block .slider-for .owl-carousel').owlCarousel({
        loop: BNFSFItems > 4,
        margin:20,
        items:4,
        navText:['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        smartSpeed: 1200,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        mouseDrag:true,
		nav:false,
		dots: true,
        responsive:{
            0:{
				autoWidth:false,
				dots: false,
				loop: true,
				items:1,
				margin:0
            },
			575:{
				loop: BNFSFItems > 2,
				items:2,
				margin:0
            },
            767:{
				loop: BNFSFItems > 3,
				items:3
            },
            991:{
            }
        }
    });
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		 e.target
		 e.relatedTarget
		 $('.benefits-block .slider-nav .owl-carousel').owlCarousel('setPosition');
		 $('.benefits-block .slider-for .owl-carousel').owlCarousel('setPosition');
	 });


    $('.overflow-line-1').trunk8({
        lines: 1,
        tooltip : false
    });
    $('.overflow-line-2').trunk8({
        lines: 2,
        tooltip : false
    });
    $('.overflow-line-3').trunk8({
        lines: 3,
        tooltip : false
    });
    $('.overflow-line-4').trunk8({
        lines: 4,
        tooltip : false
    });
    $('.overflow-line-5').trunk8({
        lines: 5,
        tooltip : false
    });
	
	
	$('.btn-mobile').click(function() {
		$('.nav-menu').toggleClass('open');
		$(this).toggleClass('close');
	});
	
	$('.btn-site').click(function() {
		$('.site-block').toggleClass('open');
	});
	
	
	
	$('.benefits-block .slider-nav .nav-item').click(function() {
		$('.benefits-block .slider-nav .nav-item').removeClass('active');
		$(this).toggleClass('active');
	});
	
	
	var width = $(window).width();
	if (width < 768) {
		$(function() {
			$('.product-block .slick').slick('slickRemove',2);
			$('.product-block .slick').slick('slickRemove',7);
			$('.product-block .slick').slick('slickRemove',12);
		});
	}
});