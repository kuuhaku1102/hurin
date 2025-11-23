> # トラブルシューティングガイド

---

## 1. OpenAI API 認証エラー (401)

**エラーメッセージ**:
`openai.AuthenticationError: Error code: 401 - {'error': {'message': 'Incorrect API key provided: ...'}}`

| 原因 | 解決策 |
| --- | --- |
| **APIキーが間違っている** | 1. OpenAIのダッシュボードで新しいAPIキーを発行する<br>2. GitHubシークレットの `OPENAI_API_KEY` を更新する<br>3. キーのコピー＆ペースト時に前後にスペースが入っていないか確認する |
| **請求設定が未完了** | 1. OpenAIの「Billing」セクションでクレジットカード情報を登録する<br>2. 支払い方法が有効になっているか確認する |
| **使用制限を超えている** | 1. OpenAIの「Usage limits」で上限額を引き上げる<br>2. 月の利用額がリセットされるのを待つ |
| **キーが無効化されている** | 1. OpenAIでキーが「Active」になっているか確認する<br>2. もし「Revoked」なら、新しいキーを発行する |

---

## 2. 画像取得・アップロードエラー

**エラーメッセージ**:
`❌ 画像の取得に失敗しました: 525`
`❌ 画像読み込みエラー: [Errno 2] No such file or directory`

| 原因 | 解決策 |
| --- | --- |
| **外部画像サイトが不安定** | 1. `wordpress-poster.py` の画像取得URLを別の安定したサービス（Unsplash APIなど）に変更する<br>2. このプロジェクトのように、ローカルのプレースホルダー画像方式に切り替える |
| **プレースホルダー画像がない** | 1. `scripts/placeholder-image.png` がリポジトリに存在するか確認する<br>2. ファイルパスが正しいか `wordpress-poster.py` を確認する |
| **WordPressの権限問題** | 1. WordPressの `uploads` ディレクトリのパーミッションを確認する<br>2. サーバーのディスク容量が不足していないか確認する |

---

## 3. WordPress投稿エラー

**エラーメッセージ**:
`❌ 記事の投稿に失敗しました: 401`
`{'code': 'jwt_auth_invalid_token', 'message': '無効なトークンです。'}`

| 原因 | 解決策 |
| --- | --- |
| **アプリケーションパスワードが無効** | 1. WordPress管理画面で新しいアプリケーションパスワードを発行する<br>2. GitHubシークレットの `WP_APP_PASSWORD` を更新する<br>3. パスワードにスペースが含まれていないか確認する |
| **REST APIが無効化されている** | 1. WordPressのセキュリティプラグイン（SiteGuardなど）がREST APIをブロックしていないか確認する<br>2. `.htaccess` ファイルの設定を確認する |
| **ユーザー権限が不足** | 1. アプリケーションパスワードを発行したユーザーに「投稿者」以上の権限があるか確認する |

---

## 4. GitHub Actions 実行エラー

**エラーメッセージ**:
`Error: Process completed with exit code 1.`

| 原因 | 解決策 |
| --- | --- |
| **シークレットが反映されていない** | 1. ワークフローファイルに小さな変更（コメント追加など）を加えてプッシュし、キャッシュをクリアする<br>2. GitHub Actionsのページで「Re-run all jobs」をクリックする |
| **Pythonの依存関係不足** | 1. ワークフローファイルの `Install dependencies` ステップで、必要なライブラリ（`pip install ...`）がすべてインストールされているか確認する |
| **スクリプトのパスが違う** | 1. ワークフローファイル内の `cd scripts` やファイルパスが正しいか確認する |

---

## 5. FTPデプロイエラー

**エラーメッセージ**:
`Error: Ftp-deployment-action could not connect to the FTP server.`

| 原因 | 解決策 |
| --- | --- |
| **FTP認証情報が間違っている** | 1. GitHubシークレットの `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD` を再確認する |
| **リモートパスが違う** | 1. GitHubシークレットの `REMOTE_PATH` が、サーバー上のテーマディレクトリへの絶対パスになっているか確認する |
| **ファイアウォール** | 1. サーバーのファイアウォールが、GitHub ActionsからのFTP接続を許可しているか確認する |
