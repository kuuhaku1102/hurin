<?php
/**
 * 都道府県別ページテンプレート
 * 
 * @package Hurin_Theme
 */

$prefecture_slug = get_query_var('prefecture');
$prefecture_data = hurin_get_prefecture_data( $prefecture_slug );

if ( ! $prefecture_data ) {
    // 404ページにリダイレクト
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( '404' );
    return;
}

$prefecture_name = $prefecture_data['name'];
$region_name = $prefecture_data['region'];

// 全女性データからランダムに8-12件取得（都道府県条件なし）
$girls = hurin_get_random_girls();

// ヘッダーの読み込み
get_header();
?>

  <!-- ページタイトル -->
  <section class="page-hero">
    <div class="page-hero-content">
      <h1 class="page-title"><?php echo esc_html( $prefecture_name ); ?>の不倫募集掲示板</h1>
      <p class="page-description">秘密の関係を求める既婚者が集まる、<?php echo esc_html( $prefecture_name ); ?>専用の出会いの場</p>
    </div>
  </section>

  <!-- 女性一覧セクション -->
  <?php if ( ! empty( $girls ) ) : ?>
    <section class="girls-list">
      <?php foreach ( $girls as $girl ) :
        // サムネイルURL（/images から始まるパス想定）
        $thumb = '';
        if ( ! empty( $girl->samune ) ) {
          // samune が /images/〜 のようなパスの場合、サイトURLを前に付ける
          if ( strpos( $girl->samune, 'http' ) === 0 ) {
            $thumb = esc_url( $girl->samune );
          } else {
            $thumb = esc_url( home_url( $girl->samune ) );
          }
        }
      ?>
      <article class="girl">
        <?php if ( $thumb ) : ?>
          <div class="girl-thumb">
            <img src="<?php echo $thumb; ?>" alt="<?php echo esc_attr( $girl->name ); ?>">
          </div>
        <?php endif; ?>
        <div class="girl-body">
          <h2 class="girl-name"><?php echo esc_html( $girl->name ); ?></h2>

          <div class="girl-meta">
            <?php if ( $girl->age !== null && $girl->age !== '' ) : ?>
              <span><span class="girl-meta-label">年齢</span><?php echo esc_html( $girl->age ); ?></span>
            <?php endif; ?>
            <?php if ( $girl->figure !== null && $girl->figure !== '' ) : ?>
              <span><span class="girl-meta-label">体型</span><?php echo esc_html( $girl->figure ); ?></span>
            <?php endif; ?>
            <?php if ( $girl->character !== null && $girl->character !== '' ) : ?>
              <span><span class="girl-meta-label">性格</span><?php echo esc_html( $girl->character ); ?></span>
            <?php endif; ?>
          </div>

          <?php if ( $girl->comment !== null && $girl->comment !== '' ) : ?>
            <p class="girl-comment"><?php echo esc_html( $girl->comment ); ?></p>
          <?php endif; ?>

          <?php if ( $girl->url !== null && $girl->url !== '' ) : ?>
            <p class="girl-link">
              <a href="<?php echo esc_url( $girl->url ); ?>" target="_blank" rel="noopener">プロフィールを見る</a>
            </p>
          <?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </section>
  <?php else : ?>
    <p>表示できるデータがありません。</p>
  <?php endif; ?>

  <!-- 都道府県別SEOコンテンツ -->
  <section class="prefecture-seo-section">
    <div class="seo-article-container">
      
      <?php
      // 都道府県別SEOコンテンツを読み込み
      require_once get_template_directory() . '/inc/prefecture-seo-content.php';
      hurin_output_prefecture_seo_content( $prefecture_name );
      ?>

    </div>
  </section>

  <!-- 他の都道府県へのリンク -->
  <section class="other-prefectures-section">
    <div class="section-header">
      <h2 class="section-title">他の都道府県を見る</h2>
      <p class="section-description">お住まいの地域や気になる地域を選択してください</p>
    </div>

    <?php
    $all_prefectures = hurin_get_all_prefectures();
    // 現在の都道府県を除外
    $other_prefectures = array_filter( $all_prefectures, function( $pref ) use ( $prefecture_slug ) {
      return $pref['slug'] !== $prefecture_slug;
    });
    // ランダムに10件取得
    shuffle( $other_prefectures );
    $other_prefectures = array_slice( $other_prefectures, 0, 10 );
    ?>

    <div class="other-prefectures-list">
      <?php foreach ( $other_prefectures as $pref ) : ?>
        <a href="<?php echo esc_url( hurin_get_prefecture_url( $pref['slug'] ) ); ?>" class="other-prefecture-link">
          <?php echo esc_html( $pref['name'] ); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="back-to-top-link">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">← トップページに戻る</a>
    </div>
  </section>

<?php
// フッターの読み込み
get_footer();
