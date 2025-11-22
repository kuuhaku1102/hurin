<?php
/**
 * AI生成記事をWordPressに投稿するスクリプト
 */

// 環境変数を取得
$wp_url = getenv('WP_URL') ?: getenv('WP_URL');
$wp_user = getenv('WP_USER') ?: getenv('WP_USER');
$wp_password = getenv('WP_APP_PASSWORD') ?: getenv('WP_PASS');

if ( ! $wp_url || ! $wp_user || ! $wp_password ) {
    echo "エラー: 環境変数が設定されていません\n";
    exit(1);
}

// generated-article.mdから記事を読み込み
$content = file_get_contents('generated-article.md');
if ( ! $content ) {
    echo "エラー: generated-article.mdが見つかりません\n";
    exit(1);
}

// タイトルを抽出
$lines = explode("\n", $content);
$title = '';
foreach ( $lines as $line ) {
    if ( strpos($line, '# ') === 0 ) {
        $title = trim(str_replace('# ', '', $line));
        break;
    }
}

if ( ! $title ) {
    $title = '不倫募集掲示板の賢い使い方';
}

echo "=== AI生成記事をWordPressに投稿 ===\n\n";
echo "記事タイトル: {$title}\n";
echo "文字数: " . strlen($content) . "文字\n\n";

// カテゴリーを取得/作成
$category_name = '不倫募集掲示板の活用法';
$category_id = get_or_create_category( $wp_url, $wp_user, $wp_password, $category_name );

// タグを取得/作成
$tag_names = array('不倫募集', 'セカンドパートナー', '活用法');
$tag_ids = array();
foreach ( $tag_names as $tag_name ) {
    $tag_id = get_or_create_tag( $wp_url, $wp_user, $wp_password, $tag_name );
    if ( $tag_id ) {
        $tag_ids[] = $tag_id;
    }
}

// 投稿データを作成
$post_data = array(
    'title' => $title,
    'content' => $content,
    'status' => 'publish',
    'categories' => array( $category_id ),
    'tags' => $tag_ids,
);

// WordPressに投稿
echo "WordPressに投稿中...\n";
$result = create_post( $wp_url, $wp_user, $wp_password, $post_data );

if ( $result ) {
    echo "✅ 成功: AI生成記事を投稿しました\n";
    echo "タイトル: {$title}\n";
    echo "URL: {$wp_url}/?p={$result['id']}\n";
} else {
    echo "❌ エラー: 記事の投稿に失敗しました\n";
    exit(1);
}

/**
 * カテゴリーを取得または作成
 */
function get_or_create_category( $wp_url, $wp_user, $wp_password, $category_name ) {
    $url = $wp_url . '/wp-json/wp/v2/categories?search=' . urlencode( $category_name );
    $response = wp_api_request( $url, 'GET', $wp_user, $wp_password );
    
    if ( $response && count( $response ) > 0 ) {
        return $response[0]['id'];
    }
    
    $url = $wp_url . '/wp-json/wp/v2/categories';
    $data = array( 'name' => $category_name );
    $response = wp_api_request( $url, 'POST', $wp_user, $wp_password, $data );
    
    return $response ? $response['id'] : 1;
}

/**
 * タグを取得または作成
 */
function get_or_create_tag( $wp_url, $wp_user, $wp_password, $tag_name ) {
    $url = $wp_url . '/wp-json/wp/v2/tags?search=' . urlencode( $tag_name );
    $response = wp_api_request( $url, 'GET', $wp_user, $wp_password );
    
    if ( $response && count( $response ) > 0 ) {
        return $response[0]['id'];
    }
    
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
 * WordPress REST APIリクエスト
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
