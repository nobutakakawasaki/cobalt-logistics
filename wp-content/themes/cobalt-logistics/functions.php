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
 * Truncate a post's body to a plain-text excerpt for card listings and meta
 * descriptions. Prefers a manually-set excerpt (`get_the_excerpt()`), and
 * otherwise auto-generates one from the start of the content — per
 * NEWS_COLUMN_BRIEF.md ("get_the_excerpt()、無ければ本文冒頭を自動生成").
 * Uses mb_substr() character truncation rather than WordPress's default
 * wp_trim_words() (word-based, i.e. whitespace-based) because Japanese text
 * has no spaces between words and trims unpredictably with it.
 *
 * @param int|WP_Post $post   Post ID or object.
 * @param int         $length Max character length before truncation.
 * @return string Plain-text excerpt, truncated with a trailing "…" if cut.
 */
function cobalt_logistics_article_excerpt( $post, $length = 88 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	if ( '' !== trim( $post->post_excerpt ) ) {
		$text = wp_strip_all_tags( $post->post_excerpt );
	} else {
		$text = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		$text = preg_replace( '/\s+/u', '', $text );
	}

	if ( mb_strlen( $text ) <= $length ) {
		return $text;
	}

	return mb_substr( $text, 0, $length ) . '…';
}

/**
 * Per-page meta description + Open Graph/Twitter Card tags. Static pages
 * (home/service/recruit/etc., including the news/column listing pages) use
 * hand-written per-slug descriptions since they have no post content to
 * summarize from. Individual news/column articles (post_type=news_article
 * or column_article, single-news_article.php/single-column_article.php)
 * instead get an auto-generated description from their own
 * content via cobalt_logistics_article_excerpt().
 */
function cobalt_logistics_meta_tags() {
	if ( is_page() ) {
		$descriptions = array(
			'home'      => 'EC物流代行・倉庫保管・輸配送手配・流通加工を一括サポート。導入企業180社以上のコバルト物流株式会社が、物流の「面倒」をまるごと引き受けます。',
			'service'   => 'EC物流代行、倉庫保管・在庫管理、輸配送手配、流通加工まで。コバルト物流のサービス内容と概算料金シミュレーターをご紹介します。',
			'recruit'   => 'コバルト物流の採用情報。倉庫内オペレーション、物流管理職、配送コーディネーターなど、未経験からでも活躍できる仲間を募集しています。',
			'company'   => 'コバルト物流株式会社の会社概要。設立・資本金・代表者・事業内容・沿革など、企業情報をまとめてご紹介します。',
			'warehouse' => '神奈川県横浜市の自社倉庫をご紹介。延床面積12,000坪、WMS完備、温湿度管理エリアなど倉庫設備の詳細と見学のお申し込みはこちら。',
			'faq'       => 'コバルト物流のサービスに関するよくあるご質問。小ロット対応、契約期間、配送エリア、システム連携などについてお答えします。',
			'privacy'   => 'コバルト物流株式会社のプライバシーポリシー。個人情報の利用目的、第三者提供の制限、管理体制について定めています。',
			'news'      => 'コバルト物流株式会社からのお知らせ一覧。設備投資、体制強化、休業案内など最新情報をお届けします。',
			'column'    => 'コバルト物流が発信するコラム。EC物流のアウトソーシング、在庫管理、繁忙期対策、物流コストの可視化など、事業成長に役立つノウハウをご紹介します。',
		);

		global $post;
		$slug        = $post ? $post->post_name : '';
		$description = isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : $descriptions['home'];
		$title       = is_front_page() ? get_bloginfo( 'name' ) . ' – EC物流代行・倉庫保管・輸配送手配・流通加工' : get_the_title() . ' – ' . get_bloginfo( 'name' );
		$image       = get_template_directory_uri() . '/assets/images/facility-exterior.jpg';
	} elseif ( is_singular( array( 'news_article', 'column_article' ) ) ) {
		global $post;
		$description = cobalt_logistics_article_excerpt( $post, 120 );
		$title       = get_the_title( $post ) . ' – ' . get_bloginfo( 'name' );
		$image       = get_template_directory_uri() . '/assets/images/facility-exterior.jpg';
	} else {
		return;
	}

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
 * Register the `job` custom post type used for job listing detail pages,
 * linked to from the RECRUIT page's 募集職種 cards. Unlike `inquiry`
 * (internal submission data), `job` posts are real public content, so this
 * is public-facing with a single template (single-job.php) and a normal
 * admin menu for editing.
 */
function cobalt_logistics_register_job_cpt() {
	$labels = array(
		'name'               => __( '求人', 'cobalt-logistics' ),
		'singular_name'      => __( '求人', 'cobalt-logistics' ),
		'menu_name'          => __( '求人', 'cobalt-logistics' ),
		'all_items'          => __( '求人一覧', 'cobalt-logistics' ),
		'add_new_item'       => __( '求人を追加', 'cobalt-logistics' ),
		'edit_item'          => __( '求人を編集', 'cobalt-logistics' ),
		'view_item'          => __( '求人を表示', 'cobalt-logistics' ),
		'search_items'       => __( '求人を検索', 'cobalt-logistics' ),
		'not_found'          => __( '求人はまだありません', 'cobalt-logistics' ),
		'not_found_in_trash' => __( 'ゴミ箱に求人はありません', 'cobalt-logistics' ),
	);

	register_post_type(
		'job',
		array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'has_archive'     => false,
			'rewrite'         => array( 'slug' => 'job' ),
			'capability_type' => 'post',
			'supports'        => array( 'title' ),
			'menu_icon'       => 'dashicons-businessman',
			'menu_position'   => 26,
		)
	);
}
add_action( 'init', 'cobalt_logistics_register_job_cpt' );

