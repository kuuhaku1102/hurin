#!/usr/bin/env python3
"""
キーワード管理システム
- 未使用のキーワード組み合わせを自動選択
- タイトルを自動生成
- 使用済みキーワードを記録
"""

import json
import random
import os
from pathlib import Path

# スクリプトのディレクトリを取得
SCRIPT_DIR = Path(__file__).parent
KEYWORDS_FILE = SCRIPT_DIR / "keywords.json"


def load_keywords():
    """キーワードデータを読み込む"""
    with open(KEYWORDS_FILE, 'r', encoding='utf-8') as f:
        return json.load(f)


def save_keywords(data):
    """キーワードデータを保存する"""
    with open(KEYWORDS_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)


def get_unused_combination(data):
    """未使用のキーワード組み合わせを取得"""
    main_keywords = data['main_keywords']
    sub_keywords = data['sub_keywords']
    article_types = data['article_types']
    used_combinations = data['used_combinations']
    
    # すべての可能な組み合わせを生成
    all_combinations = []
    for main_kw in main_keywords:
        for sub_kw in sub_keywords:
            for article_type in article_types:
                combination = {
                    'main_keyword': main_kw,
                    'sub_keyword': sub_kw,
                    'article_type': article_type['type'],
                    'title_pattern': article_type['title_pattern']
                }
                # 使用済みかチェック
                combination_str = f"{main_kw}|{sub_kw}|{article_type['type']}"
                if combination_str not in used_combinations:
                    all_combinations.append(combination)
    
    if not all_combinations:
        print("⚠️  すべてのキーワード組み合わせが使用済みです。")
        print("   used_combinations をリセットしますか？ (y/n)")
        # 自動的にリセット
        data['used_combinations'] = []
        save_keywords(data)
        print("✅ リセットしました。再度実行してください。")
        return None
    
    # ランダムに1つ選択
    selected = random.choice(all_combinations)
    
    # 使用済みとして記録
    combination_str = f"{selected['main_keyword']}|{selected['sub_keyword']}|{selected['article_type']}"
    data['used_combinations'].append(combination_str)
    save_keywords(data)
    
    return selected


def generate_title(combination):
    """タイトルを生成"""
    title_pattern = combination['title_pattern']
    title = title_pattern.format(
        main_keyword=combination['main_keyword'],
        sub_keyword=combination['sub_keyword']
    )
    return title


