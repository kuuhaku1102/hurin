import os
import openai
import json

# OpenAIクライアントの初期化
client = openai.OpenAI()

def generate_article(article_type):
    """Manus APIを使用して高品質な記事を生成する"""

    # 記事タイプに応じたキーワードとプロンプトを設定
    if article_type == "usage":
        keywords = "不倫募集掲示板 使い方, セカンドパートナー 見つけ方"
        prompt = f"「不倫募集掲示板の賢い使い方」というテーマで、SEOに強く、読者の悩みを解決する2500文字程度のブログ記事を作成してください。キーワードは「{keywords}」です。"
    elif article_type == "spot":
        prefectures = ["北海道", "東京", "大阪", "福岡", "愛知"]
        prefecture = random.choice(prefectures)
        keywords = f"{prefecture} 不倫募集, {prefecture} 出会いスポット"
        prompt = f"「{prefecture}で不倫募集掲示板で出会える！おすすめの出会いスポット」というテーマで、2500文字程度のブログ記事を作成してください。キーワードは「{keywords}」です。"
    elif article_type == "manner":
        keywords = "不倫マナー, 大人の関係, セカンドパートナー"
        prompt = f"「大人の関係のマナー」というテーマで、2500文字程度のブログ記事を作成してください。キーワードは「{keywords}」です。"
    else:
        raise ValueError("無効な記事タイプです")

    # OpenAI APIを呼び出して記事を生成
    response = client.chat.completions.create(
        model="gpt-4.1-mini",
        messages=[
            {"role": "system", "content": "あなたはプロのブロガーです。SEOを意識し、読者の心に響く高品質な記事を作成してください。"},
            {"role": "user", "content": prompt}
        ]
    )

    article_content = response.choices[0].message.content

    # 生成された記事をファイルに保存
    with open("generated-article.md", "w") as f:
        f.write(article_content)

    print("✅ 高品質な記事を生成しました: generated-article.md")

if __name__ == "__main__":
    import sys
    if len(sys.argv) > 1:
        generate_article(sys.argv[1])
    else:
        print("エラー: 記事タイプを指定してください (usage, spot, manner)")
