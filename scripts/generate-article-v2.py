#!/usr/bin/env python3
"""
AI記事生成スクリプト v2
キーワード管理システムと統合
"""

import os
import sys
import json
import argparse
from openai import OpenAI
from pathlib import Path

# スクリプトのディレクトリを取得
SCRIPT_DIR = Path(__file__).parent

# キーワード管理システムの関数を直接インポート
import importlib.util
spec = importlib.util.spec_from_file_location("keyword_manager", SCRIPT_DIR / "keyword-manager.py")
keyword_manager = importlib.util.module_from_spec(spec)
spec.loader.exec_module(keyword_manager)

load_keywords = keyword_manager.load_keywords
get_unused_combination = keyword_manager.get_unused_combination
generate_title = keyword_manager.generate_title
generate_prompt = keyword_manager.generate_prompt

# OpenAI APIキーを環境変数から取得
api_key = os.environ.get('OPENAI_API_KEY')
if not api_key:
    print("エラー: OPENAI_API_KEY環境変数が設定されていません")
    sys.exit(1)

# OpenAIクライアントを初期化
client = OpenAI(api_key=api_key)


def generate_article_with_ai(prompt):
    """OpenAI APIを使って記事を生成"""
    print("\n" + "="*50)
    print("🤖 AI記事生成中...")
    print("="*50)
    
    try:
        response = client.chat.completions.create(
            model="gpt-4.1-mini",
            messages=[
                {
                    "role": "system",
                    "content": """あなたは経験豊富なブログライターです。
SEOに最適化された、読みやすく人間味のある記事を書くことが得意です。
記事はHTML形式で出力し、h1タグは使用せず、h2から始めてください。
文体は自然で親しみやすく、AIが書いたと分からないようにしてください。"""
                },
                {
                    "role": "user",
                    "content": prompt
                }
            ],
            temperature=0.8,
            max_tokens=4000
        )
        
        article_content = response.choices[0].message.content
        print("✅ AI記事生成完了")
        return article_content
        
    except Exception as e:
        print(f"❌ エラー: {e}")
        sys.exit(1)


def save_article(title, content):
    """記事をファイルに保存"""
    output_file = SCRIPT_DIR / "generated-article.html"
    
    # AIが出力したMarkdownコードブロック記法を削除
    content = content.strip()
    if content.startswith('```html'):
        content = content[7:]  # '```html' を削除
    if content.startswith('```'):
        content = content[3:]  # '```' を削除
    if content.endswith('```'):
        content = content[:-3]  # 末尾の '```' を削除
    content = content.strip()
    
    # タイトルをh1タグで追加（WordPress投稿スクリプトがタイトルを抽出できるように）
    full_content = f"<h1>{title}</h1>\n{content}"
    
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(full_content)
    
    print(f"\n💾 記事を保存しました: {output_file}")
    print(f"   タイトル: {title}")
    print(f"   文字数: {len(full_content)}文字")
    
    return output_file


def main():
    """メイン処理"""
    parser = argparse.ArgumentParser(description='AI記事生成スクリプト v2')
    parser.add_argument('--manual', action='store_true', help='手動でキーワードを指定')
    parser.add_argument('--main-keyword', type=str, help='メインキーワード')
    parser.add_argument('--sub-keyword', type=str, help='サブキーワード')
    parser.add_argument('--article-type', type=str, help='記事タイプ')
    args = parser.parse_args()
    
    print("="*50)
    print("📝 AI記事自動生成システム v2")
    print("="*50)
    
    # キーワードデータを読み込む
    data = load_keywords()
    
    if args.manual and args.main_keyword and args.sub_keyword and args.article_type:
        # 手動モード
        print("\n🔧 手動モード")
        combination = {
            'main_keyword': args.main_keyword,
            'sub_keyword': args.sub_keyword,
            'article_type': args.article_type,
            'title_pattern': next(
                (at['title_pattern'] for at in data['article_types'] if at['type'] == args.article_type),
                "{main_keyword}の{sub_keyword}について"
            )
        }
    else:
        # 自動モード
        print("\n🤖 自動モード")
        combination = get_unused_combination(data)
        
        if combination is None:
            print("❌ 使用可能なキーワード組み合わせがありません")
            sys.exit(1)
    
    # タイトルを生成
    title = generate_title(combination)
    
    # プロンプトを生成
    prompt = generate_prompt(combination)
    
    print(f"\n✅ 選択されたキーワード:")
    print(f"   メインKW: {combination['main_keyword']}")
    print(f"   サブKW: {combination['sub_keyword']}")
    print(f"   記事タイプ: {combination['article_type']}")
    print(f"\n📝 タイトル: {title}")
    
    # AI記事を生成
    article_content = generate_article_with_ai(prompt)
    
    # 記事を保存
    output_file = save_article(title, article_content)
    
    # 選択されたキーワード情報を保存
    keyword_info_file = SCRIPT_DIR / "selected-keyword.json"
    with open(keyword_info_file, 'w', encoding='utf-8') as f:
        json.dump({
            'title': title,
            'combination': combination
        }, f, ensure_ascii=False, indent=2)
    
    print(f"\n✅ 完了！")


if __name__ == "__main__":
    main()
