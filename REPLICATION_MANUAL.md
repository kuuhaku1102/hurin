# AI記事自動投稿WordPressサイト構築マニュアル

**作成日**: 2025年11月22日  
**作成者**: Manus AI

---

## 概要

このマニュアルは、**AIによる記事の自動生成・投稿機能を備えたWordPressサイト**をゼロから構築するための手順書です。この手順に従うことで、今回開発したシステムと同様のサイトを他のドメインやサーバーで再現できます。

### 完成するシステムの機能

1.  **AIによる高品質な記事の自動生成**（OpenAI API使用）
2.  **WordPressへの自動投稿**（アイキャッチ画像付き）
3.  **GitHub Actionsによる定時実行**（毎日・毎週など）
4.  **Git-pushによるテーマの自動デプロイ**
5.  **SEOに最適化されたHTML構造**と**人間味のある文章**

---

## 1. 前提条件

作業を開始する前に、以下の情報とアカウントをご用意ください。

| 項目 | 内容 | 備考 |
|---|---|---|
| **WordPressサイト** | 正常にインストール済みのWordPress | 管理者権限が必要です |
| **レンタルサーバー** | SSHまたはFTPアクセスが可能なサーバー | ConoHa WINGを推奨 |
| **GitHubアカウント** | 無料アカウントでOK | |
| **GitHubリポジトリ** | プロジェクト用の空のリポジトリ | |
| **OpenAI APIキー** | 記事生成に使用 | 従量課金が発生します |
| **ドメイン** | サイトを公開するドメイン | |

---

## 2. 構築ステップ

### Step 1: WordPressテーマの初期設定

まず、基本的なWordPressテーマファイルを作成します。これらのファイルをリポジトリのルートに配置します。

1.  **`style.css`**: テーマ情報を記述
2.  **`index.php`**: トップページのテンプレート
3.  **`header.php`**: ヘッダー部分のテンプレート
4.  **`footer.php`**: フッター部分のテンプレート

**`style.css`の例**:
```css
/*
Theme Name: [あなたのテーマ名]
Author: [あなたの名前]
Description: AIによる自動投稿機能付きのカスタムテーマです。
Version: 1.0
*/
```

### Step 2: GitHub Actionsによる自動デプロイ設定

GitHubにプッシュすると、自動的に本番サーバーにファイルがデプロイされるように設定します。

1.  リポジトリに `.github/workflows/` ディレクトリを作成します。
2.  `deploy.yml` という名前で以下のファイルを作成します。

**`deploy.yml`**:
```yaml
name: Deploy WordPress Theme

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: FTP Deploy
        uses: SamKirkland/FTP-Deploy-Action@4.3.3
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: /public_html/[あなたのドメイン]/wp-content/themes/[あなたのテーマ名]/
          exclude: |
            **/.git*
            **/.git*/**
            **/node_modules/**
            .github/**
```

3.  GitHubリポジトリの **Settings → Secrets and variables → Actions** で、以下の3つのシークレットを登録します。
    *   `FTP_SERVER`: FTPサーバーのアドレス
    *   `FTP_USERNAME`: FTPユーザー名
    *   `FTP_PASSWORD`: FTPパスワード

### Step 3: AI記事生成・投稿スクリプトの設置

AIで記事を生成し、WordPressに投稿するためのPythonスクリプトを設置します。

1.  リポジトリに `scripts/` ディレクトリを作成します。
2.  以下の2つのスクリプトを `scripts/` 内に配置します。
    *   `generate-article.py`: OpenAI APIを呼び出して記事をHTML形式で生成するスクリプト。
    *   `wordpress-poster.py`: 生成された記事とサムネイル画像をWordPressに投稿するスクリプト。

> **注**: 今回のプロジェクトで作成した `generate-article.py` と `wordpress-poster.py` をそのまま流用できます。

### Step 4: GitHub Actionsによる自動投稿ワークフロー設定

スケジュールに従ってAI記事の生成と投稿を自動実行するワークフローを設定します。

1.  `.github/workflows/` ディレクトリに `daily-ai-article.yml` という名前でファイルを作成します。

**`daily-ai-article.yml`**:
```yaml
name: Daily AI Article - Auto Post

on:
  workflow_dispatch: # 手動実行を許可
  schedule:
    - cron: '0 0 * * *' # 毎日UTC 0時（日本時間午前9時）に実行

jobs:
  build-and-post:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v3

      - name: Set up Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          python -m pip install --upgrade pip
          pip install openai requests

      - name: Generate AI Article
        id: generate
        env:
          OPENAI_API_KEY: ${{ secrets.OPENAI_API_KEY }}
        run: |
          cd scripts
          python3 generate-article.py --type 'usage'

      - name: Post to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          cd scripts
          python3 wordpress-poster.py
```

2.  GitHubリポジトリの **Settings → Secrets and variables → Actions** で、以下の4つのシークレットを追加登録します。
    *   `OPENAI_API_KEY`: あなたのOpenAI APIキー
    *   `WP_URL`: WordPressサイトのURL (例: `https://example.com`)
    *   `WP_USER`: WordPressの管理者ユーザー名
    *   `WP_APP_PASSWORD`: WordPressのアプリケーションパスワード

> **アプリケーションパスワードの取得方法**:
> WordPress管理画面 → ユーザー → あなたのプロフィール → アプリケーションパスワード → 新しいアプリケーションパスワード名を入力 → 生成

### Step 5: WordPress管理画面での最終設定

1.  **テーマの有効化**: WordPress管理画面 → 外観 → テーマ → 作成したテーマを「有効化」
2.  **ブログアーカイブページの作成**:
    *   固定ページ → 新規追加
    *   タイトル: `blog`
    *   テンプレート: `Blog Archive` を選択
    *   公開

---

## 3. 完成！

これで、すべての設定が完了しました。指定したスケジュールで、AIが自動的に記事を生成・投稿します。

- **テーマの変更**: ローカルでファイルを編集し、`git push`するだけで自動的に本番環境に反映されます。
- **手動投稿**: GitHub Actionsのページから「Run workflow」をクリックすれば、いつでも手動で記事を投稿できます。

このマニュアルが、あなたの新しいサイト構築の助けとなれば幸いです。