/**
 * Register the `news_article` custom post type (NEWS_COLUMN_CPT_MIGRATION_BRIEF.md
 * #9). Replaces the old `post` + `news` category approach: since staff picked
 * up self-service registration/publishing, that design let a published post
 * silently fail to appear on /news/ if the category checkbox was left
 * unchecked (happened twice in practice, no error shown). A dedicated CPT
 * with no classification step removes that failure mode entirely — same
 * rationale and exact same structure as `job` above, which has never had
 * this problem.
 *
 * Post type slug is deliberately NOT `news` (that's already the post_name of
 * the "お知らせ" listing page, /news/ — a same-slug CPT would collide with
 * it), and the rewrite slug is `news-post` for the same reason (keeps
 * /news/ as the static listing page, individual articles live at
 * /news-post/<slug>/).
 */
function cobalt_logistics_register_news_article_cpt() {
	$labels = array(
		'name'               => __( 'お知らせ', 'cobalt-logistics' ),
		'singular_name'      => __( 'お知らせ', 'cobalt-logistics' ),
		'menu_name'          => __( 'お知らせ', 'cobalt-logistics' ),
		'all_items'          => __( 'お知らせ一覧', 'cobalt-logistics' ),
		'add_new_item'       => __( 'お知らせを追加', 'cobalt-logistics' ),
		'edit_item'          => __( 'お知らせを編集', 'cobalt-logistics' ),
		'view_item'          => __( 'お知らせを表示', 'cobalt-logistics' ),
		'search_items'       => __( 'お知らせを検索', 'cobalt-logistics' ),
		'not_found'          => __( 'お知らせはまだありません', 'cobalt-logistics' ),
		'not_found_in_trash' => __( 'ゴミ箱にお知らせはありません', 'cobalt-logistics' ),
	);

	register_post_type(
		'news_article',
		array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'has_archive'     => false,
			'rewrite'         => array( 'slug' => 'news-post' ),
			'capability_type' => 'post',
			'supports'        => array( 'title', 'editor' ),
			'menu_icon'       => 'dashicons-megaphone',
			'menu_position'   => 22,
		)
	);
}
add_action( 'init', 'cobalt_logistics_register_news_article_cpt' );

/**
 * Register the `column_article` custom post type. Same rationale/structure
 * as `news_article` above — see its docblock — for the `column` category's
 * equivalent "forgot to check the box" failure.
 *
 * Post type slug is `column_article` (not `column`, already the listing
 * page's slug), rewrite slug is `column-post` (not `column`, same reason).
 */
function cobalt_logistics_register_column_article_cpt() {
	$labels = array(
		'name'               => __( 'コラム', 'cobalt-logistics' ),
		'singular_name'      => __( 'コラム', 'cobalt-logistics' ),
		'menu_name'          => __( 'コラム', 'cobalt-logistics' ),
		'all_items'          => __( 'コラム一覧', 'cobalt-logistics' ),
		'add_new_item'       => __( 'コラムを追加', 'cobalt-logistics' ),
		'edit_item'          => __( 'コラムを編集', 'cobalt-logistics' ),
		'view_item'          => __( 'コラムを表示', 'cobalt-logistics' ),
		'search_items'       => __( 'コラムを検索', 'cobalt-logistics' ),
		'not_found'          => __( 'コラムはまだありません', 'cobalt-logistics' ),
		'not_found_in_trash' => __( 'ゴミ箱にコラムはありません', 'cobalt-logistics' ),
	);

	register_post_type(
		'column_article',
		array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'has_archive'     => false,
			'rewrite'         => array( 'slug' => 'column-post' ),
			'capability_type' => 'post',
			'supports'        => array( 'title', 'editor' ),
			'menu_icon'       => 'dashicons-edit',
			'menu_position'   => 23,
		)
	);
}
add_action( 'init', 'cobalt_logistics_register_column_article_cpt' );

/**
 * Flush rewrite rules once after the `job`/`news_article`/`column_article`
 * CPTs are registered, so their permalinks work immediately on a fresh
 * environment (e.g. someone re-provisioning this Docker setup from scratch)
 * without needing to remember a manual `wp rewrite flush`. Runs on every
 * request but only actually flushes once per guard version, gated by an
 * option flag — flush_rewrite_rules() is comparatively expensive, so this
 * must not run unconditionally.
 *
 * The flag is versioned (`_v2` suffix) rather than reusing the original
 * `cobalt_logistics_rewrite_flushed` flag from when only `job` existed:
 * that flag is already permanently set to '1' on any environment that has
 * ever loaded a single request post-`job`-CPT, so reusing it would mean the
 * newly-added `news_article`/`column_article` rewrite rules (added in
 * NEWS_COLUMN_CPT_MIGRATION_BRIEF.md #9) would never actually get flushed
 * in and their /news-post/<slug>/ and /column-post/<slug>/ URLs would 404.
 * Bumping the flag name forces exactly one more flush (covering all three
 * CPTs' current rewrite rules), after which it goes back to being a cheap
 * no-op on every subsequent request, same as before. Any future CPT/rewrite
 * addition should bump this suffix again (e.g. `_v3`) rather than
 * reintroduce the original bug.
 */
function cobalt_logistics_maybe_flush_rewrite_rules() {
	if ( ! get_option( 'cobalt_logistics_rewrite_flushed_v2' ) ) {
		flush_rewrite_rules();
		update_option( 'cobalt_logistics_rewrite_flushed_v2', '1' );
	}
}
add_action( 'init', 'cobalt_logistics_maybe_flush_rewrite_rules', 20 );

