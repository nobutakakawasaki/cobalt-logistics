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
} )();
