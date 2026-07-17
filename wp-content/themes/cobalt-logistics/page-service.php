<?php
/**
 * Template Name: サービス概要
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main>

	<section class="page-hero">
		<div class="container">
			<h1 class="page-hero__title">サービス概要</h1>
			<p class="page-hero__lead">EC事業者の物流業務を、入庫から出荷・返品対応まで一括でサポートします。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="service-detail">
				<div class="service-detail__icon"><?php cobalt_logistics_icon( 'box' ); ?></div>
				<div>
					<h2 class="service-detail__title">EC物流代行</h2>
					<p class="service-detail__text">受注データ連携から梱包・発送・返品対応まで、EC運営の"物流業務"を一括代行します。主要ASPカート・モールとのシステム連携に対応。</p>
				</div>
			</div>
			<div class="service-detail">
				<div class="service-detail__icon"><?php cobalt_logistics_icon( 'shelf' ); ?></div>
				<div>
					<h2 class="service-detail__title">倉庫保管・在庫管理</h2>
					<p class="service-detail__text">WMS（倉庫管理システム）によるリアルタイム在庫可視化。ロケーション管理で誤出荷を防止。</p>
				</div>
			</div>
			<div class="service-detail">
				<div class="service-detail__icon"><?php cobalt_logistics_icon( 'truck' ); ?></div>
				<div>
					<h2 class="service-detail__title">輸配送手配</h2>
					<p class="service-detail__text">複数の運送会社と提携し、荷量・エリアに応じた最適な配送ルートを手配。急な出荷増にも対応。</p>
				</div>
			</div>
			<div class="service-detail">
				<div class="service-detail__icon"><?php cobalt_logistics_icon( 'package' ); ?></div>
				<div>
					<h2 class="service-detail__title">流通加工</h2>
					<p class="service-detail__text">検品・梱包・ラベリング・セット組みなど、出荷前の付帯作業に幅広く対応。</p>
				</div>
			</div>

			<div class="pricing-note">
				<p><strong>料金体系について</strong><br>取扱商材・出荷量によりお見積りが異なります。まずはお気軽にお問い合わせください。</p>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