/**
 * Seed the 5 job listings once, on first run. This is the actual content
 * source of truth (not a DB dump) — the job posts were originally created
 * via a one-off ad-hoc script that was never committed, which meant a fresh
 * `git clone` + `docker compose up` reproduced the theme/CPT code but zero
 * job listings. Guarded the same way as the rewrite-flush above: runs on
 * every request, but the get_option() check makes it a no-op after the
 * first successful run, and re-running with existing slugs is additionally
 * idempotent via get_page_by_path() (post_type-aware) checks per job.
 */
function cobalt_logistics_seed_jobs() {
	if ( get_option( 'cobalt_logistics_jobs_seeded' ) ) {
		return;
	}

	$jobs = array(
		array(
			'slug'     => 'warehouse-operation',
			'title'    => '倉庫内オペレーションスタッフ',
			'order'    => 1,
			'type'     => '正社員/契約社員',
			'location' => '横浜本社倉庫',
			'salary'   => '契約社員 時給1,300円〜／正社員 月給22万円〜（経験・能力による）',
			'summary'  => '入荷検品、ピッキング、梱包、出荷準備等の倉庫内作業全般をお任せします。WMS端末を使用した在庫管理業務も含みます。',
			'required' => "未経験可（研修制度あり）\n基本的なPC操作（データ入力程度）",
			'preferred' => "物流・倉庫業務の実務経験\nフォークリフト運転技能講習修了者\nWMS（倉庫管理システム）の使用経験",
			'profile'  => "チームで協力して業務を進められる方\n繁忙期の変動にも柔軟に対応できる方\n正確性とスピードを両立させる意識のある方",
		),
		array(
			'slug'     => 'logistics-manager',
			'title'    => '物流管理職',
			'order'    => 2,
			'type'     => '正社員',
			'location' => '横浜本社',
			'salary'   => '月給28万円〜45万円（経験・能力による）',
			'summary'  => '倉庫内オペレーションの管理・改善、KPI管理、シフト管理、クライアント対応、新規案件立ち上げ時のオペレーション設計を担っていただきます。',
			'required' => "物流・倉庫運営における実務経験3年以上\nスタッフマネジメント経験\nExcel等を用いたデータ分析・KPI管理経験",
			'preferred' => "3PL/EC物流企業での勤務経験\nWMS導入・改善プロジェクトの経験\n中小規模チームのマネジメント経験",
			'profile'  => "現場と数字の両方を見ながら改善を進められる方\nクライアントの要望を汲み取り、社内に落とし込める方\n変化の多い環境を前向きに楽しめる方",
		),
		array(
			'slug'     => 'delivery-coordinator',
			'title'    => '配送コーディネーター',
			'order'    => 3,
			'type'     => '正社員',
			'location' => '横浜本社',
			'salary'   => '月給24万円〜32万円',
			'summary'  => '複数運送会社との配送手配調整、配送ルート最適化、遅延時のリカバリー対応、配送コスト管理をお任せします。',
			'required' => "物流・運送業界での実務経験（配車・配送調整等）\n基本的なPCスキル（Excel関数レベル）",
			'preferred' => "複数の運送会社との折衝経験\n配送管理システムの使用経験\n普通自動車運転免許",
			'profile'  => "急な変更にも冷静に対応できる方\n社外（運送会社）・社内双方と円滑にコミュニケーションが取れる方\nコスト意識を持って業務を進められる方",
		),
		array(
			'slug'     => 'customer-support',
			'title'    => 'カスタマーサポートスタッフ',
			'order'    => 4,
			'type'     => '契約社員',
			'location' => '横浜本社',
			'salary'   => '時給1,350円〜',
			'summary'  => 'クライアント（EC事業者）からの問い合わせ対応（電話・メール・チャット）、出荷状況の確認・回答、社内関連部署との連携をお任せします。',
			'required' => "電話・メール対応の実務経験\n基本的なPC操作（Excel、メールソフト）",
			'preferred' => "EC・物流業界でのカスタマーサポート経験\nCRM/問い合わせ管理システムの使用経験",
			'profile'  => "丁寧で分かりやすい対応ができる方\n複数の問い合わせを整理しながら対応できる方\nクライアント目線で考えられる方",
		),
		array(
			'slug'     => 'new-graduate',
			'title'    => '新卒採用（総合職）',
			'order'    => 5,
			'type'     => '正社員',
			'location' => '横浜本社倉庫（入社後、配属先は適性に応じて決定）',
			'salary'   => '月給22万円〜（初任給）',
			'summary'  => '入社後は倉庫内オペレーションから研修をスタートし、適性に応じて物流管理・配送コーディネート・カスタマーサポート等の部署に配属します。',
			'required' => '卒業見込みの方（大学・大学院・専門学校等）',
			'preferred' => "物流・小売・EC業界でのアルバイト経験\nチームでの活動経験（部活動・サークル・ゼミ等）",
			'profile'  => "現場の仕事に興味・関心がある方\n学び続ける姿勢がある方\n将来的にマネジメントに挑戦したい方",
		),
	);

	foreach ( $jobs as $job ) {
		$existing = get_page_by_path( $job['slug'], OBJECT, 'job' );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'job',
				'post_title'  => $job['title'],
				'post_name'   => $job['slug'],
				'post_status' => 'publish',
				'menu_order'  => $job['order'],
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, 'job_type', $job['type'] );
			update_post_meta( $post_id, 'job_location', $job['location'] );
			update_post_meta( $post_id, 'job_salary', $job['salary'] );
			update_post_meta( $post_id, 'job_summary', $job['summary'] );
			update_post_meta( $post_id, 'job_required', $job['required'] );
			update_post_meta( $post_id, 'job_preferred', $job['preferred'] );
			update_post_meta( $post_id, 'job_profile', $job['profile'] );
		}
	}

	update_option( 'cobalt_logistics_jobs_seeded', '1' );
}
add_action( 'init', 'cobalt_logistics_seed_jobs', 21 );

