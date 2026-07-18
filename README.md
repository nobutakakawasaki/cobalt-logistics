# コバルト物流株式会社 コーポレートサイト（ポートフォリオ用デモ制作）

制作実績づくりのためのデモプロジェクトです。クラウドワークスに掲載されていた実際の
求人案件（物流会社のHPデザイン + WordPressコーディング、7ページ、ブルー基調のシンプル
デザイン）の要件をお題として使い、架空のクライアント「コバルト物流株式会社」を
想定して制作しました。要件の7ページ構成を満たしたうえで、実際の受託案件で求められる
であろう機能（求人・お知らせ・コラムの投稿更新、問い合わせ管理、アクセス解析、
スタッフアカウント管理など）まで踏み込んで実装しています。

**実在の企業・募集主とは一切関係ありません。** 会社名・住所・代表者名・実績数値・
社員の声・導入事例はすべて架空です。デザインの方向性を検討する際に実在の物流会社
サイト2件（stockcrew.co.jp / suzuyo.co.jp）を参考にしましたが、コンテンツ・素材・
ロゴは一切流用していません。掲載写真8枚はすべてAI生成のオリジナル画像です
（実在の建物・人物ではありません）。

## サイト構成（全11ページ + 動的コンテンツ）

| ページ | URL | 内容 |
|---|---|---|
| HOME | `/` | ヒーロー写真スライドショー、導入実績、サービス紹介、導入事例、問い合わせフォーム |
| サービス概要 | `/service/` | サービス詳細（写真付き）、概算料金シミュレーター |
| 採用情報 | `/recruit/` | 求人一覧（クリックで詳細へ）、働く環境、社員の声、選考の流れ |
| 会社概要 | `/company/` | 会社情報、地図埋め込み、代表メッセージ、沿革 |
| 倉庫概要 | `/warehouse/` | 倉庫外観・内観写真、設備・対応商材、見学申し込み |
| FAQ | `/faq/` | よくある質問 10問（アコーディオン） |
| プライバシーポリシー | `/privacy/` | 個人情報の取り扱い、アクセス解析（GA）に関する開示 |
| お知らせ | `/news/` | 企業ニュース一覧（5件） |
| コラム | `/column/` | 業界向けビジネスコラム一覧（4件） |
| 社員登録 | `/staff-register/` | スタッフアカウント登録（ナビ非掲載、社内向け） |
| 社員ログイン | `/staff-login/` | 社員ID+パスワードでログイン（ナビ非掲載、社内向け） |

動的コンテンツ（WordPress標準機能で管理画面から追加・編集可能）:
- **求人**（カスタム投稿タイプ `job`）: `/job/<slug>/` に個別詳細ページを自動生成。
  仕事内容・必須スキル・歓迎スキル・求める人物像を掲載
- **お知らせ・コラム**（標準の投稿 `post` + カテゴリー `news`/`column`）:
  個別記事は `single.php` で共通表示、カテゴリーに応じて一覧への戻りリンクを出し分け

## 主な機能

**フロントエンド演出**
- ヒーロー写真スライドショー（3枚をポップイン→ドリフト→退場のループで自動切替）
- カーソル追従の光の玉 + アニメーションするルートライン（HOME ヒーロー）
- サービスカードの3Dチルト、スクロール連動のフェードイン演出
- スクロールで出現し、フッター接近で自動的に隠れる追従CTAバー

**問い合わせ・リード獲得**
- 実際に送信できる問い合わせフォーム（`inquiry` カスタム投稿タイプに保存、
  管理画面から一覧・確認可能）
- 概算料金シミュレーター（スライダーでリアルタイム再計算）

**採用**
- クリック可能な求人一覧 → 求人ごとの詳細ページ（Amazonの物流拠点求人などを参考にした構成）
- 社員登録・ログイン機能（WordPressコア機能のみで実装、独自の暗号化処理は一切なし）
- 管理画面ダッシュボードに「誰が・いつ・ログイン/編集したか」を表示する活動ログウィジェット

**SEO・技術基盤**
- 全ページ個別の meta description・OGP・Twitter Card
- schema.org 構造化データ（JSON-LD、LocalBusiness）
- Google Analytics 4連携（gtag.js、実測定ID設定済み）
- favicon（SVG）、ブランドに沿った404ページ、スキップリンク、`lang="ja"`
- レスポンシブ対応（768px/480px ブレークポイント）、`prefers-reduced-motion` 対応

**再現性**
- 求人5件・お知らせ5件・コラム4件・カテゴリーは、いずれも `functions.php` 内の
  init フックで**べき等に自動生成**される（DBへの一度きりの手動投入ではなく、
  gitにコミットされたコードが正のソース）。リポジトリを新規クローンして
  `docker compose up -d` するだけで、コンテンツも含めて全て再現される

## 技術スタック

- WordPress（クラシックテーマ、`page-*.php`/`single*.php` テンプレートをハンドコーディング。
  ブロックテーマ不使用 — 元案件の「WordPressコーディング」要件の証明を意識した構成）
- カスタム投稿タイプ: `job`（求人）、`inquiry`（問い合わせ、非公開）、
  `activity_log`（活動ログ、非公開）
- バニラJS/CSS（jQuery・外部ライブラリ不使用。参考サイトはGSAPを使用していたが、
  同等の演出を自前実装で再現）
- 画像: Google Gemini（`gemini-image` スキル）によるAI生成オリジナル写真
- ローカル開発環境: Docker Compose（WordPress + MySQL + WP-CLI）、Homebrew経由の
  colimaでDocker基盤を構築（Docker Desktop不要）

## ディレクトリ構成

```
wp-content/themes/cobalt-logistics/
  style.css / functions.php / header.php / footer.php
  page-home.php / page-service.php / page-recruit.php / page-company.php
  page-warehouse.php / page-faq.php / page-privacy.php
  page-news.php / page-column.php / page-staff-register.php / page-staff-login.php
  single-news_article.php             # お知らせ個別記事テンプレート
  single-column_article.php           # コラム個別記事テンプレート
  single-job.php                      # 求人詳細テンプレート
  404.php
  inc/icons.php                       # インラインSVGアイコンヘルパー
  js/main.js                          # 全インタラクション（ナビ・アコーディオン・
                                       # ヒーロー演出・シミュレーター・活動ログ等）
  assets/images/                      # AI生成写真8枚
  assets/favicon.svg
docker-compose.yml                    # ローカル検証環境
*_BRIEF.md                            # 各機能の実装ブリーフ（設計判断の記録）
```

## ローカルで動かす

```bash
docker compose up -d
./setup.sh   # 初回のみ。WordPress本体のインストール・テーマ有効化・11ページ作成・
             # メニュー設定までを自動で行う（再実行しても安全＝冪等）
# http://localhost:8080 で確認
```

## 関連ドキュメント

- [SPECIFICATION.md](SPECIFICATION.md) — 技術仕様書
- [MANUAL.md](MANUAL.md) — 運用マニュアル
- [PRICING.md](PRICING.md) — 価格の考え方
- [LICENSE.md](LICENSE.md) — ライセンス（無断転載・複製禁止、閲覧目的のみ）

管理画面: `http://localhost:8080/wp-admin/`（admin / admin_pass_2026）。
求人・お知らせ・コラムの記事は、`functions.php` のフックによって初回アクセス時に
自動投入されるため、`setup.sh` 実行後は何もしなくてもコンテンツが揃った状態になります。
ログイン後は通常のWordPress投稿編集画面から追加・編集できます。
