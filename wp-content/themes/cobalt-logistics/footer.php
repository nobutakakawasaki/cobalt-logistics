<?php
/**
 * The footer for the Cobalt Logistics theme.
 *
 * @package Cobalt_Logistics
 */
?>
	<footer class="site-footer">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-brand">
					<p class="footer-brand__name">コバルト物流株式会社</p>
					<p class="footer-brand__text">神奈川県横浜市鶴見区大黒町2-3<br>コバルト物流ベイサイド棟</p>
					<p class="footer-brand__text">EC物流代行 / 倉庫保管・入出庫管理 / 輸配送手配 / 流通加工</p>
				</div>

				<div class="footer-sitemap-wrap">
					<p class="footer-heading">サイトマップ</p>
					<nav class="footer-sitemap" aria-label="フッターサイトマップ">
						<?php
						$cobalt_footer_pages = array(
							'home'       => 'HOME',
							'service'    => 'サービス概要',
							'recruit'    => '採用情報',
							'company'    => '会社概要',
							'warehouse'  => '倉庫概要',
							'faq'        => 'FAQ',
							'privacy'    => 'プライバシーポリシー',
						);
						foreach ( $cobalt_footer_pages as $cobalt_slug => $cobalt_label ) {
							$cobalt_url = cobalt_logistics_page_url( $cobalt_slug );
							echo '<a href="' . esc_url( $cobalt_url ) . '">' . esc_html( $cobalt_label ) . '</a>';
						}
						?>
					</nav>
				</div>

				<div class="footer-contact">
					<p class="footer-heading">お問い合わせ</p>
					<p class="footer-contact__phone">045-000-0000</p>
					<p class="footer-brand__text">contact@example.com</p>
					<p class="footer-brand__text">受付時間：平日 9:00〜18:00</p>
				</div>
			</div>

			<div class="footer-bottom">
				<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> コバルト物流株式会社（本サイトは架空クライアントを想定したポートフォリオ用デモ制作です）</p>
			</div>
		</div>
	</footer>

	<?php
	/**
	 * Sticky CTA bar. Lives here (not header.php) because its
	 * show/hide behaviour in js/main.js needs `.site-footer` to already
	 * exist in the DOM to observe it, and this keeps the bar's markup next
	 * to the footer it watches. Hidden by default (aria-hidden="true",
	 * tabindex="-1" on both interactive children) — js/main.js reveals it
	 * once the hero/page-hero has scrolled past, per STICKY_CTA_BRIEF.md.
	 */
	?>
	<div class="sticky-cta" id="sticky-cta" role="region" aria-label="お問い合わせバナー" aria-hidden="true">
		<div class="sticky-cta__inner">
			<p class="sticky-cta__text">物流のご相談はお気軽に</p>
			<div class="sticky-cta__actions">
				<a class="btn btn--solid sticky-cta__link" href="<?php echo esc_url( cobalt_logistics_page_url( 'home' ) . '#contact' ); ?>" tabindex="-1">お問い合わせ</a>
				<button class="sticky-cta__close" type="button" aria-label="閉じる" tabindex="-1">
					<span class="sticky-cta__close-icon" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>

<?php wp_footer(); ?>
</body>
</html>
