$(document).ready(function() {

	// SANDWICH ANIMATION
	$(".toggle_menu").click(function() {
		$(".toggle_menu").toggleClass("active");
		$(".main_header .nav_block").toggleClass("active");
		$(".main_header .active_user").removeClass("active");
		$(".main_header .user_block .user_nav").slideUp(200);
	});

	$(".main_header .nav_block .main_nav li a").click(function() {
		$(".toggle_menu").removeClass("active");
		$(".main_header .nav_block").removeClass("active");
		$(".main_header .active_user").removeClass("active");
		$(".main_header .user_block .user_nav").slideUp(200);
	});


	// USER DROPDOWN
	$(".main_header .user_block .user_btn").click(function() {
		$(".main_header .active_user").toggleClass("active");
		$(".main_header .user_block .user_nav").slideToggle(200);
		$(".toggle_menu").removeClass("active");
		$(".main_header .nav_block").removeClass("active");
	});


	// SCROLL TO ID
	$(".reference_section .download_block .back[href*='#']").mPageScroll2id({
		scrollSpeed: 500,
		offset: 0
	});


	// FANCYBOX
	$().fancybox({
		selector: '.fancybox',
		loop: true,
		infobar: true,
		animationEffect: "zoom"
	});


	// INPUT MASK
	$(function(){
		$("input[name='tel']").mask("+7 (999) 999 99 99");
	});
	$(function(){
		$("input[name='birthday'], input[name='date']").mask("99.99.9999");
	});
	$(function(){
		$("input[name='pasport']").mask("9999-999999");
	});


	// SERVICES SLIDER
	var swiper = new Swiper('.services_slider', {
		slidesPerView: 4,
		spaceBetween: 30,
		prevButton: '.prev_service',
		nextButton: '.next_service',
		loop: true,
        breakpoints: {
			991: {
				slidesPerView: 3,
				spaceBetween: 30
			},
			767: {
				slidesPerView: 2,
				spaceBetween: 20
			},
			480: {
				slidesPerView: 1,
				spaceBetween: 20
			}
		}
	});


	// CUSTOM SCROLLBAR
	$('.spravka_modal .table_block .wrap, .messages_modal .messages_block .wrap').mCustomScrollbar({
		autoHideScrollbar:true,
		theme:"dark"
	});


	// SPOILER
	$(".spoiler_item .spoiler").click(function() {
		$(this).next().collapse('toggle');
		$(this).parent().toggleClass("active");
	});


	// FIXED BUTTONS
	var navbar =  $('.download_block .wrap');  // navigation block
	var wrapper = $('.reference_section .wrapper');       

	$(window).scroll(function(){
		if ($('.reference_section .wrapper .download_block')[0]) {
			var nsc = $(document).scrollTop();
			var bp1 = wrapper.offset().top;
			var bp2 = bp1 + wrapper.outerHeight();

			if (nsc>bp1) {  navbar.css('position','fixed'); }
			else { navbar.css('position','absolute'); }
			if (nsc>bp2) { navbar.css('top', bp2-nsc); }
			else { navbar.css('top', '0'); }
		}
	});


	// SELECT STYLE
	(function($) {
		$(function() {
			$('.mod_select').styler({
			});
		});
	})(jQuery);


	// CHECK ALL
	$(".check_all_block label input").click(function () {
		$('.user_items .checkbox_item .checkbox_label input').prop('checked', this.checked);
	});









	// MAIN FORM
	$('.main_form').each(function() {
		$(this).validate({
			rules:{
				name: {
					required: true,
				},
				family: {
					required: true,
				},
				login: {
					required: true,
					minlength: 5,
				},
				tel: {
					required: true,
					minlength: 6,
				},
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 5,
				},
				cfmpassword: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				},
				org: {
					required: true
				},
				inn: {
					required: true
				},
				ogrn: {
					required: true
				},
				director: {
					required: true
				},
				manager: {
					required: true
				}
			},
			messages:{
				name: {
					required: "Поле не заполнено"
				},
				family: {
					required: "Поле не заполнено"
				},
				login: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				},
				tel: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				},
				email: {
					required: "Поле не заполнено",
					email: "Введен некорректный email"
				},
				password: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				},
				cfmpassword: {
					required: "Поле не заполнено",
					minlength: "Мало символов",
					equalTo: "Пароль не совпадает"
				},
				org: {
					required: "Поле не заполнено"
				},
				inn: {
					required: "Поле не заполнено"
				},
				ogrn: {
					required: "Поле не заполнено"
				},
				director: {
					required: "Поле не заполнено"
				},
				manager: {
					required: "Поле не заполнено"
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: "php/submit.php",
					type: "POST",
					data: $(form).serialize(),
					success: function(response) {
						$(form).trigger('reset');
						$(".modal").modal("hide");
					}            
				});
			}
		});
	});


	// CHECK FORM
	$('.check_form').each(function() {
		$(this).validate({
			rules:{
				name: {
					required: true,
				},
				birthday: {
					required: true,
					minlength: 8
				},
				pasport: {
					required: true,
					minlength: 10
				},
				date: {
					required: true,
					minlength: 8
				}
			},
			messages:{
				name: {
					required: "Поле не заполнено"
				},
				birthday: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				},
				pasport: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				},
				date: {
					required: "Поле не заполнено",
					minlength: "Мало символов"
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: "php/submit.php",
					type: "POST",
					data: $(form).serialize(),
					success: function(response) {
						$(form).trigger('reset');
						$(".modal").modal("hide");
					}            
				});
			}
		});
	});



});


