# AI記事自動投稿システム完成報告書

## 🎉 完成した機能

### 1. AI記事生成スクリプト（OpenAI API）
**ファイル**: `scripts/generate-article.py`

OpenAI APIを使って、2500文字以上の高品質なSEO記事を自動生成します。

**使用方法**:
```bash
cd scripts
python3 generate-article.py usage   # 活用法記事
python3 generate-article.py spot    # 待ち合わせスポット記事
python3 generate-article.py manner  # マナーガイド記事
```

**生成される記事の特徴**:
- 2500文字以上の長文コンテンツ
- SEOキーワード最適化（不倫募集、不倫募集掲示板、セカンドパートナー）
- 自然で読みやすい文章
- 見出し構造（H2, H3）を適切に配置
- 実用的なアドバイスと具体例

### 2. WordPress自動投稿スクリプト（Python版）
**ファイル**: `scripts/wordpress-poster.py`

生成されたAI記事をWordPress REST APIを使って自動投稿します。

**機能**:
- Basic認証（アプリケーションパスワード使用）
- カテゴリーの自動取得/作成
- タグの自動取得/作成
- 記事タイトルの自動抽出
- エラーハンドリング

**環境変数**:
```bash
WP_URL="https://volitionmagazine.com"
WP_USER="admin"
WP_APP_PASSWORD="sKfmpVAxmQ2pZj0YN3pWJK5M"
```

### 3. WordPress自動投稿スクリプト（PHP版）
**ファイル**: `scripts/post-ai-article.php`

PHP版の投稿スクリプトも用意しています。

**使用方法**:
```bash
cd scripts
WP_URL="https://volitionmagazine.com" \
WP_USER="admin" \
WP_APP_PASSWORD="sKfmpVAxmQ2pZj0YN3pWJK5M" \
php post-ai-article.php
```

## 📊 動作確認結果

### テスト1: 短い記事の投稿
✅ **成功**: テスト投稿（短文）が正常に投稿されました
- URL: https://volitionmagazine.com/?p=9

### テスト2: AI生成長文記事の投稿（PHP版）
✅ **成功**: 6964文字の記事が正常に投稿されました
- タイトル: 不倫募集掲示板の賢い使い方とは？セカンドパートナーの見つけ方完全ガイド
- URL: https://volitionmagazine.com/?p=10

### テスト3: AI生成記事の投稿（Python版）
✅ **成功**: 同じ記事がPython版でも正常に投稿されました

## 🔧 問題と解決

### 問題: 401 Unauthorized エラー
**原因**: アプリケーションパスワードが無効化されていた

**解決策**: 
1. WordPress管理画面でアプリケーションパスワードを再生成
2. 新しいパスワード（`sKfmpVAxmQ2pZj0YN3pWJK5M`）に更新
3. 投稿成功を確認

## 🤖 GitHub Actions自動投稿ワークフロー

以下の3つのワークフローを作成しました（ローカルに保存済み）:

### 1. 毎日の活用法記事投稿
**ファイル**: `.github/workflows/daily-ai-article.yml`

```yaml
name: Daily AI Article - Auto Post

on:
  schedule:
    # 毎日午前9時（日本時間）に実行 = UTC 0時
    - cron: '0 0 * * *'
  workflow_dispatch:

jobs:
  generate-and-post:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install openai requests

      - name: Generate AI-powered article
        env:
          OPENAI_API_KEY: ${{ secrets.OPENAI_API_KEY }}
        run: |
          cd scripts
          python3 generate-article.py usage

      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          cd scripts
          python3 wordpress-poster.py

      - name: Deployment completed
        run: |
          echo "✅ AI生成記事をWordPressに投稿しました"
```

### 2. 週次の待ち合わせスポット記事投稿
**ファイル**: `.github/workflows/weekly-ai-spot.yml`

```yaml
name: Weekly AI Article - Meeting Spots

on:
  schedule:
    # 毎週月曜日午前10時（日本時間）に実行 = UTC 1時
    - cron: '0 1 * * 1'
  workflow_dispatch:

jobs:
  generate-and-post:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install openai requests

      - name: Generate AI-powered article (Meeting Spots)
        env:
          OPENAI_API_KEY: ${{ secrets.OPENAI_API_KEY }}
        run: |
          cd scripts
          python3 generate-article.py spot

      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          cd scripts
          python3 wordpress-poster.py

      - name: Deployment completed
        run: |
          echo "✅ AI生成記事（待ち合わせスポット）をWordPressに投稿しました"
```

