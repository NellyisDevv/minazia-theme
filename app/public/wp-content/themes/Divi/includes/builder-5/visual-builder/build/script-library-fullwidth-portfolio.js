!function(){var t;(t=jQuery)(window).on("et_pb_init_modules",(function(){window.et_pb_init_fullwidth_portfolio_modules=function(){const i=t(".et_pb_fullwidth_portfolio"),o="object"==typeof window.ET_Builder;function e(i){const o=i.parents(".et_pb_fullwidth_portfolio"),e=o.find(".et_pb_portfolio_items"),s=(e.find(".et_pb_portfolio_item"),e.find(".et_pb_carousel_group.active")),a=700,p=e.data("items"),d=e.data("portfolio-columns"),r=s.innerWidth()/d,_=100/d+"%";if(void 0!==p&&!o.data("carouseling"))if(o.data("carouseling",!0),s.children().each((function(){t(this).css({width:`${r+1}px`,"max-width":`${r}px`,position:"absolute",left:r*(t(this).data("position")-1)+"px"})})),i.hasClass("et-pb-arrow-next")){let i;var l=1;let f=1;const m=u=(h=p.indexOf(s.children().first()[0]))+d,w=m+d;var c=s.innerWidth();i=t('<div class="et_pb_carousel_group next" style="display: none;left: 100%;position: absolute;top: 0;">').insertAfter(s),i.css({width:`${c}px`,"max-width":`${c}px`}).show();for(let i=0,o=0;o>=h&&o<u&&(t(p[i]).addClass(`changing_position current_position current_position_${l}`),t(p[i]).data("current_position",l),l++),o>=m&&o<w&&(t(p[i]).data("next_position",f),t(p[i]).addClass(`changing_position next_position next_position_${f}`),t(p[i]).hasClass("current_position")?(t(p[i]).clone(!0).appendTo(s).hide().addClass("delayed_container_append_dup").attr("id",`${t(p[i]).attr("id")}-dup`),t(p[i]).addClass("delayed_container_append")):t(p[i]).addClass("container_append"),f++),!(f>d);i++,o++)i>=p.length-1&&(i=-1);const v=e.find(".container_append, .delayed_container_append_dup").sort(((i,o)=>{const e=parseInt(t(i).data("next_position")),n=parseInt(t(o).data("next_position"));return e<n?-1:e>n?1:0}));t(v).show().appendTo(i),i.children().each((function(){t(this).css({width:`${r}px`,"max-width":`${r}px`,position:"absolute",left:r*(t(this).data("next_position")-1)+"px"})})),s.animate({left:"-100%"},{duration:a,complete(){e.find(".delayed_container_append").each((function(){t(this).css({width:`${r}px`,"max-width":`${r}px`,position:"absolute",left:r*(t(this).data("next_position")-1)+"px"}),t(this).appendTo(i)})),s.removeClass("active"),s.children().each((function(){const i=t(this).data("position");l=t(this).data("current_position"),t(this).removeClass(`position_${i} changing_position current_position current_position_${l}`),t(this).data("position",""),t(this).data("current_position",""),t(this).hide(),t(this).css({position:"",width:"","max-width":"",left:""}),t(this).appendTo(e)})),s.remove(),n(o)}}),i.addClass("active").css({position:"absolute",top:"0px",left:"100%"}),i.animate({left:"0%"},{duration:a,complete(){setTimeout((()=>{i.removeClass("next").addClass("active").css({position:"",width:"","max-width":"",top:"",left:""}),i.find(".delayed_container_append_dup").remove(),i.find(".changing_position").each((function(i){const o=t(this).data("position");l=t(this).data("current_position"),f=t(this).data("next_position"),t(this).removeClass(`container_append delayed_container_append position_${o} changing_position current_position current_position_${l} next_position next_position_${f}`),t(this).data("current_position",""),t(this).data("next_position",""),t(this).data("position",i+1)})),e.find(".et_pb_portfolio_item").removeClass("first_in_row last_in_row"),et_pb_set_responsive_grid(e,".et_pb_portfolio_item:visible"),i.children().css({position:"",width:_,"max-width":_,left:""}),o.data("carouseling",!1)}),100)}})}else{let i;l=d;let n=d;const f=d-1;var h,u;const m=(u=(h=p.indexOf(s.children().last()[0]))-f)-1,w=m-f;c=s.innerWidth(),i=t('<div class="et_pb_carousel_group prev" style="display: none;left: 100%;position: absolute;top: 0;">').insertBefore(s),i.css({left:`-${c}px`,width:`${c}px`,"max-width":`${c}px`}).show();for(let i=p.length-1,o=p.length-1;o<=h&&o>=u&&(t(p[i]).addClass(`changing_position current_position current_position_${l}`),t(p[i]).data("current_position",l),l--),o<=m&&o>=w&&(t(p[i]).data("prev_position",n),t(p[i]).addClass(`changing_position prev_position prev_position_${n}`),t(p[i]).hasClass("current_position")?(t(p[i]).clone(!0).appendTo(s).addClass("delayed_container_append_dup").attr("id",`${t(p[i]).attr("id")}-dup`),t(p[i]).addClass("delayed_container_append")):t(p[i]).addClass("container_append"),n--),!(n<=0);i--,o--)0==i&&(i=p.length);const v=e.find(".container_append, .delayed_container_append_dup").sort(((i,o)=>{const e=parseInt(t(i).data("prev_position")),n=parseInt(t(o).data("prev_position"));return e<n?-1:e>n?1:0}));t(v).show().appendTo(i),i.children().each((function(){t(this).css({width:`${r}px`,"max-width":`${r}px`,position:"absolute",left:r*(t(this).data("prev_position")-1)+"px"})})),s.animate({left:"100%"},{duration:a,complete(){e.find(".delayed_container_append").reverse().each((function(){t(this).css({width:`${r}px`,"max-width":`${r}px`,position:"absolute",left:r*(t(this).data("prev_position")-1)+"px"}),t(this).prependTo(i)})),s.removeClass("active"),s.children().each((function(){const i=t(this).data("position");l=t(this).data("current_position"),t(this).removeClass(`position_${i} changing_position current_position current_position_${l}`),t(this).data("position",""),t(this).data("current_position",""),t(this).hide(),t(this).css({position:"",width:"","max-width":"",left:""}),t(this).appendTo(e)})),s.remove()}}),i.addClass("active").css({position:"absolute",top:"0px",left:"-100%"}),i.animate({left:"0%"},{duration:a,complete(){setTimeout((()=>{i.removeClass("prev").addClass("active").css({position:"",width:"","max-width":"",top:"",left:""}),i.find(".delayed_container_append_dup").remove(),i.find(".changing_position").each((function(i){let o=t(this).data("position");l=t(this).data("current_position"),n=t(this).data("prev_position"),t(this).removeClass(`container_append delayed_container_append position_${o} changing_position current_position current_position_${l} prev_position prev_position_${n}`),t(this).data("current_position",""),t(this).data("prev_position",""),o=i+1,t(this).data("position",o),t(this).addClass(`position_${o}`)})),e.find(".et_pb_portfolio_item").removeClass("first_in_row last_in_row"),et_pb_set_responsive_grid(e,".et_pb_portfolio_item:visible"),i.children().css({position:"",width:_,"max-width":_,left:""}),o.data("carouseling",!1)}),100)}})}}function n(t){if("on"===t.data("auto-rotate")&&t.find(".et_pb_portfolio_item").length>t.find(".et_pb_carousel_group .et_pb_portfolio_item").length&&!t.hasClass("et_carousel_hovered")){const i=setTimeout((()=>{e(t.find(".et-pb-arrow-next"))}),t.data("auto-rotate-speed"));t.data("et_carousel_timer",i)}}window.set_fullwidth_portfolio_columns=function(i,e){let n;const s=i.find(".et_pb_portfolio_items"),a=s.width(),p=s.find(".et_pb_portfolio_item");if(p.length,void 0===p)return;n=a>=1600?5:a>=1024?4:a>=768?3:a>=480?2:1;const d=a/n*.75;if(e&&s.css({height:`${d}px`}),p.css({height:`${d}px`}),!o&&n===s.data("portfolio-columns"))return;if(i.data("columns_setting_up"))return;i.data("columns_setting_up",!0);const r=100/n+"%";if(p.css({width:r,"max-width":r}),s.removeClass(`columns-${s.data("portfolio-columns")}`),s.addClass(`columns-${n}`),s.data("portfolio-columns",n),!e)return i.data("columns_setting_up",!1);s.find(".et_pb_carousel_group").length&&(p.appendTo(s),s.find(".et_pb_carousel_group").remove());const _=s.data("items"),l=t('<div class="et_pb_carousel_group active">').appendTo(s);if(void 0!==_){p.data("position",""),_.length<=n?s.find(".et-pb-slider-arrows").hide():s.find(".et-pb-slider-arrows").show();for(let i=1,o=0;o<_.length;o++,i++)o<n?(t(_[o]).show(),t(_[o]).appendTo(l),t(_[o]).data("position",i),t(_[o]).addClass(`position_${i}`)):(i=t(_[o]).data("position"),t(_[o]).removeClass(`position_${i}`),t(_[o]).data("position",""),t(_[o]).hide());i.data("columns_setting_up",!1)}},(i.length||o)&&(window.et_fullwidth_portfolio_init=function(i,o){const s=i.find(".et_pb_portfolio_items");s.data("items",s.find(".et_pb_portfolio_item").toArray()),i.data("columns_setting_up",!1),i.hasClass("et_pb_fullwidth_portfolio_carousel")?(s.prepend(`<div class="et-pb-slider-arrows"><a class="et-pb-arrow-prev" href="#"><span>${et_pb_custom.previous}</span></a><a class="et-pb-arrow-next" href="#"><span>${et_pb_custom.next}</span></a></div>`),set_fullwidth_portfolio_columns(i,!0),n(i),i.on("swiperight",(function(){t(this).find(".et-pb-arrow-prev").trigger("click")})),i.on("swipeleft",(function(){t(this).find(".et-pb-arrow-next").trigger("click")})),i.on("mouseenter",(function(){t(this).addClass("et_carousel_hovered"),void 0!==t(this).data("et_carousel_timer")&&clearInterval(t(this).data("et_carousel_timer"))})).on("mouseleave",(function(){t(this).removeClass("et_carousel_hovered"),n(t(this))})),i.data("carouseling",!1),i.on("click",".et-pb-slider-arrows a",(function(i){return e(t(this)),i.preventDefault(),!1}))):set_fullwidth_portfolio_columns(i,!1),"function"==typeof o&&o()},i.each((function(){et_fullwidth_portfolio_init(t(this))})))},window.et_pb_init_fullwidth_portfolio_modules()})),((window.divi=window.divi||{}).scriptLibrary=window.divi.scriptLibrary||{}).scriptLibraryFullwidthPortfolio={}}();