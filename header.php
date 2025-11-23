<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//matomo.sakura.ne.jp/matomo/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '8']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrap">
  
  <!-- ヘッダー -->
  <header class="site-header">
    <div class="header-content">
      <div class="site-logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <h1 class="site-title"><?php bloginfo('name'); ?></h1>
        </a>
      </div>
      <p class="site-tagline">大人の秘密の出会い - 既婚者専用不倫募集掲示板</p>
      
      <!-- ナビゲーションメニュー -->
      <nav class="main-nav">
        <ul class="nav-menu">
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a></li>
          <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">コラム</a></li>
        </ul>
      </nav>
      
      <?php
      // パンくずリスト（都道府県ページの場合のみ表示）
      if ( is_page_template( 'page-prefecture.php' ) ) {
        $current_url = $_SERVER['REQUEST_URI'];
        if ( preg_match( '#/prefecture/([^/]+)/?#', $current_url, $matches ) ) {
          $prefecture_slug = $matches[1];
          $prefecture_data = hurin_get_prefecture_by_slug( $prefecture_slug );
          if ( $prefecture_data ) {
            ?>
            <nav class="breadcrumb">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
              <span class="separator">›</span>
              <span class="current"><?php echo esc_html( $prefecture_data['name'] ); ?></span>
            </nav>
            <?php
          }
        }
      }
      ?>
    </div>
  </header>
