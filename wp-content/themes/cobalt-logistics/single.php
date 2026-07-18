<?php
/**
 * Single template for standard posts (post_type=post) — shared by the
 * `news` and `column` categories (see NEWS_COLUMN_BRIEF.md). Which listing
 * page the "戻る" link points back to, and which eyebrow label is shown, is
 * decided per-post from its category, not hardcoded to one or the other.
 *
 * @package Cobalt_Logistics
 */

get_header();

while ( have_posts() ) :
	the_post();

	$cobalt_is_column = has_category( 'column' );
	$cobalt_eyebrow    = $cobalt_is_column ? 'COLUMN' : 'NEWS';
	$cobalt_back_url   = $cobalt_is_column ? cobalt_logistics_page_url( 'column' ) : cobalt_logistics_page_url( 'news' );
	$cobalt_back_label = $cobalt_is_column ? 'コラム一覧に戻る' : 'お知らせ一覧に戻る';
	?>

	<main id="main">

		<section class="page-hero">
			<div class="container">
				<p class="eyebrow"><?php echo esc_html( $cobalt_eyebrow ); ?></p>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<p class="article-body__date"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
			</div>
		</section>

		<section class="section">
			<div class="container article-body">

				<div class="article-content">
					<?php the_content(); ?>
				</div>

				<p class="article-body__back">
					<a href="<?php echo esc_url( $cobalt_back_url ); ?>">&larr; <?php echo esc_html( $cobalt_back_label ); ?></a>
				</p>

			</div>
		</section>

	</main>

	<?php
endwhile;

get_footer();
