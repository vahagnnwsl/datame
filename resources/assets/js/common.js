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
    // $().fancybox({
    //     selector: '.fancybox',
    //     loop: true,
    //     infobar: true,
    //     animationEffect: "zoom"
    // });

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
        autoHideScrollbar: true,
        theme: "dark"
    });


    // SPOILER
    $(".spoiler_item .spoiler").click(function() {
        $(this).next().collapse('toggle');
        $(this).parent().toggleClass("active");
    });


    // FIXED BUTTONS
    var navbar = $('.download_block .wrap');  // navigation block
    var wrapper = $('.reference_section .wrapper');

    $(window).scroll(function() {
        if($('.reference_section .wrapper .download_block')[0]) {
            var nsc = $(document).scrollTop();
            var bp1 = wrapper.offset().top;
            var bp2 = bp1 + wrapper.outerHeight();

            if(nsc > bp1) {
                navbar.css('position', 'fixed');
            } else {
                navbar.css('position', 'absolute');
            }
            if(nsc > bp2) {
                navbar.css('top', bp2 - nsc);
            } else {
                navbar.css('top', '0');
            }
        }
    });


    // SELECT STYLE
    (function($) {
        $(function() {
            $('.mod_select').styler({});
        });
    })(jQuery);


    // CHECK ALL
    $(".check_all_block label input").click(function() {
        $('.user_items .checkbox_item .checkbox_label input').prop('checked', this.checked);
    });

});


