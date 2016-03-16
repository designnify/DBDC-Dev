jQuery(function( $ ){

	$('.featured-single .wrap') .css({'height': (($(window).height()*0.8))+'px'});
	$(window).resize(function(){
		$('.featured-single .wrap') .css({'height': (($(window).height()*0.8))+'px'});
	});

	$(".featured-single .entry-header .entry-meta").after('<p class="arrow"><a href="#site-inner"></a></p>');

	$.localScroll({
		duration: 750
	});

});