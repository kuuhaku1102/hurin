<?php
/**
 * 都道府県データ定義
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 47都道府県のデータを返す
 * 
 * @return array 都道府県データの配列
 */
function hurin_get_prefectures() {
    return array(
        array( 'id' => 1,  'name' => '北海道', 'slug' => 'hokkaido',   'region' => '北海道' ),
        array( 'id' => 2,  'name' => '青森県', 'slug' => 'aomori',     'region' => '東北' ),
        array( 'id' => 3,  'name' => '岩手県', 'slug' => 'iwate',      'region' => '東北' ),
        array( 'id' => 4,  'name' => '宮城県', 'slug' => 'miyagi',     'region' => '東北' ),
        array( 'id' => 5,  'name' => '秋田県', 'slug' => 'akita',      'region' => '東北' ),
        array( 'id' => 6,  'name' => '山形県', 'slug' => 'yamagata',   'region' => '東北' ),
        array( 'id' => 7,  'name' => '福島県', 'slug' => 'fukushima',  'region' => '東北' ),
        array( 'id' => 8,  'name' => '茨城県', 'slug' => 'ibaraki',    'region' => '関東' ),
        array( 'id' => 9,  'name' => '栃木県', 'slug' => 'tochigi',    'region' => '関東' ),
        array( 'id' => 10, 'name' => '群馬県', 'slug' => 'gunma',      'region' => '関東' ),
        array( 'id' => 11, 'name' => '埼玉県', 'slug' => 'saitama',    'region' => '関東' ),
        array( 'id' => 12, 'name' => '千葉県', 'slug' => 'chiba',      'region' => '関東' ),
        array( 'id' => 13, 'name' => '東京都', 'slug' => 'tokyo',      'region' => '関東' ),
        array( 'id' => 14, 'name' => '神奈川県', 'slug' => 'kanagawa', 'region' => '関東' ),
        array( 'id' => 15, 'name' => '新潟県', 'slug' => 'niigata',    'region' => '中部' ),
        array( 'id' => 16, 'name' => '富山県', 'slug' => 'toyama',     'region' => '中部' ),
        array( 'id' => 17, 'name' => '石川県', 'slug' => 'ishikawa',   'region' => '中部' ),
        array( 'id' => 18, 'name' => '福井県', 'slug' => 'fukui',      'region' => '中部' ),
        array( 'id' => 19, 'name' => '山梨県', 'slug' => 'yamanashi',  'region' => '中部' ),
        array( 'id' => 20, 'name' => '長野県', 'slug' => 'nagano',     'region' => '中部' ),
        array( 'id' => 21, 'name' => '岐阜県', 'slug' => 'gifu',       'region' => '中部' ),
        array( 'id' => 22, 'name' => '静岡県', 'slug' => 'shizuoka',   'region' => '中部' ),
        array( 'id' => 23, 'name' => '愛知県', 'slug' => 'aichi',      'region' => '中部' ),
        array( 'id' => 24, 'name' => '三重県', 'slug' => 'mie',        'region' => '近畿' ),
        array( 'id' => 25, 'name' => '滋賀県', 'slug' => 'shiga',      'region' => '近畿' ),
        array( 'id' => 26, 'name' => '京都府', 'slug' => 'kyoto',      'region' => '近畿' ),
        array( 'id' => 27, 'name' => '大阪府', 'slug' => 'osaka',      'region' => '近畿' ),
        array( 'id' => 28, 'name' => '兵庫県', 'slug' => 'hyogo',      'region' => '近畿' ),
        array( 'id' => 29, 'name' => '奈良県', 'slug' => 'nara',       'region' => '近畿' ),
        array( 'id' => 30, 'name' => '和歌山県', 'slug' => 'wakayama', 'region' => '近畿' ),
        array( 'id' => 31, 'name' => '鳥取県', 'slug' => 'tottori',    'region' => '中国' ),
        array( 'id' => 32, 'name' => '島根県', 'slug' => 'shimane',    'region' => '中国' ),
        array( 'id' => 33, 'name' => '岡山県', 'slug' => 'okayama',    'region' => '中国' ),
        array( 'id' => 34, 'name' => '広島県', 'slug' => 'hiroshima',  'region' => '中国' ),
        array( 'id' => 35, 'name' => '山口県', 'slug' => 'yamaguchi',  'region' => '中国' ),
        array( 'id' => 36, 'name' => '徳島県', 'slug' => 'tokushima',  'region' => '四国' ),
        array( 'id' => 37, 'name' => '香川県', 'slug' => 'kagawa',     'region' => '四国' ),
        array( 'id' => 38, 'name' => '愛媛県', 'slug' => 'ehime',      'region' => '四国' ),
        array( 'id' => 39, 'name' => '高知県', 'slug' => 'kochi',      'region' => '四国' ),
        array( 'id' => 40, 'name' => '福岡県', 'slug' => 'fukuoka',    'region' => '九州' ),
        array( 'id' => 41, 'name' => '佐賀県', 'slug' => 'saga',       'region' => '九州' ),
        array( 'id' => 42, 'name' => '長崎県', 'slug' => 'nagasaki',   'region' => '九州' ),
        array( 'id' => 43, 'name' => '熊本県', 'slug' => 'kumamoto',   'region' => '九州' ),
        array( 'id' => 44, 'name' => '大分県', 'slug' => 'oita',       'region' => '九州' ),
        array( 'id' => 45, 'name' => '宮崎県', 'slug' => 'miyazaki',   'region' => '九州' ),
        array( 'id' => 46, 'name' => '鹿児島県', 'slug' => 'kagoshima', 'region' => '九州' ),
        array( 'id' => 47, 'name' => '沖縄県', 'slug' => 'okinawa',    'region' => '沖縄' ),
    );
}

/**
 * スラッグから都道府県データを取得
 * 
 * @param string $slug 都道府県スラッグ
 * @return array|null 都道府県データまたはnull
 */
function hurin_get_prefecture_by_slug( $slug ) {
    $prefectures = hurin_get_prefectures();
    foreach ( $prefectures as $pref ) {
        if ( $pref['slug'] === $slug ) {
            return $pref;
        }
    }
    return null;
}

/**
 * 地域別に都道府県をグループ化して返す
 * 
 * @return array 地域別都道府県データ
 */
function hurin_get_prefectures_by_region() {
    $prefectures = hurin_get_prefectures();
    $grouped = array();
    
    foreach ( $prefectures as $pref ) {
        $region = $pref['region'];
        if ( ! isset( $grouped[$region] ) ) {
            $grouped[$region] = array();
        }
        $grouped[$region][] = $pref;
    }
    
    return $grouped;
}
