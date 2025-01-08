!function(){var t={33042:function(t,e,r){var n;!function(){"use strict";var i=function t(e){var r,n="function"==typeof Symbol&&Symbol.for&&Symbol.for("react.element"),i={use_static:!1};function o(t){var e=Object.getPrototypeOf(t);return e?Object.create(e):{}}function s(t,e,r){Object.defineProperty(t,e,{enumerable:!1,configurable:!1,writable:!1,value:r})}function a(t,e){s(t,e,(function(){throw new d("The "+e+" method cannot be invoked on an Immutable data structure.")}))}"object"!=typeof(r=e)||Array.isArray(r)||null===r||void 0!==e.use_static&&(i.use_static=Boolean(e.use_static));var c="__immutable_invariants_hold";function u(t){return"object"!=typeof t||(null===t||Boolean(Object.getOwnPropertyDescriptor(t,c)))}function f(t,e){return t===e||t!=t&&e!=e}function l(t){return!(null===t||"object"!=typeof t||Array.isArray(t)||t instanceof Date)}var p=["setPrototypeOf"],h=p.concat(["push","pop","sort","splice","shift","unshift","reverse"]),y=["keys"].concat(["map","filter","slice","concat","reduce","reduceRight"]),v=p.concat(["setDate","setFullYear","setHours","setMilliseconds","setMinutes","setMonth","setSeconds","setTime","setUTCDate","setUTCFullYear","setUTCHours","setUTCMilliseconds","setUTCMinutes","setUTCMonth","setUTCSeconds","setYear"]);function d(t){this.name="MyError",this.message=t,this.stack=(new Error).stack}function b(t,e){for(var r in s(t,c,!0),e)e.hasOwnProperty(r)&&a(t,e[r]);return Object.freeze(t),t}function g(t,e){var r=t[e];s(t,e,(function(){return J(r.apply(t,arguments))}))}function m(t,e,r){var n=r&&r.deep;if(t in this&&(n&&this[t]!==e&&l(e)&&l(this[t])&&(e=J.merge(this[t],e,{deep:!0,mode:"replace"})),f(this[t],e)))return this;var i=T.call(this);return i[t]=J(e),S(i)}d.prototype=new Error,d.prototype.constructor=Error;var w=J([]);function O(t,e,r){var n=t[0];if(1===t.length)return m.call(this,n,e,r);var i,o=t.slice(1),s=this[n];if("object"==typeof s&&null!==s)i=J.setIn(s,o,e);else{var a=o[0];i=""!==a&&isFinite(a)?O.call(w,o,e):x.call(U,o,e)}if(n in this&&s===i)return this;var c=T.call(this);return c[n]=i,S(c)}function S(t){for(var e in y){if(y.hasOwnProperty(e))g(t,y[e])}i.use_static||(s(t,"flatMap",j),s(t,"asObject",_),s(t,"asMutable",T),s(t,"set",m),s(t,"setIn",O),s(t,"update",F),s(t,"updateIn",V),s(t,"getIn",$));for(var r=0,n=t.length;r<n;r++)t[r]=J(t[r]);return b(t,h)}function P(){return new Date(this.getTime())}function j(t){if(0===arguments.length)return this;var e,r=[],n=this.length;for(e=0;e<n;e++){var i=t(this[e],e,this);Array.isArray(i)?r.push.apply(r,i):r.push(i)}return S(r)}function I(t){if(void 0===t&&0===arguments.length)return this;if("function"!=typeof t){var e=Array.isArray(t)?t.slice():Array.prototype.slice.call(arguments);e.forEach((function(t,e,r){"number"==typeof t&&(r[e]=t.toString())})),t=function(t,r){return-1!==e.indexOf(r)}}var r=o(this);for(var n in this)this.hasOwnProperty(n)&&!1===t(this[n],n)&&(r[n]=this[n]);return z(r)}function T(t){var e,r,n=[];if(t&&t.deep)for(e=0,r=this.length;e<r;e++)n.push(A(this[e]));else for(e=0,r=this.length;e<r;e++)n.push(this[e]);return n}function _(t){"function"!=typeof t&&(t=function(t){return t});var e,r={},n=this.length;for(e=0;e<n;e++){var i=t(this[e],e,this),o=i[0],s=i[1];r[o]=s}return z(r)}function A(t){return!t||"object"!=typeof t||!Object.getOwnPropertyDescriptor(t,c)||t instanceof Date?t:J.asMutable(t,{deep:!0})}function E(t,e){for(var r in t)Object.getOwnPropertyDescriptor(t,r)&&(e[r]=t[r]);return e}function M(t,e){if(0===arguments.length)return this;if(null===t||"object"!=typeof t)throw new TypeError("Immutable#merge can only be invoked with objects or arrays, not "+JSON.stringify(t));var r,n,i=Array.isArray(t),s=e&&e.deep,a=e&&e.mode||"merge",c=e&&e.merger;function u(t,n,i){var a,u=J(n[i]),p=c&&c(t[i],u,e),h=t[i];void 0===r&&void 0===p&&t.hasOwnProperty(i)&&f(u,h)||(f(h,a=void 0!==p?p:s&&l(h)&&l(u)?J.merge(h,u,e):u)&&t.hasOwnProperty(i)||(void 0===r&&(r=E(t,o(t))),r[i]=a))}function p(t,e){for(var n in t)e.hasOwnProperty(n)||(void 0===r&&(r=E(t,o(t))),delete r[n])}if(i)for(var h=0,y=t.length;h<y;h++){var v=t[h];for(n in v)v.hasOwnProperty(n)&&u(void 0!==r?r:this,v,n)}else{for(n in t)Object.getOwnPropertyDescriptor(t,n)&&u(this,t,n);"replace"===a&&p(this,t)}return void 0===r?this:z(r)}function C(t,e){var r=e&&e.deep;if(0===arguments.length)return this;if(null===t||"object"!=typeof t)throw new TypeError("Immutable#replace can only be invoked with objects or arrays, not "+JSON.stringify(t));return J.merge(this,t,{deep:r,mode:"replace"})}var D,R,k,U=J({});function x(t,e,r){if(!Array.isArray(t)||0===t.length)throw new TypeError('The first argument to Immutable#setIn must be an array containing at least one "key" string.');var n=t[0];if(1===t.length)return N.call(this,n,e,r);var i,s=t.slice(1),a=this[n];if(i=this.hasOwnProperty(n)&&"object"==typeof a&&null!==a?J.setIn(a,s,e):x.call(U,s,e),this.hasOwnProperty(n)&&a===i)return this;var c=E(this,o(this));return c[n]=i,z(c)}function N(t,e,r){var n=r&&r.deep;if(this.hasOwnProperty(t)&&(n&&this[t]!==e&&l(e)&&l(this[t])&&(e=J.merge(this[t],e,{deep:!0,mode:"replace"})),f(this[t],e)))return this;var i=E(this,o(this));return i[t]=J(e),z(i)}function F(t,e){var r=Array.prototype.slice.call(arguments,2),n=this[t];return J.set(this,t,e.apply(n,[n].concat(r)))}function B(t,e){for(var r=0,n=e.length;null!=t&&r<n;r++)t=t[e[r]];return r&&r==n?t:void 0}function V(t,e){var r=Array.prototype.slice.call(arguments,2),n=B(this,t);return J.setIn(this,t,e.apply(n,[n].concat(r)))}function $(t,e){var r=B(this,t);return void 0===r?e:r}function H(t){var e,r=o(this);if(t&&t.deep)for(e in this)this.hasOwnProperty(e)&&(r[e]=A(this[e]));else for(e in this)this.hasOwnProperty(e)&&(r[e]=this[e]);return r}function Y(){return{}}function z(t){return i.use_static||(s(t,"merge",M),s(t,"replace",C),s(t,"without",I),s(t,"asMutable",H),s(t,"set",N),s(t,"setIn",x),s(t,"update",F),s(t,"updateIn",V),s(t,"getIn",$)),b(t,p)}function J(t,e,r){if(u(t)||function(t){return"object"==typeof t&&null!==t&&(60103===t.$$typeof||t.$$typeof===n)}(t)||function(t){return"undefined"!=typeof File&&t instanceof File}(t)||function(t){return"undefined"!=typeof Blob&&t instanceof Blob}(t)||function(t){return t instanceof Error}(t))return t;if(function(t){return"object"==typeof t&&"function"==typeof t.then}(t))return t.then(J);if(Array.isArray(t))return S(t.slice());if(t instanceof Date)return o=new Date(t.getTime()),i.use_static||s(o,"asMutable",P),b(o,v);var o,a=e&&e.prototype,c=(a&&a!==Object.prototype?function(){return Object.create(a)}:Y)();if(null==r&&(r=64),r<=0)throw new d("Attempt to construct Immutable from a deeply nested object was detected. Have you tried to wrap an object with circular references (e.g. React element)? See https://github.com/rtfeldman/seamless-immutable/wiki/Deeply-nested-object-was-detected for details.");for(var f in r-=1,t)Object.getOwnPropertyDescriptor(t,f)&&(c[f]=J(t[f],void 0,r));return z(c)}function q(t){return function(){var e=[].slice.call(arguments),r=e.shift();return t.apply(r,e)}}function G(t,e){return function(){var r=[].slice.call(arguments),n=r.shift();return Array.isArray(n)?e.apply(n,r):t.apply(n,r)}}return J.from=J,J.isImmutable=u,J.ImmutableError=d,J.merge=q(M),J.replace=q(C),J.without=q(I),J.asMutable=(D=H,R=T,k=P,function(){var t=[].slice.call(arguments),e=t.shift();return Array.isArray(e)?R.apply(e,t):e instanceof Date?k.apply(e,t):D.apply(e,t)}),J.set=G(N,m),J.setIn=G(x,O),J.update=q(F),J.updateIn=q(V),J.getIn=q($),J.flatMap=q(j),J.asObject=q(_),i.use_static||(J.static=t({use_static:!0})),Object.freeze(J),J}();void 0===(n=function(){return i}.call(e,r,e,t))||(t.exports=n)}()}},e={};function r(n){var i=e[n];if(void 0!==i)return i.exports;var o=e[n]={exports:{}};return t[n](o,o.exports,r),o.exports}r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,{a:e}),e},r.d=function(t,e){for(var n in e)r.o(e,n)&&!r.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})};var n={};!function(){"use strict";r.r(n),r.d(n,{registerSpamProtectionStore:function(){return h}});var t={};r.r(t),r.d(t,{setService:function(){return c},setServices:function(){return u}});var e={};r.r(e),r.d(e,{getService:function(){return l},getServices:function(){return p}});var i=r(33042),o=r.n(i),s=window.divi.data,a=window.divi.settings;const c=({provider:t,service:e})=>({type:"SET_SPAM_PROTECTION_SERVICE",provider:t,service:e}),u=({services:t})=>({type:"SET_SPAM_PROTECTION_SERVICES",services:t}),f=(t,e)=>{switch(e.type){case"SET_SPAM_PROTECTION_SERVICE":return t.setIn(["services",e.provider],e.service);case"SET_SPAM_PROTECTION_SERVICES":return t.setIn(["services"],e.services);default:return t}},l=(t,e)=>t.getIn(["services",e]),p=t=>t.getIn(["services"]),h=()=>{if((0,s.select)("divi/settings")||(0,a.registerSettingsStore)(),(0,s.select)("divi/spam-protection"))return;const r=o()({services:(0,s.select)("divi/settings").getSetting(["services","spamProtection"])});(0,s.registerStore)("divi/spam-protection",{actions:t,reducer:f,selectors:e,initialState:r})}}(),(window.divi=window.divi||{}).spamProtection=n}();