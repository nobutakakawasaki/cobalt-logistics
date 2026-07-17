<?php
/**
 * 404 (page not found) template.
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">404</p>
			<h1 class="page-hero__title">ページが見つかりませんでした</h1>
			<p class="page-hero__lead">お探しのページは移動または削除された可能性があります。URLをご確認いただくか、下記からホームにお戻りください。</p>
		</div>
	</section>

	<section class="section">
		<div class="container" style="text-align:center;">
			<a class="btn btn--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>">ホームに戻る</a>
		</div>
	</section>

</main>

<?php
get_footer();
