# コバルト物流株式会社 コーポレートサイト（ポートフォリオ用デモ制作）

制作実績づくりのためのデモプロジェクトです。クラウドワークスに掲載されていた実際の
求人案件（物流会社のHPデザイン + WordPressコーディング、7ページ、ブルー基調のシンプル
デザイン）の要件をそのままお題として使い、架空のクライアント「コバルト物流株式会社」を
想定して制作しました。

**実在の企業・募集主とは一切関係ありません。** 会社名・住所・代表者名・実績数値はすべて
架空です。デザインの方向性を検討する際に実在の物流会社サイトを2件参考にしましたが、
コンテンツ・素材・ロゴは一切流用していません。

## 制作範囲

- ワイヤーフレームからのPC/SPデザイン設計
- WordPressテーマのフルスクラッチ実装（ブロックテーマではなくクラシックPHPテーマ）
- 7ページ構成: HOME / サービス概要 / 採用情報 / 会社概要 / 倉庫概要 / FAQ / プライバシーポリシー

## 技術スタック

- WordPress（クラシックテーマ、`page-*.php` テンプレートをハンドコーディング）
- バニラJS（ナビゲーション開閉・FAQアコーディオン、jQuery不使用）
- レスポンシブCSS（PC/SP対応、画像素材不使用でSVGアイコン+CSSのみで構成）
- ローカル開発環境: Docker Compose（WordPress + MySQL + WP-CLI）

## ディレクトリ構成

```
wp-content/themes/cobalt-logistics/   # テーマ本体（実装のメイン）
  style.css / functions.php / header.php / footer.php
  page-home.php / page-service.php / page-recruit.php
  page-company.php / page-warehouse.php / page-faq.php / page-privacy.php
  inc/icons.php                       # インラインSVGアイコンヘルパー
  js/main.js                          # ナビ開閉・アコーディオン
docker-compose.yml                    # ローカル検証環境
DESIGN_BRIEF.md                       # 制作ブリーフ（架空クライアント情報・各ページ文言）
```

## ローカルで動かす

```bash
docker compose up -d
docker compose exec wpcli wp core is-installed --path=/var/www/html
# http://localhost:8080 で確認
```
