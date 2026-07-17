<?php
/**
 * Template Name: 会社概要
 *
 * @package Cobalt_Logistics
 */

get_header();
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">COMPANY</p>
			<h1 class="page-hero__title">会社概要</h1>
			<p class="page-hero__lead">コバルト物流株式会社の会社情報をご紹介します。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<table class="info-table">
				<tbody>
					<tr>
						<th>社名</th>
						<td>コバルト物流株式会社</td>
					</tr>
					<tr>
						<th>設立</th>
						<td>2012年4月</td>
					</tr>
					<tr>
						<th>資本金</th>
						<td>3,000万円</td>
					</tr>
					<tr>
						<th>代表者</th>
						<td>代表取締役 神谷 悠真</td>
					</tr>
					<tr>
						<th>本社所在地</th>
						<td>神奈川県横浜市鶴見区大黒町2-3 コバルト物流ベイサイド棟</td>
					</tr>
					<tr>
						<th>事業内容</th>
						<td>EC物流代行、倉庫保管・入出庫管理、輸配送手配、流通加工（検品・梱包・ラベリング）</td>
					</tr>
					<tr>
						<th>電話番号</th>
						<td>045-000-0000（デモ用ダミー番号）</td>
					</tr>
				</tbody>
			</table>

			<?php
			// District-level query only (no street number / building name) so the
			// embed doesn't pin an unrelated real building for this fictional client.
			$cobalt_map_query = rawurlencode( '横浜市鶴見区大黒町' );
			$cobalt_map_src   = 'https://www.google.com/maps?q=' . $cobalt_map_query . '&output=embed';
			?>
			<div class="map-embed">
				<iframe
					src="<?php echo esc_url( $cobalt_map_src ); ?>"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="コバルト物流株式会社 所在地周辺（横浜市鶴見区大黒町エリア）の地図"
				></iframe>
			</div>
			<p class="map-embed__caption">所在地イメージ（デモ用のため実際の建物とは異なります）</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="section-title">代表メッセージ</h2>
			<div class="ceo-message">
				<p class="ceo-message__text">EC市場の成長にともない、「物流」は事業拡大のボトルネックにも、競争力の源泉にもなり得る領域になりました。私たちは倉庫保管から出荷、返品対応までを一括で引き受けることで、お客様が本来注力すべき商品開発や販売戦略に集中できる環境をつくりたいと考えています。現場のオペレーション精度と、変化に対応できる柔軟さ。その両方を大切にしながら、一社一社に向き合ってまいります。</p>
				<p class="ceo-message__name">代表取締役　神谷 悠真</p>
			</div>
		</div>
	</section>

	<section class="section section--alt">
		<div class="container">
			<h2 class="section-title">沿革</h2>
			<div class="timeline">
				<div class="timeline__item reveal">
					<p class="timeline__year">2012年4月</p>
					<p class="timeline__text">横浜市鶴見区にて創業、倉庫保管業を開始</p>
				</div>
				<div class="timeline__item reveal">
					<p class="timeline__year">2015年</p>
					<p class="timeline__text">EC物流代行サービスを開始</p>
				</div>
				<div class="timeline__item reveal">
					<p class="timeline__year">2018年</p>
					<p class="timeline__text">倉庫面積を拡張、WMS導入</p>
				</div>
				<div class="timeline__item reveal">
					<p class="timeline__year">2021年</p>
					<p class="timeline__text">流通加工サービスを開始</p>
				</div>
				<div class="timeline__item reveal">
					<p class="timeline__year">2024年</p>
					<p class="timeline__text">導入企業180社突破</p>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
