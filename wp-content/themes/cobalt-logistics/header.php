<?php
/**
 * The header for the Cobalt Logistics theme.
 *
 * @package Cobalt_Logistics
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<script>document.documentElement.classList.add( 'js-reveal-ready' );</script>

<header class="site-header">
	<div class="site-header__inner">
		<a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="site-branding__mark" aria-hidden="true"></span>
			<span class="site-branding__text"><?php bloginfo( 'name' ); ?></span>
		</a>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="メニューを開閉する">
			<span class="nav-toggle__bar"></span>
		</button>

		<nav class="main-nav" id="primary-menu" aria-label="プライマリメニュー">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'main-nav__list',
						'depth'          => 1,
					)
				);
			} else {
				cobalt_logistics_fallback_menu();
			}
			?>
		</nav>
	</div>
</header>
