!function(){"use strict";var e={d:function(r,t){for(var i in t)e.o(t,i)&&!e.o(r,i)&&Object.defineProperty(r,i,{enumerable:!0,get:t[i]})},o:function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},r={};e.r(r),e.d(r,{maybeRegisterSalvattoreGrid:function(){return t},salvattoreInit:function(){return i}});const t=()=>{const e=document.querySelectorAll(".et_pb_blog_grid");0!==e.length&&e.forEach((e=>{const r=e.querySelectorAll(".et_pb_salvattore_content"),t=setInterval((()=>{r.forEach((e=>{const r=getComputedStyle(e,":before").content;"none"!==r&&clearInterval(t),e.querySelectorAll(".column").length||"none"!==r&&(e.querySelectorAll("div").length&&!e.querySelectorAll("div").item(0).classList.length?window?.divi?.scriptLibrary?.scriptLibrarySalvattore?.recreateColumns(e):window?.divi?.scriptLibrary?.scriptLibrarySalvattore?.registerGrid(e))}))}),100)}))},i=e=>{e.querySelectorAll(":scope >.column").length&&window?.divi?.scriptLibrary?.scriptLibrarySalvattore?.recreateColumns(e),e.querySelectorAll(":scope >.column").length||window?.divi?.scriptLibrary?.scriptLibrarySalvattore?.registerGrid(e)};"diviModuleBlogGridInit"in window||Object.defineProperty(window,"diviModuleBlogGridInit",{value:t,writable:!1}),"diviModuleBlogSalvattoreInit"in window||Object.defineProperty(window,"diviModuleBlogSalvattoreInit",{value:i,writable:!1}),((window.divi=window.divi||{}).moduleLibrary=window.divi.moduleLibrary||{}).moduleLibraryScriptBlog=r}();