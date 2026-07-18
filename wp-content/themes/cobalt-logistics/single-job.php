<?php
/**
 * Single template for the `job` custom post type (job listing detail pages,
 * linked to from the RECRUIT page's 募集職種 cards).
 *
 * @package Cobalt_Logistics
 */

get_header();

while ( have_posts() ) :
	the_post();

	$job_type     = get_post_meta( get_the_ID(), 'job_type', true );
	$job_location = get_post_meta( get_the_ID(), 'job_location', true );
	$job_salary   = get_post_meta( get_the_ID(), 'job_salary', true );
	$job_summary  = get_post_meta( get_the_ID(), 'job_summary', true );
	$job_required = get_post_meta( get_the_ID(), 'job_required', true );
	$job_preferred = get_post_meta( get_the_ID(), 'job_preferred', true );
	$job_profile  = get_post_meta( get_the_ID(), 'job_profile', true );

	/**
	 * Split a newline-separated post meta value into trimmed, non-empty
	 * lines for rendering as `<li>` items.
	 *
	 * @param string $text Raw newline-separated text.
	 * @return array List of non-empty lines.
	 */
	$cobalt_job_lines = function ( $text ) {
		if ( '' === trim( (string) $text ) ) {
			return array();
		}
		$lines = preg_split( '/\r\n|\r|\n/', $text );
		$lines = array_map( 'trim', $lines );
		return array_values( array_filter( $lines, 'strlen' ) );
	};
	?>

	<main id="main">

		<section class="page-hero">
			<div class="container">
				<p class="eyebrow">CAREERS</p>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<ul class="job-hero-meta">
					<?php if ( $job_type ) : ?>
						<li class="job-hero-meta__item"><?php echo esc_html( $job_type ); ?></li>
					<?php endif; ?>
					<?php if ( $job_location ) : ?>
						<li class="job-hero-meta__item"><?php echo esc_html( $job_location ); ?></li>
					<?php endif; ?>
					<?php if ( $job_salary ) : ?>
						<li class="job-hero-meta__item"><?php echo esc_html( $job_salary ); ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</section>

		<section class="section">
			<div class="container job-body">

				<?php if ( $job_summary ) : ?>
					<div class="job-block">
						<h2 class="job-block__title">仕事内容</h2>
						<p class="job-block__text"><?php echo nl2br( esc_html( $job_summary ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php $cobalt_required_items = $cobalt_job_lines( $job_required ); ?>
				<?php if ( ! empty( $cobalt_required_items ) ) : ?>
					<div class="job-block">
						<h2 class="job-block__title">必須スキル・経験</h2>
						<ul class="job-bullet-list">
							<?php foreach ( $cobalt_required_items as $cobalt_item ) : ?>
								<li><?php echo esc_html( $cobalt_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php $cobalt_preferred_items = $cobalt_job_lines( $job_preferred ); ?>
				<?php if ( ! empty( $cobalt_preferred_items ) ) : ?>
					<div class="job-block">
						<h2 class="job-block__title">歓迎スキル・経験</h2>
						<ul class="job-bullet-list">
							<?php foreach ( $cobalt_preferred_items as $cobalt_item ) : ?>
								<li><?php echo esc_html( $cobalt_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php $cobalt_profile_items = $cobalt_job_lines( $job_profile ); ?>
				<?php if ( ! empty( $cobalt_profile_items ) ) : ?>
					<div class="job-block">
						<h2 class="job-block__title">求める人物像</h2>
						<ul class="job-bullet-list">
							<?php foreach ( $cobalt_profile_items as $cobalt_item ) : ?>
								<li><?php echo esc_html( $cobalt_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="cta-banner job-body__cta">
					<h2 class="cta-banner__title">この求人に応募する</h2>
					<p style="margin-bottom: 20px;">下記のお問い合わせフォームより、「お問い合わせ種別」で「採用について」をお選びのうえご連絡ください。</p>
					<a class="btn btn--primary" href="<?php echo esc_url( cobalt_logistics_page_url( 'home' ) . '#contact' ); ?>">この求人に応募する</a>
				</div>

				<p class="job-body__back">
					<a href="<?php echo esc_url( cobalt_logistics_page_url( 'recruit' ) ); ?>">&larr; 採用情報一覧に戻る</a>
				</p>

			</div>
		</section>

	</main>

	<?php
endwhile;

get_footer();
