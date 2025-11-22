# GitHub Actionsワークフローファイル

以下の3つのワークフローファイルを、GitHubのWeb UIから手動で作成してください。

## 作成手順

1. GitHubリポジトリ（https://github.com/kuuhaku1102/hurin）にアクセス
2. `.github/workflows/` ディレクトリに移動
3. 「Add file」→「Create new file」をクリック
4. 以下のファイル名と内容で作成

---

## ファイル1: `.github/workflows/daily-post-usage.yml`

```yaml
name: Daily Post - Usage Guide

on:
  schedule:
    # 毎日午前9時（JST）に実行 = UTC 0時
    - cron: '0 0 * * *'
  workflow_dispatch: # 手動実行も可能

jobs:
  post-article:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout repository
        uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: curl, json
      
      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          php scripts/post-to-wordpress.php usage
      
      - name: Notify completion
        run: |
          echo "✅ 「不倫募集掲示板の活用法」記事を投稿しました"
```

---

## ファイル2: `.github/workflows/weekly-post-spot.yml`

```yaml
name: Weekly Post - Spot Guide (3 times a week)

on:
  schedule:
    # 月・水・金の午前10時（JST）に実行 = UTC 1時
    - cron: '0 1 * * 1,3,5'
  workflow_dispatch: # 手動実行も可能

jobs:
  post-article:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout repository
        uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: curl, json
      
      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          php scripts/post-to-wordpress.php spot
      
      - name: Notify completion
        run: |
          echo "✅ 「都道府県別の出会いスポット紹介」記事を投稿しました"
```

---

## ファイル3: `.github/workflows/weekly-post-manner.yml`

```yaml
name: Weekly Post - Manner Guide (once a week)

on:
  schedule:
    # 毎週日曜日の午前11時（JST）に実行 = UTC 2時
    - cron: '0 2 * * 0'
  workflow_dispatch: # 手動実行も可能

jobs:
  post-article:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout repository
        uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: curl, json
      
      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          php scripts/post-to-wordpress.php manner
      
      - name: Notify completion
        run: |
          echo "✅ 「大人の関係のマナー」記事を投稿しました"
```

---

## セットアップ後の確認

ワークフローファイルを作成した後、以下を確認してください：

1. **GitHubシークレットの設定**
   - Settings → Secrets and variables → Actions
   - 以下の3つのシークレットを追加：
     - `WP_URL`: `https://volitionmagazine.com`
     - `WP_USER`: WordPressのユーザー名
     - `WP_APP_PASSWORD`: WordPressのアプリケーションパスワード

2. **手動実行でテスト**
   - Actions タブに移動
   - いずれかのワークフローを選択
   - 「Run workflow」ボタンをクリックして実行

詳細は `AUTO_POST_SETUP.md` を参照してください。
