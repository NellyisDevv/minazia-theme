/*! For license information please see theme-scripts-library-menu.js.LICENSE.txt */
!function(e){var t={};function n(i){if(t[i])return t[i].exports;var a=t[i]={i:i,l:!1,exports:{}};return e[i].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(i,a,function(t){return e[t]}.bind(null,a));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=96)}({96:function(e,t){!function(e){var t={},n=e(window),i=e("#main-content .container:first-child");function a(){return t.et_get_first_section?t.et_get_first_section:t.et_get_first_section=e(".et-l:not(.et-l--footer) .et_pb_section:visible").first()}document.addEventListener("DOMContentLoaded",(function(){var t,o=e("body").hasClass("et_fixed_nav")||e("body").hasClass("et_vertical_fixed"),r=e("body").hasClass("et_hide_nav"),s=e("body").hasClass("et_header_style_left"),d=e("body").hasClass("et_vertical_fixed"),l=e("body").hasClass("rtl"),_=e("#top-header"),p=e("#main-header"),h=e("#page-container"),c=!1,f=0,u=e("#et-top-navigation"),g=e("#logo"),v=e("ul.nav, ul.menu"),m=e(".container"),w=e("#et_top_search"),b=e("body").hasClass("et_header_style_split");function y(){var n=_.length&&_.is(":visible")?parseInt(_.innerHeight()):0,i=e("#wpadminbar").length?parseInt(e("#wpadminbar").innerHeight()):0,a=e(".et_header_style_slide .et_slide_in_menu_container"),o=e("body").hasClass("rtl");if(f=parseInt(e("#main-header").length?e("#main-header").innerHeight():0)+n,t=(f<=90?f-29:f-56)+i,a.length&&!e("body").hasClass("et_pb_slide_menu_active")&&(o?a.css({left:"-"+parseInt(a.innerWidth())+"px",display:"none"}):a.css({right:"-"+parseInt(a.innerWidth())+"px",display:"none"}),e("body").hasClass("et_boxed_layout")))if(o){var r=h.css("margin-right");p.css({right:r})}else{r=h.css("margin-left");p.css({left:r})}}if(window.et_pb_init_nav_menu(v),et_duplicate_menu(e("#et-top-navigation ul.nav"),e("#et-top-navigation .mobile_nav"),"mobile_menu","et_mobile_menu"),et_duplicate_menu("",e(".et_pb_fullscreen_nav_container"),"mobile_menu_slide","et_mobile_menu","no_click_event"),e("ul.et_disable_top_tier").length){var x=e("ul.et_disable_top_tier > li > ul").prev("a");x.attr("href","#"),x.on("click",(function(e){e.preventDefault()}));var C=e("ul#mobile_menu > li > ul").prev("a");C.attr("href","#"),C.on("click",(function(e){e.preventDefault()}))}function I(t){setTimeout((function(){var t=0,n=e("body"),i=e("#wpadminbar"),a=e("#top-header");i.length&&!Number.isNaN(i.innerHeight())&&(t+=parseFloat(i.innerHeight()));a.length&&a.is(":visible")&&(t+=a.innerHeight());var o=n.hasClass("et_fixed_nav"),r=!o&&n.hasClass("et_transparent_nav")&&n.hasClass("et_secondary_nav_enabled");window.et_is_vertical_nav||!o&&!r||e("#main-header").css("top",t+"px")}),t)}e("#et-secondary-nav").length&&e("#et-top-navigation #mobile_menu").append(e("#et-secondary-nav").clone().html()),window.et_change_primary_nav_position=I,window.et_fix_page_container_position=function(){var t,o=parseInt(n.width()),s=_.length&&_.is(":visible")?parseInt(_.innerHeight()):0,d=0,l=p.clone().addClass("et-disabled-animations main-header-clone").css({opacity:"0px",position:"fixed",top:"auto",right:"0px",bottom:"0px",left:"0px"}).appendTo(e("body"));if(e("body").hasClass("et-bfb")||e('*[data-fix-page-container="on"]').each((function(){var t=e(this),n=t.data();n&&n.fix_page_container_style&&t.css(n.fix_page_container_style)})),o>980&&(!p.attr("data-height-loaded")||e("body").is(".et-fb"))){var c=0;p.hasClass("et-fixed-header")?(l.removeClass("et-fixed-header"),c=l.height(),l.addClass("et-fixed-header")):c=p.height(),p.attr({"data-height-onload":parseInt(c),"data-height-loaded":!0})}if(o<=980?(t=parseInt(p.length?p.innerHeight():0)+s-(e("body").hasClass("et-fb")?0:1),window.et_is_transparent_nav&&!a().length&&(t+=58)):(t=parseInt(p.attr("data-height-onload"))+s,window.et_is_transparent_nav&&!window.et_is_vertical_nav&&i.length&&(t+=58),d=l.height()),r){var f=parseInt(u.data("height"))-parseInt(u.data("fixed-height"));d=parseInt(p.data("height-onload"))-f}p.attr({"data-fixed-height-onload":d});var g=e(".et_fixed_nav.et_transparent_nav.et-db.et_full_width_page #left-area > .woocommerce-notices-wrapper");if(g.length>0&&"yes"!==g.attr("data-position-set")){var v=d;0===v&&p.attr("data-height-onload")&&(v=p.attr("data-height-onload")),g.css("marginTop",parseFloat(v)+"px"),g.animate({opacity:"1"}),g.attr("data-position-set","yes")}h.css("paddingTop",t+"px"),l.remove(),I(0),e(document).trigger("et-pb-header-height-calculated")},m.data("previous-width",parseInt(m.width()));var T,H,j,E,O,D,S,F,M,P,k,L,N,z=(T=function(){et_fix_page_container_position(),"function"==typeof et_fix_transparent_nav_padding&&et_fix_transparent_nav_padding(),"function"==typeof et_fix_fullscreen_section&&et_fix_fullscreen_section()},H=200,M=Date.now||(new Date).getTime(),P=function e(){var t=M-S;t<H&&t>=0?E=setTimeout(e,H-t):(E=null,j||(F=T.apply(D,O),E||(D=O=null)))},function(){D=this,O=arguments,S=M;var e=j&&!E;return E||(E=setTimeout(P,H)),e&&(F=T.apply(D,O),D=O=null),F});if(e(window).on("resize",(function(){var t=parseInt(n.width()),i=m.length>0,a=i&&parseInt(m.data("previous-width"))||0,r=m.css("width"),s=void 0!==r?"%"!==r.substr(-1,1):"",d=i?s?parseInt(m.width()):parseInt((parseInt(m.width())/100).toFixed(0))*t:0,l=m.length&&a!==d,_=e(".et_slide_in_menu_container"),p=e("#wpadminbar");e("body").hasClass("rtl");if(o&&l&&(z(),m.data("previous-width",d)),p.length&&o&&t>=740&&t<=782&&(y(),I(0)),w.length&&window.et_set_search_form_css(),_.length&&e("body").hasClass("et_header_style_fullscreen")){var h=parseInt(_.find(".et_slide_menu_top").innerHeight());_.css({"padding-top":h+20+"px"})}})),g.length){var W=g.is("img")?g.attr("src"):g.find("img").attr("src");k=W,L=function(){var t,n,i,a="svg"===(g.is("img")?g.attr("src"):g.find("img").attr("src")).substr(-3,3);e("body").append(e("<div />",{id:"et-define-logo-wrap",style:"position: fixed; bottom: 0; opacity: 0;"})),t=e("#et-define-logo-wrap"),a&&t.addClass("svg-logo"),t.html(g.clone().css({display:"block"}).removeAttr("id")),n=t.find("img").width(),i=t.find("img").height(),g.attr({"data-actual-width":n,"data-actual-height":i}),t.remove(),b&&window.et_fix_logo_transition(!0)},(N=new Image).onLoad=L,N.onload=L,N.src=k}window.addEventListener("load",(function(){if(o&&y(),u.length&&setTimeout((function(){et_fix_page_container_position(),"function"==typeof et_fix_transparent_nav_padding&&et_fix_transparent_nav_padding()}),0),window.et_is_minified_js&&window.et_is_transparent_nav&&!window.et_is_vertical_nav&&e(window).trigger("resize"),s&&!window.et_is_vertical_nav){var g=parseInt(e("#logo").width());l?u.css("padding-right",g+30+"px"):u.css("padding-left",g+30+"px")}var v;if(e.fn.waypoint&&(d&&(v=e("#main-content")).waypoint({handler:function(t){b&&window.et_fix_logo_transition(),"down"===t?e("#main-header").addClass("et-fixed-header"):e("#main-header").removeClass("et-fixed-header")}}),o)){var m=(a().length>0?a().offset().top:0)<=(e("#wpadminbar").length?e("#wpadminbar").height():0);m&&window.et_is_transparent_nav&&!window.et_is_vertical_nav&&a().length?(v=a().is(".et_pb_fullwidth_section")?a().children(".et_pb_module:visible").first():a().find(".et_pb_row:visible").first()).length||(v=e(".et-l .et_pb_module:visible").first()):v=m&&window.et_is_transparent_nav&&!window.et_is_vertical_nav&&i.length?e("#content-area"):e("#main-content");var x=!0;setTimeout((function(){x=!1}),0),v.waypoint({offset:function(){if(c&&(setTimeout((function(){y()}),200),c=!1),r)return t-f-200;var e=v.offset();return e.top<t&&(t=0-(t-e.top)),t},handler:function(t){if(b&&window.et_fix_logo_transition(),"down"===t){if(x&&0===n.scrollTop())return;if(p.addClass("et-fixed-header"),h.addClass("et-animated-content"),_.addClass("et-fixed-header"),!r&&!window.et_is_transparent_nav&&!e(".mobile_menu_bar_toggle").is(":visible")){var i,a,o,s=_.length?parseInt(_.height()):0;i=p.clone().addClass("et-fixed-header, et_header_clone").css({transition:"none",display:"none"}),a=parseInt(i.prependTo("body").height()),window.et_is_vertical_nav||(o=parseInt(h.css("padding-top"))-a-s+1,h.css("margin-top",-o+"px")),e(".et_header_clone").remove()}}else o=1,p.removeClass("et-fixed-header"),_.removeClass("et-fixed-header"),h.css("margin-top",-o+"px");window.dispatchEvent(new CustomEvent("ETDiviFixedHeaderTransitionStart",{detail:{marginTop:-o}})),setTimeout((function(){w.length&&window.et_set_search_form_css(),window.dispatchEvent(new CustomEvent("ETDiviFixedHeaderTransitionEnd",{detail:{marginTop:-o}}))}),400)}})}}))}))}(jQuery)}});