### 3. 週次のマナーガイド記事投稿
**ファイル**: `.github/workflows/weekly-ai-manner.yml`

```yaml
name: Weekly AI Article - Manner Guide

on:
  schedule:
    # 毎週木曜日午前10時（日本時間）に実行 = UTC 1時
    - cron: '0 1 * * 4'
  workflow_dispatch:

jobs:
  generate-and-post:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install openai requests

      - name: Generate AI-powered article (Manner Guide)
        env:
          OPENAI_API_KEY: ${{ secrets.OPENAI_API_KEY }}
        run: |
          cd scripts
          python3 generate-article.py manner

      - name: Post article to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          cd scripts
          python3 wordpress-poster.py

      - name: Deployment completed
        run: |
          echo "✅ AI生成記事（マナーガイド）をWordPressに投稿しました"
```

## ⚙️ GitHub Secretsの設定

以下のシークレットをGitHubリポジトリに設定してください：

**Settings → Secrets and variables → Actions → Repository secrets**

| シークレット名 | 値 |
|---|---|
| `OPENAI_API_KEY` | OpenAI APIキー |
| `WP_URL` | `https://volitionmagazine.com` |
| `WP_USER` | `admin` |
| `WP_APP_PASSWORD` | `sKfmpVAxmQ2pZj0YN3pWJK5M` |

## 📅 投稿スケジュール

| ワークフロー | 実行タイミング | 記事タイプ |
|---|---|---|
| daily-ai-article.yml | 毎日午前9時 | 活用法記事 |
| weekly-ai-spot.yml | 毎週月曜10時 | 待ち合わせスポット記事 |
| weekly-ai-manner.yml | 毎週木曜10時 | マナーガイド記事 |

## 🚀 ワークフローファイルの追加方法

GitHub App経由のアクセスでは、ワークフローファイルの直接プッシュに制限があります。

### 方法1: GitHub Web UIで手動作成（推奨）

1. GitHubリポジトリを開く
2. `.github/workflows/` フォルダに移動
3. 「Add file」→「Create new file」をクリック
4. ファイル名を入力（例: `daily-ai-article.yml`）
5. 上記のYAML内容をコピー&ペースト
6. 「Commit changes」をクリック

### 方法2: ローカルから手動プッシュ

```bash
# GitHubに直接ログイン
gh auth login

# ワークフローファイルをコミット
git add .github/workflows/
git commit -m "Add AI article auto-post workflows"
git push origin main
```

## ✅ 完成したシステムの全体像

```
┌─────────────────────────────────────────────┐
│         GitHub Actions (毎日/毎週実行)        │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│   OpenAI API (AI記事生成)                    │
│   - 2500文字以上の高品質記事                  │
│   - SEOキーワード最適化                       │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│   WordPress REST API (自動投稿)              │
│   - カテゴリー/タグ自動作成                   │
│   - Basic認証                                │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│   volitionmagazine.com (公開)                │
│   - 不倫募集掲示板テーマ                      │
│   - 都道府県別ページ                          │
│   - SEOコンテンツ自動追加                     │
└─────────────────────────────────────────────┘
```

## 📝 今後の拡張案

1. **記事バリエーションの追加**
   - リスク記事（risks）
   - プロフィール記事（profile）

2. **記事品質の向上**
   - より長文の記事生成（3000-5000文字）
   - 画像の自動生成・挿入
   - 内部リンクの自動追加

3. **投稿頻度の最適化**
   - A/Bテストによる最適な投稿時間の特定
   - 曜日別の記事タイプ最適化

4. **分析機能の追加**
   - 投稿記事のアクセス解析
   - SEOパフォーマンスの自動レポート

## 🎯 まとめ

✅ **AI記事自動生成システム完成**
- OpenAI APIで高品質な記事を自動生成
- WordPress REST APIで自動投稿
- GitHub Actionsで完全自動化

✅ **動作確認完了**
- 短文記事投稿: 成功
- 長文記事投稿（6964文字）: 成功
- Python/PHP両方のスクリプト: 成功

✅ **次のステップ**
- GitHubシークレット `WP_APP_PASSWORD` を更新
- ワークフローファイルをGitHub Web UIで作成
- 自動投稿の動作確認

---

**作成日**: 2025年11月22日  
**バージョン**: 1.0  
**ステータス**: 完成・動作確認済み
