<?php
/**
 * WordPress REST API連携スクリプト
 * 記事を自動生成してWordPressに投稿する
 * 
 * 使用方法:
 * php post-to-wordpress.php <article_type>
 * 
 * article_type:
 * - usage: 不倫募集掲示板の活用法
 * - spot: 都道府県別の出会いスポット紹介
 * - manner: 大人の関係のマナー
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== WordPress自動投稿スクリプト ===\n\n";

// 引数チェック
if ( $argc < 2 ) {
    echo "使用方法: php post-to-wordpress.php <article_type>\n";
    echo "article_type: usage, spot, manner\n";
    exit(1);
}

$article_type = $argv[1];
echo "記事タイプ: $article_type\n";

// テンプレートファイルを読み込み
echo "テンプレートファイルを読み込み中...\n";
require_once __DIR__ . '/../inc/prefecture-data.php';
require_once __DIR__ . '/../inc/article-templates.php';
echo "テンプレートファイルの読み込み完了\n";

// 記事データを取得
echo "記事データを生成中...\n";
switch ( $article_type ) {
    case 'usage':
        $article = hurin_get_template_usage_guide();
        break;
    case 'spot':
        $article = hurin_get_template_spot_guide();
        break;
    case 'manner':
        $article = hurin_get_template_manner_guide();
        break;
    default:
        echo "エラー: 不正な article_type です\n";
        exit(1);
}

echo "記事タイトル: {$article['title']}\n";
echo "カテゴリー: {$article['category']}\n\n";

// WordPress REST API設定
echo "WordPress設定を確認中...\n";
$wp_url = getenv('WP_URL') ?: 'https://volitionmagazine.com';
$wp_user = getenv('WP_USER') ?: 'admin';
$wp_password = getenv('WP_APP_PASSWORD') ?: getenv('WP_PASS'); // アプリケーションパスワード

echo "WP_URL: $wp_url\n";
echo "WP_USER: $wp_user\n";
echo "WP_PASS: " . (empty($wp_password) ? "未設定" : "設定済み(" . strlen($wp_password) . "文字)") . "\n\n";

if ( empty( $wp_password ) ) {
    echo "エラー: WP_APP_PASSWORD 環境変数が設定されていません\n";
    exit(1);
}

// カテゴリーIDを取得または作成
echo "カテゴリーを取得/作成中...\n";
$category_id = get_or_create_category( $wp_url, $wp_user, $wp_password, $article['category'] );

if ( ! $category_id ) {
    echo "エラー: カテゴリーの取得/作成に失敗しました\n";
    exit(1);
}
echo "カテゴリーID: $category_id\n";

// タグIDを取得または作成
echo "タグを取得/作成中...\n";
$tag_ids = array();
foreach ( $article['tags'] as $tag_name ) {
    $tag_id = get_or_create_tag( $wp_url, $wp_user, $wp_password, $tag_name );
    if ( $tag_id ) {
        $tag_ids[] = $tag_id;
    }
}

// 投稿データを作成
$post_data = array(
    'title' => $article['title'],
    'content' => $article['content'],
    'status' => 'publish',
    'categories' => array( $category_id ),
    'tags' => $tag_ids,
);

echo "タグID: " . implode(', ', $tag_ids) . "\n\n";

// WordPress REST APIに投稿
echo "WordPressに投稿中...\n";
$result = create_post( $wp_url, $wp_user, $wp_password, $post_data );

if ( $result ) {
    echo "成功: 記事を投稿しました\n";
    echo "タイトル: {$article['title']}\n";
    echo "カテゴリー: {$article['category']}\n";
    echo "URL: {$wp_url}/?p={$result['id']}\n";
} else {
    echo "エラー: 記事の投稿に失敗しました\n";
    exit(1);
}

/**
 * カテゴリーを取得または作成
 */
function get_or_create_category( $wp_url, $wp_user, $wp_password, $category_name ) {
    // 既存カテゴリーを検索
    $url = $wp_url . '/wp-json/wp/v2/categories?search=' . urlencode( $category_name );
    $response = wp_api_request( $url, 'GET', $wp_user, $wp_password );
    
    if ( $response && count( $response ) > 0 ) {
        return $response[0]['id'];
    }
    
    // カテゴリーを新規作成
    $url = $wp_url . '/wp-json/wp/v2/categories';
    $data = array( 'name' => $category_name );
    $response = wp_api_request( $url, 'POST', $wp_user, $wp_password, $data );
    
    return $response ? $response['id'] : null;
}

/**
 * タグを取得または作成
 */
function get_or_create_tag( $wp_url, $wp_user, $wp_password, $tag_name ) {
    // 既存タグを検索
    $url = $wp_url . '/wp-json/wp/v2/tags?search=' . urlencode( $tag_name );
    $response = wp_api_request( $url, 'GET', $wp_user, $wp_password );
    
    if ( $response && count( $response ) > 0 ) {
        return $response[0]['id'];
    }
    
    // タグを新規作成
    $url = $wp_url . '/wp-json/wp/v2/tags';
    $data = array( 'name' => $tag_name );
    $response = wp_api_request( $url, 'POST', $wp_user, $wp_password, $data );
    
    return $response ? $response['id'] : null;
}

/**
 * 投稿を作成
 */
function create_post( $wp_url, $wp_user, $wp_password, $post_data ) {
    $url = $wp_url . '/wp-json/wp/v2/posts';
    return wp_api_request( $url, 'POST', $wp_user, $wp_password, $post_data );
}

/**
 * WordPress REST APIリクエストを送信
 */
function wp_api_request( $url, $method, $wp_user, $wp_password, $data = null ) {
    $ch = curl_init();
    
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
    curl_setopt( $ch, CURLOPT_USERPWD, $wp_user . ':' . $wp_password );
    
    if ( $method === 'POST' ) {
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
    }
    
    $response = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );
    
    if ( $http_code >= 200 && $http_code < 300 ) {
        return json_decode( $response, true );
    }
    
    echo "API Error (HTTP {$http_code}): {$response}\n";
    return null;
}
