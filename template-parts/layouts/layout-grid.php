<?php
/**
 * Template untuk menampilkan Layout Grid (3 Kolom).
 */
?>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php while ($args['query']->have_posts()) : $args['query']->the_post(); ?>
        <div class="col d-flex align-items-stretch">
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card h-100'); ?>>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-card-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large', ['class' => 'img-fluid']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="post-card-content d-flex flex-column">
                    <div class="post-card-body">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) : ?>
                            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="card-category-tag"><?php echo esc_html($categories[0]->name); ?></a>
                        <?php endif; ?>
                        <h3 class="post-card-title mt-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="post-card-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?>
                        </p>
                    </div>
                    <footer class="post-card-footer mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <div class="author-box d-flex align-items-center">
                    <div class="author-avatar me-2">
                    <?php mediman_display_author_avatar( get_the_author_meta('ID'), 40 ); ?>
                    </div>
                    <div class="author-details">
                        <span class="author-name d-block"><?php the_author(); ?></span>
                        <span class="post-date small"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
                <div class="bookmark-icon">
                    <a href="#"><i class="bi bi-bookmark"></i></a>
                </div>
            </div>
        </footer>
                </div>
            </article>

        </div>
    <?php endwhile; ?>
</div>