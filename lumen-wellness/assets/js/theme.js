/**
 * Lumen Wellness — front-end behaviour.
 * Vanilla JS, no dependencies. Loaded in the footer.
 */
(function () {
	'use strict';

	/* ── Reveal on scroll ─────────────────────────────────── */
	var reveals = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window && reveals.length) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-in');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
		reveals.forEach(function (el) { io.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-in'); });
	}

	/* ── Sticky nav background after scroll ───────────────── */
	var nav = document.getElementById('site-nav');
	if (nav) {
		var onScroll = function () {
			if (window.scrollY > 40) {
				nav.classList.add('scrolled');
			} else {
				nav.classList.remove('scrolled');
			}
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* ── Mobile menu toggle ───────────────────────────────── */
	var toggle = document.getElementById('nav-toggle');
	var mobileMenu = document.getElementById('mobile-menu');
	if (toggle && mobileMenu) {
		var siteNav = document.getElementById('site-nav');
		var setMenu = function (open) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.classList.toggle('is-open', open);
			if (siteNav) { siteNav.classList.toggle('menu-open', open); }
			if (open) {
				mobileMenu.hidden = false;
				// next frame so the transition runs
				requestAnimationFrame(function () { mobileMenu.classList.add('is-open'); });
			} else {
				mobileMenu.classList.remove('is-open');
				window.setTimeout(function () { mobileMenu.hidden = true; }, 280);
			}
		};
		toggle.addEventListener('click', function () {
			setMenu(toggle.getAttribute('aria-expanded') !== 'true');
		});
		// Close when a link inside is tapped.
		mobileMenu.querySelectorAll('a').forEach(function (a) {
			a.addEventListener('click', function () { setMenu(false); });
		});
		// Close if resized up to desktop.
		window.addEventListener('resize', function () {
			if (window.innerWidth >= 900 && toggle.getAttribute('aria-expanded') === 'true') {
				setMenu(false);
			}
		});
		// Close on Escape.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				setMenu(false);
				toggle.focus();
			}
		});
	}

	/* ── Smooth-scroll for in-page anchors ────────────────── */
	document.querySelectorAll('a[href^="#"]').forEach(function (link) {
		link.addEventListener('click', function (e) {
			var id = link.getAttribute('href');
			if (id.length < 2) { return; }
			var target = document.querySelector(id);
			if (target) {
				e.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});
	});

	/* ── Contact form (AJAX) ──────────────────────────────── */
	var form = document.getElementById('lumen-contact-form');
	if (form && typeof LUMEN !== 'undefined') {
		var status = form.querySelector('.lumen-form-status');
		var setStatus = function (msg, ok) {
			if (!status) { return; }
			status.textContent = msg;
			status.style.color = ok ? 'var(--color-accent-deep)' : '#c0392b';
		};

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var button = form.querySelector('button[type="submit"]');
			var original = button ? button.textContent : '';
			if (button) { button.disabled = true; button.textContent = 'Sending…'; }
			setStatus('', true);

			var data = new FormData(form);
			data.append('action', 'lumen_contact');
			data.append('nonce', LUMEN.nonce);

			fetch(LUMEN.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
				.then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
				.then(function (res) {
					if (res.body && res.body.success) {
						setStatus(res.body.data.message, true);
						form.reset();
					} else {
						var m = res.body && res.body.data ? res.body.data.message : 'Something went wrong.';
						setStatus(m, false);
					}
				})
				.catch(function () {
					setStatus('Network error — please email me directly.', false);
				})
				.finally(function () {
					if (button) { button.disabled = false; button.textContent = original; }
				});
		});
	}
})();