/**
 * Seed the 5 news_article posts + 4 column_article posts once, on first run.
 * Same rationale and pattern as cobalt_logistics_seed_jobs() above: this is
 * the real content source of truth, committed as PHP, not a one-off
 * DB-only script — that gap is exactly what broke the `job` feature's
 * reproducibility before it was fixed. Guarded by an option flag (no-op
 * after first successful run) and additionally idempotent per-post via
 * get_page_by_path() (post_type-aware), so re-running with existing slugs
 * never duplicates.
 *
 * Rewritten by NEWS_COLUMN_CPT_MIGRATION_BRIEF.md #9 to create
 * `news_article`/`column_article` CPT posts directly instead of `post` +
 * category assignment — see cobalt_logistics_register_news_article_cpt()
 * for why. The `news`/`column` category terms this used to assign are no
 * longer created or referenced here (existing terms, if any, are simply
 * unused now — harmless to leave in place). The option flag name is
 * unchanged from the original post+category seeder: the 9 articles this
 * seeds are the same 9 articles either way, so a fresh environment that has
 * never run either version behaves identically either way, and this repo's
 * own live data was migrated in place (post_type updated on the existing 9
 * posts) rather than reseeded, which is what keeps this flag already set to
 * '1' here.
 */
function cobalt_logistics_seed_news_column_posts() {
	if ( get_option( 'cobalt_logistics_news_column_seeded' ) ) {
		return;
	}

	$news_items = array(
		array(
			'slug'    => 'warehouse-capacity-expansion',
			'title'   => '倉庫設備増強のお知らせ',
			'date'    => '2026-07-16 10:00:00',
			'content' => '横浜本社倉庫の保管スペースを増床し、対応可能な商材の幅を拡大いたしました。今後もお客様のニーズに応じた設備投資を継続してまいります。',
		),
		array(
			'slug'    => 'information-security-enhancement',
			'title'   => '情報セキュリティ体制強化についてのお知らせ',
			'date'    => '2026-07-10 10:00:00',
			'content' => 'お客様の個人情報・データをお預かりする企業として、社内の情報セキュリティ管理体制を見直し、全社員を対象としたセキュリティ研修を必須化いたしました。今後も安心してご利用いただける体制づくりに努めてまいります。',
		),
		array(
			'slug'    => 'summer-holiday-notice',
			'title'   => '夏季休業のお知らせ',
			'date'    => '2026-06-25 10:00:00',
			'content' => '誠に勝手ながら、8月13日（木）〜8月16日（日）は夏季休業とさせていただきます。休業期間中にいただいたお問い合わせにつきましては、8月17日（月）以降順次対応いたします。',
		),
		array(
			'slug'    => 'wms-system-update',
			'title'   => 'WMS（倉庫管理システム）アップデートのお知らせ',
			'date'    => '2026-06-12 10:00:00',
			'content' => '在庫可視化の精度向上を目的に、倉庫管理システム（WMS）のアップデートを実施いたしました。これにより、在庫反映のリアルタイム性がさらに向上しております。',
		),
		array(
			'slug'    => 'media-feature',
			'title'   => '物流業界メディアへの掲載について',
			'date'    => '2026-05-28 10:00:00',
			'content' => '弊社の物流オペレーションにおける取り組みが、物流業界の専門メディアにて紹介されました。今後も業界の発展に貢献できるよう努めてまいります。',
		),
	);

	$column_items = array(
		array(
			'slug'    => 'ec-logistics-outsourcing-tips',
			'title'   => 'EC物流のアウトソーシングで失敗しないための3つのポイント',
			'date'    => '2026-07-15 10:00:00',
			'content' => <<<'TXT'
EC事業の成長にともない、自社で物流をまかなうことが難しくなり、外部の物流代行会社への委託（アウトソーシング）を検討する事業者が増えています。一方で、「委託したものの思ったような効果が出なかった」という声も少なくありません。ここでは、物流アウトソーシングで失敗しないための3つのポイントをご紹介します。

【1. 繁忙期の対応力を事前に確認する】
月間の出荷件数だけでなく、セールや年末年始などの繁忙期にどこまで対応できるかは、委託先選びの重要な判断材料です。平常時のオペレーションは問題なくても、繁忙期に出荷遅延が発生すれば、顧客満足度に直結します。

【2. システム連携の柔軟性を見る】
自社が使っているECカート・モールと物流会社のシステムがスムーズに連携できるかどうかは、日々の運用効率を大きく左右します。API連携の実績や対応範囲は、契約前に必ず確認しておきたいポイントです。

【3. コミュニケーションの取りやすさ】
物流は「任せたら終わり」ではなく、継続的な改善が必要な業務です。在庫状況や出荷トラブルについて、迅速かつ的確に報告・相談できる体制があるかどうかも、長く付き合えるパートナーかどうかの目安になります。

物流アウトソーシングは、正しく選べば事業成長の強力な後押しになります。まずは自社の課題を整理し、複数の会社を比較検討することから始めてみてください。
TXT,
		),
		array(
			'slug'    => 'warehouse-location-management-basics',
			'title'   => '倉庫の保管効率を上げるロケーション管理の基本',
			'date'    => '2026-07-01 10:00:00',
			'content' => <<<'TXT'
倉庫内の「どこに何を置くか」を管理する仕組みを、ロケーション管理と呼びます。ロケーション管理が甘いと、ピッキングに時間がかかったり、誤出荷が発生しやすくなったりと、物流コスト全体に悪影響を及ぼします。

【出荷頻度に応じた配置を見直す】
出荷頻度の高い商品を作業動線の近くに、頻度の低い商品を奥や上段に配置する「ABC分析」に基づいたレイアウト設計は、ピッキング効率を大きく改善します。

【ロケーションコードを統一する】
棚番号や商品コードのルールが現場ごとにバラバラだと、システム化した際にミスが起きやすくなります。導入初期にコード体系を統一しておくことが、後々の運用効率を左右します。

【WMSでリアルタイムに可視化する】
在庫の位置情報をWMS（倉庫管理システム）でリアルタイムに把握できると、棚卸の手間が減るだけでなく、欠品・過剰在庫の早期発見にもつながります。

ロケーション管理は地味に見えて、物流品質を左右する土台の部分です。定期的な見直しを習慣化することをおすすめします。
TXT,
		),
		array(
			'slug'    => 'peak-season-shipping-delay-prevention',
			'title'   => '繁忙期の出荷遅延を防ぐには？物流会社が教える対策',
			'date'    => '2026-06-18 10:00:00',
			'content' => <<<'TXT'
セールシーズンや年末年始など、EC事業には出荷量が急増する「繁忙期」がつきものです。繁忙期の出荷遅延は、顧客からの信頼低下に直結するため、事前の備えが欠かせません。

【需要予測を早めに共有する】
物流会社側は、事前にどれだけの出荷量が見込まれるかを把握できて初めて、人員やスペースの調整が可能になります。過去の実績データをもとに、繁忙期の2〜3ヶ月前には見込み数量を共有することが理想的です。

【梱包資材は余裕を持って確保する】
繁忙期は業界全体で梱包資材の需要が高まり、調達が難しくなることがあります。必要な資材は早めに手配し、在庫切れによる出荷ストップを防ぎましょう。

【一時的な増員体制を確認しておく】
自社物流の場合、繁忙期だけ人員を増やすのは容易ではありません。3PL（物流アウトソーシング）を活用している場合は、委託先が繁忙期の増員体制を持っているかを事前に確認しておくと安心です。

繁忙期対応の巧拙は、物流会社選びの大きな分かれ目になります。日頃からのコミュニケーションが、いざという時の対応力を左右します。
TXT,
		),
		array(
			'slug'    => 'logistics-cost-kpi',
			'title'   => '物流コストを可視化する — KPI設定の考え方',
			'date'    => '2026-06-05 10:00:00',
			'content' => <<<'TXT'
「物流コストが高い気がするが、何が原因か分からない」という声をよく聞きます。物流コストを適切にコントロールするための第一歩は、コストを構成する要素を分解し、KPI（重要業績評価指標）として可視化することです。

【代表的な物流KPIの例】
・出荷1件あたりのコスト
・誤出荷率
・在庫回転率
・出荷リードタイム

これらを月次で数値化するだけでも、どこにボトルネックがあるかが見えてきます。

【「コストを下げる」より「コストの構造を理解する」】
KPIを見る目的は、単純にコストを下げることだけではありません。保管コストを多少上げてでも出荷リードタイムを短縮した方が、結果的に売上増につながるケースもあります。自社のビジネスモデルに照らして、どのKPIを優先すべきかを見極めることが重要です。

【物流会社とKPIを共有する】
物流を外部委託している場合、これらのKPIを物流会社と定期的に共有し、一緒に改善策を検討できる関係性を築けると、コスト最適化のスピードが大きく変わります。

数字で物流を語れるようになることが、事業成長を支える物流体制づくりの第一歩です。
TXT,
		),
	);

	foreach ( $news_items as $item ) {
		$existing = get_page_by_path( $item['slug'], OBJECT, 'news_article' );
		if ( $existing ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_type'    => 'news_article',
				'post_title'   => $item['title'],
				'post_name'    => $item['slug'],
				'post_content' => $item['content'],
				'post_status'  => 'publish',
				'post_date'    => $item['date'],
			)
		);
	}

	foreach ( $column_items as $item ) {
		$existing = get_page_by_path( $item['slug'], OBJECT, 'column_article' );
		if ( $existing ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_type'    => 'column_article',
				'post_title'   => $item['title'],
				'post_name'    => $item['slug'],
				'post_content' => $item['content'],
				'post_status'  => 'publish',
				'post_date'    => $item['date'],
			)
		);
	}

	update_option( 'cobalt_logistics_news_column_seeded', '1' );
}
add_action( 'init', 'cobalt_logistics_seed_news_column_posts', 22 );

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

