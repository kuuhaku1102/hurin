# 不倫募集掲示板テーマ

既婚者専用の不倫募集掲示板WordPressテーマです。妖艶で大人っぽいデザインを採用し、全国47都道府県に対応しています。

## 特徴

- **都道府県別ページ**: 47都道府県それぞれに専用ページを自動生成
- **女性一覧表示**: データベースから都道府県別に女性データを取得・表示
- **SEOコンテンツ**: 不倫バレない方法、おすすめスポットなど充実したコンテンツ
- **妖艶なデザイン**: ダークトーン、深い紫とゴールドのアクセントカラー
- **レスポンシブ対応**: スマートフォンからPCまで完全対応

## インストール方法

1. このテーマフォルダをZIP化
2. WordPress管理画面 > 外観 > テーマ > 新規追加 > テーマのアップロード
3. アップロードして有効化

## 必須要件

### データベーステーブル

`wp_mama_gen` テーブルが必要です（`wp_`はテーブルプレフィックス）。

#### 必須カラム

- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `post_status` (VARCHAR) - 'publish' で公開
- `post_type` (VARCHAR)
- `post_title` (VARCHAR)
- `name` (VARCHAR) - 女性の名前
- `age` (INT) - 年齢
- `figure` (VARCHAR) - 体型
- `character` (VARCHAR) - 性格
- `comment` (TEXT) - コメント
- `samune` (VARCHAR) - サムネイル画像URL
- `url` (VARCHAR) - プロフィールURL
- `prefecture` (VARCHAR) - **都道府県名** (例: '東京都', '大阪府')

#### prefectureカラムの追加方法

既存のテーブルに`prefecture`カラムを追加する場合:

```sql
ALTER TABLE wp_mama_gen ADD COLUMN prefecture VARCHAR(50) DEFAULT NULL AFTER comment;
```

## ページ構成

### トップページ (/)

- ヒーローセクション
- SEOコンテンツ（4つの記事カード）
  - 不倫がバレない7つの鉄則
  - 既婚者が出会えるおすすめの場所
  - 理想の相手の見つけ方
  - トラブルを避けるための注意点
- 都道府県一覧（地域別にグループ化）

### 都道府県別ページ (/prefecture/{slug}/)

例: `/prefecture/tokyo/`, `/prefecture/osaka/`

- ページヒーロー
- 該当都道府県の女性一覧
- 都道府県別SEOコンテンツ（4つの記事）
  - {都道府県}で不倫がバレない方法
  - {都道府県}の不倫におすすめのスポット
  - {都道府県}で理想の相手と出会う方法
  - {都道府県}でトラブルを避けるための注意点
- 他の都道府県へのリンク

## ファイル構成

```
hurin/
├── index.php                    # トップページテンプレート
├── page-prefecture.php          # 都道府県別ページテンプレート
├── functions.php                # テーマ機能
├── style.css                    # メインスタイル
├── inc/
│   └── prefecture-data.php      # 都道府県データ定義
├── assets/
│   └── css/
│       └── prefecture.css       # 都道府県ページ専用CSS
└── README.md                    # このファイル
```

## カスタマイズ

### デザインのカスタマイズ

`style.css`の`:root`セクションでカラーパレットを変更できます:

```css
:root {
  --color-primary: #8B4789;        /* メインカラー */
  --color-secondary: #C17B7B;      /* セカンダリカラー */
  --color-accent: #D4AF37;         /* アクセントカラー */
  /* ... */
}
```

### データ取得条件の変更

`functions.php`の`hurin_get_girls_by_prefecture()`関数で取得条件を変更できます。

### SEOコンテンツの編集

`page-prefecture.php`内の各記事セクションを直接編集してください。

## URL構造

- トップページ: `https://example.com/`
- 都道府県ページ: `https://example.com/prefecture/{slug}/`
  - 東京都: `https://example.com/prefecture/tokyo/`
  - 大阪府: `https://example.com/prefecture/osaka/`
  - 北海道: `https://example.com/prefecture/hokkaido/`
  - など

## パーマリンク設定

テーマ有効化後、WordPress管理画面で以下を実行してください:

1. 設定 > パーマリンク設定
2. 「変更を保存」ボタンをクリック（設定変更不要、リライトルールの更新のため）

## 注意事項

- 18歳以上の既婚者専用サイトとして運営してください
- プライバシー保護に十分配慮してください
- 法令遵守を心がけてください

## バージョン

- Version: 2.0
- Author: InfinityDesign
- Text Domain: hurin-theme

## ライセンス

このテーマは個人利用・商用利用ともに自由に使用できます。
