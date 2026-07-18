<?php
/**
 * Template Name: お知らせ
 *
 * Listing page for the `news_article` custom post type (see
 * NEWS_COLUMN_CPT_MIGRATION_BRIEF.md — replaces the original `post` +
 * `news` category query from NEWS_COLUMN_BRIEF.md). Cards show
 * date + title + excerpt + "続きを読む" link, styled to match the existing
 * .job-card look.
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">NEWS</p>
			<h1 class="page-hero__title">お知らせ</h1>
			<p class="page-hero__lead">コバルト物流からの最新のお知らせをまとめてご覧いただけます。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php
			$cobalt_news_query = new WP_Query(
				array(
					'post_type'      => 'news_article',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			?>
			<?php if ( $cobalt_news_query->have_posts() ) : ?>
				<div class="article-list">
					<?php
					while ( $cobalt_news_query->have_posts() ) :
						$cobalt_news_query->the_post();
						?>
						<a class="article-card-link" href="<?php the_permalink(); ?>">
							<div class="article-card">
								<p class="article-card__date font-mono"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
								<h2 class="article-card__title"><?php the_title(); ?></h2>
								<p class="article-card__excerpt"><?php echo esc_html( cobalt_logistics_article_excerpt( get_the_ID() ) ); ?></p>
								<span class="article-card__more">続きを読む &rarr;</span>
							</div>
						</a>
						<?php
					endwhile;
					?>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p class="article-list__empty">現在お知らせはありません。</p>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php
get_footer();
