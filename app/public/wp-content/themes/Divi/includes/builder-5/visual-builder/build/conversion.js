!function(){"use strict";var e={d:function(t,n){for(var o in n)e.o(n,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:n[o]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{ConvertedPresetAttrs:function(){return L},ImportedPresetAttrs:function(){return F},convertEmailServiceAccount:function(){return B},convertFontIcon:function(){return R},convertGlobalColor:function(){return E},convertIcon:function(){return z},convertImageAndIconWidth:function(){return G},convertImageIconWidth:function(){return H},convertInlineFont:function(){return D},convertPreset:function(){return A},convertShortcodeToGbFormat:function(){return M},convertSuccessRedirectQuery:function(){return J},convertTrueFalseToOnOff:function(){return U},enabled:function(){return o},encodeAttrs:function(){return P},filterLegacyPresets:function(){return T},getAttrMap:function(){return u},getModuleGlobalColors:function(){return p},isLegacyPostContent:function(){return I},maybeConvertContent:function(){return O},maybeConvertPresetsData:function(){return C},maybeImportLegacyPreset:function(){return W},maybeParseValue:function(){return l},normalizeAbSubjectId:function(){return N},parseShortcode:function(){return g},stickyStatus:function(){return s},valueSanitization:function(){return d}});var n=window.lodash;const o=(e,t,o,r={})=>{const i="responsive"===e?"_last_edited":`__${e}_enabled`,s=o[`${r?.[t]??t}${i}`];return(0,n.isString)(s)&&!(0,n.isUndefined)(s)&&s.startsWith("on")};var r=window.vendor.wp.hooks,i=window.divi.sanitize;const s=e=>{const t={active:!1,viewport:"desktop"},r="sticky_position";return o("responsive",r,e)?((0,n.has)(e,`${r}_phone`)&&"none"!==e[`${r}_phone`]&&""!==e[`${r}_phone`]&&(t.active=!0,t.viewport="phone"),(0,n.has)(e,`${r}_tablet`)&&"none"!==e[`${r}_tablet`]&&""!==e[`${r}_tablet`]&&(t.active=!0,t.viewport="tablet"),(0,n.has)(e,r)&&"none"!==e[r]&&""!==e[r]&&(t.active=!0,t.viewport="desktop")):(0,n.has)(e,r)&&"none"!==e[r]&&""!==e[r]&&(t.active=!0),t},a=["address_lat","address_lng","pin_address_lat","pin_address_lng","zoom_level"],c=["pin.desktop.value.lat","pin.desktop.value.lng","map.desktop.value.lat","map.desktop.value.lng","pin.desktop.value.zoom","map.desktop.value.zoom"],l=(e,t)=>a.includes(e)||c.includes(e)?Number(t):t,d=(e,t,o)=>{let r=e;if(["divi/map","divi/map-pin","divi/fullwidth-map"].includes(o)&&(0,n.isString)(r)&&(r=l(t,r)),"divi/section"===o&&["fullwidth","specialty"].includes(t)&&(r=t),["position_origin_a","position_origin_f","position_origin_r"].includes(t)&&(r=e.toString().replace("_"," ")),["background_position","background_pattern_repeat_origin","background_mask_position"].includes(t))switch(e){case"top_left":r="left top";break;case"top_center":r="center top";break;case"top_right":r="right top";break;case"center_left":r="left center";break;case"center_right":r="right center";break;case"bottom_left":r="left bottom";break;case"bottom_center":r="center bottom";break;case"bottom_right":r="right bottom"}"background_color_gradient_unit"===t&&(r=`100${e}`),t.startsWith("custom_css_")&&(r=e.toString().split("||").join("\n")),"divi/portfolio"===o&&"fullwidth"===t&&(r="off"===e?"grid":"fullwidth");return["bottom_divider_arrangement","bottom_divider_arrangement_phone","bottom_divider_arrangement_tablet","top_divider_arrangement","top_divider_arrangement_phone","top_divider_arrangement_tablet"].includes(t)&&(r="above_content"===e?"above":"below"),r=(e=>{const t=e.toString();return/%91|%93|%22|%92|%5c/.test(t)?t.replace(/%22/g,'"').replace(/%92/g,"\\").replace(/%91/g,"&#91;").replace(/%93/g,"&#93;").replace(/%5c/g,"\\"):e})(r),r},u=(e,t,a)=>{const c=e[t],l={},u=t.replace(/_tablet|_phone|__hover|__sticky/,"");let p="desktop",_="value";const f=(0,r.applyFilters)("divi.conversion.moduleLibrary.conversionMap",{})?.[a];if(t.endsWith("_tablet")||t.endsWith("_phone")){if(!o("responsive",u,e,f?.optionEnableMap))return{};p=t.endsWith("_tablet")?"tablet":"phone"}if(t.endsWith("__hover")){if(!o("hover",u,e,f?.optionEnableMap))return{};_="hover"}if(t.endsWith("__sticky")){const t=s(e);if(!t.active||!o("sticky",u,e,f?.optionEnableMap))return{};p=t.viewport,_="sticky"}let b=f?.attributeMap?.[u];if(["fb_built","sticky_enabled","hover_enabled","_dynamic_attributes"].includes(u))return{};if(((0,n.isUndefined)(b)||""===b)&&(b=!(0,n.isEmpty)(f?.attributeMap)&&Object.keys(f?.attributeMap).includes(u)?f?.attributeMap?.[u]:["admin_label","theme_builder_area","global_colors_info","on","locked","open"].includes(u)?`${(0,i.camelCase)(u)}.*`:["global_module","global_parent","nonconvertible","shortcodeName"].includes(u)?(0,i.camelCase)(u):["_builder_version","_module_preset"].includes(u)?(0,i.camelCase)(u.substring(1)):`unknownAttributes.${u}`),f?.conditionalAttributeConversionFunctionMap?.[u]){const t=f.conditionalAttributeConversionFunctionMap[u];"function"==typeof t&&(b=t(b,e))}const m=b.replace("*",`${p}.${_}`);if(f?.valueExpansionFunctionMap?.[u]){const t=f?.valueExpansionFunctionMap[u](c,{attrs:e,desktopName:u,moduleName:a,viewport:p,state:_});t instanceof Error||((0,n.isArray)(t)||(0,n.isObject)(t)?(0,n.forEach)(t,((e,t)=>{l[`${m}.${t}`]=e})):l[m]=t)}else(0,n.isString)(f?.attributeMap?.[u])?l[m]=d(c,u,a):l[m]=c;return l},p=e=>{const t=(0,n.get)(e,"global_colors_info","");if((0,n.isEmpty)(t))return{};try{const e=t.replace(/%91/g,"[").replace(/%93/g,"]").replace(/%22/g,'"');return JSON.parse(e)}catch(e){return{}}};var _=window.vendor.wp.shortcode,f=window.divi.constantLibrary;const b=(e,t,o)=>{const r=["_builder_version","_module_preset","nonconvertible"];return{...(0,n.keys)(e).filter((e=>r.includes(e))).reduce(((t,n)=>(t[n]=e[n],t)),{}),nonconvertible:o,shortcodeName:t}},m=e=>{const{nonconvertible:t,content:o,modules:r,shortcodeModules:i,shortcodeName:s}=e;if("yes"===t)return!1;if(["et_pb_section","et_pb_row","et_pb_row_inner","et_pb_column","et_pb_column_inner"].includes(s))return!0;const a=i[s].childrenName;if(!a)return!1;const c=(0,n.map)(a,(e=>r[e].d4Shortcode));return new RegExp(`^\\[(${c.join("|")})`).test((0,n.trim)(o))},g=(e,t,o)=>{const r=(0,n.filter)(t,(e=>!(0,n.isEmpty)(e.d4Shortcode))),i=(0,n.keyBy)(r,"name"),s=(0,n.keyBy)(r,"d4Shortcode");let a=(0,_.regexp)(f.shortcodeTagPattern).exec(e),c="",l="";const d=[];for(;null!==a;){const e=a&&a[2]&&"et_pb_unsupported"!==a[2]&&s[a[2]]?"no":"yes",r=a[2].toString(),u="yes"===e?b((0,n.get)((0,_.attrs)(a[3]),"named"),r,e):(0,n.get)((0,_.attrs)(a[3]),"named"),p=m({nonconvertible:e,content:a[5],modules:i,shortcodeModules:s,shortcodeName:r}),h=p?r:"";c=a.input,l="yes"===e?a[0]:p?g(a[5],t,h):a[5],d.push({name:r,attrs:u,parentName:o??"",content:l}),c=c.replace(a[0],""),a=(0,_.regexp)(f.shortcodeTagPattern).exec(c)}return d};var h=window.vendor.wp.blockSerializationDefaultParser,v=window.divi.data,y=window.divi.moduleUtils;const w=/@ET-DC@([^@]+)@/,k="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",S=/@ET-DC@([^@]+)@/g,$=e=>{try{return e.replace(S,((e,t)=>{const n=(e=>{const t=String(e),n=[6,4,2,0],o=k.slice(0,-1);let r,i="",s=0,a=0,c="";try{for(let e=0;(r=o.indexOf(t[e]))>-1;e++)s=s<<6|r,e%4&&(a=255&s>>n[e%4],a<128?(i+=c?decodeURI(c):"",i+=String.fromCharCode(a),c=""):c+=`%${`0${a.toString(16)}`.slice(-2)}`);return i+=c?decodeURI(c):"",i}catch(e){throw new Error("base64 malformed!")}})(t);if(t!==(e=>{const t=String(e),n=[2,4,6,8];let o=k,r="",i=0;for(let e=0,s=0;e<t.length||63&i||(o="=",s%4!=0);e++){const a=t.charCodeAt(e),c=a>127?encodeURI(t.charAt(e)).split("%").slice(1).map((e=>parseInt(e,16))):[a];for(let e=0;e<c.length;e++)i=i<<8|c[e],r+=o.charAt(63&i>>n[s%4]),s++,3==s%4&&(r+=Number.isNaN(c[e])?"=":o.charAt(63&i),s++,i=0)}return r})(n))return e;const o=JSON.parse(n);return r=o.content,i={...o.settings},`$variable(${JSON.stringify({type:"content",value:{name:r,settings:i}})})$`;var r,i}))}catch(t){return e}};var x=window.divi.globalData;const E=(e,t,n)=>{const o=Object.keys(n);for(let r=0;r<o.length;r++){const i=o[r];if(n[i].includes(t))if("object"==typeof e&&t.includes("_gradient_stops")){if((0,x.isGlobalColor)(`var(--${e.color})`)){const t=(0,v.select)("divi/global-data").getGlobalColorById(e.color);if(t&&e.color===i){e={position:e.position,color:"active"===t.status?`var(--${i})`:t.color};break}}}else if((0,x.isGlobalColor)(`var(--${i})`)){const t=(0,v.select)("divi/global-data").getGlobalColorById(i);if(t&&e===i){e="active"===t.status?`var(--${i})`:t.color;break}}}return e},P=(e,t,o=null)=>{const r={};null!==o&&(e.content=o),e=(e=>((0,n.has)(e,"ab_subject")&&(0,n.has)(e,"ab_subject_id")&&(e={...e,disabled_on:"1"===e.ab_subject_id.toString()?"off|off|off":"on|on|on"}),(0,n.omit)(e,["ab_subject","ab_subject_id","ab_goal"])))(e);const i=p(e);return(0,n.forEach)((0,n.keys)(e),(o=>{if(o.endsWith("_last_edited")||o.endsWith("__hover_enabled")||o.endsWith("__sticky_enabled"))return;if("divi/section"===t&&["specialty","fullwidth"].includes(o)&&"on"!==e[o])return;if("global_colors_info"===o)return;const s=(0,y.maybeRestorePresetModuleName)(t),a=u(e,o,s);(0,n.keys)(a).length>0&&(0,n.forEach)(a,((t,a)=>{var c;(0,n.size)(i)>0&&(t=E(t,o,i)),!(0,n.isObject)(t)&&(c=t?.toString()??"",w.test(c))&&(t=$(t.toString())),["divi/map","divi/map-pin","divi/fullwidth-map"].includes(s)&&(t=l(a,t)),(0,n.set)(r,a,t),"background_enable_color"===o&&"off"===t&&(0,n.set)(r,"module.decoration.background.desktop.value.color",""),"divi/social-media-follow-network"!==s||Object.prototype.hasOwnProperty.call(e,"background_color")||(0,n.set)(r,"module.decoration.background.desktop.value.color","")}))})),JSON.stringify(r)};let j;const M=(e,t=!0,o=null)=>{!0===t&&(j="");const r=(0,v.select)("divi/module-library").getModules(),i=(0,n.filter)(r,(e=>!(0,n.isEmpty)(e.d4Shortcode)));let s={};return(0,n.forEach)(i,(e=>{s={...s,[e.d4Shortcode]:e.name}})),e.forEach((e=>{const t="yes"===(0,n.get)(e,"attrs.nonconvertible")?"yes":"no",r="yes"===t||(0,n.isUndefined)(s[e.name])?"divi/shortcode-module":s[e.name],i="yes"!==t&&"string"==typeof e.content&&""!==e.content?e.content:null,a=o||e.attrs.global_module,c={...e.attrs,...o&&{global_parent:a}},l=P(c,r,i);"object"==typeof e.content?(j+=`\x3c!-- wp:${r} ${l} --\x3e`,M(e.content,!1,a),j+=`\x3c!-- /wp:${r} --\x3e`):j+="yes"===t?`\x3c!-- wp:${r} ${l} --\x3e${e.content}\x3c!-- /wp:${r} --\x3e`:`\x3c!-- wp:${r} ${l} --\x3e\x3c!-- /wp:${r} --\x3e`})),j},N=e=>{if(!e.includes("ab_subject_id"))return e;if(e.includes('ab_subject_id="1"'))return e;if(1===(e.match(/ab_subject_id/g)||[]).length){const t=e.match(/ab_subject_id="(\d+)"/);if(t&&"1"!==t[1])return e.replace(/ab_subject_id="\d+"/,'ab_subject_id="1"')}return e.replace(/ab_subject_id="\d+"/,'ab_subject_id="1"')},O=e=>{const t=N(e);let o=t;const r=(0,v.select)("divi/module-library").getModules();if(f.startsWithShortcodeRegExp.test(t))o=M(g(t,r));else if((0,n.includes)(t,"\x3c!-- wp:divi/layout --\x3e")){const e=(0,h.parse)(t);o="",(0,n.forEach)(e,(e=>{if("divi/layout"===e.blockName)o+=M(g((0,n.trim)(e.innerHTML),r));else if(null===e.blockName)o+=e.innerHTML;else{const t=e.blockName.replace("core/","wp:");o+=`\x3c!-- ${t} ${JSON.stringify(e.attrs)} --\x3e${e.innerHTML}\x3c!-- /${t} --\x3e`}}))}return o},A=(e,t,o)=>{const r=(0,v.select)("divi/settings").getSetting(["version"]),i=(0,v.select)("divi/module-library").getModules(),s=(0,n.filter)(i,(e=>!(0,n.isEmpty)(e.d4Shortcode)));let a={};(0,n.forEach)(s,(e=>{a={...a,[e.d4Shortcode]:e.name}})),a={...a,et_pb_section_fullwidth:"divi/fullwidth-section",et_pb_section_specialty:"divi/specialty-section"};const c=a[o];if(!c)return new Promise((e=>{e(null)}));const l=P(e?.settings,c),d=JSON.parse(l),u=(0,y.maybeRestorePresetModuleName)(c);return new Promise((n=>{(0,y.getPresetAttrsMapping)(u).then((({map:o})=>{const i={id:t,moduleName:c,name:e.name,attrs:d,styleAttrs:(0,y.getPresetAttrs)({moduleAttrs:d,presetType:["style"],map:o}),renderAttrs:(0,y.getPresetAttrs)({moduleAttrs:d,presetType:["html","script"],map:o}),version:e.version||r,type:"module"};n(i)})).catch((()=>{n(null)}))}))};const C=e=>{if(function(e){return(0,n.has)(e,"module")}(e))return new Promise((t=>{t(e)}));const t={module:{}};if((0,n.isEmpty)(e))return new Promise((e=>{e(t)}));const o=(0,v.select)("divi/module-library").getModules(),r=(0,n.filter)(o,(e=>!(0,n.isEmpty)(e.d4Shortcode)));let i={};const s={};(0,n.forEach)(r,(t=>{i={...i,[t.d4Shortcode]:t.name},e[t.d4Shortcode]?.default&&(s[t.name]=e[t.d4Shortcode]?.default)})),e?.et_pb_section_fullwidth?.default&&(s["divi/fullwidth-section"]=e?.et_pb_section_fullwidth?.default),e?.et_pb_section_specialty?.default&&(s["divi/specialty-section"]=e?.et_pb_section_fullwidth?.default);const a=(0,n.map)(e,((e,t)=>(0,n.map)(e?.presets,((e,n)=>A(e,n,t)))));return new Promise((e=>{Promise.all(a.flat()).then((o=>{o.forEach((e=>{(0,n.set)(t,`module.${e?.moduleName}.items.${e?.id}`,e),t?.module?.[e?.moduleName]?.default||(0,n.set)(t,`module.${e?.moduleName}.default`,s[e?.moduleName]??"")})),e(t)})).catch((()=>{e(t)}))}))},I=e=>!!f.startsWithShortcodeRegExp.test(e)||!!(0,n.includes)(e,"\x3c!-- wp:divi/layout --\x3e"),T=(e,t)=>{const n={module:{}};return Object.keys(t.module).forEach((o=>{const r=e?.module?.[o]?.items,i=t.module[o].items;if(e?.module?.[o]){const s={};Object.keys(i).forEach((e=>{r[e]||(s[e]=i[e])})),Object.keys(s).length>0&&(n.module[o]={...n.module[o],items:s,default:e?.module[o]?.default},t.module[o].default&&(n.module[o].default=t.module[o].default))}else Object.keys(i).length>0&&(n.module[o]={...t.module[o]})})),n},W=()=>{const e=(0,v.select)("divi/settings").getSetting(["post","content"]);if(!I(e))return;if((0,v.select)("divi/settings").getSetting(["globalPresets","isLegacyDataImported"]))return;const t=(0,v.select)("divi/settings").getSetting(["globalPresets","legacyData"])?.asMutable({deep:!0}),n=(0,v.select)("divi/settings").getSetting(["globalPresets","data"])?.asMutable({deep:!0});C(t).then((e=>{if(t){const t=(0,y.processPresets)(e),o=T(n,t.presets);(0,v.dispatch)("divi/global-data").importPresets(o,!0)}})).catch((e=>{console.log(e)}))};let F,L;F||(F={}),L||(L={});const R=e=>{const t=e.split("||"),o={},r=(0,n.get)(t,0,""),i=(0,n.get)(t,1,""),s=(0,n.get)(t,2,"");return r&&(o.unicode=r),i&&(o.type=i),s&&(o.weight=s),o},z=e=>{if(/^%*[0-9]*%*$/.test(e)){const t=["&#x22;","&#x33;","&#x37;","&#x3b;","&#x3f;","&#x43;","&#x47;","&#xe03a;","&#xe044;","&#xe048;","&#xe04c;"],o=/^%*([0-9]*)%*$/.exec(e)[1]||null;return{unicode:(0,n.isNull)(o)?"":t[parseInt(o)],type:"divi",weight:"400"}}return R(e)},D=e=>(0,n.isString)(e)?e.split(","):[],G=e=>({image:e,icon:e}),U=e=>"true"===e?"on":"off",J=e=>{let t=[];const n=["name","last_name","email","ip_address","css_id"],o=e?.split("|")??[];return o.length===n.length&&o.forEach(((e,o)=>{"on"===e&&(t=t.concat(n[o]))})),t},B=(e,{attrs:t,desktopName:n})=>{let o=t?.provider;o||(o="mailchimp");return[`${o}_list`,`${o}_account_name`].includes(n)?e:new Error(`The attribute name ${n} did match with selected provider ${o}.`)},H=(e,t)=>{for(let n=0;n<e.length;n++){const o=e[n];if("use_icon_on"===o.condition&&"on"===t?.use_icon)return o.path;if("use_icon_off"===o.condition&&"off"===(t?.use_icon??"off"))return o.path}return e[0].path};(window.divi=window.divi||{}).conversion=t}();