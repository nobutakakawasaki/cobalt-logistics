# お知らせ・コラム CPT移行ブリーフ #9

## 背景（重要、必ず読むこと）

`NEWS_COLUMN_BRIEF.md`（#7）で、お知らせ・コラムをWordPress標準の `post` 投稿タイプ
+ カテゴリー（`news`/`column`）で実装した。これは実際にユーザー（架空クライアント役の
社長）に社員登録→投稿を試してもらったところ、**2回連続で同じ失敗が発生した**:
「投稿」→「新規追加」で記事を書いて公開したが、右サイドバーの「カテゴリー」パネルで
`news`/`column` にチェックを入れ忘れ、WordPress標準の「未分類」のまま公開されてしまい、
`/news/`・`/column/` に反映されなかった（エラーも出ず、気づきにくい）。さらにその前には、
「コラム」の一覧ページ（固定ページ）自体を直接編集してしまうという別の混乱も1回発生している
（すでに #7 の追加コミットで固定ページの本文に案内文を入れて対応済みだが、カテゴリー
選び忘れ問題は未解決）。

技術的な正しさ（post+categoryはWordPressとして一般的な構成）よりも、**選び間違えようが
ない作りにすること**を優先する。判断基準は「求人（`job` カスタム投稿タイプ）は同様の
混乱が一度も起きていない」という実績。求人と同じパターンに揃える。

## 移行内容

### 1. 新しいカスタム投稿タイプを2つ追加する

`functions.php` の `cobalt_logistics_register_job_cpt()` と全く同じ構成で:
- `news_article`（ラベル「お知らせ」、`public => true`, `show_ui => true`,
  `show_in_menu => true`, `has_archive => false`, `rewrite => array('slug' => 'news-post')`,
  `supports => array('title', 'editor')`）
- `column_article`（ラベル「コラム」、同上、`rewrite => array('slug' => 'column-post')`）

投稿タイプのスラッグ自体は `news`/`column` にしないこと（既存の固定ページのスラッグ
`news`/`column` と衝突するため）。パーマリンクのプレフィックスも `news-post`/`column-post`
のように既存の `/news/`・`/column/`（一覧固定ページ）と衝突しない値にする。

`job` CPT登録後に呼ばれている `cobalt_logistics_maybe_flush_rewrite_rules()` の
オプションフラグ（`cobalt_logistics_rewrite_flushed`）は、新しいCPT追加後に再度
flushが必要になる。既存のフラグ管理方式（一度flushしたら二度としない）だと
新CPTのリライトルールが反映されないため、フラグの扱いを見直すこと
（例: フラグ名にバージョン番号を含める、または新CPT登録のタイミングでフラグを
一度リセットする一回限りの処理を追加する）。

### 2. 既存9件のデータをCPTに移行する

現在 `post` タイプ + カテゴリーで存在する9件（news 5件、column 4件）を、対応する
新CPTの投稿として作り直す（`wp_insert_post` で `post_type` を新CPTに変更して再作成、
または既存投稿の `post_type` を直接更新する方法でもよい。後者の方がシンプルで
IDやパーマリインク変更の影響が少ない場合はそちらを選んでよい）。

### 3. `cobalt_logistics_seed_news_column_posts()` / カテゴリー作成関数を書き換える

`post_type=post` + `wp_insert_term` でカテゴリーに割り当てる現在の実装を、
`post_type='news_article'`/`'column_article'` で直接作成する形に変更する。
カテゴリー（`news`/`column` term）はもう不要なので、`wp_insert_term` の呼び出しは
削除してよい（既存タームが残っていても実害はないが、新規シードでは作らない）。

### 4. テンプレートの更新

- `page-news.php`: `WP_Query` の条件を `category_name=news` から
  `post_type='news_article'` に変更する。
- `page-column.php`: 同様に `post_type='column_article'` に変更する。
- `single.php` は news_article/column_article 用としては使わなくなるため、
  `single-news_article.php`・`single-column_article.php` の2ファイルに分割する
  （`single-job.php` と同じ命名パターン: `single-{post_type}.php`）。中身は
  現行 `single.php` のカテゴリー分岐ロジックを、分岐不要な形にそれぞれ書き直せばよい
  （news側は常に「お知らせ一覧に戻る」、column側は常に「コラム一覧に戻る」）。
  既存の `single.php` は他のpost_type（もし今後増えた場合）のフォールバック用として
  残しておいてよいが、news/column関連の分岐コードは削除すること。

### 5. `functions.php` のメタタグ関数

`cobalt_logistics_meta_tags()` の `is_singular('post')` 分岐を
`is_singular(array('news_article', 'column_article'))` に変更する。

## 制約

- 既存の全機能（問い合わせフォーム・地図・カーソル追従・ルートライン・シミュレーター・
  スクロールリビール・導入事例・サービス画像・追従CTAバー・ヒーロースライドショー・
  社員の声・選考の流れ・求人詳細ページ・求人CPT・社員登録ログイン・活動ログ・
  Google Analytics）を壊さないこと。
- `job` CPT・`inquiry` CPT・その関連関数には触れないこと（お手本として読むのみ）。
- 固定ページ「お知らせ」（`/news/`）「コラム」（`/column/`）に既に入れてある案内文
  （本文が表示されない旨の説明）はそのまま残す。
- vanilla PHP/CSSのみ。

## 完了後の確認

- `php -l` 全ファイル、`docker compose logs wordpress --tail=50` でエラーが無いこと
- 移行後の記事URL（9件）+ 既存の他URL、全て200
- 管理画面に「お知らせ」「コラム」という専用メニューが左サイドバーに表示され、
  それぞれの「新規追加」からカテゴリー選択なしで記事を追加できることを実際に確認する
  （カテゴリーパネルが存在しないことも確認する）
- 実際に管理画面から新規記事を1件ずつ（お知らせ・コラム）作成し、公開後
  `/news/`・`/column/` に**選択操作なしで**反映されることを確認する（テスト後、
  作成した投稿は削除すること）
- 求人機能と同様の「DBを空にしてリクエストを1回投げるだけで記事が自動的に復元される」
  再現性テストを実施する
- 問い合わせフォーム・求人機能・社員登録ログインに回帰がないこと（再送信テストで確認）
- 旧 `/news/xxx-xxx/` 形式のURLで作成されていた記事が、移行後も
  （新しいURL形式に変わったとしても）すべて正しく閲覧できること
