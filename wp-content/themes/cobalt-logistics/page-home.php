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
		<div class="hero-blob hero-blob--1" aria-hidden="true"></div>
		<div class="hero-blob hero-blob--2" aria-hidden="true"></div>
		<svg class="hero-route" viewBox="0 0 1000 460" preserveAspectRatio="none" aria-hidden="true" focusable="false">
			<path id="hero-route-path" class="hero-route__path" d="M -40 360 C 140 300 200 420 380 350 S 620 210 760 270 S 960 150 1040 90"></path>
			<circle id="hero-route-dot" class="hero-route__dot" cx="380" cy="350" r="5"></circle>
		</svg>
		<div class="hero__inner">
			<div class="hero__content">
				<h1 class="hero__title">物流の"面倒"を、まるごと引き受ける。</h1>
				<p class="hero__lead">入庫から出荷まで。EC事業者の物流を、コバルト物流がワンストップで支えます。</p>
				<div class="hero__buttons">
					<a class="btn btn--primary" href="#contact">資料請求はこちら</a>
					<a class="btn btn--outline" href="<?php echo esc_url( cobalt_logistics_page_url( 'warehouse' ) ); ?>">倉庫見学のお問い合わせ</a>
				</div>
			</div>
			<ul class="hero-manifest" aria-label="導入実績">
				<li class="hero-manifest__item reveal-onload">
					<span class="stat-label">導入企業数</span>
					<span class="stat-number">180社+</span>
				</li>
				<li class="hero-manifest__item reveal-onload">
					<span class="stat-label">出荷精度</span>
					<span class="stat-number">99.98%</span>
				</li>
				<li class="hero-manifest__item reveal-onload">
					<span class="stat-label">対応倉庫面積</span>
					<span class="stat-number">12,000坪</span>
				</li>
				<li class="hero-manifest__item reveal-onload">
					<span class="stat-label">稼働年数</span>
					<span class="stat-number">13年</span>
				</li>
			</ul>
		</div>
	</section>

	<section class="section section--alt">
		<div class="container">
			<p class="eyebrow">SERVICES</p>
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

	<?php
	$cobalt_inquiry_status  = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : '';
	$cobalt_inquiry_missing = isset( $_GET['inquiry_missing'] ) ? sanitize_text_field( wp_unslash( $_GET['inquiry_missing'] ) ) : '';

	$cobalt_missing_labels = array(
		'name'    => 'お名前',
		'email'   => 'メールアドレス',
		'message' => 'お問い合わせ内容',
	);
	$cobalt_missing_fields = array();
	if ( $cobalt_inquiry_missing ) {
		foreach ( explode( ',', $cobalt_inquiry_missing ) as $cobalt_missing_key ) {
			if ( isset( $cobalt_missing_labels[ $cobalt_missing_key ] ) ) {
				$cobalt_missing_fields[] = $cobalt_missing_labels[ $cobalt_missing_key ];
			}
		}
	}
	?>

	<section class="section section--alt" id="contact">
		<div class="container">
			<h2 class="section-title">お問い合わせ</h2>
			<p class="section-lead">資料請求・倉庫見学など、お気軽にお問い合わせください。</p>

			<?php if ( 'success' === $cobalt_inquiry_status ) : ?>
				<p class="form-message form-message--success" role="status">お問い合わせありがとうございます。担当者より折り返しご連絡いたします。</p>
			<?php elseif ( 'error' === $cobalt_inquiry_status ) : ?>
				<p class="form-message form-message--error" role="alert">
					<?php if ( $cobalt_missing_fields ) : ?>
						以下の項目をご確認ください：<?php echo esc_html( implode( '、', $cobalt_missing_fields ) ); ?>
					<?php else : ?>
						送信に失敗しました。お手数ですが、入力内容をご確認のうえ再度お試しください。
					<?php endif; ?>
				</p>
			<?php endif; ?>

			<form class="contact-form" id="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
				<input type="hidden" name="action" value="cobalt_submit_inquiry">
				<?php wp_nonce_field( 'cobalt_submit_inquiry', 'cobalt_inquiry_nonce' ); ?>

				<div class="contact-form__field">
					<label for="inquiry-name">お名前 <span class="required">必須</span></label>
					<input type="text" id="inquiry-name" name="inquiry_name" autocomplete="name" required>
				</div>
				<div class="contact-form__field">
					<label for="inquiry-company">会社名</label>
					<input type="text" id="inquiry-company" name="inquiry_company" autocomplete="organization">
				</div>
				<div class="contact-form__field">
					<label for="inquiry-email">メールアドレス <span class="required">必須</span></label>
					<input type="email" id="inquiry-email" name="inquiry_email" autocomplete="email" required>
				</div>
				<div class="contact-form__field">
					<label for="inquiry-type">お問い合わせ種別</label>
					<select id="inquiry-type" name="inquiry_type">
						<option value="資料請求">資料請求</option>
						<option value="倉庫見学">倉庫見学</option>
						<option value="その他">その他</option>
					</select>
				</div>
				<div class="contact-form__field">
					<label for="inquiry-message">お問い合わせ内容 <span class="required">必須</span></label>
					<textarea id="inquiry-message" name="inquiry_message" rows="5" required></textarea>
				</div>

				<p class="contact-form__error" id="contact-form-error" role="alert" hidden></p>

				<button type="submit" class="btn btn--solid" style="width:100%;">送信する</button>
			</form>

			<div class="contact-alt">
				<p class="section-lead" style="margin-top:32px;">フォームをご利用にならない場合は、お電話・メールでも承っております。</p>
				<p class="footer-contact__phone" style="color: var(--cobalt); text-align:center;">045-000-0000</p>
				<p style="text-align:center;">contact@example.com（デモ用ダミー）</p>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
