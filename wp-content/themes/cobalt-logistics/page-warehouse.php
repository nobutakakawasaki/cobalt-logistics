<?php
/**
 * Template Name: 倉庫概要
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main>

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">FACILITY</p>
			<h1 class="page-hero__title">倉庫概要</h1>
			<p class="page-hero__lead">本社倉庫の設備・対応商材についてご紹介します。</p>
		</div>
	</section>

	<section class="warehouse-band">
		<img
			src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/facility-exterior.jpg' ); ?>"
			alt="夕方の倉庫外観。荷捌きバースにトラックが並んでいる様子"
			width="1600"
			height="893"
			class="warehouse-band__img"
		>
	</section>

	<section class="section">
		<div class="container">
			<dl class="spec-list">
				<div class="spec-list__item">
					<dt>所在地</dt>
					<dd>神奈川県横浜市鶴見区大黒町2-3</dd>
				</div>
				<div class="spec-list__item">
					<dt>延床面積</dt>
					<dd>12,000坪</dd>
				</div>
				<div class="spec-list__item">
					<dt>設備</dt>
					<dd>
						<ul class="warehouse-tags">
							<li><?php cobalt_logistics_icon( 'thermo' ); ?> 温湿度管理エリア</li>
							<li>自動仕分けライン</li>
							<li>WMS完備</li>
							<li><?php cobalt_logistics_icon( 'shield' ); ?> 24時間セキュリティ</li>
						</ul>
					</dd>
				</div>
				<div class="spec-list__item">
					<dt>対応商材</dt>
					<dd>アパレル、食品（常温）、雑貨、化粧品 等</dd>
				</div>
			</dl>

			<div class="warehouse-tour">
				<div class="warehouse-tour__media">
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/warehouse-interior.jpg' ); ?>"
						alt="青いラッキング什器が並ぶ倉庫内の通路"
						width="1600"
						height="893"
						loading="lazy"
						class="warehouse-tour__img"
					>
				</div>
				<div class="cta-banner warehouse-tour__cta">
					<h2 class="cta-banner__title">見学のお申し込み</h2>
					<p style="margin-bottom: 20px;">倉庫見学は随時受け付けております。お気軽にお問い合わせください。</p>
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">お問い合わせはこちら</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
