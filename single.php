<?php
/**
 * Template untuk menampilkan halaman postingan tunggal (Single Post).
 * Layout: 3 kolom dengan share-buttons di kiri.
 */

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="container-md my-4 my-lg-5">
        <div class="row g-5">
            
            <div class="col-lg-2 d-none d-lg-block">
                <aside class="sidebar-single-left sticky-top">
                    <div class="share-box-vertical">
                        <h6 class="share-title">Bagikan</h6>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="_blank" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>" target="_blank" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" aria-label="Share on Telegram"><i class="fab fa-telegram-plane"></i></a>
                        <a href="mailto:?subject=<?php echo rawurlencode(get_the_title()); ?>&body=<?php echo rawurlencode('Saya menemukan artikel menarik: ' . get_permalink()); ?>" aria-label="Share via Email"><i class="fas fa-envelope"></i></a>
                    
                         <div class="comment-jump-box ">
                            <span class="comment-jump-label">Komentar</span>
                            <a href="#comments" class="comment-jump-link">
                                <i class="far fa-comment"></i>
                                <span class="comment-jump-count"><?php echo get_comments_number(); ?></span>
                            </a>
                        </div>
                    
                    
                    </div>
                </aside>
            </div>
            <div class="col-lg-8">
                <main id="main-content">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article pb-5'); ?>>
                        <?php
                        get_template_part('template-parts/section-content');
                        ?>
                    </article>

                  
                </main>
            </div>

            <div class="col-lg-2 d-none d-lg-block">


            <?php if (is_active_sidebar('sidebar-main')) : ?>
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar('sidebar-main'); ?>
    </aside>
<?php endif; ?>
                </div>

                <div class="p-0 m-0">
                <?php
                // Ini membuatnya menjadi section terpisah di bawah artikel.
                get_template_part('template-parts/section-related-posts');
                ?>
                </div>

                    <?php
                    // Menampilkan form komentar jika diizinkan
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
        </div></div><?php endwhile; endif; ?>

<?php get_footer(); ?>