/**
 * ============================================================
 * Staff registration / login / activity log
 * (STAFF_AUTH_BRIEF.md)
 * ============================================================
 */

/**
 * Default staff registration code, used only as a fallback when the
 * `cobalt_logistics_staff_registration_code` option has not been set yet.
 *
 * ★実運用時は必ずこの値を変更すること。この定数はデモ環境用のデフォルト値
 * であり、本番運用前に wp-admin から `cobalt_logistics_staff_registration_code`
 * オプション（例: `wp option update cobalt_logistics_staff_registration_code '<新しい値>'`）
 * に別の値を設定し、このデフォルトのままにしないこと。
 */
define( 'COBALT_LOGISTICS_STAFF_REG_CODE_DEFAULT', 'COBALT-STAFF-2026' );

/**
 * Current staff registration code: the `cobalt_logistics_staff_registration_code`
 * option if set, otherwise the demo default constant above.
 *
 * @return string
 */
function cobalt_logistics_get_staff_registration_code() {
	$code = get_option( 'cobalt_logistics_staff_registration_code' );
	return $code ? (string) $code : COBALT_LOGISTICS_STAFF_REG_CODE_DEFAULT;
}

/**
 * Register the `activity_log` custom post type used to record "who did what,
 * when" (logins + job/news/column edits). Same `public => false` admin-only
 * pattern as the `inquiry` CPT above — this is internal data, not
 * public-facing content.
 */
