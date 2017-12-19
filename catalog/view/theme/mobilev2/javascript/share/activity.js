!function(a) {
	$(a).on("load", function() {
		var b = $("#share-popup"),
			c = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",
			d = "ontouchend" in a ? "touchend" : "click",
			e = "ontouchstart" in a ? "touchstart" : "mousedown",
			f = "ontouchend" in a ? "touchend" : "mouseup",
			g = -1 !== navigator.userAgent.indexOf("Safari") && -1 !== navigator.userAgent.indexOf("iPhone"),
			h = $("#share-qrcode"),
			i = !1,
			j = $(".page" + location.hash);
		$(".spinner-wrapper").remove(), j = 1 === j.length ? j : $(".page#page-intro"), j.addClass("active top").addClass("animated").addClass("fadeIn").one(c, function() {
			$(this).removeClass("animated fadeIn")
		}), $("body").on(d, ".button-next", function() {
			var a, b = $(this),
				d = b.parents(".page"),
				e = d.attr("id"),
				f = "animated slideInUp";
			("page-dinner" === e || g) && (f = "animated fadeIn"), d.hasClass("page-question") && (f += " pause"), "page-dinner" === e ? a = $("#page-result-" + (+new Date % 3 + 1)) : d.hasClass("page-result") ? a = $("#page-share") : "page-share" === e ? (a = $("#page-intro"), location.hash = "") : a = d.next(), d.removeClass("top"), a.addClass("active top").addClass("animated").addClass(f).one(c, function() {
				d.removeClass("active " + f), d.find(".cq-selected").addClass("hidden").removeClass("animated rollIn"), i = !1, a.removeClass(f)
			})
		}).on(d, ".cq-option", function() {
			var a = $(this);
			i !== !0 && a.children(".cq-selected").removeClass("hidden").addClass("animated").addClass("rollIn"), i = !0
		}).on(d, "#share-redirect", function() {
			h.show()
		}).on(d, "#share-share", function() {
			location.hash = "", b.show()
		}).on(d, "#share-popup>.content", function() {
			b.hide()
		}), h.on(d, function() {
			$(this).hide()
		});
		var k, l, m = function(a) {
				a.preventDefault();
				var b = a.changedTouches ? a.changedTouches[0] : a.originalEvent ? a.originalEvent.touches[0] : {};
				return b.timestamp = b.timestamp || +new Date, b.pageY = b.pageY || 0, b
			};
		$(".page-result").on(e, function(a) {
			a = m(a), k = a.timestamp, l = a.pageY
		}).on(f, function(a) {
			a = m(a);
			var b = $(a.target);
			if (1 === b.length && !b.hasClass("button-to-share") && !b.parents(".button-to-share").length) {
				var c = a.timestamp - k,
					e = l - a.pageY;
				(e > 40 || 200 > c && e > 5) && $(this).find(".button-to-share").trigger(d)
			}
		})
	})
}(window);