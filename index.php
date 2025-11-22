<?php
/**
 * 不倫募集掲示板 - トップページ
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo('name'); ?> - 大人の秘密の出会い</title>
  <meta name="description" content="既婚者専用の不倫募集掲示板。全国47都道府県対応。秘密厳守で安全な出会いをサポートします。">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrap">
  
  <!-- ヘッダー -->
  <header class="site-header">
    <div class="header-content">
      <h1 class="site-title"><?php bloginfo('name'); ?></h1>
      <p class="site-tagline">大人の秘密の出会い - 既婚者専用掲示板</p>
    </div>
  </header>

  <!-- メインビジュアル -->
  <section class="hero-section">
    <div class="hero-content">
      <h2 class="hero-title">秘密の関係を、<br>あなたの街で</h2>
      <p class="hero-description">全国47都道府県対応。匿名性を守りながら、<br class="sp-only">理想の相手と出会えます。</p>
      <a href="#regions" class="hero-cta">お住まいの地域を選ぶ</a>
    </div>
  </section>

  <!-- SEOコンテンツセクション -->
  <section class="seo-content-section">
    <div class="section-header">
      <h2 class="section-title">安全な不倫のための<br class="sp-only">ガイド</h2>
      <p class="section-description">バレないための知識と、おすすめの出会い方をご紹介</p>
    </div>

    <div class="content-grid">
      
      <article class="content-card">
        <div class="content-card-icon">🔒</div>
        <h3 class="content-card-title">不倫がバレない<br>7つの鉄則</h3>
        <p class="content-card-text">
          不倫関係を続けるために最も重要なのは「バレないこと」。スマホの管理、行動パターンの変化、SNSの使い方など、絶対に守るべき7つのルールを詳しく解説します。特に既婚者同士の場合は、お互いの立場を理解し合うことが大切です。
        </p>
        <ul class="content-card-list">
          <li>スマホのロックとアプリ管理</li>
          <li>クレジットカード明細の注意点</li>
          <li>行動パターンを変えない工夫</li>
          <li>SNSでの繋がり方のルール</li>
        </ul>
      </article>

      <article class="content-card">
        <div class="content-card-icon">💝</div>
        <h3 class="content-card-title">既婚者が出会える<br>おすすめの場所</h3>
        <p class="content-card-text">
          不倫相手との出会いは、慎重に場所を選ぶ必要があります。知人に遭遇しにくく、プライバシーが守られる空間を選びましょう。ホテルのラウンジ、郊外のカフェ、個室のあるレストランなど、シチュエーション別におすすめスポットをご紹介。
        </p>
        <ul class="content-card-list">
          <li>高級ホテルのラウンジバー</li>
          <li>郊外の隠れ家カフェ</li>
          <li>完全個室のダイニング</li>
          <li>ビジネスホテルの活用法</li>
        </ul>
      </article>

      <article class="content-card">
        <div class="content-card-icon">👤</div>
        <h3 class="content-card-title">理想の相手の<br>見つけ方</h3>
        <p class="content-card-text">
          既婚者専用の掲示板では、お互いの事情を理解し合える相手と出会えます。プロフィールの書き方、メッセージの送り方、初回デートの設定方法まで、安全に関係を始めるためのステップを丁寧に解説します。
        </p>
        <ul class="content-card-list">
          <li>魅力的なプロフィール作成術</li>
          <li>好印象を与えるメッセージ</li>
          <li>初回デートの場所選び</li>
          <li>長続きする関係の築き方</li>
        </ul>
      </article>

      <article class="content-card">
        <div class="content-card-icon">⚠️</div>
        <h3 class="content-card-title">トラブルを避ける<br>ための注意点</h3>
        <p class="content-card-text">
          不倫関係には様々なリスクが伴います。金銭トラブル、ストーカー被害、相手の配偶者からの追及など、起こりうる問題とその対処法を事前に知っておくことが重要です。安全な関係を維持するための心構えをお伝えします。
        </p>
        <ul class="content-card-list">
          <li>金銭の貸し借りは絶対NG</li>
          <li>本名・住所は教えない</li>
          <li>感情的になりすぎない距離感</li>
          <li>終わり方も考えておく</li>
        </ul>
      </article>

    </div>
  </section>

  <!-- 都道府県一覧セクション -->
  <section class="regions-section" id="regions">
    <div class="section-header">
      <h2 class="section-title">お住まいの地域を<br class="sp-only">お選びください</h2>
      <p class="section-description">全国47都道府県、あなたの街で理想の相手が見つかります</p>
    </div>

    <?php
    $prefectures_by_region = hurin_get_prefectures_by_region();
    $region_order = array('北海道', '東北', '関東', '中部', '近畿', '中国', '四国', '九州', '沖縄');
    ?>

    <div class="regions-grid">
      <?php foreach ( $region_order as $region_name ) : ?>
        <?php if ( isset( $prefectures_by_region[$region_name] ) ) : ?>
          <div class="region-block">
            <h3 class="region-name"><?php echo esc_html( $region_name ); ?></h3>
            <ul class="prefecture-list">
              <?php foreach ( $prefectures_by_region[$region_name] as $pref ) : ?>
                <li>
                  <a href="<?php echo esc_url( hurin_get_prefecture_url( $pref['slug'] ) ); ?>" class="prefecture-link">
                    <?php echo esc_html( $pref['name'] ); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- フッター -->
  <footer class="footer">
    <div class="footer-content">
      <p class="footer-note">※ 当サイトは18歳以上の既婚者専用です。プライバシーは厳守されます。</p>
      <p class="footer-copyright">&copy; <?php echo date( 'Y' ); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
    </div>
  </footer>

</div>

<?php wp_footer(); ?>
</body>
</html>
