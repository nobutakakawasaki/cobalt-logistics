<?php
/**
 * Template Name: HOME
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main>

	<section class="hero">
		<div class="hero__inner">
			<h1 class="hero__title">物流の"面倒"を、まるごと引き受ける。</h1>
			<p class="hero__lead">入庫から出荷まで。EC事業者の物流を、コバルト物流がワンストップで支えます。</p>
			<div class="hero__buttons">
				<a class="btn btn--primary" href="#contact">資料請求はこちら</a>
				<a class="btn btn--outline" href="<?php echo esc_url( cobalt_logistics_page_url( 'warehouse' ) ); ?>">倉庫見学のお問い合わせ</a>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-icon"><?php cobalt_logistics_icon( 'building' ); ?></div>
					<p class="stat-number">180社+</p>
					<p class="stat-label">導入企業数</p>
				</div>
				<div class="stat-card">
					<div class="stat-icon"><?php cobalt_logistics_icon( 'target' ); ?></div>
					<p class="stat-number">99.98%</p>
					<p class="stat-label">出荷精度</p>
				</div>
				<div class="stat-card">
					<div class="stat-icon"><?php cobalt_logistics_icon( 'area' ); ?></div>
					<p class="stat-number">12,000坪</p>
					<p class="stat-label">対応倉庫面積</p>
				</div>
				<div class="stat-card">
					<div class="stat-icon"><?php cobalt_logistics_icon( 'clock' ); ?></div>
					<p class="stat-number">13年</p>
					<p class="stat-label">稼働年数</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section section--alt">
		<div class="container">
			<h2 class="section-title">サービス紹介</h2>
			<p class="section-lead">EC事業者の物流業務を、入庫から出荷・返品対応まで一括でサポートします。</p>
			<div class="services-grid">
				<div class="service-card">
					<div class="service-card__icon"><?php cobalt_logistics_icon( 'box' ); ?></div>
					<h3 class="service-card__title">EC物流代行</h3>
					<p class="service-card__text">受注データ連携から梱包・発送・返品対応まで一括代行。</p>
				</div>
				<div class="service-card">
					<div class="service-card__icon"><?php cobalt_logistics_icon( 'shelf' ); ?></div>
					<h3 class="service-card__title">倉庫保管・在庫管理</h3>
					<p class="service-card__text">WMSによるリアルタイム在庫可視化とロケーション管理。</p>
				</div>
				<div class="service-card">
					<div class="service-card__icon"><?php cobalt_logistics_icon( 'truck' ); ?></div>
					<h3 class="service-card__title">輸配送手配</h3>
					<p class="service-card__text">複数運送会社と提携し、最適な配送ルートを手配。</p>
				</div>
				<div class="service-card">
					<div class="service-card__icon"><?php cobalt_logistics_icon( 'package' ); ?></div>
					<h3 class="service-card__title">流通加工</h3>
					<p class="service-card__text">検品・梱包・ラベリング・セット組みに幅広く対応。</p>
				</div>
			</div>
			<p style="text-align:center; margin-top: 32px;">
				<a class="btn btn--solid" href="<?php echo esc_url( cobalt_logistics_page_url( 'service' ) ); ?>">サービス概要を見る</a>
			</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="cta-banner">
				<h2 class="cta-banner__title">一緒に物流の"当たり前"を支えませんか</h2>
				<a class="btn btn--primary" href="<?php echo esc_url( cobalt_logistics_page_url( 'recruit' ) ); ?>">採用情報を見る</a>
			</div>
		</div>
	</section>

	<section class="section section--alt" id="contact">
		<div class="container" style="text-align:center;">
			<h2 class="section-title">お問い合わせ</h2>
			<p class="section-lead">資料請求・倉庫見学のお問い合わせは、お電話またはメールにて承っております。</p>
			<p class="footer-contact__phone" style="color: var(--cobalt);">045-000-0000</p>
			<p>contact@example.com（デモ用ダミー）</p>
		</div>
	</section>

</main>

<?php
get_footer();