function cobalt_logistics_register_activity_log_cpt() {
	$labels = array(
		'name'               => __( '活動ログ', 'cobalt-logistics' ),
		'singular_name'      => __( '活動ログ', 'cobalt-logistics' ),
		'menu_name'          => __( '活動ログ', 'cobalt-logistics' ),
		'all_items'          => __( '活動ログ一覧', 'cobalt-logistics' ),
		'view_item'          => __( '活動ログを表示', 'cobalt-logistics' ),
		'search_items'       => __( '活動ログを検索', 'cobalt-logistics' ),
		'not_found'          => __( '活動ログはまだありません', 'cobalt-logistics' ),
		'not_found_in_trash' => __( 'ゴミ箱に活動ログはありません', 'cobalt-logistics' ),
	);

	register_post_type(
		'activity_log',
		array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'capability_type' => 'post',
			'supports'        => array( 'title' ),
			'menu_icon'       => 'dashicons-list-view',
			'menu_position'   => 27,
		)
	);
}
add_action( 'init', 'cobalt_logistics_register_activity_log_cpt' );

/**
 * Handle the staff-registration form submission (admin-post.php), same
 * pattern as cobalt_logistics_handle_inquiry_submission() above: nonce
 * check, sanitize, validate, redirect back to the form page with a
 * `staff_register` status query arg (+ `staff_register_errors` on failure).
 *
 * Security-critical: the registration-code check below MUST run (and fail
 * closed) before wp_insert_user() is ever called — a request without the
 * correct code must not create a user. Password handling goes through
 * wp_insert_user() only; this function never hashes, stores, or logs the
 * plaintext password itself.
 */
function cobalt_logistics_handle_staff_registration() {
	$redirect_url = cobalt_logistics_page_url( 'staff-register' );

	if ( ! isset( $_POST['cobalt_staff_register_nonce'] ) || ! check_admin_referer( 'cobalt_staff_register', 'cobalt_staff_register_nonce' ) ) {
		wp_safe_redirect( add_query_arg( 'staff_register', 'error', $redirect_url ) );
		exit;
	}

	$name              = isset( $_POST['staff_name'] ) ? sanitize_text_field( wp_unslash( $_POST['staff_name'] ) ) : '';
	$staff_id          = isset( $_POST['staff_id'] ) ? sanitize_text_field( wp_unslash( $_POST['staff_id'] ) ) : '';
	$email             = isset( $_POST['staff_email'] ) ? sanitize_email( wp_unslash( $_POST['staff_email'] ) ) : '';
	// Passwords are intentionally NOT run through sanitize_text_field() (which
	// can silently strip/alter characters) — only wp_unslash(), matching how
	// WordPress core itself reads $_POST['pwd'] in wp-login.php. The plaintext
	// value only ever lives in these local variables for the duration of this
	// request and is passed straight into wp_insert_user(), which hashes it
	// internally; it is never stored, logged, or echoed anywhere by this code.
	$password          = isset( $_POST['staff_password'] ) ? (string) wp_unslash( $_POST['staff_password'] ) : '';
	$password_confirm  = isset( $_POST['staff_password_confirm'] ) ? (string) wp_unslash( $_POST['staff_password_confirm'] ) : '';
	$reg_code          = isset( $_POST['staff_reg_code'] ) ? sanitize_text_field( wp_unslash( $_POST['staff_reg_code'] ) ) : '';

	$errors = array();

	if ( '' === $name ) {
		$errors[] = 'name';
	}

	if ( '' === $staff_id || ! preg_match( '/^[A-Za-z0-9]+$/', $staff_id ) ) {
		$errors[] = 'staff_id_format';
	} elseif ( username_exists( $staff_id ) ) {
		$errors[] = 'staff_id_taken';
	}

	if ( '' === $email || ! is_email( $email ) ) {
		$errors[] = 'email_format';
	} elseif ( email_exists( $email ) ) {
		$errors[] = 'email_taken';
	}

	if ( mb_strlen( $password ) < 8 ) {
		$errors[] = 'password_length';
	} elseif ( $password !== $password_confirm ) {
		$errors[] = 'password_mismatch';
	}

	// Registration-code gate — hash_equals() for constant-time comparison.
	// This MUST be checked (and MUST block user creation on failure)
	// regardless of what else is valid on the form.
	if ( ! hash_equals( cobalt_logistics_get_staff_registration_code(), $reg_code ) ) {
		$errors[] = 'reg_code';
	}

	if ( ! empty( $errors ) ) {
		wp_safe_redirect(
			add_query_arg(
				array(
					'staff_register'        => 'error',
					'staff_register_errors' => implode( ',', $errors ),
				),
				$redirect_url
			)
		);
		exit;
	}

	// wp_insert_user() hashes the password internally (core's
	// wp_hash_password()) — this function never calls a hashing function
	// directly and never persists the plaintext anywhere else.
	$user_id = wp_insert_user(
		array(
			'user_login'   => $staff_id,
			'user_pass'    => $password,
			'user_email'   => $email,
			'display_name' => $name,
			'nickname'     => $name,
			'role'         => 'editor', // Self-registered staff never get more than `editor` — no admin capabilities.
		)
	);

	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect(
			add_query_arg(
				array(
					'staff_register'        => 'error',
					'staff_register_errors' => 'unknown',
				),
				$redirect_url
			)
		);
		exit;
	}

	// Independent from user_login by design (STAFF_AUTH_BRIEF.md): same value
	// today, but kept as its own user meta so a future change to the login-ID
	// naming convention doesn't require touching user_login itself.
	update_user_meta( $user_id, 'cobalt_staff_id', $staff_id );

	wp_safe_redirect( add_query_arg( 'staff_register', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_cobalt_staff_register', 'cobalt_logistics_handle_staff_registration' );
add_action( 'admin_post_nopriv_cobalt_staff_register', 'cobalt_logistics_handle_staff_registration' );

/**
 * Handle the staff-login form submission (admin-post.php). Authenticates via
 * wp_signon() (core session/cookie handling only, no custom auth/session
 * code) and redirects to /wp-admin/ on success. On failure, always shows the
 * same generic status regardless of the specific WP_Error wp_signon()
 * returned (wrong 社員ID vs. wrong password) — see page-staff-login.php for
 * the rendered message — to avoid leaking whether a given 社員ID exists.
 */
function cobalt_logistics_handle_staff_login() {
	$redirect_url = cobalt_logistics_page_url( 'staff-login' );

	if ( ! isset( $_POST['cobalt_staff_login_nonce'] ) || ! check_admin_referer( 'cobalt_staff_login', 'cobalt_staff_login_nonce' ) ) {
		wp_safe_redirect( add_query_arg( 'staff_login', 'error', $redirect_url ) );
		exit;
	}

	$staff_id = isset( $_POST['staff_login_id'] ) ? sanitize_text_field( wp_unslash( $_POST['staff_login_id'] ) ) : '';
	// Not sanitized with sanitize_text_field() for the same reason as in
	// cobalt_logistics_handle_staff_registration() above — only wp_unslash(),
	// passed straight into wp_signon(), never stored/logged.
	$password = isset( $_POST['staff_login_password'] ) ? (string) wp_unslash( $_POST['staff_login_password'] ) : '';

	if ( '' === $staff_id || '' === $password ) {
		wp_safe_redirect( add_query_arg( 'staff_login', 'error', $redirect_url ) );
		exit;
	}

	$user = wp_signon(
		array(
			'user_login'    => $staff_id,
			'user_password' => $password,
			'remember'      => true,
		),
		is_ssl()
	);

	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'staff_login', 'error', $redirect_url ) );
		exit;
	}

	wp_safe_redirect( admin_url() );
	exit;
}
add_action( 'admin_post_cobalt_staff_login', 'cobalt_logistics_handle_staff_login' );
add_action( 'admin_post_nopriv_cobalt_staff_login', 'cobalt_logistics_handle_staff_login' );

