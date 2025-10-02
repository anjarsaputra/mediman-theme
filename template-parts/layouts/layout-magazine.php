<?php
/**
 * Template untuk menampilkan Layout Majalah (1 Besar + Grid 2x2 Kecil).
 * Dipanggil oleh section-category-block.php
 *
 * Catatan: Query harus mengambil 5 postingan agar layout ini berfungsi.
 */
?>
<div class="row">
    <?php 
    $p_count = 0;
    // Memulai loop dari query yang dikirimkan
    while ($args['query']->have_posts()) : $args['query']->the_post(); 
    ?>
        <?php 
        // Logika untuk Postingan Utama (yang pertama)
        if ($p_count == 0) : 
        ?>
            <div class="col-lg-5 mb-4 mb-lg-0">
                <article id="post-<?php the_ID(); ?>" <?php post_class('featured-card-split h-100'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="featured-thumbnail-split">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="featured-content-split">
                        <h2 class="featured-title-split"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="featured-excerpt-split d-none d-sm-block"><?php 
                            // Ambil konten utama, hapus tag HTML, lalu potong
                            $content = get_the_content();
                            $content = strip_tags($content);
                            echo wp_trim_words($content, 70, '...'); 
                            ?></p>
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
            <div class="col-lg-7">
                <div class="side-posts-grid">
                <div class="row row-cols-1 row-cols-md-2 g-3"> 

        <?php 
        // Logika untuk 4 Postingan Sampingan
        else : 
        ?>
            <div class="col">
                <article id="post-<?php the_ID(); ?>" <?php post_class('side-grid-card h-100'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="side-grid-thumbnail">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="side-grid-content">
                        <h3 class="side-grid-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
        <?php endif; ?>
        
        <?php $p_count++; ?>
    <?php endwhile; ?>