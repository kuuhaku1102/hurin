import os
import requests
import json

# 環境変数を取得
WP_URL = os.getenv("WP_URL")
WP_USER = os.getenv("WP_USER")
WP_APP_PASSWORD = os.getenv("WP_APP_PASSWORD")

def post_to_wordpress(title, content, category, tags):
    """WordPressに記事を投稿する"""

    # APIエンドポイント
    api_url = f"{WP_URL}/wp-json/wp/v2/posts"

    # ヘッダー
    headers = {
        "Content-Type": "application/json",
    }

    # 認証
    auth = (WP_USER, WP_APP_PASSWORD)

    # カテゴリーIDを取得/作成
    category_id = get_or_create_term("categories", category, auth)
    if category_id is None:
        category_id = 1  # デフォルトカテゴリー

    # タグIDを取得/作成
    tag_ids = [get_or_create_term("tags", tag, auth) for tag in tags]
    tag_ids = [tid for tid in tag_ids if tid is not None]  # Noneを除外

    # 投稿データ
    post_data = {
        "title": title,
        "content": content,
        "status": "publish",
        "categories": [category_id],
        "tags": tag_ids,
    }

    # 投稿
    response = requests.post(api_url, headers=headers, auth=auth, json=post_data)

    if response.status_code == 201:
        print(f"✅ 成功: 記事を投稿しました\nURL: {response.json()['link']}")
    else:
        print(f"❌ エラー: 記事の投稿に失敗しました\nステータスコード: {response.status_code}\nレスポンス: {response.text}")

def get_or_create_term(term_type, name, auth):
    """カテゴリーまたはタグを取得/作成する"""

    # 既存のタームを検索
    search_url = f"{WP_URL}/wp-json/wp/v2/{term_type}?search={name}"
    response = requests.get(search_url, auth=auth)
    terms = response.json()

    if terms:
        return terms[0]["id"]
    else:
        # 新しいタームを作成
        create_url = f"{WP_URL}/wp-json/wp/v2/{term_type}"
        data = {"name": name}
        response = requests.post(create_url, auth=auth, json=data)
        result = response.json()
        if "id" in result:
            return result["id"]
        else:
            print(f"エラー: タームの作成に失敗しました: {result}")
            return None

if __name__ == "__main__":
    # generated-article.mdから記事内容を読み込み
    import os
    script_dir = os.path.dirname(os.path.abspath(__file__))
    article_path = os.path.join(script_dir, "generated-article.md")
    with open(article_path, "r") as f:
        content = f.read()

    # タイトルを抽出（#で始まる最初の行）
    lines = content.split("\n")
    title = ""
    for line in lines:
        if line.startswith("# "):
            title = line.replace("# ", "").strip()
            break
    
    if not title:
        title = "不倫募集掲示板の賒い使い方"
    
    category = "不倫募集掲示板の活用法"
    tags = ["不倫募集", "セカンドパートナー"]

    post_to_wordpress(title, content, category, tags)
