import os
import requests
import json
import re
from html.parser import HTMLParser

# 環境変数を取得
WP_URL = os.getenv("WP_URL")
WP_USER = os.getenv("WP_USER")
WP_APP_PASSWORD = os.getenv("WP_APP_PASSWORD")

class HTMLTitleExtractor(HTMLParser):
    """HTMLからh1タグのタイトルを抽出するパーサー"""
    def __init__(self):
        super().__init__()
        self.title = ""
        self.in_h1 = False
    
    def handle_starttag(self, tag, attrs):
        if tag == "h1":
            self.in_h1 = True
    
    def handle_endtag(self, tag):
        if tag == "h1":
            self.in_h1 = False
    
    def handle_data(self, data):
        if self.in_h1:
            self.title += data

def extract_title_from_html(html_content):
    """HTMLからタイトルを抽出する"""
    parser = HTMLTitleExtractor()
    parser.feed(html_content)
    return parser.title.strip() if parser.title else "不倫募集掲示板の賢い使い方"

def remove_h1_from_html(html_content):
    """HTMLからh1タグを削除する（WordPressが自動でタイトルを表示するため）"""
    # h1タグとその内容を削除
    html_content = re.sub(r'<h1[^>]*>.*?</h1>', '', html_content, flags=re.DOTALL)
    return html_content.strip()

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
        return response.json()['link']
    else:
        print(f"❌ エラー: 記事の投稿に失敗しました\nステータスコード: {response.status_code}\nレスポンス: {response.text}")
        return None

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
    # generated-article.htmlから記事内容を読み込み
    import os
    script_dir = os.path.dirname(os.path.abspath(__file__))
    
    # HTMLファイルを優先的に読み込み
    html_path = os.path.join(script_dir, "generated-article.html")
    md_path = os.path.join(script_dir, "generated-article.md")
    
    if os.path.exists(html_path):
        with open(html_path, "r", encoding="utf-8") as f:
            html_content = f.read()
        
        # HTMLからタイトルを抽出
        title = extract_title_from_html(html_content)
        
        # h1タグを削除（WordPressが自動でタイトルを表示するため）
        content = remove_h1_from_html(html_content)
        
    elif os.path.exists(md_path):
        # 後方互換性のためMarkdownファイルも対応
        with open(md_path, "r", encoding="utf-8") as f:
            md_content = f.read()
        
        # タイトルを抽出（#で始まる最初の行）
        lines = md_content.split("\n")
        title = ""
        for line in lines:
            if line.startswith("# "):
                title = line.replace("# ", "").strip()
                break
        
        if not title:
            title = "不倫募集掲示板の賢い使い方"
        
        content = md_content
    else:
        print("❌ エラー: 記事ファイルが見つかりません")
        exit(1)
    
    category = "不倫募集掲示板の活用法"
    tags = ["不倫募集", "セカンドパートナー"]

    post_to_wordpress(title, content, category, tags)
