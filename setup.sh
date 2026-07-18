#!/bin/bash
# Idempotent first-time setup for a fresh clone of this repo.
#
# Everything the theme's own code can self-seed (job/news/column content,
# CPT rewrite rules) already does so automatically via init hooks in
# functions.php. What it CANNOT do is bootstrap WordPress core itself --
# creating the admin account, activating the theme, and creating the 11
# WordPress "page" records that carry each page-*.php template (WordPress
# has no equivalent hook that fires before core is installed). That
# bootstrapping was originally done by hand with a series of one-off
# wp-cli commands during development, which is exactly the kind of
# uncommitted "lives only in this machine's DB" state this project has
# deliberately avoided everywhere else (see JOB_LISTINGS_BRIEF.md /
# NEWS_COLUMN_BRIEF.md for the same lesson learned earlier). This script
# is that missing piece, committed as real code.
#
# Usage: after `docker compose up -d`, run `./setup.sh` once. Safe to
# re-run — every step checks current state first and skips if already done.

set -e

WP="docker compose exec -T wpcli wp --path=/var/www/html"

echo "==> Waiting for the database to accept connections..."
# `wp db check` shells out to the mariadb-check binary, which fails on this
# image with a self-signed-cert TLS error unrelated to actual readiness --
# checked directly and confirmed it's not a transient/retry-able condition,
# so it must not be used as the readiness probe. `wp option get` instead
# goes through WordPress's own DB connection (mysqli/PDO), which is what
# every other command in this project already relies on successfully.
tries=0
until $WP option get siteurl > /dev/null 2>&1; do
	tries=$((tries + 1))
	if [ "$tries" -ge 30 ]; then
		echo "    Still waiting after 60s -- continuing anyway (core install will surface any real error)."
		break
	fi
	sleep 2
done

if ! $WP core is-installed > /dev/null 2>&1; then
	echo "==> Installing WordPress core..."
	$WP core install \
		--url="http://localhost:8080" \
		--title="コバルト物流株式会社" \
		--admin_user="admin" \
		--admin_password="admin_pass_2026" \
		--admin_email="demo@example.com" \
		--skip-email
else
	echo "==> WordPress core already installed, skipping."
fi

echo "==> Installing and switching to Japanese..."
$WP language core install ja > /dev/null 2>&1 || true
$WP site switch-language ja > /dev/null 2>&1 || true

echo "==> Setting permalink structure..."
$WP rewrite structure '/%postname%/' --hard > /dev/null

echo "==> Activating theme..."
$WP theme activate cobalt-logistics > /dev/null

# slug => Template Name file
declare -a PAGES=(
	"home:HOME:page-home.php"
	"service:サービス概要:page-service.php"
	"recruit:採用情報:page-recruit.php"
	"company:会社概要:page-company.php"
	"warehouse:倉庫概要:page-warehouse.php"
	"faq:FAQ:page-faq.php"
	"privacy:プライバシーポリシー:page-privacy.php"
	"news:お知らせ:page-news.php"
	"column:コラム:page-column.php"
	"staff-register:社員登録:page-staff-register.php"
	"staff-login:社員ログイン:page-staff-login.php"
)

echo "==> Creating pages (skipping any that already exist)..."
for entry in "${PAGES[@]}"; do
	IFS=':' read -r slug title template <<< "$entry"
	existing_id=$($WP post list --post_type=page --name="$slug" --field=ID 2>/dev/null || true)
	if [ -n "$existing_id" ]; then
		echo "    - $slug already exists (ID $existing_id), skipping."
		continue
	fi
	new_id=$($WP post create --post_type=page --post_title="$title" --post_name="$slug" --post_status=publish --porcelain)
	$WP post meta update "$new_id" _wp_page_template "$template" > /dev/null

	# news/column render their content by querying `post`-type entries in
	# the matching category (see page-news.php/page-column.php) -- these
	# two pages' own post_content is never output on the front end. A real
	# editor opening "コラム"/"お知らせ" in Pages and typing directly into
	# it (a very natural thing to try) gets no error and no visual feedback
	# that nothing happened -- confirmed this is exactly what happened
	# during manual testing. Seed a visible in-editor note instead of
	# leaving it blank, so the confusion can't repeat.
	if [ "$slug" = "news" ]; then
		$WP post update "$new_id" --post_content='<!-- wp:paragraph --><p><strong>お知らせ：この本文欄は画面には表示されません。</strong></p><!-- /wp:paragraph --><!-- wp:paragraph --><p>「お知らせ」ページ（/news/）は、実際には「投稿」の中で「お知らせ」カテゴリーが付いた記事を自動的に一覧表示する仕組みになっています。ここに書いた文章はサイトには反映されません。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>新しいお知らせ記事を追加するには：<br>左メニューの「投稿」→「新規追加」から記事を作成し、右側の「カテゴリー」欄で「お知らせ」にチェックを入れて公開してください。</p><!-- /wp:paragraph -->' > /dev/null
	elif [ "$slug" = "column" ]; then
		$WP post update "$new_id" --post_content='<!-- wp:paragraph --><p><strong>お知らせ：この本文欄は画面には表示されません。</strong></p><!-- /wp:paragraph --><!-- wp:paragraph --><p>「コラム」ページ（/column/）は、実際には「投稿」の中で「コラム」カテゴリーが付いた記事を自動的に一覧表示する仕組みになっています。ここに書いた文章はサイトには反映されません。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>新しいコラム記事を追加するには：<br>左メニューの「投稿」→「新規追加」から記事を作成し、右側の「カテゴリー」欄で「コラム」にチェックを入れて公開してください。</p><!-- /wp:paragraph -->' > /dev/null
	fi

	echo "    - created $slug (ID $new_id)"
done

echo "==> Setting HOME as the static front page..."
home_id=$($WP post list --post_type=page --name=home --field=ID)
$WP option update show_on_front page > /dev/null
$WP option update page_on_front "$home_id" > /dev/null

echo "==> Setting up the primary navigation menu (skipping if it already has items)..."
existing_menu_id=$($WP menu list --fields=term_id,locations --format=csv | grep primary | cut -d, -f1 || true)
if [ -z "$existing_menu_id" ]; then
	menu_id=$($WP menu create "Primary Menu" --porcelain)
	$WP menu location assign "$menu_id" primary
	for slug in home service recruit company warehouse news column faq privacy; do
		page_id=$($WP post list --post_type=page --name="$slug" --field=ID)
		$WP menu item add-post "$menu_id" "$page_id" > /dev/null
	done
	echo "    - menu created with 9 items."
else
	echo "    - primary menu already assigned, skipping."
fi

echo ""
echo "Done. Visit http://localhost:8080 -- admin login: admin / admin_pass_2026"
