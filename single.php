<?php
/**
 * 投稿詳細ページテンプレート
 * 
 * @package Hurin_Theme
 */

// ヘッダーの読み込み
get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <!-- 記事ヘッダー -->
  <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="post-thumbnail">
        <?php the_post_thumbnail( 'large' ); ?>
      </div>
    <?php endif; ?>
    
    <header class="post-header">
      <div class="post-meta">
        <?php
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
          echo '<span class="post-category">' . esc_html( $categories[0]->name ) . '</span>';
        }
        ?>
        <time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
          <?php echo get_the_date(); ?>
        </time>
      </div>
      <h1 class="post-title"><?php the_title(); ?></h1>
    </header>

    <!-- 記事本文 -->
    <div class="post-content">
      <?php the_content(); ?>
    </div>

    <!-- 記事フッター -->
    <footer class="post-footer">
      <?php
      $tags = get_the_tags();
      if ( $tags ) {
        echo '<div class="post-tags">';
        echo '<span class="tags-label">タグ:</span>';
        foreach ( $tags as $tag ) {
          echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link">' . esc_html( $tag->name ) . '</a>';
        }
        echo '</div>';
      }
      ?>
    </footer>
  </article>

  <!-- 関連記事 -->
  <?php
  $categories = get_the_category();
  if ( ! empty( $categories ) ) {
    $category_id = $categories[0]->term_id;
    $related_posts = new WP_Query( array(
      'category__in' => array( $category_id ),
      'post__not_in' => array( get_the_ID() ),
      'posts_per_page' => 3,
      'orderby' => 'rand',
    ));

    if ( $related_posts->have_posts() ) : ?>
      <section class="related-posts">
        <h2 class="section-title">関連記事</h2>
        <div class="related-posts-grid">
          <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
            <article class="related-post-card">
              <a href="<?php the_permalink(); ?>" class="related-post-link">
                <h3 class="related-post-title"><?php the_title(); ?></h3>
                <time class="related-post-date"><?php echo get_the_date(); ?></time>
              </a>
            </article>
          <?php endwhile; ?>
        </div>
      </section>
    <?php
    endif;
    wp_reset_postdata();
  }
  ?>

  <!-- トップページへ戻るリンク -->
  <div class="back-to-home">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-link">← トップページに戻る</a>
  </div>

<?php endwhile; endif; ?>

<?php
// フッターの読み込み
get_footer();
