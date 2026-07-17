<?php
/**
 * Fallback template.
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<h1><?php the_title(); ?></h1>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'コンテンツが見つかりませんでした。', 'cobalt-logistics' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