/**
 * Additive `authenticate` filter: lets a 社員ID (matched via the
 * `cobalt_staff_id` user meta set at registration) be used in place of
 * user_login when signing in. Hooked at priority 21 — strictly AFTER
 * WordPress core's own username (`wp_authenticate_username_password`,
 * priority 20) and email (`wp_authenticate_email_password`, priority 20)
 * checks — so it only ever runs as a fallback once those have already
 * failed to resolve a user; existing username/email login is completely
 * unaffected (this filter returns the already-resolved $user unchanged
 * whenever $user is already a WP_User by the time it runs).
 *
 * When a 社員ID match is found, the submitted identifier is translated to
 * the matching user's real user_login and handed to
 * wp_authenticate_username_password() — i.e. this delegates back into core's
 * own password-check logic rather than reimplementing any authentication or
 * hashing itself.
 *
 * @param WP_User|WP_Error|null $user     Result of previous authenticate filters.
 * @param string                $username Submitted username/社員ID/email.
 * @param string                $password Submitted password.
 * @return WP_User|WP_Error|null
 */
function cobalt_logistics_authenticate_staff_id( $user, $username, $password ) {
	if ( $user instanceof WP_User ) {
		return $user;
	}

	if ( empty( $username ) || empty( $password ) ) {
		return $user;
	}

	$matches = get_users(
		array(
			'meta_key'   => 'cobalt_staff_id',
			'meta_value' => sanitize_text_field( $username ),
			'number'     => 1,
			'fields'     => 'all',
		)
	);

	if ( empty( $matches ) ) {
		// No 社員ID matched — leave whatever core's own filters already
		// produced (a WP_Error, or null) untouched.
		return $user;
	}

	return wp_authenticate_username_password( null, $matches[0]->user_login, $password );
}
add_filter( 'authenticate', 'cobalt_logistics_authenticate_staff_id', 21, 3 );

/**
 * Record a single activity_log entry.
 *
 * @param string $title Log post title (e.g. "ログイン: 山田太郎").
 * @param string $type  'login' or 'edit'.
 * @param array  $meta  Extra post meta to store (e.g. log_user_id, log_post_id).
 */
function cobalt_logistics_log_activity( $title, $type, $meta = array() ) {
	$log_id = wp_insert_post(
		array(
			'post_type'   => 'activity_log',
			'post_title'  => sanitize_text_field( $title ),
			'post_status' => 'publish',
		),
		true
	);

	if ( is_wp_error( $log_id ) || ! $log_id ) {
		return;
	}

	update_post_meta( $log_id, 'log_type', sanitize_key( $type ) );
	foreach ( $meta as $meta_key => $meta_value ) {
		update_post_meta( $log_id, $meta_key, $meta_value );
	}
}

