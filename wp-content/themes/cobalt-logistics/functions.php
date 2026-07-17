<?php
/**
 * Cobalt Logistics theme functions and definitions.
 *
 * @package Cobalt_Logistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/icons.php';

/**
 * Theme setup.
 */
function cobalt_logistics_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus(
		array(
			'primary' => __( 'プライマリメニュー', 'cobalt-logistics' ),
		)
	);
}
add_action( 'after_setup_theme', 'cobalt_logistics_setup' );

/**
 * Enqueue theme styles and scripts.
 */
function cobalt_logistics_scripts() {
	$style_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'cobalt-logistics-style', get_stylesheet_uri(), array(), $style_version );

	wp_enqueue_script(
		'cobalt-logistics-main',
		get_template_directory_uri() . '/js/main.js',
		array(),
		$style_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cobalt_logistics_scripts' );

/**
 * Per-page meta description + Open Graph/Twitter Card tags. Descriptions are
 * hand-written per page slug rather than auto-excerpted, since these are
 * static corporate pages with no post content to summarize from.
 */
function cobalt_logistics_meta_tags() {
	if ( ! is_page() ) {
		return;
	}

	$descriptions = array(
		'home'      => 'EC物流代行・倉庫保管・輸配送手配・流通加工を一括サポート。導入企業180社以上のコバルト物流株式会社が、物流の"面倒"をまるごと引き受けます。',
		'service'   => 'EC物流代行、倉庫保管・在庫管理、輸配送手配、流通加工まで。コバルト物流のサービス内容と概算料金シミュレーターをご紹介します。',
		'recruit'   => 'コバルト物流の採用情報。倉庫内オペレーション、物流管理職、配送コーディネーターなど、未経験からでも活躍できる仲間を募集しています。',
		'company'   => 'コバルト物流株式会社の会社概要。設立・資本金・代表者・事業内容・沿革など、企業情報をまとめてご紹介します。',
		'warehouse' => '神奈川県横浜市の自社倉庫をご紹介。延床面積12,000坪、WMS完備、温湿度管理エリアなど倉庫設備の詳細と見学のお申し込みはこちら。',
		'faq'       => 'コバルト物流のサービスに関するよくあるご質問。小ロット対応、契約期間、配送エリア、システム連携などについてお答えします。',
		'privacy'   => 'コバルト物流株式会社のプライバシーポリシー。個人情報の利用目的、第三者提供の制限、管理体制について定めています。',
	);

	global $post;
	$slug        = $post ? $post->post_name : '';
	$description = isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : $descriptions['home'];
	$title       = is_front_page() ? get_bloginfo( 'name' ) . ' – EC物流代行・倉庫保管・輸配送手配・流通加工' : get_the_title() . ' – ' . get_bloginfo( 'name' );
	$image       = get_template_directory_uri() . '/assets/images/facility-exterior.jpg';

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:locale" content="ja_JP">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
}
add_action( 'wp_head', 'cobalt_logistics_meta_tags', 1 );

/**
 * Organization structured data (schema.org JSON-LD), site-wide. Values match
 * the company info established in DESIGN_BRIEF.md — all fictional/demo data.
 */
function cobalt_logistics_structured_data() {
	$data = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'LocalBusiness', // No schema.org type fits "3PL/EC物流代行" precisely; LocalBusiness is the closest accurate generic fit (physical address, serves clients) without implying an unrelated service like household moving.
		'name'      => get_bloginfo( 'name' ),
		'url'       => home_url( '/' ),
		'logo'      => get_template_directory_uri() . '/assets/favicon.svg',
		'image'     => get_template_directory_uri() . '/assets/images/facility-exterior.jpg',
		'telephone' => '045-000-0000',
		'email'     => 'contact@example.com',
		'address'   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '大黒町2-3 コバルト物流ベイサイド棟',
			'addressLocality' => '横浜市鶴見区',
			'addressRegion'   => '神奈川県',
			'addressCountry'  => 'JP',
		),
		'sameAs'    => array(),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cobalt_logistics_structured_data', 2 );

/**
 * Fallback menu output when no primary menu is assigned yet.
 */
function cobalt_logistics_fallback_menu() {
	echo '<ul class="main-nav__list">';
	echo '<li class="main-nav__item"><a href="' . esc_url( home_url( '/' ) ) . '">HOME</a></li>';
	echo '</ul>';
}

/**
 * Look up a page's permalink by slug, with a per-request cache so repeated
 * lookups (e.g. footer sitemap, in-page links) don't each hit the database.
 * Falls back to a guessed /{slug}/ URL if no page with that slug exists yet.
 *
 * @param string $slug Page slug (post_name).
 * @return string Permalink URL.
 */
function cobalt_logistics_page_url( $slug ) {
	static $cache = array();
	if ( ! isset( $cache[ $slug ] ) ) {
		$page = get_page_by_path( $slug );
		$cache[ $slug ] = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
	}
	return $cache[ $slug ];
}

/**
 * Register the `inquiry` custom post type used to store contact form
 * submissions from the HOME page. Not public-facing (no single template),
 * but visible in the admin so submissions can be reviewed.
 */
function cobalt_logistics_register_inquiry_cpt() {
	$labels = array(
		'name'               => __( 'お問い合わせ', 'cobalt-logistics' ),
		'singular_name'      => __( 'お問い合わせ', 'cobalt-logistics' ),
		'menu_name'          => __( 'お問い合わせ', 'cobalt-logistics' ),
		'all_items'          => __( 'お問い合わせ一覧', 'cobalt-logistics' ),
		'view_item'          => __( 'お問い合わせを表示', 'cobalt-logistics' ),
		'search_items'       => __( 'お問い合わせを検索', 'cobalt-logistics' ),
		'not_found'          => __( 'お問い合わせはまだありません', 'cobalt-logistics' ),
		'not_found_in_trash' => __( 'ゴミ箱にお問い合わせはありません', 'cobalt-logistics' ),
	);

	register_post_type(
		'inquiry',
		array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'capability_type' => 'post',
			'supports'        => array( 'title', 'editor' ),
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 25,
		)
	);
}
add_action( 'init', 'cobalt_logistics_register_inquiry_cpt' );

