!function(){var t={33042:function(t,e,n){var r;!function(){"use strict";var o=function t(e){var n,r="function"==typeof Symbol&&Symbol.for&&Symbol.for("react.element"),o={use_static:!1};function i(t){var e=Object.getPrototypeOf(t);return e?Object.create(e):{}}function s(t,e,n){Object.defineProperty(t,e,{enumerable:!1,configurable:!1,writable:!1,value:n})}function a(t,e){s(t,e,(function(){throw new v("The "+e+" method cannot be invoked on an Immutable data structure.")}))}"object"!=typeof(n=e)||Array.isArray(n)||null===n||void 0!==e.use_static&&(o.use_static=Boolean(e.use_static));var c="__immutable_invariants_hold";function u(t){return"object"!=typeof t||(null===t||Boolean(Object.getOwnPropertyDescriptor(t,c)))}function l(t,e){return t===e||t!=t&&e!=e}function p(t){return!(null===t||"object"!=typeof t||Array.isArray(t)||t instanceof Date)}var f=["setPrototypeOf"],d=f.concat(["push","pop","sort","splice","shift","unshift","reverse"]),y=["keys"].concat(["map","filter","slice","concat","reduce","reduceRight"]),h=f.concat(["setDate","setFullYear","setHours","setMilliseconds","setMinutes","setMonth","setSeconds","setTime","setUTCDate","setUTCFullYear","setUTCHours","setUTCMilliseconds","setUTCMinutes","setUTCMonth","setUTCSeconds","setYear"]);function v(t){this.name="MyError",this.message=t,this.stack=(new Error).stack}function g(t,e){for(var n in s(t,c,!0),e)e.hasOwnProperty(n)&&a(t,e[n]);return Object.freeze(t),t}function b(t,e){var n=t[e];s(t,e,(function(){return Q(n.apply(t,arguments))}))}function w(t,e,n){var r=n&&n.deep;if(t in this&&(r&&this[t]!==e&&p(e)&&p(this[t])&&(e=Q.merge(this[t],e,{deep:!0,mode:"replace"})),l(this[t],e)))return this;var o=j.call(this);return o[t]=Q(e),D(o)}v.prototype=new Error,v.prototype.constructor=Error;var m=Q([]);function _(t,e,n){var r=t[0];if(1===t.length)return w.call(this,r,e,n);var o,i=t.slice(1),s=this[r];if("object"==typeof s&&null!==s)o=Q.setIn(s,i,e);else{var a=i[0];o=""!==a&&isFinite(a)?_.call(m,i,e):q.call(x,i,e)}if(r in this&&s===o)return this;var c=j.call(this);return c[r]=o,D(c)}function D(t){for(var e in y){if(y.hasOwnProperty(e))b(t,y[e])}o.use_static||(s(t,"flatMap",O),s(t,"asObject",M),s(t,"asMutable",j),s(t,"set",w),s(t,"setIn",_),s(t,"update",B),s(t,"updateIn",L),s(t,"getIn",U));for(var n=0,r=t.length;n<r;n++)t[n]=Q(t[n]);return g(t,d)}function S(){return new Date(this.getTime())}function O(t){if(0===arguments.length)return this;var e,n=[],r=this.length;for(e=0;e<r;e++){var o=t(this[e],e,this);Array.isArray(o)?n.push.apply(n,o):n.push(o)}return D(n)}function A(t){if(void 0===t&&0===arguments.length)return this;if("function"!=typeof t){var e=Array.isArray(t)?t.slice():Array.prototype.slice.call(arguments);e.forEach((function(t,e,n){"number"==typeof t&&(n[e]=t.toString())})),t=function(t,n){return-1!==e.indexOf(n)}}var n=i(this);for(var r in this)this.hasOwnProperty(r)&&!1===t(this[r],r)&&(n[r]=this[r]);return $(n)}function j(t){var e,n,r=[];if(t&&t.deep)for(e=0,n=this.length;e<n;e++)r.push(k(this[e]));else for(e=0,n=this.length;e<n;e++)r.push(this[e]);return r}function M(t){"function"!=typeof t&&(t=function(t){return t});var e,n={},r=this.length;for(e=0;e<r;e++){var o=t(this[e],e,this),i=o[0],s=o[1];n[i]=s}return $(n)}function k(t){return!t||"object"!=typeof t||!Object.getOwnPropertyDescriptor(t,c)||t instanceof Date?t:Q.asMutable(t,{deep:!0})}function P(t,e){for(var n in t)Object.getOwnPropertyDescriptor(t,n)&&(e[n]=t[n]);return e}function C(t,e){if(0===arguments.length)return this;if(null===t||"object"!=typeof t)throw new TypeError("Immutable#merge can only be invoked with objects or arrays, not "+JSON.stringify(t));var n,r,o=Array.isArray(t),s=e&&e.deep,a=e&&e.mode||"merge",c=e&&e.merger;function u(t,r,o){var a,u=Q(r[o]),f=c&&c(t[o],u,e),d=t[o];void 0===n&&void 0===f&&t.hasOwnProperty(o)&&l(u,d)||(l(d,a=void 0!==f?f:s&&p(d)&&p(u)?Q.merge(d,u,e):u)&&t.hasOwnProperty(o)||(void 0===n&&(n=P(t,i(t))),n[o]=a))}function f(t,e){for(var r in t)e.hasOwnProperty(r)||(void 0===n&&(n=P(t,i(t))),delete n[r])}if(o)for(var d=0,y=t.length;d<y;d++){var h=t[d];for(r in h)h.hasOwnProperty(r)&&u(void 0!==n?n:this,h,r)}else{for(r in t)Object.getOwnPropertyDescriptor(t,r)&&u(this,t,r);"replace"===a&&f(this,t)}return void 0===n?this:$(n)}function I(t,e){var n=e&&e.deep;if(0===arguments.length)return this;if(null===t||"object"!=typeof t)throw new TypeError("Immutable#replace can only be invoked with objects or arrays, not "+JSON.stringify(t));return Q.merge(this,t,{deep:n,mode:"replace"})}var T,R,E,x=Q({});function q(t,e,n){if(!Array.isArray(t)||0===t.length)throw new TypeError('The first argument to Immutable#setIn must be an array containing at least one "key" string.');var r=t[0];if(1===t.length)return z.call(this,r,e,n);var o,s=t.slice(1),a=this[r];if(o=this.hasOwnProperty(r)&&"object"==typeof a&&null!==a?Q.setIn(a,s,e):q.call(x,s,e),this.hasOwnProperty(r)&&a===o)return this;var c=P(this,i(this));return c[r]=o,$(c)}function z(t,e,n){var r=n&&n.deep;if(this.hasOwnProperty(t)&&(r&&this[t]!==e&&p(e)&&p(this[t])&&(e=Q.merge(this[t],e,{deep:!0,mode:"replace"})),l(this[t],e)))return this;var o=P(this,i(this));return o[t]=Q(e),$(o)}function B(t,e){var n=Array.prototype.slice.call(arguments,2),r=this[t];return Q.set(this,t,e.apply(r,[r].concat(n)))}function F(t,e){for(var n=0,r=e.length;null!=t&&n<r;n++)t=t[e[n]];return n&&n==r?t:void 0}function L(t,e){var n=Array.prototype.slice.call(arguments,2),r=F(this,t);return Q.setIn(this,t,e.apply(r,[r].concat(n)))}function U(t,e){var n=F(this,t);return void 0===n?e:n}function W(t){var e,n=i(this);if(t&&t.deep)for(e in this)this.hasOwnProperty(e)&&(n[e]=k(this[e]));else for(e in this)this.hasOwnProperty(e)&&(n[e]=this[e]);return n}function H(){return{}}function $(t){return o.use_static||(s(t,"merge",C),s(t,"replace",I),s(t,"without",A),s(t,"asMutable",W),s(t,"set",z),s(t,"setIn",q),s(t,"update",B),s(t,"updateIn",L),s(t,"getIn",U)),g(t,f)}function Q(t,e,n){if(u(t)||function(t){return"object"==typeof t&&null!==t&&(60103===t.$$typeof||t.$$typeof===r)}(t)||function(t){return"undefined"!=typeof File&&t instanceof File}(t)||function(t){return"undefined"!=typeof Blob&&t instanceof Blob}(t)||function(t){return t instanceof Error}(t))return t;if(function(t){return"object"==typeof t&&"function"==typeof t.then}(t))return t.then(Q);if(Array.isArray(t))return D(t.slice());if(t instanceof Date)return i=new Date(t.getTime()),o.use_static||s(i,"asMutable",S),g(i,h);var i,a=e&&e.prototype,c=(a&&a!==Object.prototype?function(){return Object.create(a)}:H)();if(null==n&&(n=64),n<=0)throw new v("Attempt to construct Immutable from a deeply nested object was detected. Have you tried to wrap an object with circular references (e.g. React element)? See https://github.com/rtfeldman/seamless-immutable/wiki/Deeply-nested-object-was-detected for details.");for(var l in n-=1,t)Object.getOwnPropertyDescriptor(t,l)&&(c[l]=Q(t[l],void 0,n));return $(c)}function G(t){return function(){var e=[].slice.call(arguments),n=e.shift();return t.apply(n,e)}}function V(t,e){return function(){var n=[].slice.call(arguments),r=n.shift();return Array.isArray(r)?e.apply(r,n):t.apply(r,n)}}return Q.from=Q,Q.isImmutable=u,Q.ImmutableError=v,Q.merge=G(C),Q.replace=G(I),Q.without=G(A),Q.asMutable=(T=W,R=j,E=S,function(){var t=[].slice.call(arguments),e=t.shift();return Array.isArray(e)?R.apply(e,t):e instanceof Date?E.apply(e,t):T.apply(e,t)}),Q.set=V(z,w),Q.setIn=V(q,_),Q.update=G(B),Q.updateIn=G(L),Q.getIn=G(U),Q.flatMap=G(O),Q.asObject=G(M),o.use_static||(Q.static=t({use_static:!0})),Object.freeze(Q),Q}();void 0===(r=function(){return o}.call(e,n,e,t))||(t.exports=r)}()}},e={};function n(r){var o=e[r];if(void 0!==o)return o.exports;var i=e[r]={exports:{}};return t[r](i,i.exports,n),i.exports}n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,{a:e}),e},n.d=function(t,e){for(var r in e)n.o(e,r)&&!n.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})};var r={};!function(){"use strict";n.r(r),n.d(r,{columnLayouts:function(){return l},registerSettingsStore:function(){return O}});var t={};n.r(t),n.d(t,{add:function(){return u}});var e={};n.r(e),n.d(e,{checkRolePermission:function(){return f},getSetting:function(){return D},isEditingLibraryLayout:function(){return S}});var o=window.lodash,i=n(33042),s=n.n(i),a=window.divi.data,c=window.divi.rest;const u=(t,e)=>({type:"ADD",settingKey:t,payload:e}),l={specialty:[{structure:"1_2,1_2",position:"1,0"},{structure:"1_2,1_2",position:"0,1"},{structure:"1_4,3_4",position:"0,1"},{structure:"3_4,1_4",position:"1,0"},{structure:"1_4,1_4,1_2",position:"0,0,1"},{structure:"1_2,1_4,1_4",position:"1,0,0"},{structure:"1_4,1_2,1_4",position:"0,1,0"},{structure:"1_3,2_3",position:"0,1"},{structure:"2_3,1_3",position:"1,0"}],regular:["4_4","1_2,1_2","1_3,1_3,1_3","1_4,1_4,1_4,1_4","1_5,1_5,1_5,1_5,1_5","1_6,1_6,1_6,1_6,1_6,1_6","2_5,3_5","3_5,2_5","1_3,2_3","2_3,1_3","1_4,3_4","3_4,1_4","1_4,1_2,1_4","1_5,3_5,1_5","1_4,1_4,1_2","1_2,1_4,1_4","1_5,1_5,3_5","3_5,1_5,1_5","1_6,1_6,1_6,1_2","1_2,1_6,1_6,1_6"],columnInner:["4_4","1_2,1_2","1_3,1_3,1_3"],columnInnerWide:["4_4","1_2,1_2","1_3,1_3,1_3","1_4,1_4,1_4,1_4"]},p=(t,e)=>{if("ADD"===e.type){const n=(0,o.get)(e,"settingKey",""),r=(0,o.get)(e,"payload");return t.setIn([n],r)}return t},f=(t,e)=>{const n=t.getIn(["currentUser","role"]);return"on"===(t.getIn(["settings","role",n])?.[e]??"on")};function d(t,e="expected a function, instead received "+typeof t){if("function"!=typeof t)throw new TypeError(e)}var y=t=>Array.isArray(t)?t:[t];function h(t){const e=Array.isArray(t[0])?t[0]:t;return function(t,e="expected all items to be functions, instead received the following types: "){if(!t.every((t=>"function"==typeof t))){const n=t.map((t=>"function"==typeof t?`function ${t.name||"unnamed"}()`:typeof t)).join(", ");throw new TypeError(`${e}[${n}]`)}}(e,"createSelector expects all input-selectors to be functions, but received the following types: "),e}function v(t,e){const n=[],{length:r}=t;for(let o=0;o<r;o++)n.push(t[o].apply(null,e));return n}Symbol(),Object.getPrototypeOf({});var g="undefined"!=typeof WeakRef?WeakRef:class{constructor(t){this.value=t}deref(){return this.value}};function b(t,e={}){let n={s:0,v:void 0,o:null,p:null};const{resultEqualityCheck:r}=e;let o,i=0;function s(){let e=n;const{length:s}=arguments;for(let t=0,n=s;t<n;t++){const n=arguments[t];if("function"==typeof n||"object"==typeof n&&null!==n){let t=e.o;null===t&&(e.o=t=new WeakMap);const r=t.get(n);void 0===r?(e={s:0,v:void 0,o:null,p:null},t.set(n,e)):e=r}else{let t=e.p;null===t&&(e.p=t=new Map);const r=t.get(n);void 0===r?(e={s:0,v:void 0,o:null,p:null},t.set(n,e)):e=r}}const a=e;let c;if(1===e.s?c=e.v:(c=t.apply(null,arguments),i++),a.s=1,r){const t=o?.deref?.()??o;null!=t&&r(t,c)&&(c=t,0!==i&&i--);o="object"==typeof c&&null!==c||"function"==typeof c?new g(c):c}return a.v=c,c}return s.clearCache=()=>{n={s:0,v:void 0,o:null,p:null},s.resetResultsCount()},s.resultsCount=()=>i,s.resetResultsCount=()=>{i=0},s}function w(t,...e){const n="function"==typeof t?{memoize:t,memoizeOptions:e}:t,r=(...t)=>{let e,r=0,o=0,i={},s=t.pop();"object"==typeof s&&(i=s,s=t.pop()),d(s,`createSelector expects an output function after the inputs, but received: [${typeof s}]`);const a={...n,...i},{memoize:c,memoizeOptions:u=[],argsMemoize:l=b,argsMemoizeOptions:p=[],devModeChecks:f={}}=a,g=y(u),w=y(p),m=h(t),_=c((function(){return r++,s.apply(null,arguments)}),...g);const D=l((function(){o++;const t=v(m,arguments);return e=_.apply(null,t),e}),...w);return Object.assign(D,{resultFunc:s,memoizedResultFunc:_,dependencies:m,dependencyRecomputations:()=>o,resetDependencyRecomputations:()=>{o=0},lastResult:()=>e,recomputations:()=>r,resetRecomputations:()=>{r=0},memoize:c,argsMemoize:l})};return Object.assign(r,{withTypes:()=>r}),r}var m=w(b),_=Object.assign(((t,e=m)=>{!function(t,e="expected an object, instead received "+typeof t){if("object"!=typeof t)throw new TypeError(e)}(t,"createStructuredSelector expects first argument to be an object where each property is a selector, instead received a "+typeof t);const n=Object.keys(t);return e(n.map((e=>t[e])),((...t)=>t.reduce(((t,e,r)=>(t[n[r]]=e,t)),{})))}),{withTypes:()=>_});const D=m([t=>t,(t,e)=>e,(t,e,n)=>n],((t,e,n="")=>{const r=(0,o.isArray)(e)?e:(0,o.isString)(e)?e.split("."):[e];return t.getIn(r,n)})),S=t=>""!==t.getIn(["layout","type"]),O=()=>{if((0,a.select)("divi/settings"))return;const n={appColorMode:"light",appColorScheme:"blue",appAdminBar:{visible:!1},appInteractionLayers:{actionOnHover:!0,parentActionOnHover:!0,xRay:!1},toolbarClick:!1,toolbarGrid:!1,toolbarHover:!1,toolbarDesktop:!0,toolbarPhone:!0,toolbarTablet:!0,toolbarWireframe:!0,toolbarZoom:!0,builderAnimation:!0,builderEnableDummyContent:!0,hideDisabledModules:!1,viewMode:"desktop",historyIntervals:1,pageCreationFlow:"default",quickActionsAlwaysStartWith:"nothing",quickActionsShowRecentQueries:"off",quickActionsRecentQueries:"",quickActionsRecentCategory:"",modalPreference:"default",builderDisplayModalSettings:!0,isLayersView:!1},r=t=>window?.DiviSettingsData?.preferences?.[t]??n[t],i={appColorMode:r("appColorMode"),appColorScheme:r("appColorScheme"),appAdminBar:r("appAdminBar"),appInteractionLayers:r("appInteractionLayers"),toolbarClick:r("toolbarClick"),toolbarGrid:r("toolbarGrid"),toolbarHover:r("toolbarHover"),toolbarDesktop:r("toolbarDesktop"),toolbarPhone:r("toolbarPhone"),toolbarTablet:r("toolbarTablet"),toolbarWireframe:r("toolbarWireframe"),toolbarZoom:r("toolbarZoom"),builderAnimation:r("builderAnimation"),builderEnableDummyContent:r("builderEnableDummyContent"),hideDisabledModules:r("hideDisabledModules"),viewMode:r("viewMode"),historyIntervals:r("historyIntervals"),pageCreationFlow:r("pageCreationFlow"),quickActionsAlwaysStartWith:r("quickActionsAlwaysStartWith"),quickActionsShowRecentQueries:r("quickActionsShowRecentQueries"),quickActionsRecentQueries:r("quickActionsRecentQueries"),quickActionsRecentCategory:r("quickActionsRecentCategory"),modalPreference:r("modalPreference"),builderDisplayModalSettings:r("builderDisplayModalSettings"),isLayersView:r("isLayersView")},u={...window?.DiviSettingsData,columnLayouts:l,conditionalTags:window?.DiviSettingsData?.conditionalTags,currentPage:window?.DiviSettingsData?.currentPage,currentUser:window?.DiviSettingsData?.currentUser,dynamicContent:window?.DiviSettingsData?.dynamicContent,fonts:window?.DiviSettingsData?.fonts,globalPresets:window?.DiviSettingsData?.globalPresets,google:window?.DiviSettingsData?.google,layout:window?.DiviSettingsData?.layout,markups:window?.DiviSettingsData?.markups,navMenus:window?.DiviSettingsData?.navMenus,nonces:window?.DiviSettingsData?.nonces,post:window?.DiviSettingsData?.post??{status:"draft"},preferences:i,services:window?.DiviSettingsData?.services,settings:window?.DiviSettingsData?.settings,shortcodeModuleDefinitions:window?.DiviSettingsData?.shortcodeModuleDefinitions,shortcodeTags:window?.DiviSettingsData?.shortcodeTags??[],styles:window?.DiviSettingsData?.styles,taxonomy:window?.DiviSettingsData?.taxonomy,themeBuilder:window?.DiviSettingsData?.themeBuilder??{},tinymce:window?.DiviSettingsData?.tinymce,urls:window?.DiviSettingsData?.urls,version:"VB|Divi|5.0"};(0,a.registerStore)("divi/settings",{actions:t,reducer:p,selectors:e,initialState:s()(u)}),(0,c.fetch)({method:"GET",restRoute:"/divi/v1/settings-data/after-app-load"}).then((t=>{(0,o.forEach)(t,((t,e)=>{(0,a.dispatch)("divi/settings").add(e,t)}))})).catch((t=>{console.log("Error",t)}))}}(),(window.divi=window.divi||{}).settings=r}();