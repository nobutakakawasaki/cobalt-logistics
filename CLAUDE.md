# コバルト物流株式会社 コーポレートサイト（ポートフォリオ用デモ制作）

## これは何か

クラウドワークスに掲載されていた実際の求人案件（物流会社のHPデザイン+WordPressコーディング、
7ページ、ブルー基調のシンプルデザイン）の要件をそのままテンプレートとして使い、
**架空のクライアント「コバルト物流株式会社」**を想定して制作するポートフォリオ用デモサイト。

実在の募集主・実在の物流会社（参考サイトとして挙げられた stockcrew.co.jp / www.suzuyo.co.jp を含む）
とは一切関係がない。会社名・住所・代表者名・実績数値はすべて架空。参考サイトはデザインの
方向性（配色・レイアウト・雰囲気）のみを参考にし、コンテンツ・素材・ロゴは流用しない。

作品集に載せる際は「架空クライアント想定のデモ制作」であることを明記する。

## 画像について(2026-07-17追記)

当初「外部画像・素材は使わない」方針だったが、参考サイトとの品質差を埋めるため、
`gemini-image` スキル(Google Gemini/Imagen経由のAI画像生成)で**オリジナル生成した
写真素材**を追加する方針に変更した。実在する倉庫・企業の写真を無断使用しているわけ
ではなく、すべて生成AIによる架空の情景。実在ブランドのロゴ・識別可能な人物の顔が
写り込んでいないか毎回確認してから採用すること(1回目の生成で実在ブランド"Scotch 3M"
が写り込んだケース・顔がはっきり写ったケースがあり、プロンプトを修正して再生成した)。

- APIキーは `~/projects/web/cobalt-logistics/.env`(gitignore済み、`GOOGLE_API_KEY=`)
- 生成画像は `wp-content/themes/cobalt-logistics/assets/images/` に配置し、通常どおり
  gitコミット対象とする(これはオリジナルコンテンツであり、ライセンス制約のある
  外部素材ではないため)
- 生成コマンド例: `node /Users/5kimac/.claude/skills/gemini-image/scripts/generate_image.js "prompt" --model gemini-3.1-flash-image-preview --aspect 16:9 --size 2K --output-dir <dir>`
  (`imagen-4.0-generate-001` は新規ユーザー向けに廃止済み、`gemini-3.1-flash-image-preview`
  を使うこと)

## 技術スタック

- クラシックPHPテーマ（ブロックテーマではない）。募集要件が「ワードプレスでコーディング」と
  明記されているため、page-*.php テンプレート・style.css・functions.php をハンドコーディングし、
  コーディングスキルの証明になる構成にする。
- ローカル開発環境: Docker Compose（`docker-compose.yml`）
  - `wordpress` サービス: http://localhost:8080
  - `db` サービス: MySQL 8.0
  - `wpcli` サービス: `docker compose exec wpcli wp <command> --path=/var/www/html`
- テーマ本体は `wp-content/themes/cobalt-logistics/` に配置し、docker-compose でコンテナに
  ライブマウント済み（このディレクトリだけが git 管理対象。WordPress コア本体・DBはコンテナ内）
- 起動: `docker compose up -d`（要 colima 起動中: `colima start`）
- WP管理画面: http://localhost:8080/wp-admin （admin / admin_pass_2026、デモ用ローカル限定）

## デザイン方針（参考サイト調査済み）

- 配色: コバルトブルー基調 + 白。信頼感を出すため紺に寄せすぎない、明るめの企業ブルー。
- レイアウト: 大型見出しのファーストビュー、数字を使った実績訴求、アイコンベースのサービス
  カード、写真（倉庫・港湾・現場感のあるビジュアル）、フッターに整理されたサイトマップ。
- PC/SP 両対応のレスポンシブ実装必須。
- フォントはサンセリフ、可読性優先。過度な装飾を避けたシンプルな企業サイト。

## ページ構成（7ページ、詳細コピーは DESIGN_BRIEF.md 参照）

1. HOME
2. サービス概要
3. 採用情報
4. 会社概要
5. 倉庫概要
6. FAQ
7. プライバシーポリシー
