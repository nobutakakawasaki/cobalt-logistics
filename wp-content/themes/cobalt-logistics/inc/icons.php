<?php
/**
 * Inline SVG icon helper.
 *
 * All icons are simple line-style SVGs using currentColor so they inherit
 * the surrounding element's text color. No external image assets are used.
 *
 * @package Cobalt_Logistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Echo an inline SVG icon by name.
 *
 * @param string $name Icon name.
 */
function cobalt_logistics_icon( $name ) {
	$common = 'fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true" focusable="false"';

	$icons = array(
		// Building / companies count.
		'building'  => '<svg ' . $common . '><rect x="4" y="3" width="10" height="18"></rect><rect x="14" y="9" width="6" height="12"></rect><line x1="7" y1="7" x2="7" y2="7.01"></line><line x1="11" y1="7" x2="11" y2="7.01"></line><line x1="7" y1="11" x2="7" y2="11.01"></line><line x1="11" y1="11" x2="11" y2="11.01"></line><line x1="7" y1="15" x2="7" y2="15.01"></line><line x1="11" y1="15" x2="11" y2="15.01"></line></svg>',
		// Target / accuracy.
		'target'    => '<svg ' . $common . '><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1"></circle></svg>',
		// Warehouse area.
		'area'      => '<svg ' . $common . '><path d="M3 10.5 12 4l9 6.5"></path><path d="M5 9.5V20h14V9.5"></path><path d="M9 20v-6h6v6"></path></svg>',
		// Clock / years in operation.
		'clock'     => '<svg ' . $common . '><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3.5 2"></path></svg>',
		// EC / delivery box.
		'box'       => '<svg ' . $common . '><path d="M3 8 12 4l9 4-9 4-9-4Z"></path><path d="M3 8v9l9 4 9-4V8"></path><path d="M12 12v9"></path></svg>',
		// Warehouse shelving / inventory.
		'shelf'     => '<svg ' . $common . '><rect x="4" y="4" width="16" height="16"></rect><line x1="4" y1="10" x2="20" y2="10"></line><line x1="4" y1="16" x2="20" y2="16"></line><line x1="9" y1="4" x2="9" y2="20"></line></svg>',
		// Truck / delivery arrangement.
		'truck'     => '<svg ' . $common . '><rect x="1" y="7" width="13" height="10"></rect><path d="M14 10h4l3 3v4h-7z"></path><circle cx="6" cy="19" r="1.6"></circle><circle cx="17" cy="19" r="1.6"></circle></svg>',
		// Packaging / logistics processing.
		'package'   => '<svg ' . $common . '><rect x="4" y="4" width="16" height="16" rx="1"></rect><path d="M4 9h16"></path><path d="M9 4v16"></path></svg>',
		// Checkmark for perks / benefits.
		'check'     => '<svg ' . $common . '><circle cx="12" cy="12" r="9"></circle><path d="m8 12.5 2.5 2.5L16 9.5"></path></svg>',
		// Location pin.
		'pin'       => '<svg ' . $common . '><path d="M12 21s7-6.6 7-12a7 7 0 1 0-14 0c0 5.4 7 12 7 12Z"></path><circle cx="12" cy="9" r="2.5"></circle></svg>',
		// Shield / security.
		'shield'    => '<svg ' . $common . '><path d="M12 3l7 3v6c0 5-3.2 8.4-7 9-3.8-.6-7-4-7-9V6l7-3Z"></path><path d="m9 12 2 2 4-4"></path></svg>',
		// Thermometer / temperature control.
		'thermo'    => '<svg ' . $common . '><path d="M12 3a2 2 0 0 0-2 2v9.3a4 4 0 1 0 4 0V5a2 2 0 0 0-2-2Z"></path><line x1="12" y1="9" x2="12" y2="15"></line></svg>',
	);

	if ( isset( $icons[ $name ] ) ) {
		echo $icons[ $name ]; // phpcs:ignore -- trusted, hardcoded inline SVG markup only.
	}
}
