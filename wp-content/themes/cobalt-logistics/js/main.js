/**
 * Cobalt Logistics theme scripts.
 * Vanilla JS only, no jQuery dependency.
 */
( function () {
	'use strict';

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
} )();
