/* Förderverein Biedensand Bäder – Theme-Skript (Navigation, Kopfbild-Slideshow) */
(function () {
	'use strict';
	var d = document, body = d.body;
	var t = window.fvbbTheme || { menuOpen: 'Menü öffnen', menuClose: 'Menü schließen', subOpen: 'Untermenü öffnen' };
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var chevron = '<svg class="fvbb-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>';

	/* ---------- Navigation ---------- */
	var nav = d.getElementById('primary-nav');
	var toggle = d.querySelector('.nav-toggle');
	var mq = window.matchMedia('(max-width: 1100px)');

	function setOpen(open) {
		body.classList.toggle('nav-open', open);
		if (toggle) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			var l = toggle.querySelector('.nav-toggle__label');
			if (l) { l.textContent = open ? t.menuClose : t.menuOpen; }
		}
	}
	if (toggle) {
		toggle.addEventListener('click', function () { setOpen(!body.classList.contains('nav-open')); });
	}
	d.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			if (body.classList.contains('nav-open')) { setOpen(false); toggle && toggle.focus(); }
			if (nav) { nav.querySelectorAll('li.is-open').forEach(function (li) { li.classList.remove('is-open'); }); }
		}
	});
	mq.addEventListener && mq.addEventListener('change', function () { setOpen(false); });

	if (nav) {
		nav.querySelectorAll('li.menu-item-has-children, li.page_item_has_children').forEach(function (li) {
			var link = li.querySelector(':scope > a');
			var sub = li.querySelector(':scope > ul');
			if (!link || !sub) { return; }
			var row = d.createElement('div');
			row.className = 'menu-row';
			li.insertBefore(row, link);
			row.appendChild(link);
			var btn = d.createElement('button');
			btn.type = 'button';
			btn.className = 'sub-toggle';
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-label', t.subOpen + ': ' + link.textContent.trim());
			btn.innerHTML = chevron;
			row.appendChild(btn);

			function flip(e) {
				e.preventDefault();
				var open = !li.classList.contains('is-open');
				// Geschwister schließen
				Array.prototype.forEach.call(li.parentNode.children, function (s) {
					if (s !== li) { s.classList.remove('is-open'); var b = s.querySelector(':scope > .menu-row > .sub-toggle'); b && b.setAttribute('aria-expanded', 'false'); }
				});
				li.classList.toggle('is-open', open);
				btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			}
			btn.addEventListener('click', flip);
			// Platzhalter-Links ("#") öffnen nur das Untermenü
			var href = link.getAttribute('href');
			if (!href || href === '#') { link.addEventListener('click', flip); link.setAttribute('role', 'button'); }
		});
		// Klick außerhalb schließt Desktop-Untermenüs
		d.addEventListener('click', function (e) {
			if (!nav.contains(e.target)) {
				nav.querySelectorAll('li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
			}
		});
		nav.querySelectorAll('a[href*="#"]').forEach(function (a) {
			if (a.getAttribute('href') !== '#') { a.addEventListener('click', function () { setOpen(false); }); }
		});
	}

	/* ---------- Kopfbild-Slideshow ---------- */
	d.querySelectorAll('.hero-media[data-slideshow="1"]').forEach(function (box) {
		var imgs = box.querySelectorAll('.hero-media__img');
		if (imgs.length < 2 || reduce) { return; }
		var i = 0;
		imgs.forEach(function (img) { img.loading = 'eager'; });
		setInterval(function () {
			if (d.hidden) { return; }
			var next = (i + 1) % imgs.length;
			if (!imgs[next].complete) { return; }
			imgs[i].classList.remove('is-active');
			imgs[next].classList.add('is-active');
			i = next;
		}, 6000);
	});

	/* ---------- Kennzahlen hochzählen ---------- */
	var stats = d.querySelectorAll('.is-style-fvbb-stat');
	if (stats.length && 'IntersectionObserver' in window && !reduce) {
		var fmt = new Intl.NumberFormat('de-DE');
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) { return; }
				io.unobserve(en.target);
				var el = en.target, txt = el.textContent;
				var m = txt.match(/^(\D*?)(\d[\d.,\s]*)(.*)$/);
				if (!m) { return; }
				var target = parseInt(m[2].replace(/\D/g, ''), 10);
				if (!target || target < 5) { return; }
				var start = null, dur = Math.min(2200, 900 + target);
				el.style.minWidth = el.offsetWidth + 'px';
				function step(ts) {
					if (!start) { start = ts; }
					var p = Math.min(1, (ts - start) / dur), e = 1 - Math.pow(1 - p, 3);
					el.textContent = m[1] + fmt.format(Math.round(target * e)) + m[3];
					if (p < 1) { requestAnimationFrame(step); } else { el.textContent = txt; }
				}
				requestAnimationFrame(step);
			});
		}, { threshold: .5 });
		stats.forEach(function (s) { io.observe(s); });
	}

	/* ---------- Kopfzeile beim Scrollen ---------- */
	var header = d.getElementById('site-header');
	if (header) {
		var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* ---------- Tabellen: Gruppenzeilen erkennen (nur erste Zelle gefüllt) ---------- */
	d.querySelectorAll('.wp-block-table tbody tr').forEach(function (tr) {
		var cells = tr.children;
		if (cells.length < 3) { return; }
		var filled = 0;
		for (var k = 0; k < cells.length; k++) { if (cells[k].textContent.trim() !== '') { filled++; } }
		if (filled === 1 && cells[0].textContent.trim() !== '') { tr.classList.add('is-group-row'); }
	});
})();
