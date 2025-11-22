<?php
/*
Template Name: Blog Archive
*/

get_header();
?>

<main class="main-content">
  <div class="container">
    
    <!-- ブログヘッダー -->
    <div class="blog-header">
      <h1 class="page-title">コラム</h1>
      <p class="page-description">不倫募集掲示板の活用法や、大人の関係のマナーなど、役立つ情報をお届けします。</p>
    </div>
    
    <!-- ブログ記事一覧 -->
    <div class="blog-list">
      <?php
      // 投稿を取得
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
      $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC'
      );
      
      $blog_query = new WP_Query( $args );
      
      if ( $blog_query->have_posts() ) :
        while ( $blog_query->have_posts() ) : $blog_query->the_post();
      ?>
      
      <article class="blog-item">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="blog-thumbnail">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'medium' ); ?>
            </a>
          </div>
        <?php endif; ?>
        
        <div class="blog-content">
          <h2 class="blog-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          
          <div class="blog-meta">
            <span class="blog-date">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
              <?php echo get_the_date(); ?>
            </span>
            
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) :
            ?>
              <span class="blog-category">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                  <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <?php echo esc_html( $categories[0]->name ); ?>
              </span>
            <?php endif; ?>
          </div>
          
          <div class="blog-excerpt">
            <?php echo wp_trim_words( get_the_excerpt(), 80, '...' ); ?>
          </div>
          
          <a href="<?php the_permalink(); ?>" class="read-more">続きを読む →</a>
        </div>
      </article>
      
      <?php
        endwhile;
        
        // ページネーション
        if ( $blog_query->max_num_pages > 1 ) :
      ?>
        <div class="pagination">
          <?php
          echo paginate_links( array(
            'total' => $blog_query->max_num_pages,
            'current' => $paged,
            'prev_text' => '← 前へ',
            'next_text' => '次へ →',
            'type' => 'list'
          ) );
          ?>
        </div>
      <?php
        endif;
        
        wp_reset_postdata();
      else :
      ?>
        <p class="no-posts">まだ記事がありません。</p>
      <?php endif; ?>
    </div>
    
  </div>
</main>

<?php get_footer(); ?>
