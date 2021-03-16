$(function(){
		$('#camera_wrap_1').camera({
		transPeriod: 500,
		time: 3000,
		height: '490px',
		thumbnails: false,
		pagination: true,
		playPause: false,
		loader: false,
		navigation: false,
		hover: false
	});
});


$(document).ready(function(){

	function sdf_FTS(_number,_decimal,_separator)
	{
		var decimal=(typeof(_decimal)!='undefined')?_decimal:2;
		var separator=(typeof(_separator)!='undefined')?_separator:'';
		var r=parseFloat(_number)
		var exp10=Math.pow(10,decimal);
		r=Math.round(r*exp10)/exp10;
		rr=Number(r).toFixed(decimal).toString().split('.');
		b=rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1"+separator);
		r=(rr[1]?b+'.'+rr[1]:b);

		return r;
	}

	var startval = 0;
	var startval1 = 0;
	var startval2 = 0;

	var curval=parseInt($('#counter').text());
	var curval1=parseInt($('#counter1').text().replace(' ',''));
	var curval2=parseInt($('#counter2').text());
	
	$('#counter').text('0');
	$('#counter1').text('0');
	$('#counter2').text('0');

	setTimeout(function(){
		setInterval(function(){
			if(startval<=curval){
				startval = startval+1;
				$('#counter').text(startval);
			}
			if(startval1<=curval1){
				startval1 = startval1+99;
				if (startval1 > curval1) {startval1 = curval1};
				$('#counter1').text(sdf_FTS((startval1),0,' '));
			}
			if(startval2<=curval2){
				startval2 = startval2+1;
				$('#counter2').text(startval2);
			}
		}, 2);
		
	}, 500);
});




$(document).ready(function(){
	$('#menu').slicknav();

});

$(document).ready(function(){

	var $menu = $("#menuF");

	$(window).scroll(function(){
		if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
			$menu.fadeOut('fast',function(){
				$(this).removeClass("default")
				.addClass("fixed transbg")
				.fadeIn('fast');
			});
		} else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
			$menu.fadeOut('fast',function(){
				$(this).removeClass("fixed transbg")
				.addClass("default")
				.fadeIn('fast');
			});
		}
	});
});

// /*menu*/
// function calculateScroll() {
// 	var contentTop      =   [];
// 	var contentBottom   =   [];
// 	var winTop      =   $(window).scrollTop();
// 	var rangeTop    =   200;
// 	var rangeBottom =   500;
// 	$('.navmenu').find('a').each(function(){
// 		contentTop.push( $( $(this).attr('href') ).offset().top );
// 		contentBottom.push( $( $(this).attr('href') ).offset().top + $( $(this).attr('href') ).height() );
// 	})
// 	$.each( contentTop, function(i){
// 		if ( winTop > contentTop[i] - rangeTop && winTop < contentBottom[i] - rangeBottom ){
// 			$('.navmenu li')
// 			.removeClass('active')
// 			.eq(i).addClass('active');				
// 		}
// 	})
// };

// $(document).ready(function(){
// 	calculateScroll();
// 	$(window).scroll(function(event) {
// 		calculateScroll();
// 	});
// 	$('.navmenu ul li a').click(function() {  
// 		$('html, body').animate({scrollTop: $(this.hash).offset().top - 80}, 800);
// 		return false;
// 	});
// });		


// $(document).ready(function(){
// 	$(".pretty a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true, social_tools: ''});

// });