/**
 * Custom admin list columns for the `inquiry` post type: name (title),
 * company, email, inquiry type, a message excerpt, and submission date.
 *
 * @param array $columns Default columns.
 * @return array Modified columns.
 */
function cobalt_logistics_inquiry_columns( $columns ) {
	return array(
		'cb'              => $columns['cb'],
		'title'           => __( '氏名', 'cobalt-logistics' ),
		'inquiry_company' => __( '会社名', 'cobalt-logistics' ),
		'inquiry_email'   => __( 'メールアドレス', 'cobalt-logistics' ),
		'inquiry_type'    => __( 'お問い合わせ種別', 'cobalt-logistics' ),
		'inquiry_excerpt' => __( 'お問い合わせ内容（抜粋）', 'cobalt-logistics' ),
		'date'            => __( '送信日時', 'cobalt-logistics' ),
	);
}
add_filter( 'manage_edit-inquiry_columns', 'cobalt_logistics_inquiry_columns' );

/**
 * Render content for the custom `inquiry` admin list columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function cobalt_logistics_inquiry_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'inquiry_company':
			echo esc_html( get_post_meta( $post_id, '_cobalt_inquiry_company', true ) );
			break;

		case 'inquiry_email':
			$email = get_post_meta( $post_id, '_cobalt_inquiry_email', true );
			if ( $email ) {
				echo '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>';
			}
			break;

		case 'inquiry_type':
			echo esc_html( get_post_meta( $post_id, '_cobalt_inquiry_type', true ) );
			break;

		case 'inquiry_excerpt':
			$content = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
			echo esc_html( mb_strimwidth( $content, 0, 40, '…' ) );
			break;
	}
}
add_action( 'manage_inquiry_posts_custom_column', 'cobalt_logistics_inquiry_column_content', 10, 2 );

/**
 * Handle the HOME page contact form submission (admin-post.php), for both
 * logged-in and logged-out visitors. Validates + sanitizes input, stores it
 * as an `inquiry` post, best-effort attempts an admin notification email,
 * then redirects back to #contact with a status query arg.
 */
function cobalt_logistics_handle_inquiry_submission() {
	$redirect_url = home_url( '/#contact' );

	if ( ! isset( $_POST['cobalt_inquiry_nonce'] ) || ! check_admin_referer( 'cobalt_submit_inquiry', 'cobalt_inquiry_nonce' ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect_url ) );
		exit;
	}

	$name    = isset( $_POST['inquiry_name'] ) ? sanitize_text_field( wp_unslash( $_POST['inquiry_name'] ) ) : '';
	$company = isset( $_POST['inquiry_company'] ) ? sanitize_text_field( wp_unslash( $_POST['inquiry_company'] ) ) : '';
	$email   = isset( $_POST['inquiry_email'] ) ? sanitize_email( wp_unslash( $_POST['inquiry_email'] ) ) : '';
	$message = isset( $_POST['inquiry_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['inquiry_message'] ) ) : '';
	$type    = isset( $_POST['inquiry_type'] ) ? sanitize_text_field( wp_unslash( $_POST['inquiry_type'] ) ) : '';

	$allowed_types = array( '資料請求', '倉庫見学', 'その他' );
	if ( ! in_array( $type, $allowed_types, true ) ) {
		$type = 'その他';
	}

	$missing = array();
	if ( '' === $name ) {
		$missing[] = 'name';
	}
	if ( '' === $email || ! is_email( $email ) ) {
		$missing[] = 'email';
	}
	if ( '' === $message ) {
		$missing[] = 'message';
	}

	if ( ! empty( $missing ) ) {
		wp_safe_redirect(
			add_query_arg(
				array(
					'inquiry'         => 'error',
					'inquiry_missing' => implode( ',', $missing ),
				),
				$redirect_url
			)
		);
		exit;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'inquiry',
			'post_title'   => $name,
			'post_content' => $message,
			'post_status'  => 'publish',
		),
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect_url ) );
		exit;
	}

	update_post_meta( $post_id, '_cobalt_inquiry_company', $company );
	update_post_meta( $post_id, '_cobalt_inquiry_email', $email );
	update_post_meta( $post_id, '_cobalt_inquiry_type', $type );

	// Best-effort admin notification. This local Docker environment has no
	// mail transport configured, so wp_mail() is expected to fail silently
	// here; the `inquiry` post is the source of truth, not the email.
	$admin_email = get_option( 'admin_email' );
	if ( $admin_email ) {
		wp_mail(
			$admin_email,
			'【コバルト物流】新しいお問い合わせ（' . $type . '）',
			"氏名: {$name}\n会社名: {$company}\nメールアドレス: {$email}\n種別: {$type}\n\n{$message}"
		);
	}

	wp_safe_redirect( add_query_arg( 'inquiry', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_cobalt_submit_inquiry', 'cobalt_logistics_handle_inquiry_submission' );
add_action( 'admin_post_nopriv_cobalt_submit_inquiry', 'cobalt_logistics_handle_inquiry_submission' );
