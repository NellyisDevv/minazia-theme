/*! For license information please see theme-scripts-library-hide-menu.js.LICENSE.txt */
!function(e){var t={};function n(a){if(t[a])return t[a].exports;var r=t[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(a,r,function(t){return e[t]}.bind(null,r));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=91)}({91:function(e,t){var n;n=jQuery,document.addEventListener("DOMContentLoaded",(function(){var e=n("body").hasClass("et_hide_nav");n(window).on("resize",(function(){var t,a,r;e&&(t=n("body"),a=n(document).height(),r=n(window).height()+0+200,t.hasClass("et_vertical_nav")||(t.hasClass("et_hide_nav")||t.hasClass("et_hide_nav_disabled")&&t.hasClass("et_fixed_nav"))&&(a>r?(t.hasClass("et_hide_nav_disabled")&&(t.addClass("et_hide_nav"),t.removeClass("et_hide_nav_disabled")),n("#main-header").css("transform","translateY(-0px)"),n("#top-header").css("transform","translateY(-0px)")):(n("#main-header").css({transform:"translateY(0)",opacity:"1"}),n("#top-header").css({transform:"translateY(0)",opacity:"1"}),t.removeClass("et_hide_nav"),t.addClass("et_hide_nav_disabled")),window.et_fix_page_container_position()))}))}))}});