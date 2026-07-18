<?php
/**
 * Single template for the `column_article` custom post type
 * (NEWS_COLUMN_CPT_MIGRATION_BRIEF.md #9). Always links back to the
 * コラム listing page — no category branching needed (that's the whole
 * point of splitting news/column into their own post types instead of
 * relying on a `post` category that could be left unchecked).
 *
 * @package Cobalt_Logistics
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<main id="main">

		<section class="page-hero">
			<div class="container">
				<p class="eyebrow">COLUMN</p>
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
					<a href="<?php echo esc_url( cobalt_logistics_page_url( 'column' ) ); ?>">&larr; コラム一覧に戻る</a>
				</p>

			</div>
		</section>

	</main>

	<?php
endwhile;

get_footer();
