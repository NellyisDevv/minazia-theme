!function(){var a;(a=jQuery)(window).on("et_pb_init_modules",(function(){window.et_pb_init_gallery_modules=function(){var e="object"==typeof window.ET_Builder,t=a(".et_pb_gallery"),i=a(".et_post_gallery"),n=void 0!==window.ETBlockLayoutModulesScript&&a("body").hasClass("et-block-layout-preview");a((()=>{if(!n&&i.length>0){const e=a.magnificPopup.instance;a("body").on("swiperight",".mfp-container",(()=>{e.prev()})),a("body").on("swipeleft",".mfp-container",(()=>{e.next()})),i.each((function(){a(this).magnificPopup({delegate:".et_pb_gallery_image a",type:"image",removalDelay:500,gallery:{enabled:!0,navigateByImgClick:!0},mainClass:"mfp-fade",zoom:{enabled:window.et_pb_custom&&!window.et_pb_custom.is_builder_plugin_used,duration:500,opener:a=>a.find("img")},autoFocusLast:!1})})),i.find("a").off("click")}(t.length||e)&&(window.set_gallery_grid_items=function(e){const t=e.find(".et_pb_gallery_items"),i=t.find(".et_pb_gallery_item");var n=i.length;const s=parseInt(t.attr("data-per_page")),l=isNaN(s)||0===s?4:s,p=Math.ceil(n/l);window.et_pb_set_responsive_grid(t,".et_pb_gallery_item"),set_gallery_grid_pages(e,p),n=0;let r=1;i.data("page",""),i.each((function(e){n++;const t=a(this);0===parseInt(n%l)?(t.data("page",r),r++):t.data("page",r)})),i.filter((function(){return 1==a(this).data("page")})).show(),i.filter((function(){return 1!=a(this).data("page")})).hide()},window.set_gallery_grid_pages=function(a,e){const t=a.find(".et_pb_gallery_pagination");if(!t.length)return;if(t.html("<ul></ul>"),e<=1)return void t.hide();const i=t.children("ul");i.append(`<li class="prev" style="display:none;"><a href="#" data-page="prev" class="page-prev">${et_pb_custom.prev}</a></li>`);for(let a=1;a<=e;a++){const t=1===a?" active":"",n=a===e?" last-page":"",s=a>=5?' style="display:none;"':"";i.append(`<li${s} class="page page-${a}"><a href="#" data-page="${a}" class="page-${a}${t}${n}">${a}</a></li>`)}i.append(`<li class="next"><a href="#" data-page="next" class="page-next">${et_pb_custom.next}</a></li>`)},window.set_gallery_hash=function(a){if(!a.attr("id"))return;let e=[];e.push(a.attr("id")),a.find(".et_pb_gallery_pagination a.active").length?e.push(a.find(".et_pb_gallery_pagination a.active").data("page")):e.push(1),e=e.join(et_hash_module_param_seperator),setHash(e)},window.et_pb_gallery_init=function(e){e.hasClass("et_pb_gallery_grid")&&(e.show(),set_gallery_grid_items(e),e.on("et_hashchange",(t=>{const{params:i}=t;e=a(`#${t.target.id}`);const n=i[0];n&&(e.find(`.et_pb_gallery_pagination a.page-${n}`).hasClass("active")||e.find(`.et_pb_gallery_pagination a.page-${n}`).addClass("active").trigger("click"))})))},window.et_pb_gallery_pagination_nav=function(e){e.on("click",".et_pb_gallery_pagination a",(function(e){e.preventDefault();let t=a(this).data("page");const i=a(this).parents(".et_pb_gallery"),n=i.find(".et_pb_gallery_items"),s=n.find(".et_pb_gallery_item");if(i.data("paginating"))return;i.data("paginating",!0),a(this).hasClass("page-prev")?t=parseInt(a(this).parents("ul").find("a.active").data("page"))-1:a(this).hasClass("page-next")&&(t=parseInt(a(this).parents("ul").find("a.active").data("page"))+1),a(this).parents("ul").find("a").removeClass("active"),a(this).parents("ul").find(`a.page-${t}`).addClass("active");const l=a(this).parents("ul").find(`a.page-${t}`).parent().index(),p=a(this).parents("ul").find("li.page").length;a(this).parent().nextUntil(`.page-${l+3}`).show(),a(this).parent().prevUntil(".page-"+(l-3)).show(),a(this).parents("ul").find("li.page").each((function(e){a(this).hasClass("prev")||a(this).hasClass("next")||(e<l-3||e>l+1?a(this).hide():a(this).show(),(p-l<=2&&p-e<=5||l<=3&&e<=4)&&a(this).show())})),t>1?a(this).parents("ul").find("li.prev").show():a(this).parents("ul").find("li.prev").hide(),a(this).parents("ul").find("a.active").hasClass("last-page")?a(this).parents("ul").find("li.next").hide():a(this).parents("ul").find("li.next").show(),s.hide(),s.filter((function(e){return a(this).data("page")===t})).show(),i.data("paginating",!1),window.et_pb_set_responsive_grid(n,".et_pb_gallery_item"),setTimeout((()=>{set_gallery_hash(i)}),100)}))},et_pb_gallery_pagination_nav(t),e&&et_pb_gallery_pagination_nav(a("#et-fb-app"))),t.each((function(){const e=a(this);et_pb_gallery_init(e)})),t.data("paginating",!1)}))},window.et_pb_init_gallery_modules()})),((window.divi=window.divi||{}).scriptLibrary=window.divi.scriptLibrary||{}).scriptLibraryGallery={}}();