/*! For license information please see theme-scripts-library-split-menu.js.LICENSE.txt */
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(r,i,function(t){return e[t]}.bind(null,i));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=92)}({92:function(e,t){var n,r;n=jQuery,r=n(window),document.addEventListener("DOMContentLoaded",(function(){var e=n("#et-top-navigation"),t=n("body").hasClass("et_is_customize_preview");function i(){var t=n("#main-header > .container > .logo_container"),i=n(".centered-inline-logo-wrap > .logo_container"),a=e.children("nav").children("ul").children("li").length,o=Math.round(a/2)-1,l=window.innerWidth||r.width();l>980&&t.length&&n("body").hasClass("et_header_style_split")&&(n('<li class="centered-inline-logo-wrap"></li>').insertAfter(e.find("nav > ul >li:nth("+o+")")),t.appendTo(e.find(".centered-inline-logo-wrap"))),l<=980&&i.length&&(i.prependTo("#main-header > .container"),n("#main-header .centered-inline-logo-wrap").remove())}(n(".et_header_style_split").length&&!window.et_is_vertical_nav||t)&&(i(),n(window).on("resize",(function(){i()})))})),window.et_fix_logo_transition=function(e){var t,r=n("body"),i=n("#logo"),a=parseInt(i.attr("data-actual-width")),o=parseInt(i.attr("data-actual-height")),l=parseInt(i.attr("data-height-percentage")),d=n("#et-top-navigation"),s=parseInt(d.attr("data-height")),c=parseInt(d.attr("data-fixed-height")),u=n("#main-header"),p=r.hasClass("et_header_style_split"),h=u.hasClass("et-fixed-header"),_=r.hasClass("et_hide_primary_logo"),f=r.hasClass("et_hide_fixed_logo"),g=h?s:c;e=void 0!==e&&e,p&&!window.et_is_vertical_nav&&(e&&(g=s),t=a*((g*(l/100)+22)/o),_&&(h||e)&&(t=0),!f||h||e||(t=0),n(".et_header_style_split .centered-inline-logo-wrap").css({width:t+"px"}))}}});