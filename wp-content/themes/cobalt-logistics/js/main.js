/**
 * Cobalt Logistics theme scripts.
 * Vanilla JS only, no jQuery dependency.
 */
( function () {
	'use strict';

	// Note: the `js-reveal-ready` class used by .reveal / .reveal-onload
	// (see style.css section 11) is added by a tiny inline <script> right
	// after <body> in header.php, not here — this file loads in the footer,
	// which would be late enough to cause a visible flash (content painted
	// visible, then hidden, then animated back in) for the above-the-fold
	// hero stats. The inline version runs before the page paints and even
	// survives this file failing to load at all.

	// Mobile hamburger navigation toggle.
	var navToggle = document.querySelector( '.nav-toggle' );
	var mainNav = document.querySelector( '.main-nav' );

	if ( navToggle && mainNav ) {
		navToggle.addEventListener( 'click', function () {
			var isOpen = mainNav.classList.toggle( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		// Close the mobile menu when a nav link is clicked.
		mainNav.addEventListener( 'click', function ( event ) {
			if ( event.target.tagName === 'A' ) {
				mainNav.classList.remove( 'is-open' );
				navToggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	// FAQ accordion.
	var faqQuestions = document.querySelectorAll( '.faq-question' );

	faqQuestions.forEach( function ( button ) {
		var item = button.closest( '.faq-item' );
		var answer = item ? item.querySelector( '.faq-answer' ) : null;

		if ( ! answer ) {
			return;
		}

		// Start collapsed and hidden from assistive tech until first toggle.
		answer.setAttribute( 'aria-hidden', 'true' );

		button.addEventListener( 'click', function () {
			var isOpen = item.classList.contains( 'is-open' );

			if ( isOpen ) {
				item.classList.remove( 'is-open' );
				button.setAttribute( 'aria-expanded', 'false' );
				answer.setAttribute( 'aria-hidden', 'true' );
				answer.style.maxHeight = null;
			} else {
				item.classList.add( 'is-open' );
				button.setAttribute( 'aria-expanded', 'true' );
				answer.setAttribute( 'aria-hidden', 'false' );
				answer.style.maxHeight = answer.scrollHeight + 'px';
			}
		} );
	} );

	// Shared motion preference checks, reused by the hero blob and card
	// tilt interactions below. Both are purely decorative, so we skip
	// attaching any listeners entirely when the user prefers reduced
	// motion or is on a touch/coarse-pointer device (no hover concept).
	var prefersReducedMotion = !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
	var isCoarsePointer = !! ( window.matchMedia && window.matchMedia( '(pointer: coarse)' ).matches );
	var motionEnabled = ! prefersReducedMotion && ! isCoarsePointer;

	// Hero photo slideshow (HOME only — the markup simply doesn't exist on
	// other pages, so this whole block is a no-op there). Cycle per slide:
	// pop-in (850ms, handled by the .is-active class swap below) -> hold
	// with a slow Ken-Burns drift (.is-drift, 4.5s) -> fade out (.is-exit,
	// 600ms) -> next slide's pop-in. Driven by a chained setTimeout rather
	// than setInterval so a mid-phase pause (tab hidden) can resume with
	// only the phase's *remaining* time rather than restarting the whole
	// cycle or drifting out of sync with the CSS transition durations.
	//
	// Gated on prefers-reduced-motion only (like the route-line dot below,
	// not the coarse-pointer check used for the cursor-follow effects),
	// since this is a self-running background loop rather than a cursor
	// interaction — touch users still see it play. Under reduced motion
	// this whole block is skipped and the timer literally never starts
	// (not just visually suppressed): the first slide's .is-active class
	// is already set server-side in page-home.php, so it simply renders as
	// a static photo, and the CSS reduced-motion backstop (style.css
	// section 13) strips the transitions too as a belt-and-braces measure.
	var heroSlides = document.querySelectorAll( '.hero-slideshow__slide' );

	if ( heroSlides.length && ! prefersReducedMotion ) {
		var HERO_SLIDE_POPIN_MS = 850;
		var HERO_SLIDE_DRIFT_MS = 4500;
		var HERO_SLIDE_EXIT_MS = 600;

		var heroSlideState = {
			index: 0,
			phase: null,
			phaseDuration: 0,
			phaseStart: 0,
			timeoutId: null,
			remaining: null
		};

		// Starts (or restarts) the timer for the given phase. If the tab is
		// hidden at the moment this is called, no timer is scheduled at all
		// — `remaining` is set to the full duration instead, and the
		// visibilitychange listener below picks it up once the tab becomes
		// visible. This covers both a normal mid-cycle pause and the edge
		// case of the page loading in an already-hidden background tab.
		function heroSetSlidePhase( phase, duration ) {
			heroSlideState.phase = phase;
			heroSlideState.phaseDuration = duration;
			heroSlideState.remaining = null;
			clearTimeout( heroSlideState.timeoutId );
			heroSlideState.timeoutId = null;

			if ( document.visibilityState === 'hidden' ) {
				heroSlideState.remaining = duration;
				return;
			}

			heroSlideState.phaseStart = Date.now();
			heroSlideState.timeoutId = setTimeout( heroAdvanceSlidePhase, duration );
		}

		function heroAdvanceSlidePhase() {
			var slide = heroSlides[ heroSlideState.index ];

			if ( heroSlideState.phase === 'popin' ) {
				slide.classList.remove( 'is-active' );
				slide.classList.add( 'is-drift' );
				heroSetSlidePhase( 'drift', HERO_SLIDE_DRIFT_MS );
			} else if ( heroSlideState.phase === 'drift' ) {
				slide.classList.remove( 'is-drift' );
				slide.classList.add( 'is-exit' );
				heroSetSlidePhase( 'exit', HERO_SLIDE_EXIT_MS );
			} else {
				// 'exit' finished: reset this slide back to its idle base
				// state (opacity 0 / scale 0.94, invisible already so the
				// class removal itself is never seen), advance to the next
				// slide in the loop, and kick off its pop-in.
				slide.classList.remove( 'is-exit' );
				heroSlideState.index = ( heroSlideState.index + 1 ) % heroSlides.length;
				heroSlides[ heroSlideState.index ].classList.add( 'is-active' );
				heroSetSlidePhase( 'popin', HERO_SLIDE_POPIN_MS );
			}
		}

		// Pause/resume on tab visibility. Tracing through a hide mid-cycle:
		// say the tab is hidden 2s into the 4.5s drift phase. `hidden`
		// clears the pending timeout and records remaining = 2.5s; no timer
		// is running at all while hidden, however long that lasts. When the
		// tab becomes visible again, `remaining` (2.5s) is used as a fresh
		// phase duration starting from that moment, so the drift phase's
		// *visible* time always adds up to the full 4.5s rather than being
		// cut short or double-counting the hidden time. (The CSS
		// transition itself isn't paused — it runs on wall-clock time
		// regardless of tab visibility — so if the tab is hidden for
		// longer than the remaining transition time, the drift will have
		// silently finished by the time it's shown again; the slide just
		// sits at its fully-drifted size a little longer before the timer
		// above catches up and fades it out. No visible jump either way.)
		document.addEventListener( 'visibilitychange', function () {
			if ( document.visibilityState === 'hidden' ) {
				if ( heroSlideState.timeoutId !== null ) {
					clearTimeout( heroSlideState.timeoutId );
					heroSlideState.timeoutId = null;
					var elapsed = Date.now() - heroSlideState.phaseStart;
					heroSlideState.remaining = Math.max( heroSlideState.phaseDuration - elapsed, 0 );
				}
			} else if ( heroSlideState.timeoutId === null && heroSlideState.remaining !== null ) {
				var resumeDuration = heroSlideState.remaining;
				heroSlideState.remaining = null;
				heroSlideState.phaseDuration = resumeDuration;
				heroSlideState.phaseStart = Date.now();
				heroSlideState.timeoutId = setTimeout( heroAdvanceSlidePhase, resumeDuration );
			}
		} );

		// Slide 0 is already rendered .is-active (opacity 1 / scale 1) by
		// page-home.php, so its pop-in has effectively already happened —
		// jump straight into the drift phase for it instead of re-running
		// the pop-in transition (which would require resetting it to
		// opacity 0 first and would flash the hero empty for a moment).
		heroSlides[ 0 ].classList.remove( 'is-active' );
		heroSlides[ 0 ].classList.add( 'is-drift' );
		heroSetSlidePhase( 'drift', HERO_SLIDE_DRIFT_MS );
	}

	// Cursor-following gradient blobs in the HOME hero. Mouse position is
	// captured on mousemove (throttled to one update per animation frame),
	// but the blobs themselves ease toward that target continuously via a
	// separate rAF loop, so they visibly "lag" behind the cursor rather
	// than snapping to it.
	if ( motionEnabled ) {
		var hero = document.querySelector( '.hero' );
		var heroBlobs = hero ? hero.querySelectorAll( '.hero-blob' ) : [];

		if ( hero && heroBlobs.length ) {
			var heroTargetX = 0;
			var heroTargetY = 0;
			var heroCurrentX = [];
			var heroCurrentY = [];
			var heroBlobStrength = [ 0.05, 0.03 ]; // parallax depth per blob (fraction of pointer offset)
			var heroEasing = 0.08;
			var heroMoveScheduled = false;
			var heroLastEvent = null;
			var heroLoopRunning = false;
			var heroSettleThreshold = 0.05; // px; below this the loop parks itself instead of running forever

			heroBlobs.forEach( function () {
				heroCurrentX.push( 0 );
				heroCurrentY.push( 0 );
			} );

			function updateHeroTarget( event ) {
				var rect = hero.getBoundingClientRect();
				heroTargetX = event.clientX - rect.left - rect.width / 2;
				heroTargetY = event.clientY - rect.top - rect.height / 2;
			}

			function animateHeroBlobs() {
				var stillMoving = false;
				heroBlobs.forEach( function ( blob, index ) {
					var strength = heroBlobStrength[ index ] || heroBlobStrength[ heroBlobStrength.length - 1 ];
					var deltaX = heroTargetX * strength - heroCurrentX[ index ];
					var deltaY = heroTargetY * strength - heroCurrentY[ index ];
					if ( Math.abs( deltaX ) > heroSettleThreshold || Math.abs( deltaY ) > heroSettleThreshold ) {
						stillMoving = true;
					}
					heroCurrentX[ index ] += deltaX * heroEasing;
					heroCurrentY[ index ] += deltaY * heroEasing;
					blob.style.transform =
						'translate3d(calc(-50% + ' + heroCurrentX[ index ].toFixed( 1 ) + 'px), calc(-50% + ' +
						heroCurrentY[ index ].toFixed( 1 ) + 'px), 0)';
				} );

				if ( stillMoving ) {
					requestAnimationFrame( animateHeroBlobs );
				} else {
					// Blobs have settled on their target (mouse stopped, or left the hero
					// and they drifted back to center) — park the rAF loop instead of
					// ticking forever at near-zero deltas. mousemove/mouseleave below
					// restart it on demand.
					heroLoopRunning = false;
				}
			}

			function ensureHeroLoopRunning() {
				if ( ! heroLoopRunning ) {
					heroLoopRunning = true;
					requestAnimationFrame( animateHeroBlobs );
				}
			}

			hero.addEventListener( 'mousemove', function ( event ) {
				heroLastEvent = event;
				if ( ! heroMoveScheduled ) {
					heroMoveScheduled = true;
					requestAnimationFrame( function () {
						if ( heroLastEvent ) {
							updateHeroTarget( heroLastEvent );
							ensureHeroLoopRunning();
						}
						heroMoveScheduled = false;
					} );
				}
			} );

			hero.addEventListener( 'mouseleave', function () {
				heroTargetX = 0;
				heroTargetY = 0;
				ensureHeroLoopRunning();
			} );

			ensureHeroLoopRunning();
		}
	}

	// Subtle tilt micro-interaction on service cards (HOME + サービス概要).
	// Tilt angle is driven by cursor position within the card, throttled
	// to one update per animation frame, and applied via CSS custom
	// properties so it composes with the existing hover lift instead of
	// overwriting it.
	if ( motionEnabled ) {
		var tiltCards = document.querySelectorAll( '.service-card, .service-detail' );
		var maxTilt = 6; // degrees, kept small for a subtle effect

		tiltCards.forEach( function ( card ) {
			var tiltScheduled = false;
			var tiltLastEvent = null;

			function applyTilt() {
				tiltScheduled = false;
				if ( ! tiltLastEvent ) {
					return;
				}
				var rect = card.getBoundingClientRect();
				var relX = ( tiltLastEvent.clientX - rect.left ) / rect.width - 0.5;
				var relY = ( tiltLastEvent.clientY - rect.top ) / rect.height - 0.5;
				card.style.setProperty( '--card-tilt-y', ( relX * maxTilt ).toFixed( 2 ) + 'deg' );
				card.style.setProperty( '--card-tilt-x', ( -relY * maxTilt ).toFixed( 2 ) + 'deg' );
			}

			card.addEventListener( 'mousemove', function ( event ) {
				tiltLastEvent = event;
				if ( ! tiltScheduled ) {
					tiltScheduled = true;
					requestAnimationFrame( applyTilt );
				}
			} );

			card.addEventListener( 'mouseleave', function () {
				tiltLastEvent = null;
				card.style.setProperty( '--card-tilt-x', '0deg' );
				card.style.setProperty( '--card-tilt-y', '0deg' );
			} );
		} );
	}

	// Signature "route line" motif (HOME hero): a small dot travels along
	// the dashed SVG path in a gentle loop. This is a self-running
	// background loop, not a cursor interaction, so it's gated on
	// prefers-reduced-motion only (not the coarse-pointer check used for
	// the cursor-follow effects above) — touch users still see it play.
	// Under reduced motion we simply never start the rAF loop, leaving
	// the dot at the static cx/cy already baked into the SVG markup, so
	// the route line still renders as a dashed path + stationary dot
	// rather than disappearing.
	var routePath = document.getElementById( 'hero-route-path' );
	var routeDot = document.getElementById( 'hero-route-dot' );

	if ( routePath && routeDot && ! prefersReducedMotion ) {
		var routeLength = routePath.getTotalLength();
		var routeDurationMs = 9000; // one full loop along the path
		var routeStartTime = null;

		function animateRouteDot( timestamp ) {
			if ( routeStartTime === null ) {
				routeStartTime = timestamp;
			}
			var elapsed = ( timestamp - routeStartTime ) % routeDurationMs;
			var progress = elapsed / routeDurationMs;
			var point = routePath.getPointAtLength( progress * routeLength );
			routeDot.setAttribute( 'cx', point.x );
			routeDot.setAttribute( 'cy', point.y );
			requestAnimationFrame( animateRouteDot );
		}

		requestAnimationFrame( animateRouteDot );
	}

	// Scroll reveal: the hero manifest stats and the 沿革 timeline items
	// fade + slide in the first time they enter the viewport. Reduced
	// motion is handled primarily in CSS (`.reveal` is already fully
	// visible under that media query), and mirrored here so browsers
	// without IntersectionObserver support get the same "just show it"
	// fallback instead of content stuck invisible.
	var revealTargets = document.querySelectorAll( '.reveal' );

	if ( revealTargets.length ) {
		if ( prefersReducedMotion || ! ( 'IntersectionObserver' in window ) ) {
			revealTargets.forEach( function ( target ) {
				target.classList.add( 'is-visible' );
			} );
		} else {
			var revealObserver = new IntersectionObserver(
				function ( entries, observer ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'is-visible' );
							observer.unobserve( entry.target );
						}
					} );
				},
				{ threshold: 0.15 }
			);

			revealTargets.forEach( function ( target ) {
				revealObserver.observe( target );
			} );
		}
	}

	// HOME contact form: client-side validation in addition to the
	// server-side checks in functions.php. Only blocks submission when a
	// required field is missing/invalid; otherwise the real POST to
	// admin-post.php goes through normally.
	var contactForm = document.getElementById( 'contact-form' );

	if ( contactForm ) {
		var contactFormError = document.getElementById( 'contact-form-error' );
		var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		contactForm.addEventListener( 'submit', function ( event ) {
			var nameField = contactForm.querySelector( '#inquiry-name' );
			var emailField = contactForm.querySelector( '#inquiry-email' );
			var messageField = contactForm.querySelector( '#inquiry-message' );
			var missingLabels = [];

			if ( ! nameField || ! nameField.value.trim() ) {
				missingLabels.push( 'お名前' );
			}
			if ( ! emailField || ! emailPattern.test( emailField.value.trim() ) ) {
				missingLabels.push( 'メールアドレス' );
			}
			if ( ! messageField || ! messageField.value.trim() ) {
				missingLabels.push( 'お問い合わせ内容' );
			}

			if ( missingLabels.length ) {
				event.preventDefault();
				if ( contactFormError ) {
					contactFormError.textContent = '以下の項目をご確認ください：' + missingLabels.join( '、' );
					contactFormError.hidden = false;
				}
			} else if ( contactFormError ) {
				contactFormError.hidden = true;
			}
		} );
	}

	// Pricing simulator (サービス概要 / page-service.php only — these
	// elements simply don't exist on other pages, so this whole block is
	// a no-op there). Recalculates the estimated monthly total on every
	// `input` event from the three controls; there is no submit step.
	var simShipments = document.getElementById( 'sim-shipments' );
	var simStorage = document.getElementById( 'sim-storage' );
	var simProcessing = document.getElementById( 'sim-processing' );

	if ( simShipments && simStorage && simProcessing ) {
		var simShipmentsValue = document.getElementById( 'sim-shipments-value' );
		var simStorageValue = document.getElementById( 'sim-storage-value' );
		var simTotalLow = document.getElementById( 'sim-total-low' );
		var simTotalHigh = document.getElementById( 'sim-total-high' );

		var SIM_PRICE_PER_SHIPMENT = 250;
		var SIM_PRICE_PER_TSUBO = 3000;
		var SIM_PROCESSING_OPTION_PRICE = 50000;
		var SIM_RANGE_SPREAD = 0.15; // display the total as a simple +-15% estimate range

		function formatSimYen( amount ) {
			return Math.round( amount ).toLocaleString( 'ja-JP' );
		}

		function recalcSimulator() {
			var shipments = parseInt( simShipments.value, 10 ) || 0;
			var storage = parseInt( simStorage.value, 10 ) || 0;
			var total = shipments * SIM_PRICE_PER_SHIPMENT + storage * SIM_PRICE_PER_TSUBO;

			if ( simProcessing.checked ) {
				total += SIM_PROCESSING_OPTION_PRICE;
			}

			var low = total * ( 1 - SIM_RANGE_SPREAD );
			var high = total * ( 1 + SIM_RANGE_SPREAD );

			simShipmentsValue.textContent = shipments.toLocaleString( 'ja-JP' ) + '件';
			simStorageValue.textContent = storage.toLocaleString( 'ja-JP' ) + '坪';
			simTotalLow.textContent = formatSimYen( low );
			simTotalHigh.textContent = formatSimYen( high );
		}

		simShipments.addEventListener( 'input', recalcSimulator );
		simStorage.addEventListener( 'input', recalcSimulator );
		simProcessing.addEventListener( 'input', recalcSimulator );

		recalcSimulator();
	}

	// Sticky CTA bar (markup in footer.php, present on every page). Shows
	// once the hero (`.hero` on HOME, `.page-hero` on inner pages) has
	// scrolled out of view, auto-hides again when `.site-footer` comes
	// into view so the two never overlap, and can be dismissed for the
	// rest of the browser session via the close button (sessionStorage).
	// Deliberately IntersectionObserver-only — no scroll/mousemove
	// listeners, per the brief. The bar starts hidden in the markup itself
	// (aria-hidden="true", tabindex="-1" on its link/close button), so if
	// this whole block never runs (no JS, no IntersectionObserver) it just
	// stays hidden rather than appearing stuck open.
	var stickyCta = document.getElementById( 'sticky-cta' );

	if ( stickyCta ) {
		var STICKY_CTA_DISMISS_KEY = 'cobaltStickyCtaDismissed';
		var stickyCtaLink = stickyCta.querySelector( '.sticky-cta__link' );
		var stickyCtaCloseBtn = stickyCta.querySelector( '.sticky-cta__close' );
		var stickyCtaFocusables = [ stickyCtaLink, stickyCtaCloseBtn ].filter( function ( el ) {
			return !! el;
		} );

		var stickyCtaDismissed = false;
		try {
			stickyCtaDismissed = window.sessionStorage.getItem( STICKY_CTA_DISMISS_KEY ) === '1';
		} catch ( e ) {
			// sessionStorage can throw (private browsing / disabled storage) —
			// fail open, i.e. treat as "not dismissed" for this page view.
			stickyCtaDismissed = false;
		}

		var stickyCtaHeroPassed = false;
		var stickyCtaFooterNear = false;

		function setStickyCtaVisible( visible ) {
			if ( ! visible ) {
				// A keyboard user may have tabbed to the link/close button while
				// the bar was visible, then triggered a hide (e.g. scrolling on
				// toward the footer). Applying aria-hidden to an ancestor of the
				// still-focused element is an ARIA violation browsers react to
				// unpredictably (Chrome force-blurs to <body> with a console
				// warning) — so explicitly move focus out first.
				if ( stickyCta.contains( document.activeElement ) ) {
					document.activeElement.blur();
				}
			}
			stickyCta.classList.toggle( 'is-visible', visible );
			stickyCta.setAttribute( 'aria-hidden', visible ? 'false' : 'true' );
			stickyCtaFocusables.forEach( function ( el ) {
				if ( visible ) {
					el.removeAttribute( 'tabindex' );
				} else {
					el.setAttribute( 'tabindex', '-1' );
				}
			} );
		}

		function updateStickyCtaVisibility() {
			if ( stickyCtaDismissed ) {
				setStickyCtaVisible( false );
				return;
			}
			setStickyCtaVisible( stickyCtaHeroPassed && ! stickyCtaFooterNear );
		}

		if ( stickyCtaCloseBtn ) {
			stickyCtaCloseBtn.addEventListener( 'click', function () {
				stickyCtaDismissed = true;
				try {
					window.sessionStorage.setItem( STICKY_CTA_DISMISS_KEY, '1' );
				} catch ( e ) {
					// Ignore write failures — the bar still hides for the rest
					// of this page view either way, it just won't stay hidden
					// after a reload.
				}
				updateStickyCtaVisibility();
			} );
		}

		if ( ! stickyCtaDismissed && 'IntersectionObserver' in window ) {
			var stickyCtaHeroTarget = document.querySelector( '.hero, .page-hero' );

			if ( stickyCtaHeroTarget ) {
				var stickyCtaHeroObserver = new IntersectionObserver(
					function ( entries ) {
						entries.forEach( function ( entry ) {
							stickyCtaHeroPassed = ! entry.isIntersecting;
							updateStickyCtaVisibility();
						} );
					},
					{ threshold: 0 }
				);
				stickyCtaHeroObserver.observe( stickyCtaHeroTarget );
			}

			var stickyCtaFooterTarget = document.querySelector( '.site-footer' );

			if ( stickyCtaFooterTarget ) {
				var stickyCtaFooterObserver = new IntersectionObserver(
					function ( entries ) {
						entries.forEach( function ( entry ) {
							stickyCtaFooterNear = entry.isIntersecting;
							updateStickyCtaVisibility();
						} );
					},
					{ threshold: 0 }
				);
				stickyCtaFooterObserver.observe( stickyCtaFooterTarget );
			}
		}
	}
} )();
