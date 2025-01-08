!function(){var e={53316:function(e){function t(e,t,n,r){var i,o=null==(i=r)||"number"==typeof i||"boolean"==typeof i?r:n(r),s=t.get(o);return void 0===s&&(s=e.call(this,r),t.set(o,s)),s}function n(e,t,n){var r=Array.prototype.slice.call(arguments,3),i=n(r),o=t.get(i);return void 0===o&&(o=e.apply(this,r),t.set(i,o)),o}function r(e,t,n,r,i){return n.bind(t,e,r,i)}function i(e,i){return r(e,this,1===e.length?t:n,i.cache.create(),i.serializer)}function o(){return JSON.stringify(arguments)}function s(){this.cache=Object.create(null)}s.prototype.has=function(e){return e in this.cache},s.prototype.get=function(e){return this.cache[e]},s.prototype.set=function(e,t){this.cache[e]=t};var l={create:function(){return new s}};e.exports=function(e,t){var n=t&&t.cache?t.cache:l,r=t&&t.serializer?t.serializer:o;return(t&&t.strategy?t.strategy:i)(e,{cache:n,serializer:r})},e.exports.strategies={variadic:function(e,t){return r(e,this,n,t.cache.create(),t.serializer)},monadic:function(e,n){return r(e,this,t,n.cache.create(),n.serializer)}}},33042:function(e,t,n){var r;!function(){"use strict";var i=function e(t){var n,r="function"==typeof Symbol&&Symbol.for&&Symbol.for("react.element"),i={use_static:!1};function o(e){var t=Object.getPrototypeOf(e);return t?Object.create(t):{}}function s(e,t,n){Object.defineProperty(e,t,{enumerable:!1,configurable:!1,writable:!1,value:n})}function l(e,t){s(e,t,(function(){throw new y("The "+t+" method cannot be invoked on an Immutable data structure.")}))}"object"!=typeof(n=t)||Array.isArray(n)||null===n||void 0!==t.use_static&&(i.use_static=Boolean(t.use_static));var a="__immutable_invariants_hold";function u(e){return"object"!=typeof e||(null===e||Boolean(Object.getOwnPropertyDescriptor(e,a)))}function c(e,t){return e===t||e!=e&&t!=t}function d(e){return!(null===e||"object"!=typeof e||Array.isArray(e)||e instanceof Date)}var p=["setPrototypeOf"],f=p.concat(["push","pop","sort","splice","shift","unshift","reverse"]),h=["keys"].concat(["map","filter","slice","concat","reduce","reduceRight"]),m=p.concat(["setDate","setFullYear","setHours","setMilliseconds","setMinutes","setMonth","setSeconds","setTime","setUTCDate","setUTCFullYear","setUTCHours","setUTCMilliseconds","setUTCMinutes","setUTCMonth","setUTCSeconds","setYear"]);function y(e){this.name="MyError",this.message=e,this.stack=(new Error).stack}function g(e,t){for(var n in s(e,a,!0),t)t.hasOwnProperty(n)&&l(e,t[n]);return Object.freeze(e),e}function v(e,t){var n=e[t];s(e,t,(function(){return L(n.apply(e,arguments))}))}function b(e,t,n){var r=n&&n.deep;if(e in this&&(r&&this[e]!==t&&d(t)&&d(this[e])&&(t=L.merge(this[e],t,{deep:!0,mode:"replace"})),c(this[e],t)))return this;var i=N.call(this);return i[e]=L(t),O(i)}y.prototype=new Error,y.prototype.constructor=Error;var w=L([]);function k(e,t,n){var r=e[0];if(1===e.length)return b.call(this,r,t,n);var i,o=e.slice(1),s=this[r];if("object"==typeof s&&null!==s)i=L.setIn(s,o,t);else{var l=o[0];i=""!==l&&isFinite(l)?k.call(w,o,t):D.call(J,o,t)}if(r in this&&s===i)return this;var a=N.call(this);return a[r]=i,O(a)}function O(e){for(var t in h){if(h.hasOwnProperty(t))v(e,h[t])}i.use_static||(s(e,"flatMap",M),s(e,"asObject",P),s(e,"asMutable",N),s(e,"set",b),s(e,"setIn",k),s(e,"update",R),s(e,"updateIn",z),s(e,"getIn",U));for(var n=0,r=e.length;n<r;n++)e[n]=L(e[n]);return g(e,f)}function A(){return new Date(this.getTime())}function M(e){if(0===arguments.length)return this;var t,n=[],r=this.length;for(t=0;t<r;t++){var i=e(this[t],t,this);Array.isArray(i)?n.push.apply(n,i):n.push(i)}return O(n)}function S(e){if(void 0===e&&0===arguments.length)return this;if("function"!=typeof e){var t=Array.isArray(e)?e.slice():Array.prototype.slice.call(arguments);t.forEach((function(e,t,n){"number"==typeof e&&(n[t]=e.toString())})),e=function(e,n){return-1!==t.indexOf(n)}}var n=o(this);for(var r in this)this.hasOwnProperty(r)&&!1===e(this[r],r)&&(n[r]=this[r]);return H(n)}function N(e){var t,n,r=[];if(e&&e.deep)for(t=0,n=this.length;t<n;t++)r.push(_(this[t]));else for(t=0,n=this.length;t<n;t++)r.push(this[t]);return r}function P(e){"function"!=typeof e&&(e=function(e){return e});var t,n={},r=this.length;for(t=0;t<r;t++){var i=e(this[t],t,this),o=i[0],s=i[1];n[o]=s}return H(n)}function _(e){return!e||"object"!=typeof e||!Object.getOwnPropertyDescriptor(e,a)||e instanceof Date?e:L.asMutable(e,{deep:!0})}function E(e,t){for(var n in e)Object.getOwnPropertyDescriptor(e,n)&&(t[n]=e[n]);return t}function j(e,t){if(0===arguments.length)return this;if(null===e||"object"!=typeof e)throw new TypeError("Immutable#merge can only be invoked with objects or arrays, not "+JSON.stringify(e));var n,r,i=Array.isArray(e),s=t&&t.deep,l=t&&t.mode||"merge",a=t&&t.merger;function u(e,r,i){var l,u=L(r[i]),p=a&&a(e[i],u,t),f=e[i];void 0===n&&void 0===p&&e.hasOwnProperty(i)&&c(u,f)||(c(f,l=void 0!==p?p:s&&d(f)&&d(u)?L.merge(f,u,t):u)&&e.hasOwnProperty(i)||(void 0===n&&(n=E(e,o(e))),n[i]=l))}function p(e,t){for(var r in e)t.hasOwnProperty(r)||(void 0===n&&(n=E(e,o(e))),delete n[r])}if(i)for(var f=0,h=e.length;f<h;f++){var m=e[f];for(r in m)m.hasOwnProperty(r)&&u(void 0!==n?n:this,m,r)}else{for(r in e)Object.getOwnPropertyDescriptor(e,r)&&u(this,e,r);"replace"===l&&p(this,e)}return void 0===n?this:H(n)}function I(e,t){var n=t&&t.deep;if(0===arguments.length)return this;if(null===e||"object"!=typeof e)throw new TypeError("Immutable#replace can only be invoked with objects or arrays, not "+JSON.stringify(e));return L.merge(this,e,{deep:n,mode:"replace"})}var C,x,T,J=L({});function D(e,t,n){if(!Array.isArray(e)||0===e.length)throw new TypeError('The first argument to Immutable#setIn must be an array containing at least one "key" string.');var r=e[0];if(1===e.length)return F.call(this,r,t,n);var i,s=e.slice(1),l=this[r];if(i=this.hasOwnProperty(r)&&"object"==typeof l&&null!==l?L.setIn(l,s,t):D.call(J,s,t),this.hasOwnProperty(r)&&l===i)return this;var a=E(this,o(this));return a[r]=i,H(a)}function F(e,t,n){var r=n&&n.deep;if(this.hasOwnProperty(e)&&(r&&this[e]!==t&&d(t)&&d(this[e])&&(t=L.merge(this[e],t,{deep:!0,mode:"replace"})),c(this[e],t)))return this;var i=E(this,o(this));return i[e]=L(t),H(i)}function R(e,t){var n=Array.prototype.slice.call(arguments,2),r=this[e];return L.set(this,e,t.apply(r,[r].concat(n)))}function $(e,t){for(var n=0,r=t.length;null!=e&&n<r;n++)e=e[t[n]];return n&&n==r?e:void 0}function z(e,t){var n=Array.prototype.slice.call(arguments,2),r=$(this,e);return L.setIn(this,e,t.apply(r,[r].concat(n)))}function U(e,t){var n=$(this,e);return void 0===n?t:n}function B(e){var t,n=o(this);if(e&&e.deep)for(t in this)this.hasOwnProperty(t)&&(n[t]=_(this[t]));else for(t in this)this.hasOwnProperty(t)&&(n[t]=this[t]);return n}function V(){return{}}function H(e){return i.use_static||(s(e,"merge",j),s(e,"replace",I),s(e,"without",S),s(e,"asMutable",B),s(e,"set",F),s(e,"setIn",D),s(e,"update",R),s(e,"updateIn",z),s(e,"getIn",U)),g(e,p)}function L(e,t,n){if(u(e)||function(e){return"object"==typeof e&&null!==e&&(60103===e.$$typeof||e.$$typeof===r)}(e)||function(e){return"undefined"!=typeof File&&e instanceof File}(e)||function(e){return"undefined"!=typeof Blob&&e instanceof Blob}(e)||function(e){return e instanceof Error}(e))return e;if(function(e){return"object"==typeof e&&"function"==typeof e.then}(e))return e.then(L);if(Array.isArray(e))return O(e.slice());if(e instanceof Date)return o=new Date(e.getTime()),i.use_static||s(o,"asMutable",A),g(o,m);var o,l=t&&t.prototype,a=(l&&l!==Object.prototype?function(){return Object.create(l)}:V)();if(null==n&&(n=64),n<=0)throw new y("Attempt to construct Immutable from a deeply nested object was detected. Have you tried to wrap an object with circular references (e.g. React element)? See https://github.com/rtfeldman/seamless-immutable/wiki/Deeply-nested-object-was-detected for details.");for(var c in n-=1,e)Object.getOwnPropertyDescriptor(e,c)&&(a[c]=L(e[c],void 0,n));return H(a)}function W(e){return function(){var t=[].slice.call(arguments),n=t.shift();return e.apply(n,t)}}function Z(e,t){return function(){var n=[].slice.call(arguments),r=n.shift();return Array.isArray(r)?t.apply(r,n):e.apply(r,n)}}return L.from=L,L.isImmutable=u,L.ImmutableError=y,L.merge=W(j),L.replace=W(I),L.without=W(S),L.asMutable=(C=B,x=N,T=A,function(){var e=[].slice.call(arguments),t=e.shift();return Array.isArray(t)?x.apply(t,e):t instanceof Date?T.apply(t,e):C.apply(t,e)}),L.set=Z(F,b),L.setIn=Z(D,k),L.update=W(R),L.updateIn=W(z),L.getIn=W(U),L.flatMap=W(M),L.asObject=W(P),i.use_static||(L.static=e({use_static:!0})),Object.freeze(L),L}();void 0===(r=function(){return i}.call(t,n,t,e))||(e.exports=r)}()}},t={};function n(r){var i=t[r];if(void 0!==i)return i.exports;var o=t[r]={exports:{}};return e[r](o,o.exports,n),o.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var r={};!function(){"use strict";n.r(r),n.d(r,{PresetAttrsMappingParserContainer:function(){return De},addMediaQuery:function(){return e},addModuleInlineFont:function(){return l},breakpoints:function(){return u},canEditInline:function(){return c},extractFieldAttrValue:function(){return d},extractLowestModuleType:function(){return f},extractModuleTypes:function(){return m},flattenAttrs:function(){return y},getAncestorIds:function(){return v},getAndInheritBackgroundAttr:function(){return M},getAndInheritIconStyleAttr:function(){return N},getAndInheritTextShadowAttr:function(){return _},getAttrByMode:function(){return E},getAttrSubnameValue:function(){return I},getAttrValue:function(){return w},getAttributeValueType:function(){return j},getChildType:function(){return x},getDefaultPresetAttrs:function(){return xe},getGutenbergEmbeds:function(){return T},getInheritBreakpoint:function(){return O},getInheritState:function(){return A},getModuleClassByName:function(){return B},getModuleClassName:function(){return V},getModuleOrderClassName:function(){return H},getModuleType:function(){return h},getModuleTypeLabel:function(){return Z},getParentId:function(){return g},getParentType:function(){return q},getPresetAttrs:function(){return $e},getPresetAttrsMapping:function(){return Fe},getPresetAttrsNames:function(){return Re},getPresetClassName:function(){return G},getPresetClassNameForModule:function(){return Ze},getRangeSelectionIds:function(){return Y},getSelectedPresetOrDefault:function(){return Q},getSettingsComponent:function(){return ee},hasHighlight:function(){return te},hasMousetrap:function(){return ne},hasOptions:function(){return re},inheritAttrValue:function(){return b},inheritBreakpoints:function(){return k},isModuleCurrentlySticky:function(){return ie},isOwnModule:function(){return oe},isValidChild:function(){return se},isValidDescendant:function(){return ae},isValidDraggedModulesParent:function(){return le},loadModuleInlineFont:function(){return ue},maybeConvertPresetModuleName:function(){return ze},maybeRestorePresetModuleName:function(){return Ue},maybeUnwrapPlaceholderBlock:function(){return We},mergeAttrs:function(){return ce},mergeRecursive:function(){return de},moduleTypeRanks:function(){return p},moduleTypeRelationship:function(){return C},openAddModuleModal:function(){return me},parseShortcode:function(){return ve},populateContentTree:function(){return Be},populateUsedPresets:function(){return Ve},processPresets:function(){return Ce},removeEmptyObjectAttributes:function(){return He},removeMatchingAttrs:function(){return we},removeModuleInlineFont:function(){return Oe},replaceMatchingAttrs:function(){return ke},saveInlineEditorValue:function(){return Ae},shouldRenderDropZone:function(){return Me},sortBreakpoints:function(){return Se},sortStates:function(){return Ne},states:function(){return Pe},structuralModules:function(){return _e},wrapPlaceholderBlock:function(){return Le}});const e=(e,t)=>{switch(e){case"desktop_above":return`@media only screen and ( min-width: 981px ) {${t}}`;case"tablet":return`@media only screen and ( max-width: 980px ) {${t}}`;case"phone":return`@media only screen and ( max-width: 767px ) {${t}}`;default:return t}};var t=window.lodash,i=n(33042),o=n.n(i),s=window.divi.data;const l=e=>{const{fontName:n,moduleId:r,shouldLoadFont:i=!0}=e;if((0,t.isUndefined)(r))return;const l=(0,s.select)("divi/app-ui").getBreakpoint(),a=(0,s.select)("divi/app-ui").getAttributeState(),u=(0,s.select)("divi/edit-post").getModuleAttr(r,"content.decoration.inlineFont"),c=u.getIn([l,a,"families"],o()([])).asMutable();c.push(n),(0,s.dispatch)("divi/edit-post").editModuleAttribute({id:r,attrName:"content.decoration.inlineFont",value:u.setIn([l,a,"families"],(0,t.uniq)(c))}),i&&(0,s.dispatch)("divi/fonts").loadFont(n)};var a=window.vendor.wp.hooks;const u=(0,a.applyFilters)("divi.moduleUtils.breakpoints",["desktop","tablet","phone"]),c=e=>{const n=(0,s.select)("divi/edit-post"),r=(0,s.select)("divi/module-library"),i=(0,s.select)("divi/settings"),o=n.getModuleAttrs(e),l=n.getParentModule(e)?.props?.attrs,a="on"===(0,t.get)(o,["locked","desktop","value"],"off"),u="on"===(0,t.get)(l,["locked","desktop","value"],"off")||!0===(0,t.get)(o,"lockedParent",!1),c=a||u,d=!!n.getGlobalLayoutId(e),p=!(0,t.isEmpty)(o?.globalParent),f=d||p,h=i.checkRolePermission("edit_module"),m=i.checkRolePermission("edit_content"),y=i.checkRolePermission("general_settings"),g=i.checkRolePermission("edit_global_library"),v=n.getModuleType(e);let b=!0;if(["module","fullwidth-module"].includes(v)){const t=n.getModuleName(e),o=r.getModule(t)?.d4Shortcode,s=i.getSetting(["currentUser","role"]),l=i.getSetting(["settings","role",s,o]);l.length>0&&(b="on"===l)}return!(!(!c&&h&&b&&m&&y)||f&&!g)},d=(e,n)=>n&&(0,t.isObject)(e)?(0,t.mapValues)(e,(e=>(0,t.mapValues)(e,(e=>(0,t.get)(e,n.split(".")))))):e,p={root:0,"fullwidth-section":1,"specialty-section":1,layout:1,section:1,row:2,column:3,"specialty-column":3,"row-inner":4,"column-inner":5,unsupported:6,module:6,"fullwidth-module":6,"child-module":7},f=e=>{let n,r=10;return(0,t.forEach)(e,(e=>{const t=p[e];t&&t<r&&(r=t,n=e)})),n},h=(e,n,r=!1)=>"divi/root"===e?"root":"divi/section"===e?r||"fullwidth"!==(0,t.get)(n,["module","advanced","type","desktop","value"])?r||"specialty"!==(0,t.get)(n,["module","advanced","type","desktop","value"])?"section":"specialty-section":"fullwidth-section":"divi/row"===e?"row":"divi/row-inner"===e?r?"row":"row-inner":"divi/column"===e?!r&&(0,t.has)(n,["module","advanced","specialtyColumns","desktop","value"])?"specialty-column":"column":"divi/column-inner"===e?r?"column":"column-inner":!r&&(0,s.select)("divi/module-library").isFullwidthModule(e)?"fullwidth-module":!r&&(0,s.select)("divi/module-library").isChildModule(e)?"child-module":"module",m=(e,n={})=>{const r=(0,t.map)((0,t.values)(e),(e=>{const r=e?.name,i=e?.props,o=i?.attrs,s=h(r,o);return(0,t.isEmpty)(n)?s:(0,t.get)(n,s,s)}));return(0,t.uniq)(r)},y=(e,n="",r={})=>(Object.keys(e).forEach((i=>{const o=n?`${n}.${i}`:i,s=e[i]??null;(0,t.isPlainObject)(s)?y(s,o,r):(0,t.isNull)(s)||(r[o]=s)})),r),g=(e,n)=>(0,t.get)(e,[n,"parent"]),v=(e,n)=>{const r=[],i=(e,n)=>{const o=g(e,n);(0,t.isString)(o)&&"root"!==o&&(r.push(o),i(e,o))};return i(e,n),r},b=({attr:e,breakpoint:n,state:r,inheritMode:i="all"})=>{const o="value"===r;if("desktop"===n&&o)return null;const s=u.indexOf(n),l=(0,t.slice)(u,0,s).reverse();let a;o||(a=e?.[n]?.value);for(let n=0;n<l.length;n++){const r=l?.[n],o=e?.[r]?.value;if((0,t.isObject)(o)&&"all"===i)a=(0,t.merge)({},o,a);else{if(void 0!==o&&void 0===a){a=o;break}if(void 0!==a&&"closest"===i)break}}return a??null},w=({attr:e,breakpoint:n,state:r,defaultValue:i,mode:o="getOrInheritAll"})=>{const s=e?.[n]?.[r];let l,a;switch(o){case"getAndInheritClosest":case"getOrInheritClosest":case"inheritClosest":l=b({attr:e,breakpoint:n,state:r,inheritMode:"closest"});break;default:l=b({attr:e,breakpoint:n,state:r,inheritMode:"all"})}switch(o){case"getAndInheritAll":case"getAndInheritClosest":a=Array.isArray(s)&&Array.isArray(l)?[...l,...s]:(0,t.isObject)(s)&&!Array.isArray(s)&&(0,t.isObject)(l)&&!Array.isArray(l)?(0,t.merge)({},l,s):s??l;break;case"getOrInheritAll":case"getOrInheritClosest":a=s??l;break;case"inheritAll":case"inheritClosest":a=l;break;default:a=s}return a??i??null},k={phone:{sticky:["phone","value"],hover:["phone","value"],value:["tablet","value"]},tablet:{sticky:["tablet","value"],hover:["tablet","value"],value:["desktop","value"]},desktop:{sticky:["desktop","value"],hover:["desktop","value"],value:["desktop","value"]}},O=(e,n)=>(0,t.get)(k,[e,n,0]),A=(e,n)=>(0,t.get)(k,[e,n,1]),M=({attr:e,defaultPrintedStyleAttr:n})=>{let r={...e},i={...e};const o=(e,t)=>{const n="enabled"in e;return!(!n&&t)&&(!n||"on"===e.enabled)},s=(i,s)=>{const l=n?.[i]?.[s]??{},a=O(i,s),u=A(i,s),c="desktop"===i&&"value"===s?{}:r[a][u],d=w({attr:e,breakpoint:i,state:s,defaultValue:l,mode:"getOrInheritClosest"});r={...r,[i]:{...r[i],[s]:{color:d?.color??c?.color??"",gradient:o(d?.gradient??{})?{...c?.gradient,...d?.gradient,stops:d?.gradient?.stops??c?.gradient?.stops??[]}:{enabled:d?.gradient?.enabled},image:o(d?.image??{})?(0,t.merge)({},c?.image,d?.image):{enabled:d?.image?.enabled},mask:o(d?.mask??{})?(0,t.merge)({},c?.mask,d?.mask):{enabled:d?.mask?.enabled},pattern:o(d?.pattern??{})?(0,t.merge)({},c?.pattern,d?.pattern):{enabled:d?.pattern?.enabled}}}}},l=(e,t)=>{i={...i,[e]:{...i[e],[t]:{}}};const n=O(e,t),o=A(e,t),s="desktop"===e&&"value"===t,l=r[n]?.[o]?.color??null,a=r[e]?.[t]?.color??null,u=JSON.stringify(a)===JSON.stringify(l),c=null===a,d=s||""!==a?a:"initial",p=r[n]?.[o]?.image??{},f=r[e]?.[t]?.image??{},h=JSON.stringify(f)===JSON.stringify(p),m=JSON.stringify(f)===JSON.stringify({}),y=JSON.stringify(p)===JSON.stringify({}),g=r[n]?.[o]?.gradient??{},v=r[e]?.[t]?.gradient??{},b=JSON.stringify(v)===JSON.stringify(g),w=[JSON.stringify({}),JSON.stringify({stops:[]})].includes(JSON.stringify(v)),k=JSON.stringify(g)===JSON.stringify({}),M=m&&w,S=r[n]?.[o]?.mask??{},N=r[e]?.[t]?.mask??{},P=JSON.stringify(N)===JSON.stringify(S),_=JSON.stringify(N)===JSON.stringify({}),E=r[n]?.[o]?.pattern??{},j=r[e]?.[t]?.pattern??{},I=JSON.stringify(j)===JSON.stringify(E),C=JSON.stringify(j)===JSON.stringify({});s&&!c?i[e][t].color=d:u||c||(i[e][t].color=d),e in i&&t in i[e]&&(i[e][t].color||delete i[e][t].color),s&&!M?(i[e][t].image=f,i[e][t].gradient=v):h&&b||M?h||(y||(i[e][t].image=p),k||(i[e][t].gradient=g)):(i[e][t].image=f,i[e][t].gradient=v),e in i&&t in i[e]&&(i[e][t].image||i[e][t].gradient||(delete i[e][t].image,delete i[e][t].gradient),m&&y&&delete i[e][t].image,w&&k&&delete i[e][t].gradient),s&&!_?i[e][t].mask=N:P||_||(i[e][t].mask=N),e in i&&t in i[e]&&(i[e][t].mask||delete i[e][t].mask),s&&!C?i[e][t].pattern=j:I||C||(i[e][t].pattern=j),e in i&&t in i[e]&&0===Object.keys(i[e][t]).length&&delete i[e][t]};return(0,t.isObject)(e)&&(s("desktop","value"),s("desktop","hover"),s("desktop","sticky"),s("tablet","value"),s("tablet","hover"),s("tablet","sticky"),s("phone","value"),s("phone","hover"),s("phone","sticky")),(0,t.isObject)(r)?(l("phone","sticky"),l("phone","hover"),l("phone","value"),"phone"in i&&0===Object.keys(i.phone).length&&delete i.phone,l("tablet","sticky"),l("tablet","hover"),l("tablet","value"),"tablet"in i&&0===Object.keys(i.tablet).length&&delete i.tablet,l("desktop","sticky"),l("desktop","hover"),l("desktop","value"),"desktop"in i&&0===Object.keys(i.desktop).length&&delete i.desktop,i):e},S=e=>e instanceof Object,N=({attr:e,defaultPrintedStyleAttr:n})=>{const r={...e},i={...e},o=(o,s)=>{const l=n?.[o]?.[s]??{},a=O(o,s),u=A(o,s),c=w({attr:e,breakpoint:a,state:u,mode:"getAndInheritAll",defaultValue:l}),d=w({attr:e,breakpoint:o,state:s,defaultValue:l,mode:"getAndInheritAll"});r[o][s]={color:d?.color??c?.color??l?.color??null,show:d?.show??c?.show??l?.show??null,size:d?.size??c?.size??l?.size??null,type:d?.type??c?.type??l?.type??null,unicode:d?.unicode??c?.unicode??l?.unicode??null,useSize:d?.useSize??c?.useSize??l?.useSize??null,weight:d?.weight??c?.weight??l?.weight??null},S(r[o][s])&&Object.keys(r[o][s]).forEach((e=>{(0,t.isUndefined)(i[o][s])||null!==r[o][s][e]||delete i[o][s][e]})),i[o][s]=r[o][s]},s=(t,n)=>{const o=O(t,n),s=A(t,n),l="desktop"===t&&"value"===n,a=r[t]?.[n]??null,u=e[o]?.[s]??null,c=JSON.stringify(a)===JSON.stringify(u),d=JSON.stringify(a)===JSON.stringify({});l&&!d?i[t][n]=a:c||d?delete i[t][n]:i[t][n]=a};return S(e)&&(S(e.desktop)&&(o("desktop","value"),o("desktop","hover"),o("desktop","sticky")),S(e.tablet)&&(o("tablet","value"),o("tablet","hover"),o("tablet","sticky")),S(e.phone)&&(o("phone","value"),o("phone","hover"),o("phone","sticky"))),S(e)&&(S(e.phone)&&(s("phone","sticky"),s("phone","hover"),s("phone","value")),S(e.tablet)&&(s("tablet","sticky"),s("tablet","hover"),s("tablet","value")),S(e.desktop)&&(s("desktop","sticky"),s("desktop","hover"),s("desktop","value"))),i},P=e=>e instanceof Object,_=({attr:e,defaultPrintedStyleAttr:t})=>{const n={...e},r={...e},i=(i,o)=>{const s=t?.[i]?.[o]??{},l=O(i,o),a=A(i,o),u=w({attr:e,breakpoint:l,state:a,mode:"getAndInheritAll",defaultValue:s}),c=w({attr:e,breakpoint:i,state:o,defaultValue:s,mode:"getAndInheritAll"});n[i][o]={style:c?.style??u?.style??s?.style??null,horizontal:c?.horizontal??u?.horizontal??s?.horizontal??null,vertical:c?.vertical??u?.vertical??s?.vertical??null,blur:c?.blur??u?.blur??s?.blur??null,color:c?.color??u?.color??s?.color??null},P(n[i][o])&&Object.keys(n[i][o]).forEach((e=>{null===n[i][o][e]&&delete r[i][o][e]})),r[i][o]=n[i][o]},o=(t,i)=>{const o=O(t,i),s=A(t,i),l="desktop"===t&&"value"===i,a=n[t]?.[i]??null,u=e[o]?.[s]??null,c=JSON.stringify(a)===JSON.stringify(u),d=JSON.stringify(a)===JSON.stringify({});l&&!d?r[t][i]=a:c||d?delete r[t][i]:r[t][i]=a};return P(e)&&(P(e.desktop)&&(i("desktop","value"),i("desktop","hover"),i("desktop","sticky")),P(e.tablet)&&(i("tablet","value"),i("tablet","hover"),i("tablet","sticky")),P(e.phone)&&(i("phone","value"),i("phone","hover"),i("phone","sticky"))),P(e)&&(P(e.phone)&&(o("phone","sticky"),o("phone","hover"),o("phone","value")),P(e.tablet)&&(o("tablet","sticky"),o("tablet","hover"),o("tablet","value")),P(e.desktop)&&(o("desktop","sticky"),o("desktop","hover"),o("desktop","value"))),r},E=(e,t)=>{const n=t?.breakpoint??(0,s.useSelect)((e=>e("divi/app-ui")?.getBreakpoint()))??"desktop",r=t?.state??(0,s.useSelect)((e=>e("divi/app-ui")?.getAttributeState()))??"value";return w({attr:e,breakpoint:n,state:r,mode:t?.inheritMode??"getAndInheritAll"})},j=e=>{if((0,t.isNull)(e))return"null";if((0,t.isArray)(e))return"array";const n=typeof e;switch(n){case"boolean":case"object":return n;case"undefined":return"null";case"bigint":case"number":return"number";default:return"string"}},I=({attr:e,breakpoint:n,state:r,defaultValue:i,mode:o="getOrInheritAll",subname:s})=>{const l=w({attr:e,breakpoint:n,state:r,mode:o});return(0,t.get)(l,s,i)},C={root:{parent:"",child:["section","specialty-section","fullwidth-section"],descendant:["section","specialty-section","fullwidth-section","row","row-inner","column","column-inner","specialty-column","module","fullwidth-module","child-module","unsupported"]},section:{parent:"root",child:"row",descendant:["row","column","module","child-module","unsupported"]},"specialty-section":{parent:"root",child:["column","specialty-column"],descendant:["column","specialty-column","row-inner","column-inner","module","child-module","unsupported"]},"fullwidth-section":{parent:"root",child:"fullwidth-module",descendant:["fullwidth-module","child-module"]},row:{parent:"section",child:"column",descendant:["column","module","child-module","unsupported"]},column:{parent:"row",child:"module",descendant:["module","child-module","unsupported"]},"specialty-column":{parent:"specialty-section",child:"row-inner",descendant:["row-inner","column-inner","module","child-module","unsupported"]},"row-inner":{parent:"specialty-column",child:"column-inner",descendant:["column-inner","module","child-module","unsupported"]},"column-inner":{parent:"row-inner",child:"module",descendant:["module","child-module","unsupported"]},module:{parent:["column","column-inner"],child:"child-module",descendant:["child-module"]},"fullwidth-module":{parent:"fullwidth-section",child:"child-module",descendant:["child-module"]},"child-module":{parent:["module","fullwidth-module"],child:"",descendant:[]},layout:{parent:["root","section","fullwidth-section","specialty-section","row","row-inner","column","column-inner","specialty-column","unsupported"],child:["section","fullwidth-section","specialty-section","row","row-inner","column","column-inner","specialty-column","module","fullwidth-module","unsupported"],descendant:["section","fullwidth-section","specialty-section","row","row-inner","column","column-inner","specialty-column","module","fullwidth-module","unsupported"]},unsupported:{parent:["column","column-inner"],child:"",descendant:[]}},x=e=>(0,t.get)(C,[e,"child"]),T=(e,t)=>"string"!=typeof e?null:e.match(t);var J=function(){return J=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var i in t=arguments[n])Object.prototype.hasOwnProperty.call(t,i)&&(e[i]=t[i]);return e},J.apply(this,arguments)};Object.create;Object.create;function D(e){return e.toLowerCase()}var F=[/([a-z0-9])([A-Z])/g,/([A-Z])([A-Z][a-z])/g],R=/[^A-Z0-9]+/gi;function $(e,t,n){return t instanceof RegExp?e.replace(t,n):t.reduce((function(e,t){return e.replace(t,n)}),e)}function z(e,t){return void 0===t&&(t={}),function(e,t){void 0===t&&(t={});for(var n=t.splitRegexp,r=void 0===n?F:n,i=t.stripRegexp,o=void 0===i?R:i,s=t.transform,l=void 0===s?D:s,a=t.delimiter,u=void 0===a?" ":a,c=$($(e,r,"$1\0$2"),o,"\0"),d=0,p=c.length;"\0"===c.charAt(d);)d++;for(;"\0"===c.charAt(p-1);)p--;return c.slice(d,p).split("\0").map(l).join(u)}(e,J({delimiter:"."},t))}function U(e,t){return void 0===t&&(t={}),z(e,J({delimiter:"_"},t))}const B=e=>{const t=e.split("/",2);if(2!==t.length||!t[0]||!t[1])return"";return`${"divi"===t[0]?"et_pb":U(t[0])}_${U(t[1])}`},V=e=>{const t=(0,s.select)("divi/module-library").getModule(e);let n="";return t&&(n=t?.moduleClassName??""),n||(n=B(e)),n},H=e=>{const t=(0,s.select)("divi/edit-post").getModule(e);if(t){const n=(e=>{const t=(0,s.select)("divi/module-library").getModule(e);let n="";return t&&(n=t?.moduleOrderClassName??"",n||(n=t?.moduleClassName??"")),n||(n=B(e)),n})(t.name);if(n)return`${n}_${e}`}return""};var L=window.vendor.wp.i18n;const W={root:(0,L.__)("Root","et_builder"),"fullwidth-section":(0,L.__)("Fullwidth Section","et_builder"),"specialty-section":(0,L.__)("Specialty Section","et_builder"),section:(0,L.__)("Section","et_builder"),row:(0,L.__)("Row","et_builder"),"row-inner":(0,L.__)("Row Inner","et_builder"),"specialty-column":(0,L.__)("Specialty Column","et_builder"),column:(0,L.__)("Column","et_builder"),"column-inner":(0,L.__)("Column Inner","et_builder"),"fullwidth-module":(0,L.__)("Fullwidth Module","et_builder"),"child-module":(0,L.__)("Child Module","et_builder"),module:(0,L.__)("Module","et_builder"),layout:(0,L.__)("Layout","et_builder"),unsupported:(0,L.__)("Unsupported","et_builder")},Z=e=>(0,t.get)(W,[e],W.module),q=e=>(0,t.get)(C,[e,"parent"]),G=(e,n)=>`preset--module--${(0,t.kebabCase)(e)}--${n}`,Y=(e,n,r)=>{if((0,t.isEmpty)(r))return[];if((0,t.isEmpty)((0,t.intersection)(n,r)))return[];(0,t.reverse)(r);const i=(0,t.find)(r,(e=>(0,t.includes)(n,e))),o=n.indexOf(i),s=n.indexOf(e),l=o<s,a=(0,t.filter)(n,(e=>{const t=n.indexOf(e);return l?o<t&&t<=s:o>t&&t>=s}));return l||(0,t.reverse)(a),a},Q=(e,t)=>e&&"default"!==e?e:t;var K=window.vendor.React,X=n.n(K);const ee=(e,n="right-click-panel-module-settings")=>{const r=(0,s.select)("divi/module-library").getModule(e),i=(0,t.get)(r,["settings"]);return(0,t.values)(i).filter((e=>null!==e)).map(((e,t)=>(0,K.createElement)(e,{key:`${n}-${t}`})))},te=({displayHighlightOnChildHover:e,displayHighlightOnHover:t,isChildHovered:n,isColumn:r,isDirectChildHovered:i,isHovered:o,isHoveredByDraggedModule:s,isRowEmpty:l,isParentModule:a,isSelected:u,isChildModule:c,isRow:d,isSection:p,isModuleRightClicked:f})=>{if(s)return!1;if(l||u)return!0;if(f)return!0;return!(r||c||!(n&&e||o&&t||n&&!d&&!p&&e||d&&t&&i||a&&t&&i))},ne=({isChildHovered:e,isChildModule:t,isCurrentModuleSettingsOpened:n,isHovered:r,isParentModule:i,isSelected:o})=>!n&&(!t&&(!!o||(r||i&&e))),re=({displayOptionsOnChildHover:e,displayOptionsOnHover:t,isChildHovered:n,isChildSelected:r,isColumn:i,isDirectChildHovered:o,isHovered:s,isHoveredByDraggedModule:l,isRow:a,isRowEmpty:u,isSelected:c,isModuleRightClicked:d})=>{if(i)return!1;if(l)return!1;if(u||c)return!0;if(d)return!0;const p=a&&o;return n&&e&&!p||r||s&&t||p&&t},ie=e=>!(0,t.isEmpty)(window?.ET_FE)&&window?.ET_FE?.stores?.sticky?.isSticky(e),oe=e=>{const t=e.split("/");return 2===t.length&&"divi"===t[0]&&0<t[1].length},se=(e,n)=>{const r=(0,t.get)(C,[e,"child"]);return(0,t.isArray)(r)?(0,t.includes)(r,n):n===r},le=(e,t)=>t?.map((t=>se(e,t.moduleType))).every((e=>!0===e)),ae=(e,t)=>{const n=C[e]?.descendant;return Array.isArray(n)?n.includes(t):t===n},ue=e=>{if((0,t.isUndefined)(e))return;const n=(0,s.select)("divi/app-ui").getBreakpoint(),r=(0,s.select)("divi/app-ui").getAttributeState(),i=(0,s.select)("divi/edit-post").getModuleAttr(e,"content.decoration.inlineFont").getIn([n,r,"families"],[]);(0,t.forEach)(i,(e=>{(0,s.dispatch)("divi/fonts").loadFont(e)}))},ce=({defaultAttrs:e,presetAttrs:n,attrs:r})=>(0,t.merge)({},e,n,r),de=(e,...t)=>(t.forEach((t=>{t&&Object.keys(t).forEach((n=>{const r=t[n],i=e[n];Array.isArray(r)||"object"!=typeof r||null===r?e[n]=r:("object"==typeof i&&null!==i||(e[n]={}),de(e[n],r))}))})),e);var pe=window.jQuery,fe=n.n(pe),he=window.divi.uiLibrary;const me=(e,t,n)=>{const r=e.width(),i=e.height(),o={height:450,width:360},l=e.offset();l.top-=fe()(window).scrollTop()-i/2,l.left-=o.width/2-r/2;const a=(0,s.select)("divi/app-ui").getView();if("100vw"!==(0,s.select)("divi/app-ui").getViewItem(a).width){const e=(0,s.select)("divi/app-ui").getWindow("width","app"),t=(0,s.select)("divi/app-ui").getWindow("width","top"),n=(0,s.select)("divi/app-ui").getElementProperty({elementName:"builderBar",propertyGroup:"dimension",propertyName:"width"}),r=(0,s.select)("divi/app-ui").getElementProperty({elementName:"sidebarLeft",propertyGroup:"dimension",propertyName:"width"}),i=(0,s.select)("divi/app-ui").getElementProperty({elementName:"sidebarRight",propertyGroup:"dimension",propertyName:"width"}),o=(0,s.select)("divi/app-ui").getAppFrameZoom({format:"decimal"}),a=(0,s.select)("divi/app-ui").isUserDefinedWindowProperty({attr:"width",windowLocation:"app"}),u=(0,s.select)("divi/app-ui").isViewBreakpoint("desktop"),c=(0,he.getAppFrameMarginSides)({builderBarWidth:n,appWindowWidth:e,sidebarLeftWidth:r,sidebarRightWidth:i,topWindowWidth:t,isDesktopBreakpoint:u,zoomScale:o,isUserDefinedAppWindowWidth:a});l.left+=c}(0,s.dispatch)("divi/modal-library").open({name:"divi/add-module",owner:t,dimension:o,offset:l,attributes:{position:n?"inside":"after"}})};var ye=window.vendor.wp.shortcode,ge=window.divi.constantLibrary;const ve=e=>{let t=(0,ye.regexp)(ge.shortcodeTagPattern).exec(e),n="";const r=[];for(;null!==t;){const e=t[2].toString(),i=(0,ye.attrs)(t[3])?.named,o=(0,s.select)("divi/settings").getSetting(["shortcodeModuleDefinitions",e]);n=t.input,r.push({name:e,attrs:i,content:t[5],children:o?.child?.slug?ve(t[5]):null,moduleDefinition:o,originalShortcode:t[0]}),n=n.replace(t[0],""),t=(0,ye.regexp)(ge.shortcodeTagPattern).exec(n)}return r};var be=n(53316);const we=n.n(be)()(((e,n)=>{if((0,t.isEmpty)(n))return e;const r=y(e),i=y(n);return Object.keys(r).reduce(((n,r)=>((0,t.has)(i,r)||(0,t.set)(n,r,(0,t.get)(e,r)),n)),{})})),ke=(e,n)=>{if((0,t.isEmpty)(n))return e;const r=y(e),i=y(n);return Object.keys(r).reduce(((r,o)=>((0,t.has)(i,o)?(0,t.set)(r,o,(0,t.get)(n,o)):(0,t.set)(r,o,(0,t.get)(e,o)),r)),{})},Oe=e=>{const{fontName:n,moduleId:r}=e;if((0,t.isUndefined)(r))return;const i=(0,s.select)("divi/app-ui").getBreakpoint(),l=(0,s.select)("divi/app-ui").getAttributeState(),a=(0,s.select)("divi/edit-post").getModuleAttr(r,"content.decoration.inlineFont"),u=a.getIn([i,l,"families"],o()([])).asMutable(),c=[];(0,t.forEach)(u,(e=>c.push((0,t.toLower)(e))));const d=(0,t.indexOf)(c,(0,t.toLower)(n));-1!==d&&(u.splice(d,1),(0,s.dispatch)("divi/edit-post").editModuleAttribute({id:r,attrName:"content.decoration.inlineFont",value:a.setIn([i,l,"families"],(0,t.uniq)(u))}))},Ae=({moduleId:e,attrName:t,attrSubName:n,breakpoint:r,state:i,value:o})=>{const l=(0,s.select)("divi/edit-post").getModuleAttr(e,t),a=n?r.split(".").concat(i.split("."),n.split(".")):r.split(".").concat(i.split("."));(0,s.dispatch)("divi/edit-post").editModuleAttribute({id:e,attrName:t,value:l.setIn(a,o)})},Me=(e,n)=>{if(!(0,a.applyFilters)("divi.moduleUtils.shouldRenderDropZone.enable",!0,e,n))return!1;const r=(0,s.select)("divi/events").getDraggedModules(),i=(o=r,(0,t.uniq)((0,t.map)(o,(e=>e.moduleType))));var o;const l=1===i.length?i[0]:f(i),u=(0,s.select)("divi/edit-post").getModule(e),c="inside"===n?e:(0,t.get)(u,"parent"),d=(0,s.select)("divi/edit-post").getModuleName(c),p=(0,s.select)("divi/edit-post").getModuleAttrs(c).asMutable({deep:!0}),m=h(d,p);return se(m,l)},Se=e=>{const t=["desktop","desktopAbove","tablet","tabletOnly","phone"],n=Object.keys(e);if(!n?.sort||!n?.forEach)return e;n.sort(((e,n)=>{const r=t.indexOf(e),i=t.indexOf(n);return(-1===r?t.length:r)-(-1===i?t.length:i)}));const r={};return n.forEach((t=>{r[t]=e[t]})),r},Ne=e=>{const t=["value","hover","sticky"],n=Object.keys(e);if(!n?.sort||!n?.forEach)return e;n.sort(((e,n)=>{const r=t.indexOf(e),i=t.indexOf(n);return(-1===r?t.length:r)-(-1===i?t.length:i)}));const r={};return n.forEach((t=>{r[t]=e[t]})),r},Pe=(0,a.applyFilters)("divi.moduleUtils.states",["value","hover","sticky"]),_e=["divi/section","divi/row","divi/row-inner","divi/column","divi/column-inner"];let Ee=e=>crypto.getRandomValues(new Uint8Array(e)),je=(e,t=21)=>((e,t,n)=>{let r=(2<<Math.log(e.length-1)/Math.LN2)-1,i=-~(1.6*r*t/e.length);return(o=t)=>{let s="";for(;;){let t=n(i),l=i;for(;l--;)if(s+=e[t[l]&r]||"",s.length===o)return s}}})(e,t,Ee);const Ie=()=>je("0123456789abcdefghijklmnopqrstuvwxyz",10)(),Ce=e=>{const n={module:{}},r=(0,s.select)("divi/global-data").getAllPresetData();let i={};return(0,t.forEach)(e.module,((e,o)=>{if(!o)return;const s={};(0,t.forEach)(e.items,((n,l)=>{if(!n)return;const a=r?.getIn([o,"items",l])?.asMutable({deep:!0}),u=e?.default===l,c=Date.now(),d=(0,t.isEmpty)(a)?l:Ie();(d!==l||u)&&(i={[o]:{...i[o],[u?"default":l]:d}}),s[d]={...n,id:d,name:!(0,t.isEmpty)(a)||u?(0,L.sprintf)((0,L.__)("%s imported","et_builder"),n.name):n.name,created:c,updated:c}})),(0,t.isEmpty)(s)||(n.module[o]={items:s,default:e.default})})),{presets:n,newIds:i}},xe=e=>{const n={};return(0,t.forEach)(e.module,((e,t)=>{const r=e?.default,i=e?.items[r];i&&(n[t]=i.attrs)})),n};var Te=window.divi.contextLibrary;const Je=({moduleName:e,callbacks:n,setPresetAttrsMap:r})=>{const i={};return(0,K.useEffect)((()=>{r({moduleName:e,map:i}),(0,t.forEach)(n,(t=>{t(e,i)}))}),[]),X().createElement(Te.clipboardAttrsMapsParserContext.Provider,{value:{optionType:"module",isParsingAttrsMaps:!0}},X().createElement(Te.fieldContext.Provider,{value:{onContainerComponentMount:e=>{const{attrName:n,subName:r,features:o}=e;let s=n;const l={attrName:n,preset:o.preset?o.preset:["style"]};(0,t.isUndefined)(r)||(s=`${n}__${r}`,l.subName=r),(0,t.isEmpty)(s)||(i[s]=l)},onContainerComponentUnMount:t.noop,containerRenderer:()=>null}},X().createElement("div",{className:"et-vb-preset-attrs-maps-parser",style:{display:"none"}},X().createElement(Te.moduleContext.Provider,{value:{moduleId:"preset-attrs-mapping-parser",moduleName:e}},ee(e,"preset-attrs-panel-module-settings")))))},De=()=>{const e=(0,s.useSelect)((e=>e("divi/global-data").getPresetAttrsMapJobs())),{setPresetAttrsMap:n}=(0,s.dispatch)("divi/global-data");return(0,K.createElement)(X().Fragment,null,(0,t.map)(e,((e,t)=>(0,K.createElement)(Je,{key:t,moduleName:t,callbacks:e,setPresetAttrsMap:n}))))},Fe=e=>new Promise((t=>{const n=(0,s.select)("divi/global-data").getPresetAttrsMap(e);n?t({moduleName:e,map:n}):(0,s.dispatch)("divi/global-data").addPresetAttrsMapJob({moduleName:e,id:Ie(),callback:(e,n)=>{const r=(0,a.applyFilters)("divi.conversion.presets.attrs.map",{...n},e);t({moduleName:e,map:r})}})})),Re=({presetType:e,moduleAttrs:n,map:r})=>{let i=[];const o=e=>i.some((n=>(0,t.isEqual)(n,e)));return Object.values(r).filter((({preset:t})=>Array.isArray(t)?t.some((t=>e.includes(t))):e.includes(t))).forEach((({attrName:e,subName:r})=>{const s=e.split(".");if((0,t.has)(n,s)){const l=(0,t.get)(n,s);if(r){const n=r.split(".");(0,t.forEach)(l,((s,l)=>{(0,t.forEach)(s,((s,a)=>{const u={attrName:e,subName:r,breakpoint:l,state:a};(0,t.has)(s,n)&&!o(u)&&(i=i.concat(u))}))}))}else o({attrName:e})||(i=i.concat({attrName:e}))}})),i},$e=({presetType:e,moduleAttrs:n,map:r})=>{const i={};return Re({presetType:e,moduleAttrs:n,map:r}).forEach((({attrName:e,subName:r,breakpoint:o,state:s})=>{let l=e.split(".");o&&(l=l.concat(o)),s&&(l=l.concat(s)),r&&(l=l.concat(r.split("."))),(0,t.has)(n,l)&&(0,t.set)(i,l,(0,t.get)(n,l))})),i},ze=(e,n)=>"divi/section"===e?"fullwidth"===(0,t.get)(n,["module","advanced","type","desktop","value"])?"divi/fullwidth-section":"specialty"===(0,t.get)(n,["module","advanced","type","desktop","value"])?"divi/specialty-section":"divi/section":e,Ue=e=>["divi/fullwidth-section","divi/specialty-section","divi/section"].includes(e)?"divi/section":e,Be=({tree:e,rootId:t="root"})=>{const n={},r=(i,o)=>{const s=e[i];s&&(t!==i&&s.parent!==o||(n[i]=s,Array.isArray(s.children)&&s.children.length>0&&s?.children.forEach((e=>{r(e,i)}))))};return r(t,null),n},Ve=e=>{const n={},r=Be({tree:e});return(0,t.forEach)(r,(({props:e,name:r})=>{if("divi/root"===r)return;const i=ze(r,e?.attrs);let o=(0,s.select)("divi/global-data").getModulePreset({moduleName:i,id:e?.attrs?.modulePreset,useDefaultFallback:!1})?.id;o||(o="default"),(0,t.has)(n,i)||(n[i]=[]),n[i].includes(o)||(n[i]=n[i].concat(o))})),n},He=e=>((0,t.isEmpty)(e)||Object.keys(e).forEach((n=>{(0,t.isPlainObject)(e[n])&&(e[n]=He(e[n]),(0,t.isEmpty)(e[n])&&delete e[n])})),e),Le=e=>{const t="divi/placeholder";return`\x3c!-- wp:${t} --\x3e${e}\x3c!-- /wp:${t} --\x3e`},We=e=>{const t=e.match(/<!-- wp:divi\/placeholder -->([\s\S]*?)<!-- \/wp:divi\/placeholder -->/);return t&&t[1]?t[1].trim():e},Ze=(e,n,r)=>{const i=(0,t.isEmpty)(n)||n?.id===r?.id;if(i&&((0,t.isEmpty)(r)||(0,t.isEmpty)(r?.attrs)))return null;let o=n?.id;return i&&(o="default"),`preset--module--${(0,t.kebabCase)(e)}--${o}`}}(),(window.divi=window.divi||{}).moduleUtils=r}();