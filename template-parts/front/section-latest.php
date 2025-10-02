<?php
/**
 * Template part untuk menampilkan section "Latest Articles"
 * Layout: 2 kolom, dengan gambar di kanan.
 */

$latest_posts_args = [
    'post_type'           => 'post',
    'posts_per_page'      => 4, // Ambil 4 postingan terbaru
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
    'offset'             => 4, // Mulai dari postingan pertama'    
];

$latest_posts_query = new WP_Query($latest_posts_args);

if ( $latest_posts_query->have_posts() ) :
?>
<section class="latest-articles-section my-5">
    
    <div class="section-title-header text-center mb-4">
        <div class="section-title-header text-center mb-4">
    <?php
    // Ambil nilai dari Customizer, berikan nilai default jika kosong
    $section_title = get_theme_mod('latest_posts_section_title', 'LATEST ARTICLES');
    $section_subtitle = get_theme_mod('latest_posts_section_subtitle', 'Discover the most recent articles from our authors.');
            ?>
            <h2 class="section-title"><span><?php echo esc_html($section_title); ?></span></h2>
            <p class="section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
        </div>
    </div>

    <div class="row">
        <?php while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post(); ?>
            <div class="col-md-6 mb-4">
                <article id="post-<?php the_ID(); ?>" <?php post_class('latest-article-card-v2 h-100'); ?>>
                    <div class="row g-0">
                        <div class="col-7">
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title-v2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) : ?>
                                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="card-category-tag-v2"><?php echo esc_html($categories[0]->name); ?></a>
                                <?php endif; ?>

                                <p class="card-excerpt-v2 d-none d-lg-block">
                                    <?php echo wp_trim_words(get_the_excerpt(), 12, '...'); ?>
                                </p>

                                <div class="card-meta-v2 mt-auto">
                                    <span class="card-date-v2"><?php echo get_the_date(); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-thumbnail-v2">
                                    <?php the_post_thumbnail('medium', ['class' => 'img-fluid']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div></article>
            </div><?php endwhile; ?>
    </div></section>
<?php
endif;

wp_reset_postdata();