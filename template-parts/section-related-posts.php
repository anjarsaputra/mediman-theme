<?php
/**
 * Template Part untuk menampilkan Section Artikel Terkait.
 * Versi ini sudah responsif dengan jarak di mobile.
 */

$categories = get_the_category(get_the_ID());
if ($categories) {
    $category_ids = [];
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    $related_args = [
        'category__in'   => $category_ids,
        'post__not_in'   => [get_the_ID()],
        'posts_per_page' => 3,
        'ignore_sticky_posts' => 1,
    ];
    $related_query = new WP_Query($related_args);

    if ($related_query->have_posts()) :
?>
<section class="related-posts-section border-top py-5">
    <div class="container-md">
     
        
        <div class="row">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                
                <div class="col-md-4 mb-4 mb-md-0">
                    <?php get_template_part('template-parts/content-card'); ?>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
    endif;
    wp_reset_postdata();
}
?>