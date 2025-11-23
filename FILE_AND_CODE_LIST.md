>**ファイル一覧**

| パス | 説明 |
| --- | --- |
| `.github/workflows/daily-ai-article.yml` | 毎日記事を生成・投稿するワークフロー |
| `scripts/keywords.json` | キーワードデータベース |
| `scripts/keyword-manager.py` | キーワード選択スクリプト |
| `scripts/generate-article-v2.py` | AI記事生成スクリプト |
| `scripts/wordpress-poster.py` | WordPress投稿スクリプト |
| `scripts/placeholder-image.png` | アイキャッチ画像 |
| `header.php` | WordPressテーマヘッダー |
| `style.css` | WordPressテーマスタイルシート |
| `page-blog.php` | ブログアーカイブページテンプレート |
| `single.php` | 単一記事ページテンプレート |

---

## 2. コード集

### `daily-ai-article.yml`

```yaml
name: Daily AI Article - Auto Post

on:
  workflow_dispatch:
  schedule:
    - cron: '0 0 * * *'

jobs:
  generate-and-post:
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
        env:
          OPENAI_API_KEY: ${{ secrets.OPENAI_API_KEY }}
        run: |
          cd scripts
          python3 generate-article-v2.py

      - name: Post to WordPress
        env:
          WP_URL: ${{ secrets.WP_URL }}
          WP_USER: ${{ secrets.WP_USER }}
          WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
        run: |
          cd scripts
          python3 wordpress-poster.py
```

### `keywords.json`

```json
{
  "main_keywords": [
    "不倫募集掲示板",
    "セカンドパートナー探し",
    "既婚者向けマッチング",
    "大人の関係募集",
    "秘密の恋人探し",
    "結婚してるけど恋愛したい",
    "家庭外恋愛",
    "割り切った関係",
    "婚外恋愛パートナー",
    "プラトニックな関係"
  ],
  "sub_keywords": [
    "40代",
    "50代",
    "安全性",
    "初心者",
    "体験談",
    "注意点",
    "成功のコツ",
    "身バレしない方法",
    "女性向け",
    "男性向け",
    "地方",
    "都会",
    "料金",
    "無料",
    "アプリとの違い",
    "長続きさせる秘訣",
    "罪悪感",
    "メリット・デメリット",
    "Q&A",
    "おすすめ",
    "口コミ・評判"
  ],
  "article_types": [
    {
      "type": "usage",
      "title_pattern": "{main_keyword}の賢い使い方｜{sub_keyword}向け完全ガイド"
    },
    {
      "type": "experience",
      "title_pattern": "【体験談】私が{main_keyword}で{sub_keyword}を見つけた話"
    },
    {
      "type": "qa",
      "title_pattern": "{main_keyword}のよくある質問｜{sub_keyword}の疑問を解決Q&A"
    },
    {
      "type": "ranking",
      "title_pattern": "{main_keyword}おすすめランキング｜{sub_keyword}で選ぶベスト5"
    },
    {
      "type": "comparison",
      "title_pattern": "{main_keyword}とアプリの違いは？{sub_keyword}で徹底比較"
    },
    {
      "type": "safety",
      "title_pattern": "{main_keyword}の安全性｜{sub_keyword}で身バレしないための注意点"
    },
    {
      "type": "beginner",
      "title_pattern": "初心者必見！{main_keyword}で{sub_keyword}を始めるためのステップ"
    },
    {
      "type": "advanced",
      "title_pattern": "{main_keyword}上級者向け｜{sub_keyword}を活用した効果的な方法"
    },
    {
      "type": "psychology",
      "title_pattern": "{main_keyword}の心理学｜{sub_keyword}が求める関係性とは"
    },
    {
      "type": "future",
      "title_pattern": "{main_keyword}の未来｜{sub_keyword}との関係を長続きさせる秘訣"
    }
  ],
  "used_combinations": []
}
```

...（以下、他のスクリプトのコードを追記）...
