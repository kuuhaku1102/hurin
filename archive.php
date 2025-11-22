<?php
/**
 * アーカイブページテンプレート
 * 
 * @package Hurin_Theme
 */

// ヘッダーの読み込み
get_header();
?>

  <!-- アーカイブヘッダー -->
  <section class="archive-header">
    <div class="archive-header-content">
      <?php if ( is_category() ) : ?>
        <h1 class="archive-title">カテゴリー: <?php single_cat_title(); ?></h1>
        <?php
        $category_description = category_description();
        if ( ! empty( $category_description ) ) {
          echo '<p class="archive-description">' . $category_description . '</p>';
        }
        ?>
      <?php elseif ( is_tag() ) : ?>
        <h1 class="archive-title">タグ: <?php single_tag_title(); ?></h1>
      <?php elseif ( is_date() ) : ?>
        <h1 class="archive-title">アーカイブ: <?php the_archive_title(); ?></h1>
      <?php else : ?>
        <h1 class="archive-title">記事一覧</h1>
      <?php endif; ?>
    </div>
  </section>

  <!-- 記事一覧 -->
  <section class="archive-posts">
    <?php if ( have_posts() ) : ?>
      <div class="posts-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
            <a href="<?php the_permalink(); ?>" class="post-card-link">
              <div class="post-card-content">
                <div class="post-card-meta">
                  <?php
                  $categories = get_the_category();
                  if ( ! empty( $categories ) ) {
                    echo '<span class="post-card-category">' . esc_html( $categories[0]->name ) . '</span>';
                  }
                  ?>
                  <time class="post-card-date" datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                  </time>
                </div>
                <h2 class="post-card-title"><?php the_title(); ?></h2>
                <?php if ( has_excerpt() ) : ?>
                  <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>
              </div>
            </a>
          </article>
        <?php endwhile; ?>
      </div>

      <!-- ページネーション -->
      <div class="pagination">
        <?php
        the_posts_pagination( array(
          'mid_size' => 2,
          'prev_text' => '← 前へ',
          'next_text' => '次へ →',
        ));
        ?>
      </div>

    <?php else : ?>
      <p class="no-posts">記事が見つかりませんでした。</p>
    <?php endif; ?>
  </section>

  <!-- トップページへ戻るリンク -->
  <div class="back-to-home">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-link">← トップページに戻る</a>
  </div>

<?php
// フッターの読み込み
get_footer();
