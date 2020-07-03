/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/common.js":
/*!***************************************!*\
  !*** ./resources/assets/js/common.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  // SANDWICH ANIMATION
  $(".toggle_menu").click(function () {
    $(".toggle_menu").toggleClass("active");
    $(".main_header .nav_block").toggleClass("active");
    $(".main_header .active_user").removeClass("active");
    $(".main_header .user_block .user_nav").slideUp(200);
  });
  $(".main_header .nav_block .main_nav li a").click(function () {
    $(".toggle_menu").removeClass("active");
    $(".main_header .nav_block").removeClass("active");
    $(".main_header .active_user").removeClass("active");
    $(".main_header .user_block .user_nav").slideUp(200);
  }); // USER DROPDOWN

  $(".main_header .user_block .user_btn").click(function () {
    $(".main_header .active_user").toggleClass("active");
    $(".main_header .user_block .user_nav").slideToggle(200);
    $(".toggle_menu").removeClass("active");
    $(".main_header .nav_block").removeClass("active");
  }); // SCROLL TO ID

  $(".reference_section .download_block .back[href*='#']").mPageScroll2id({
    scrollSpeed: 500,
    offset: 0
  }); // FANCYBOX
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
  }); // CUSTOM SCROLLBAR

  $('.spravka_modal .table_block .wrap, .messages_modal .messages_block .wrap').mCustomScrollbar({
    autoHideScrollbar: true,
    theme: "dark"
  }); // SPOILER

  $(".spoiler_item .spoiler").click(function () {
    $(this).next().collapse('toggle');
    $(this).parent().toggleClass("active");
  }); // FIXED BUTTONS

  var navbar = $('.download_block .wrap'); // navigation block

  var wrapper = $('.reference_section .wrapper');
  $(window).scroll(function () {
    if ($('.reference_section .wrapper .download_block')[0]) {
      var nsc = $(document).scrollTop();
      var bp1 = wrapper.offset().top;
      var bp2 = bp1 + wrapper.outerHeight();

      if (nsc > bp1) {
        navbar.css('position', 'fixed');
      } else {
        navbar.css('position', 'absolute');
      }

      if (nsc > bp2) {
        navbar.css('top', bp2 - nsc);
      } else {
        navbar.css('top', '0');
      }
    }
  }); // SELECT STYLE

  (function ($) {
    $(function () {
      $('.mod_select').styler({});
    });
  })(jQuery); // CHECK ALL


  $(".check_all_block label input").click(function () {
    $('.user_items .checkbox_item .checkbox_label input').prop('checked', this.checked);
  });
});

/***/ }),

/***/ 2:
/*!*********************************************!*\
  !*** multi ./resources/assets/js/common.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\OSPanel\domains\datame\resources\assets\js\common.js */"./resources/assets/js/common.js");


/***/ })

/******/ });