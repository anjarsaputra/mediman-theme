
<main id="main-content" class="site-main">             


    <?php if (have_posts()) : ?>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php 
            // Memulai loop standar WordPress
            while (have_posts()) : the_post(); 
            ?>
                <div class="col d-flex align-items-stretch">
                    <?php
                      get_template_part('template-parts/content-card'); 
                    ?>
                </div>
            <?php endwhile; ?>
        </div><nav class="posts-pagination-wrap my-3" aria-label="Navigasi Postingan">
            <?php 
            the_posts_pagination([
                'prev_text' => '<i class="bi bi-arrow-left"></i>',
                'next_text' => '<i class="bi bi-arrow-right"></i>',
                'screen_reader_text' => ' ',
            ]); 
            ?>
        </nav>

    <?php else : ?>
        
        <div class="text-center py-5">
            <h2>Tidak Ada Postingan</h2>
            <p>Sepertinya belum ada artikel yang dipublikasikan di kategori ini.</p>
        </div>

    <?php endif; ?>

</main>

