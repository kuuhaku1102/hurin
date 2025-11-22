<?php
/**
 * 都道府県別ページテンプレート
 */

$prefecture_slug = get_query_var('prefecture');
$prefecture_data = hurin_get_prefecture_by_slug( $prefecture_slug );

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

// 該当都道府県の女性データをランダムに8-12件取得
$girls = hurin_get_random_girls_by_prefecture( $prefecture_name );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo esc_html( $prefecture_name ); ?>の不倫募集掲示板 | <?php bloginfo('name'); ?></title>
  <meta name="description" content="<?php echo esc_attr( $prefecture_name ); ?>で不倫相手を探すなら当サイト。既婚者専用の安全な出会いをサポート。バレない方法やおすすめスポットもご紹介。">
  <?php wp_head(); ?>
</head>
<body <?php body_class('prefecture-page'); ?>>
<div class="wrap">
  
  <!-- ヘッダー -->
  <header class="site-header">
    <div class="header-content">
      <p class="site-logo"><a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a></p>
      <nav class="breadcrumb">
        <a href="<?php echo esc_url( home_url('/') ); ?>">トップ</a>
        <span class="separator">›</span>
        <span class="current"><?php echo esc_html( $prefecture_name ); ?></span>
      </nav>
    </div>
  </header>

  <!-- ページタイトル -->
  <section class="page-hero">
    <div class="page-hero-content">
      <h1 class="page-title"><?php echo esc_html( $prefecture_name ); ?>の<br class="sp-only">不倫募集掲示板</h1>
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
    <div class="section-header">
      <h2 class="section-title"><?php echo esc_html( $prefecture_name ); ?>での<br class="sp-only">不倫ガイド</h2>
      <p class="section-description">地域に特化した情報をお届けします</p>
    </div>

    <div class="seo-articles">
      
      <!-- 記事1: バレない方法 -->
      <article class="seo-article">
        <div class="article-header">
          <span class="article-tag">安全対策</span>
          <h3 class="article-title"><?php echo esc_html( $prefecture_name ); ?>で不倫がバレない方法</h3>
        </div>
        <div class="article-content">
          <p>
            <?php echo esc_html( $prefecture_name ); ?>で不倫関係を続けるには、地域特有の注意点を理解することが重要です。<?php echo esc_html( $region_name ); ?>地方は<?php 
            // 地域別の特徴を出力
            switch($region_name) {
              case '北海道':
                echo '広大な土地柄、移動距離が長くなりがちです。車での移動が多いため、ドライブレコーダーやETCの履歴に注意';
                break;
              case '東北':
                echo '地域コミュニティが密接な傾向があります。知人に遭遇しないよう、隣県での待ち合わせも検討';
                break;
              case '関東':
                echo '人口が多く匿名性が高い反面、SNSでの拡散リスクも。都心部のホテルやカフェを活用';
                break;
              case '中部':
                echo '車社会のため、駐車場での目撃に注意。郊外のビジネスホテルやロードサイドのカフェが便利';
                break;
              case '近畿':
                echo '大阪・京都・神戸など選択肢が豊富。電車移動が便利で、交通系ICカードの履歴管理に注意';
                break;
              case '中国':
                echo '地方都市が点在し、車移動が中心。ガソリンスタンドのレシートや高速道路の利用履歴に注意';
                break;
              case '四国':
                echo '橋を渡っての移動も選択肢に。本州側での待ち合わせなど、行動範囲を広げることで匿名性を確保';
                break;
              case '九州':
                echo '温泉地が多く、日帰り温泉デートも人気。ただし観光地では知人遭遇のリスクも';
                break;
              case '沖縄':
                echo '島という特性上、目撃リスクが高め。リゾートホテルの利用や、離島デートも検討';
                break;
              default:
                echo '地域の特性を理解し、慎重に行動することが大切';
            }
            ?>しましょう。
          </p>
          <h4>具体的な対策</h4>
          <ul>
            <li><strong>待ち合わせ場所の選び方</strong> - 自宅や職場から適度に離れた場所を選びましょう。<?php echo esc_html( $prefecture_name ); ?>の主要駅周辺は避け、郊外のホテルラウンジなどがおすすめです。</li>
            <li><strong>移動手段の工夫</strong> - 自家用車を使う場合は、ドライブレコーダーの映像やカーナビの履歴に注意。公共交通機関を利用する際は、交通系ICカードの履歴が残ることを意識しましょう。</li>
            <li><strong>連絡手段の管理</strong> - LINEやメールの通知設定をオフにし、専用のアプリを使うのも一案。スマホのロックは必須です。</li>
            <li><strong>時間帯の選択</strong> - 平日の昼間や、仕事終わりの時間帯を活用。休日は家族との時間を優先し、不自然な外出は避けましょう。</li>
          </ul>
        </div>
      </article>

      <!-- 記事2: おすすめスポット -->
      <article class="seo-article">
        <div class="article-header">
          <span class="article-tag">デートスポット</span>
          <h3 class="article-title"><?php echo esc_html( $prefecture_name ); ?>の不倫におすすめのスポット</h3>
        </div>
        <div class="article-content">
          <p>
            <?php echo esc_html( $prefecture_name ); ?>で不倫デートを楽しむなら、プライバシーが守られる場所選びが重要です。知人に遭遇しにくく、落ち着いた雰囲気の場所をご紹介します。
          </p>
          <h4>おすすめのデートスポット</h4>
          <ul>
            <li><strong>高級ホテルのラウンジ</strong> - <?php echo esc_html( $prefecture_name ); ?>の主要都市にある高級ホテルのラウンジは、プライバシーが確保され、大人の雰囲気を楽しめます。平日の昼間は比較的空いており、ゆっくり会話ができます。</li>
            <li><strong>郊外の隠れ家カフェ</strong> - 市街地から少し離れた場所にある、知る人ぞ知るカフェ。個室や半個室タイプの席があれば、周囲を気にせず過ごせます。</li>
            <li><strong>ビジネスホテル</strong> - <?php echo esc_html( $prefecture_name ); ?>の駅近くにあるビジネスホテルは、チェックインが簡単で目立ちません。デイユースプランを活用すれば、昼間の時間を有効に使えます。</li>
            <li><strong>ドライブスポット</strong> - 車でのデートなら、景色の良い展望台や、人気の少ない海岸線などもおすすめ。ただし、駐車場での目撃には注意が必要です。</li>
            <li><strong>個室レストラン</strong> - 完全個室のある和食店やイタリアンレストランなら、食事を楽しみながらプライベートな時間を過ごせます。</li>
          </ul>
          <h4>避けるべき場所</h4>
          <ul>
            <li>地元で有名な観光地や人気スポット</li>
            <li>ショッピングモールなど、家族連れが多い場所</li>
            <li>自宅や職場の近く</li>
            <li>SNS映えするような話題の店</li>
          </ul>
        </div>
      </article>

      <!-- 記事3: 出会い方 -->
      <article class="seo-article">
        <div class="article-header">
          <span class="article-tag">出会い方</span>
          <h3 class="article-title"><?php echo esc_html( $prefecture_name ); ?>で理想の相手と出会う方法</h3>
        </div>
        <div class="article-content">
          <p>
            <?php echo esc_html( $prefecture_name ); ?>で不倫相手を探すなら、既婚者専用の掲示板が最も安全で効率的です。お互いの立場を理解し合える相手と出会えるため、トラブルのリスクも低くなります。
          </p>
          <h4>効果的なプロフィールの作り方</h4>
          <ul>
            <li><strong>正直に希望を書く</strong> - 求める関係性や会える頻度、希望する年齢層などを明確に記載しましょう。</li>
            <li><strong>顔写真は慎重に</strong> - 身バレのリスクがあるため、顔の一部を隠すか、雰囲気が伝わる写真を選びましょう。</li>
            <li><strong>誠実さをアピール</strong> - 遊び目的ではなく、真剣に関係を築きたい姿勢を示すことで、質の高い出会いにつながります。</li>
          </ul>
          <h4>初回デートの進め方</h4>
          <ul>
            <li><strong>昼間のカフェで</strong> - 初めて会う際は、明るい時間帯にカフェなど公共の場所を選びましょう。</li>
            <li><strong>1〜2時間程度に</strong> - 最初は短時間で切り上げ、お互いの印象を確かめます。</li>
            <li><strong>次回の約束は慎重に</strong> - 相性が良ければ次回の予定を立てますが、無理に決める必要はありません。</li>
          </ul>
          <h4>長続きする関係のコツ</h4>
          <ul>
            <li>お互いの家庭を最優先にする</li>
            <li>連絡の頻度やタイミングを相手に合わせる</li>
            <li>金銭的な負担は平等に</li>
            <li>感情的になりすぎず、適度な距離感を保つ</li>
          </ul>
        </div>
      </article>

      <!-- 記事4: 注意点 -->
      <article class="seo-article">
        <div class="article-header">
          <span class="article-tag">トラブル回避</span>
          <h3 class="article-title"><?php echo esc_html( $prefecture_name ); ?>でトラブルを避けるための注意点</h3>
        </div>
        <div class="article-content">
          <p>
            不倫関係には様々なリスクが伴います。<?php echo esc_html( $prefecture_name ); ?>で安全に関係を続けるために、以下の点に注意しましょう。
          </p>
          <h4>絶対に避けるべきこと</h4>
          <ul>
            <li><strong>金銭の貸し借り</strong> - どんな理由があっても、お金の貸し借りは絶対にNG。トラブルの元になります。</li>
            <li><strong>個人情報の開示</strong> - 本名、住所、勤務先などの詳細な個人情報は教えないようにしましょう。</li>
            <li><strong>SNSでの繋がり</strong> - FacebookやInstagramなど、実名のSNSでは繋がらないこと。</li>
            <li><strong>家族への連絡</strong> - 相手の配偶者や家族に連絡を取ることは厳禁です。</li>
          </ul>
          <h4>トラブルが起きた時の対処法</h4>
          <ul>
            <li><strong>冷静に対応する</strong> - 感情的にならず、まずは状況を整理しましょう。</li>
            <li><strong>証拠を残さない</strong> - メッセージのやり取りは定期的に削除し、証拠を残さないようにします。</li>
            <li><strong>専門家に相談</strong> - 深刻な問題の場合は、弁護士などの専門家に相談することも検討しましょう。</li>
          </ul>
          <h4>関係を終える時のマナー</h4>
          <ul>
            <li>誠実に理由を伝える</li>
            <li>一方的に連絡を絶たない</li>
            <li>お互いの秘密は守る約束をする</li>
            <li>未練を残さないよう、きっぱりと</li>
          </ul>
        </div>
      </article>

    </div>
  </section>

  <!-- 他の都道府県へのリンク -->
  <section class="other-prefectures-section">
    <div class="section-header">
      <h2 class="section-title">他の地域も<br class="sp-only">チェックする</h2>
    </div>
    <div class="other-prefectures-list">
      <?php
      $all_prefectures = hurin_get_prefectures();
      $random_prefectures = array();
      
      // 現在の都道府県以外をランダムに10件取得
      $filtered = array_filter($all_prefectures, function($p) use ($prefecture_slug) {
        return $p['slug'] !== $prefecture_slug;
      });
      
      shuffle($filtered);
      $random_prefectures = array_slice($filtered, 0, 10);
      
      foreach ($random_prefectures as $pref) :
      ?>
        <a href="<?php echo esc_url( hurin_get_prefecture_url( $pref['slug'] ) ); ?>" class="other-prefecture-link">
          <?php echo esc_html( $pref['name'] ); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="back-to-top-link">
      <a href="<?php echo esc_url( home_url('/') ); ?>">全都道府県一覧に戻る</a>
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
