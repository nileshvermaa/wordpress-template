/**
 * Live Customizer preview for postMessage settings.
 */
(function () {
	'use strict';
	if (typeof wp === 'undefined' || !wp.customize) { return; }

	wp.customize('lumen_brand_name', function (value) {
		value.bind(function (to) {
			var brand = document.querySelector('.nav-brand');
			if (brand) {
				var accent = brand.querySelector('span');
				brand.childNodes[0].nodeValue = to + ' ';
				if (accent) { brand.appendChild(accent); }
			}
			var heroName = document.querySelector('.hero-footer .name');
			if (heroName) { heroName.textContent = to; }
		});
	});

	wp.customize('lumen_hero_word', function (value) {
		value.bind(function (to) {
			var word = document.querySelector('.hero-word');
			if (!word) { return; }
			var html = '';
			for (var i = 0; i < to.length; i++) {
				var ch = to.charAt(i);
				html += /[aeiou]/i.test(ch) ? '<span class="ink">' + ch + '</span>' : ch;
			}
			word.innerHTML = html;
		});
	});

	wp.customize('lumen_tagline', function (value) {
		value.bind(function (to) {
			var el = document.querySelector('.hero-tagline');
			if (el) { el.textContent = to; }
		});
	});
})();