/**
 * Log successful logins (`wp_login` fires only on success, both from the
 * new staff-login flow above and from normal wp-login.php).
 *
 * @param string  $user_login Logged-in user's user_login.
 * @param WP_User $user       Logged-in user object.
 */
function cobalt_logistics_log_login( $user_login, $user ) {
	$display_name = ( $user instanceof WP_User ) ? $user->display_name : $user_login;

	cobalt_logistics_log_activity(
		'ログイン: ' . $display_name,
		'login',
		array(
			'log_user_id' => ( $user instanceof WP_User ) ? $user->ID : 0,
		)
	);
}
add_action( 'wp_login', 'cobalt_logistics_log_login', 10, 2 );

/**
 * Log job/news/column edits via `save_post`. Excludes autosaves/revisions,
 * and only logs the `job`, `news_article`, `column_article`, and `post`
 * post types — explicitly NOT `activity_log` itself (would infinite-loop:
 * logging an edit would create a new activity_log post, which would itself
 * fire save_post...) and NOT `inquiry` (internal submission data, not
 * staff-authored content), per STAFF_AUTH_BRIEF.md's noise/loop-prevention
 * requirement.
 *
 * `news_article`/`column_article` were added here by
 * NEWS_COLUMN_CPT_MIGRATION_BRIEF.md #9 (replacing `post`+category as the
 * news/column storage — see cobalt_logistics_register_news_article_cpt())
 * so that editing お知らせ/コラム content keeps showing up in the activity
 * log exactly as it did before the migration; `post` itself is left in the
 * list too since nothing in that brief asked for logging of plain posts to
 * be removed.
 *
 * Also excludes `post_status === 'auto-draft'`: WordPress core's dashboard
 * "Quick Draft" widget silently creates an empty auto-draft `post`-type post
 * (via get_default_post_to_edit()) on ordinary dashboard page loads — this
 * is a real save_post firing that is neither an autosave nor a revision, so
 * wp_is_post_autosave()/wp_is_post_revision() alone don't catch it, but
 * logging it would spam "編集: 自動下書き" on every dashboard visit, which is
 * exactly the kind of log noise this feature must avoid.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function cobalt_logistics_log_post_edit( $post_id, $post, $update ) {
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'auto-draft' === $post->post_status ) {
		return;
	}

	if ( ! in_array( $post->post_type, array( 'job', 'news_article', 'column_article', 'post' ), true ) ) {
		return;
	}

	cobalt_logistics_log_activity(
		'編集: ' . $post->post_title,
		'edit',
		array(
			'log_user_id' => get_current_user_id(),
			'log_post_id' => $post_id,
		)
	);
}
add_action( 'save_post', 'cobalt_logistics_log_post_edit', 10, 3 );

/**
 * Dashboard widget: most recent 20 activity_log entries, newest first.
 */
function cobalt_logistics_register_dashboard_widget() {
	wp_add_dashboard_widget(
		'cobalt_logistics_activity_log_widget',
		__( '活動ログ（直近20件）', 'cobalt-logistics' ),
		'cobalt_logistics_render_dashboard_widget'
	);
}
add_action( 'wp_dashboard_setup', 'cobalt_logistics_register_dashboard_widget' );

/**
 * Render the activity-log dashboard widget content.
 */
function cobalt_logistics_render_dashboard_widget() {
	$query = new WP_Query(
		array(
			'post_type'      => 'activity_log',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( ! $query->have_posts() ) {
		echo '<p>まだ活動ログがありません。</p>';
		return;
	}

	echo '<table class="widefat striped"><thead><tr><th>日時</th><th>種別</th><th>内容</th><th>ユーザー</th></tr></thead><tbody>';

	while ( $query->have_posts() ) {
		$query->the_post();
		$log_id       = get_the_ID();
		$log_type     = get_post_meta( $log_id, 'log_type', true );
		$log_user_id  = (int) get_post_meta( $log_id, 'log_user_id', true );
		$log_user     = $log_user_id ? get_userdata( $log_user_id ) : false;
		$type_labels  = array(
			'login' => 'ログイン',
			'edit'  => '編集',
		);
		$type_label   = isset( $type_labels[ $log_type ] ) ? $type_labels[ $log_type ] : $log_type;

		echo '<tr>';
		echo '<td>' . esc_html( get_the_date( 'Y-m-d H:i' ) ) . '</td>';
		echo '<td>' . esc_html( $type_label ) . '</td>';
		echo '<td>' . esc_html( get_the_title() ) . '</td>';
		echo '<td>' . esc_html( $log_user ? $log_user->display_name : '-' ) . '</td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	wp_reset_postdata();
}

/**
 * Google Analytics 4 (gtag.js) integration.
 *
 * Real GA4 property created 2026-07-18 for this demo (stream URL set to a
 * placeholder https://example.com at creation time since GA4 rejects
 * "localhost" as a stream URL — update it in GA4 Admin > Data Streams once
 * this site has a real public domain). Measurement ID below is real; the
 * `G-XXXXXXXXXX` fallback check stays in place as a safety net in case this
 * ever gets reset back to a placeholder.
 */
define( 'COBALT_LOGISTICS_GA_MEASUREMENT_ID', 'G-FX88E8JYPV' );

function cobalt_logistics_analytics() {
	if ( 'G-XXXXXXXXXX' === COBALT_LOGISTICS_GA_MEASUREMENT_ID ) {
		return;
	}
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( COBALT_LOGISTICS_GA_MEASUREMENT_ID ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo esc_js( COBALT_LOGISTICS_GA_MEASUREMENT_ID ); ?>');
	</script>
	<?php
}
add_action( 'wp_head', 'cobalt_logistics_analytics', 5 );
