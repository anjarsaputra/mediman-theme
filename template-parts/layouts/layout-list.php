<?php
/**
 * Template untuk menampilkan Layout Grid 2x2 (Gambar Kiri, Teks Kanan).
 * Query harus diatur untuk mengambil 4 postingan.
 */
?>
<div class="row row-cols-1 row-cols-lg-2 g-4">
    <?php while ($args['query']->have_posts()) : $args['query']->the_post(); ?>
        <div class="col d-flex align-items-stretch">
            <article id="post-<?php the_ID(); ?>" <?php post_class('grid-card-horizontal h-100'); ?>>
                <div class="row g-0">
                    <div class="col-4">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="grid-card-thumbnail">
                            <?php 
                                // PERBAIKAN DI SINI: Ganti 'thumbnail' menjadi 'medium_large'
                                the_post_thumbnail('medium_large', ['class' => 'img-fluid']); 
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-8">
                        <div class="grid-card-content d-flex flex-column h-100">
                            <h3 class="grid-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="grid-card-excerpt d-none d-sm-block">
                                <?php echo wp_trim_words(get_the_excerpt(), 10, '...'); ?>
                            </p>
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
                    </div>
                </div>
            </article>
        </div>
    <?php endwhile; ?>
</div>