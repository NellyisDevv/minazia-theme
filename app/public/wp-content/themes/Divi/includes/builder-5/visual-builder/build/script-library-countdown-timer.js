!function(){var e;(e=jQuery)(window).on("et_pb_init_modules",(function(){window.et_pb_init_countdown_timer_modules=function(){const t=e(".et_pb_countdown_timer"),n="object"==typeof window.ET_Builder;e(window).resize((function(){t.length&&t.each((function(){const t=e(this);et_countdown_timer_labels(t)}))})),window.et_countdown_timer=function(e){let t=parseInt(e.attr("data-end-timestamp"))-(new Date).getTime()/1e3,n=parseInt(t/86400);n=n>0?n:0,t%=86400;let s=parseInt(t/3600);s=s>0?s:0,t%=3600;let o=parseInt(t/60);o=o>0?o:0;let i=parseInt(t%60);i=i>0?i:0;const a=e.find(".days > .value").parent(".section"),l=e.find(".hours > .value").parent(".section"),d=e.find(".minutes > .value").parent(".section"),r=e.find(".seconds > .value").parent(".section");if(0==n)a.hasClass("zero")||e.find(".days > .value").html("000").parent(".section").addClass("zero").next().addClass("zero");else{const t=n.toString().length>=3?n.toString().length:3;e.find(".days > .value").html(`000${n}`.slice(-t)),a.hasClass("zero")&&a.removeClass("zero").next().removeClass("zero")}0===n&&0===s?l.hasClass("zero")||e.find(".hours > .value").html("00").parent(".section").addClass("zero").next().addClass("zero"):(e.find(".hours > .value").html(`0${s}`.slice(-2)),l.hasClass("zero")&&l.removeClass("zero").next().removeClass("zero")),0===n&&0===s&&0===o?d.hasClass("zero")||e.find(".minutes > .value").html("00").parent(".section").addClass("zero").next().addClass("zero"):(e.find(".minutes > .value").html(`0${o}`.slice(-2)),d.hasClass("zero")&&d.removeClass("zero").next().removeClass("zero")),0===n&&0===s&&0===o&&0===i?r.hasClass("zero")||e.find(".seconds > .value").html("00").parent(".section").addClass("zero"):(e.find(".seconds > .value").html(`0${i}`.slice(-2)),r.hasClass("zero")&&r.removeClass("zero").next().removeClass("zero"))},window.et_countdown_timer_labels=function(e){e.closest(".et_pb_column_3_8").length||e.closest(".et_pb_column_1_4").length||e.children(".et_pb_countdown_timer_container").width()<=400?(e.find(".days .label").html(e.find(".days").data("short")),e.find(".hours .label").html(e.find(".hours").data("short")),e.find(".minutes .label").html(e.find(".minutes").data("short")),e.find(".seconds .label").html(e.find(".seconds").data("short"))):(e.find(".days .label").html(e.find(".days").data("full")),e.find(".hours .label").html(e.find(".hours").data("full")),e.find(".minutes .label").html(e.find(".minutes").data("full")),e.find(".seconds .label").html(e.find(".seconds").data("full")))},(t.length||n)&&(window.et_pb_countdown_timer_init=function(t){t.each((function(){const t=e(this);et_countdown_timer_labels(t),et_countdown_timer(t),setInterval((()=>{et_countdown_timer(t)}),1e3)}))},et_pb_countdown_timer_init(t))},window.et_pb_init_countdown_timer_modules()})),((window.divi=window.divi||{}).scriptLibrary=window.divi.scriptLibrary||{}).scriptLibraryCountdownTimer={}}();