def generate_prompt(combination):
    """記事生成用のプロンプトを生成"""
    title = generate_title(combination)
    
    # 記事タイプに応じたプロンプトを生成
    article_type = combination['article_type']
    main_kw = combination['main_keyword']
    sub_kw = combination['sub_keyword']
    
    prompts = {
        'usage': f"""
以下のタイトルで、実用的な活用方法を解説するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}の基本的な使い方
- {sub_kw}に特化した活用テクニック
- 実際の成功例
- よくある失敗と対策
- 安全に利用するためのポイント

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 人間味のある自然な文章（「実は」「正直なところ」などの口語表現を使う）
""",
        'experience': f"""
以下のタイトルで、実際の体験談を元にしたブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}を使い始めたきっかけ
- {sub_kw}での具体的な体験
- 成功するまでの試行錯誤
- 学んだこと・気づいたこと
- これから始める人へのアドバイス

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 体験談風の親しみやすい文章（「私も最初は〜」「実際にやってみて〜」など）
""",
        'qa': f"""
以下のタイトルで、Q&A形式で疑問に答えるブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}に関するよくある質問5〜7個
- {sub_kw}に特化した疑問への回答
- 初心者が気になるポイント
- 上級者向けのTips
- まとめ

文字数: 2500文字以上
形式: HTML（h2でQ、h3でA、p, strong, ul, liタグを使用）
文体: 丁寧で分かりやすい解説（「〜ですね」「〜と思います」など）
""",
        'comparison': f"""
以下のタイトルで、複数の選択肢を比較するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}の主要な選択肢3〜5個
- {sub_kw}の観点からの比較
- それぞれのメリット・デメリット
- どんな人におすすめか
- 最終的な結論

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 客観的で公平な比較（「一方で〜」「他方では〜」など）
""",
        'ranking': f"""
以下のタイトルで、ランキング形式で紹介するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}のおすすめランキング5位まで
- {sub_kw}の観点からの評価基準
- 各順位の詳細な解説
- 選び方のポイント
- まとめ

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 明るく前向きな紹介（「第1位は〜」「特におすすめなのは〜」など）
""",
        'risk': f"""
以下のタイトルで、リスクと対策を解説するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}の主なリスク5〜7個
- {sub_kw}に関連する注意点
- 各リスクへの具体的な対策
- トラブル事例と解決方法
- 安全に利用するための心構え

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 警告的だが冷静な解説（「注意が必要です」「〜を心がけましょう」など）
""",
        'beginner': f"""
以下のタイトルで、初心者向けの基礎知識を解説するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}とは何か
- {sub_kw}から始める理由
- 初心者が知っておくべき基礎知識
- ステップバイステップの始め方
- よくある初心者の失敗と対策

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 優しく丁寧な解説（「まずは〜」「最初は〜」など）
""",
        'advanced': f"""
以下のタイトルで、上級者向けのテクニックを解説するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}の高度な活用方法
- {sub_kw}を使った効果的なテクニック
- 上級者だけが知っているコツ
- さらに成果を上げるための工夫
- 注意すべき落とし穴

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 専門的だが分かりやすい解説（「実は〜」「ここがポイントです」など）
""",
        'spot': f"""
以下のタイトルで、待ち合わせ場所やデートスポットを紹介するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}で出会った後のおすすめスポット5選
- {sub_kw}に適した場所の選び方
- 各スポットの詳細情報（アクセス、雰囲気など）
- 注意点とマナー
- まとめ

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 明るく楽しい紹介（「おすすめは〜」「雰囲気が良いのは〜」など）
""",
        'manner': f"""
以下のタイトルで、マナーやルールを解説するブログ記事を書いてください。

タイトル: {title}

記事の内容:
- {main_kw}で守るべき基本マナー
- {sub_kw}に関連するエチケット
- やってはいけないNG行動
- 相手に好印象を与えるポイント
- トラブルを避けるための心構え

文字数: 2500文字以上
形式: HTML（h2, h3, p, strong, ul, liタグを使用）
文体: 丁寧で教育的な解説（「〜しましょう」「〜は避けるべきです」など）
"""
    }
    
    return prompts.get(article_type, prompts['usage'])


def main():
    """メイン処理"""
    print("="*50)
    print("🔑 キーワード自動選択システム")
    print("="*50)
    
    # キーワードデータを読み込む
    data = load_keywords()
    
    # 未使用の組み合わせを取得
    combination = get_unused_combination(data)
    
    if combination is None:
        return
    
    # タイトルを生成
    title = generate_title(combination)
    
    # プロンプトを生成
    prompt = generate_prompt(combination)
    
    # 結果を表示
    print(f"\n✅ 選択されたキーワード組み合わせ:")
    print(f"   メインKW: {combination['main_keyword']}")
    print(f"   サブKW: {combination['sub_keyword']}")
    print(f"   記事タイプ: {combination['article_type']}")
    print(f"\n📝 生成されたタイトル:")
    print(f"   {title}")
    print(f"\n📄 プロンプト:")
    print(prompt)
    
    # 結果をファイルに保存
    output_file = SCRIPT_DIR / "selected-keyword.json"
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump({
            'title': title,
            'prompt': prompt,
            'combination': combination
        }, f, ensure_ascii=False, indent=2)
    
    print(f"\n💾 結果を保存しました: {output_file}")
    print(f"\n📊 使用済み組み合わせ数: {len(data['used_combinations'])}")
    total_combinations = len(data['main_keywords']) * len(data['sub_keywords']) * len(data['article_types'])
    print(f"   全組み合わせ数: {total_combinations}")
    print(f"   残り: {total_combinations - len(data['used_combinations'])}")


if __name__ == "__main__":
    main()
