
jQuery( document ).ready(function() {
  var date = new Date();
  var hours = date.getHours();
  var mins = date.getMinutes();
  menuUrlAM = jQuery(".order-online.AM");
  menuUrlPM = jQuery(".order-online.PM");
  
  //console.log(date);
  console.log('Hour: ' + hours);
  console.log('Minutes: ' + mins);
 
  //console.log(menuUrlAM);

  //if (hours >= 4 && (hours <= 16 && mins <= 29) || (hours < 16 && hours > 11)) { // if it's after 11am but before 430pm
  if (hours >= 4 && hours < 16) { // //if it's after 4am but before 4pm
  	//console.log('am menu');
    if(menuUrlAM && menuUrlPM) {
    	console.log('Show AM');
		menuUrlAM.css('display', 'inline-block');
		menuUrlPM.css('display', 'none');  

	}
  } 
  else {
    console.log('pm menu');
    if(menuUrlAM && menuUrlPM) {
		console.log('Show PM');
		menuUrlAM.css('display', 'none');
		menuUrlPM.css('display', 'inline-block');  
    
    }
  }
});



window.addEventListener('load', function(){
	if(jQuery(document).width() > 1120){
		var screenWidth = jQuery(document).width() - 1080;
		var oneSideMargin = screenWidth/2;
		jQuery('.hero-slider .bx-controls .bx-pager').css('right', oneSideMargin);
	}
});

jQuery( document ).ready(function() {
	jQuery('#gform_6').attr('action','https://www.opentable.com/restaurant-search.aspx');
	jQuery('#gform_6').attr('target','_blank');
	jQuery('#gform_6').attr('method','GET');
	jQuery('#input_6_1').attr('name','PartySize');
	jQuery('#input_6_6').attr('name','ResTime');
	jQuery('#input_6_5').attr('name','startDate');
	jQuery('#input_6_7').attr('name','RestaurantReferralID');
	jQuery('#input_6_8').attr('name','RestaurantID');
	jQuery('#input_6_9').attr('name','txtDateFormat');
	if(jQuery(document).width() > 1120){
		var screenWidth = jQuery(document).width() - 1080;
		var oneSideMargin = screenWidth/2;
		console.log(oneSideMargin);
		jQuery('.hero-slider .fl-slide-foreground').css('margin-right', oneSideMargin);
		jQuery('.hero-slider .bx-controls').css('margin-right', oneSideMargin);
		jQuery('.hero-slider .fl-content-slider-navigation').css('margin-right', oneSideMargin);
	   
	}
	
/*var slider = jQuery('.fl-content-slider').bxSlider({
   onSlideNext: doThis,
   onSlidePrev: doThis
 });

function doThis(ele, old, newi){
	console.log(test);
  //slider.goToSlide(newi);
  //slider1.goToSlide(newi);
}*/

jQuery(".beermenu").hide();

jQuery(".pizzalink").click(function () {
    jQuery(".pizzamenu").show();
	jQuery(".beermenu").hide();
	jQuery('.pizzalink a').css({"color":"#F04C24"});
	jQuery('.beerlink a').css({"color":"#294754"});
	return false;
});

jQuery(".beerlink").click(function () {
    jQuery(".beermenu").show();
	jQuery(".pizzamenu").hide();
	jQuery('.beerlink a').css({"color":"#F04C24"});
	jQuery('.pizzalink a').css({"color":"#294754"});
	return false;
});

jQuery('.sp_section_header').each(function(){
	jQuery(this).click(function(){
		jQuery(this).next(".flex-items").slideToggle();
	});
});
	

});

jQuery(window).bind("load", function() {
	if (jQuery("header").hasClass("fl-theme-builder-header-sticky")) {
		var headerHeight = jQuery('header').innerHeight();
		jQuery('body').css("padding-top", headerHeight); 
		console.log(headerHeight);
	}
});

		jQuery(document).ready(function($){
			$(".iscwp-img-link").addClass("first-class");
			$(".first-class").html("<span>view instagram image</span>")
			$(".first-class").css({"display":"none"});
		});


		jQuery(document).ready(function($){
			$(".mfp-close").addClass("second-class");
			$(".second-class").html("<span>close window</span>")
			$(".second-class").css({"display":"none"});
		});