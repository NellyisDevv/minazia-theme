/*! For license information please see theme-scripts-library-side-nav.js.LICENSE.txt */
!function(e){var t={};function n(i){if(t[i])return t[i].exports;var o=t[i]={i:i,l:!1,exports:{}};return e[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(i,o,function(t){return e[t]}.bind(null,o));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=97)}({97:function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var i,o;i=jQuery,o="object"===n(window.ET_Builder),window.et_calculating_scroll_position=!1,window.et_side_nav_links_initialized=!1,document.addEventListener("DOMContentLoaded",(function(){var e=function(){var e,t=i(".et-l--post"),n=i(".et-l--body .et_pb_section:visible").not(".et-l--post .et_pb_section");return e=o?t.find(".et-fb-post-content > .et_pb_section"):t.find(".et_builder_inner_content > .et_pb_section:visible"),0===n.length||e.length>1?e:n};window.et_pb_window_side_nav_scroll_init=function(){if(!0!==window.et_calculating_scroll_position&&!1!==window.et_side_nav_links_initialized){var t=e();window.et_calculating_scroll_position=!0;var n,o=i(".et-l--header").length||i(".et-l--body").length||!i("#main-header").length?0:-90,a=i("body").hasClass("et_fixed_nav")?20:o,_=i("#top-header").length>0?parseInt(i("#top-header").height()):0,r=i("#main-header").length>0?parseInt(i("#main-header").height()):0;i("#wpadminbar").length>0&&parseInt(i(window).width())>600&&(a+=parseInt(i("#wpadminbar").outerHeight())),n=window.et_is_vertical_nav?_+a+60:_+r+a;for(var l=parseInt(i(window).height()),s=parseInt(i(window).scrollTop()),d=l+s===parseInt(i(document).height()),c=i(".side_nav_item a").length-1,p=0;p<=c;p++){var u=t.eq(p),f=void 0===u.offset(),v=i(".side_nav_item a.active").parent().index(),b=null,w=!1===f?u.offset().top-n:0;f?b=0:d?b=c:s>=w&&(b=p),null!==b&&b!==v&&(i(".side_nav_item a").removeClass("active"),i("a#side_nav_item_id_"+b).addClass("active"))}window.et_calculating_scroll_position=!1}},window.et_pb_side_nav_page_init=function(t){if(i(".et_pb_side_nav_page").length){var n=e(),o=n.length,a=parseInt((20*o+40)/2);window.et_side_nav_links_initialized=!1,window.et_calculating_scroll_position=!1,o>1&&i(".et_pb_side_nav_page").length&&(t?i(".et_pb_side_nav").empty():i("#main-content").append('<ul class="et_pb_side_nav"></ul>'),n.each((function(e,t){var n=0===e?"active":"";i(".et_pb_side_nav").append('<li class="side_nav_item"><a href="#" id="side_nav_item_id_'+e+'" class= "'+n+'">'+e+"</a></li>"),o-1===e&&(window.et_side_nav_links_initialized=!0)})),i("ul.et_pb_side_nav").css("marginTop","-"+a+"px"),i(".et_pb_side_nav").addClass("et-visible"),i(".et_pb_side_nav a").on("click",(function(){var e=parseInt(i(this).text()),t=n.eq(e),o="0"==i(this).text()&&!i(".et-l--body").length;return window.et_pb_smooth_scroll(t,o,800),!i("#main-header").hasClass("et-fixed-header")&&i("body").hasClass("et_fixed_nav")&&parseInt(i(window).width())>980&&setTimeout((function(){window.et_pb_smooth_scroll(t,o,200)}),500),!1})),i(window).on("scroll",et_pb_window_side_nav_scroll_init))}},i(window).on("resize",(function(){et_pb_side_nav_page_init(!0)})),et_pb_side_nav_page_init()}))}});