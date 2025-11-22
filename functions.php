<?php
/**
 * Mama Gen Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 都道府県データを読み込み
require_once get_template_directory() . '/inc/prefecture-data.php';

// CSS 読み込み
function mama_gen_enqueue_styles() {
    wp_enqueue_style( 'mama-gen-style', get_stylesheet_uri(), array(), '1.0' );
    
    // 都道府県ページの場合は専用CSSも読み込み
    if ( is_page() && get_query_var('prefecture') ) {
        wp_enqueue_style( 'prefecture-style', get_template_directory_uri() . '/assets/css/prefecture.css', array('mama-gen-style'), '1.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'mama_gen_enqueue_styles' );

/**
 * 都道府県ページ用のリライトルールを追加
 */
function hurin_add_rewrite_rules() {
    add_rewrite_rule(
        '^prefecture/([^/]+)/?$',
        'index.php?prefecture=$matches[1]',
        'top'
    );
}
add_action( 'init', 'hurin_add_rewrite_rules' );

/**
 * クエリ変数に prefecture を追加
 */
function hurin_query_vars( $vars ) {
    $vars[] = 'prefecture';
    return $vars;
}
add_filter( 'query_vars', 'hurin_query_vars' );

/**
 * テンプレート選択をカスタマイズ
 */
function hurin_template_include( $template ) {
    $prefecture_slug = get_query_var('prefecture');
    
    if ( $prefecture_slug ) {
        $prefecture_data = hurin_get_prefecture_by_slug( $prefecture_slug );
        
        if ( $prefecture_data ) {
            $new_template = locate_template( array( 'page-prefecture.php' ) );
            if ( $new_template ) {
                return $new_template;
            }
        } else {
            // 存在しない都道府県の場合は404
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            return get_404_template();
        }
    }
    
    return $template;
}
add_filter( 'template_include', 'hurin_template_include' );

/**
 * データベースに prefecture カラムを追加する関数
 * （手動実行用 - 実際の運用では管理画面から実行するか、プラグインで実装）
 */
function hurin_add_prefecture_column() {
    global $wpdb;
    $table = $wpdb->prefix . 'mama_gen';
    
    // カラムが存在するか確認
    $column_exists = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = %s 
            AND TABLE_NAME = %s 
            AND COLUMN_NAME = 'prefecture'",
            DB_NAME,
            $table
        )
    );
    
    if ( empty( $column_exists ) ) {
        $wpdb->query(
            "ALTER TABLE {$table} ADD COLUMN prefecture VARCHAR(50) DEFAULT NULL AFTER comment"
        );
    }
}
// 必要に応じてアクティベーション時に実行
// register_activation_hook( __FILE__, 'hurin_add_prefecture_column' );

/**
 * 都道府県別に女性データを取得
 * 
 * @param string $prefecture_name 都道府県名
 * @return array 女性データの配列
 */
function hurin_get_girls_by_prefecture( $prefecture_name ) {
    global $wpdb;
    $table = $wpdb->prefix . 'mama_gen';
    
    $girls = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$table} 
            WHERE post_status = 'publish' 
            AND prefecture = %s 
            ORDER BY id DESC",
            $prefecture_name
        )
    );
    
    return $girls;
}

/**
 * 都道府県のURL生成
 * 
 * @param string $slug 都道府県スラッグ
 * @return string URL
 */
function hurin_get_prefecture_url( $slug ) {
    return home_url( '/prefecture/' . $slug . '/' );
}
