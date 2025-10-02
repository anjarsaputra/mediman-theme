<?php
/**
 * Template untuk menampilkan halaman arsip (kategori, tag, dll).
 * Versi ini menggunakan template part 'content-card' untuk konsistensi.
 */

get_header(); ?>

<main id="main-content" class="container-md my-4">

   

    <?php if (have_posts()) : ?>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php 
            // Memulai loop standar WordPress
            while (have_posts()) : the_post(); 
            ?>
                <div class="col d-flex align-items-stretch">
                    <?php
                    /**
                     * PERBAIKAN UTAMA:
                     * Alih-alih menulis ulang HTML di sini, kita panggil desain kartu
                     * yang sudah ada dari 'template-parts/content-card.php'.
                     */
                    get_template_part('template-parts/content-card'); 
                    ?>
                </div>
            <?php endwhile; ?>
        </div><nav class="posts-pagination-wrap mt-5" aria-label="Navigasi Postingan">
            <?php 
            the_posts_pagination([
                'prev_text' => '<i class="bi bi-arrow-left"></i>',
                'next_text' => '<i class="bi bi-arrow-right"></i>',
            ]); 
            ?>
        </nav>

    <?php else : ?>
        
        <div class="text-center py-5">
            <h2>Tidak Ada yang Ditemukan</h2>
            <p>Sepertinya tidak ada postingan yang cocok dengan kriteria Anda.</p>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>