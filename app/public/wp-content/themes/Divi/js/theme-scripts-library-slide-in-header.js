/*! For license information please see theme-scripts-library-slide-in-header.js.LICENSE.txt */
!function(e){var t={};function n(s){if(t[s])return t[s].exports;var i=t[s]={i:s,l:!1,exports:{}};return e[s].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,s){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:s})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var s=Object.create(null);if(n.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(s,i,function(t){return e[t]}.bind(null,i));return s},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=89)}({89:function(e,t){var n;n=jQuery,document.addEventListener("DOMContentLoaded",(function(){var e=n("body").hasClass("et_fixed_nav")||n("body").hasClass("et_vertical_fixed"),t=n("#main-header"),s=n("#page-container");if(n(".et_slide_in_menu_container").length){var i=n(".et_slide_in_menu_container").find(".menu-item-has-children > a");i.length&&i.append('<span class="et_mobile_menu_arrow"></span>')}n(window).on("resize",(function(){var i,a,r=n(".et_slide_in_menu_container"),o=n("body").hasClass("rtl");r.length&&!n("body").hasClass("et_pb_slide_menu_active")&&(o?r.css({left:"-"+parseInt(r.innerWidth())+"px",right:"unset"}):r.css({right:"-"+parseInt(r.innerWidth())+"px"}),n("body").hasClass("et_boxed_layout")&&e&&(o?(i=s.css("margin-right"),t.css({right:i})):(i=s.css("margin-left"),t.css({left:i})))),r.length&&n("body").hasClass("et_pb_slide_menu_active")&&(n("body").hasClass("et_boxed_layout")?(i=parseFloat(s.css("margin-left")),s.css({left:"-"+(parseInt(r.innerWidth())-i)+"px"}),e&&(a=0>parseInt(r.innerWidth())-2*i?Math.abs(r.innerWidth()-2*i):"-"+(r.innerWidth()-2*i))<parseInt(r.innerWidth())&&t.css({left:a+"px"})):o?n("#page-container, .et_fixed_nav #main-header").css({right:"-"+parseInt(r.innerWidth())+"px"}):n("#page-container, .et_fixed_nav #main-header").css({left:"-"+parseInt(r.innerWidth())+"px"}))}))})),n("#main-header").on("click",".et_toggle_slide_menu",(function(){var e,t,s,i,a,r,o,l,d,_;t=n(".et_header_style_slide .et_slide_in_menu_container"),s=n(".et_header_style_slide #page-container, .et_header_style_slide.et_fixed_nav #main-header"),i=n(".et_header_style_slide #main-header"),a=t.hasClass("et_pb_slide_menu_opened"),r=void 0!==e?e:"auto",o=n("body").hasClass("et_boxed_layout"),l=o?parseFloat(n("#page-container").css("margin-left")):0,d=t.innerWidth(),_=n("body").hasClass("rtl"),"auto"!==r&&(a&&"open"===r||!a&&"close"===r)||(a?(_?(t.css({left:"-"+d+"px"}),s.css({right:"0px"})):(t.css({right:"-"+d+"px"}),s.css({left:"0px"})),o&&et_is_fixed_nav&&(_?i.css({right:l+"px"}):i.css({left:l+"px"})),setTimeout((function(){t.css({display:"none"})}),700)):(t.css({display:"block"}),setTimeout((function(){if(_?(t.css({left:"0px"}),s.css({right:"-"+(d-l)+"px"})):(t.css({right:"0px"}),s.css({left:"-"+(d-l)+"px"})),o&&et_is_fixed_nav){var e=0>d-2*l?Math.abs(d-2*l):"-"+(d-2*l);e<d&&(_?i.css({right:e+"px"}):i.css({left:e+"px"}))}}),50)),n("body").toggleClass("et_pb_slide_menu_active"),t.toggleClass("et_pb_slide_menu_opened"))}))}});