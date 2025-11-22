# 自動ブログ投稿システム セットアップガイド

## 概要

このシステムは、GitHub Actionsを使用してWordPressに自動的に記事を投稿します。

### 投稿スケジュール

1. **毎日投稿**: 「不倫募集掲示板の活用法」- 毎日午前9時（JST）
2. **週3回投稿**: 「都道府県別の出会いスポット紹介」- 月・水・金の午前10時（JST）
3. **週1回投稿**: 「大人の関係のマナー」- 毎週日曜日の午前11時（JST）

## セットアップ手順

### 1. WordPressでアプリケーションパスワードを生成

1. WordPressの管理画面にログイン
2. 「ユーザー」→「プロフィール」に移動
3. 「アプリケーションパスワード」セクションまでスクロール
4. 新しいアプリケーション名（例: "GitHub Actions"）を入力
5. 「新しいアプリケーションパスワードを追加」をクリック
6. 生成されたパスワードをコピー（スペースは含めない）

### 2. GitHubリポジトリにシークレットを設定

1. GitHubリポジトリ（kuuhaku1102/hurin）にアクセス
2. 「Settings」→「Secrets and variables」→「Actions」に移動
3. 「New repository secret」をクリックして以下を追加：

| Name | Value | 説明 |
|------|-------|------|
| `WP_URL` | `https://volitionmagazine.com` | WordPressサイトのURL |
| `WP_USER` | `admin` | WordPressのユーザー名 |
| `WP_APP_PASSWORD` | `xxxx xxxx xxxx xxxx xxxx xxxx` | 手順1で生成したアプリケーションパスワード |

### 3. WordPress REST APIの有効化確認

WordPressのREST APIが有効になっているか確認します：

```bash
curl https://volitionmagazine.com/wp-json/wp/v2/posts
```

正常にレスポンスが返ってくればOKです。

## 動作確認

### 手動実行でテスト

GitHub Actionsは手動実行も可能です：

1. GitHubリポジトリの「Actions」タブに移動
2. 実行したいワークフローを選択：
   - `Daily Post - Usage Guide`
   - `Weekly Post - Spot Guide`
   - `Weekly Post - Manner Guide`
3. 「Run workflow」ボタンをクリック
4. 「Run workflow」を再度クリックして実行

### ローカルでテスト

ローカル環境でスクリプトをテストすることもできます：

```bash
# 環境変数を設定
export WP_URL="https://volitionmagazine.com"
export WP_USER="admin"
export WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx"

# スクリプトを実行
php scripts/post-to-wordpress.php usage
php scripts/post-to-wordpress.php spot
php scripts/post-to-wordpress.php manner
```

## ファイル構成

```
hurin/
├── .github/workflows/
│   ├── daily-post-usage.yml          # 毎日投稿ワークフロー
│   ├── weekly-post-spot.yml          # 週3回投稿ワークフロー
│   └── weekly-post-manner.yml        # 週1回投稿ワークフロー
├── scripts/
│   └── post-to-wordpress.php         # WordPress REST API連携スクリプト
├── inc/
│   ├── article-templates.php         # 記事生成テンプレート
│   └── prefecture-data.php           # 都道府県データ
├── single.php                        # 投稿詳細ページテンプレート
├── archive.php                       # アーカイブページテンプレート
└── AUTO_POST_SETUP.md                # このファイル
```

## 記事テンプレートのカスタマイズ

記事の内容をカスタマイズするには、`inc/article-templates.php`を編集します。

### 例: 新しいトピックを追加

```php
function hurin_get_template_usage_guide() {
  $topics = array(
    "安全なプロフィールの作り方",
    "相手に好印象を与えるメッセージ術",
    // ここに新しいトピックを追加
    "あなたの新しいトピック",
  );
  // ...
}
```

## トラブルシューティング

### 記事が投稿されない

1. GitHub Actionsのログを確認
   - 「Actions」タブで失敗したワークフローをクリック
   - エラーメッセージを確認

2. シークレットが正しく設定されているか確認
   - `WP_URL`、`WP_USER`、`WP_APP_PASSWORD`が正しいか

3. WordPress REST APIが有効か確認
   - プラグインやセキュリティ設定でREST APIがブロックされていないか

### カテゴリーやタグが作成されない

WordPressの管理画面で、以下のパーマリンクを持つカテゴリーとタグが自動作成されます：

**カテゴリー**:
- 不倫募集掲示板の活用法
- 都道府県別の出会いスポット紹介
- 大人の関係のマナー

**タグ**:
- 不倫募集
- セカンドパートナー
- 活用法
- 出会いスポット
- マナー
- （都道府県名）

## 投稿スケジュールの変更

投稿時間を変更するには、`.github/workflows/`内のYAMLファイルのcron式を編集します。

### cron式の例

```yaml
# 毎日午前9時（JST） = UTC 0時
- cron: '0 0 * * *'

# 月・水・金の午前10時（JST） = UTC 1時
- cron: '0 1 * * 1,3,5'

# 毎週日曜日の午前11時（JST） = UTC 2時
- cron: '0 2 * * 0'
```

**注意**: GitHub Actionsのcronはすべて UTC タイムゾーンです。JSTはUTC+9なので、9時間引いた時間を指定してください。

## サポート

問題が発生した場合は、GitHubのIssuesで報告してください。
