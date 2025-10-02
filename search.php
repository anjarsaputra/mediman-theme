<?php
/**
 * Template untuk menampilkan halaman hasil pencarian.
 * Versi ini sudah dirapikan, responsif, dan memastikan footer tetap di bawah.
 */

get_header(); ?>

<div class="site-content-wrapper">
    <main id="main-content" class="container-md my-4">

        <header class="search-header mb-5">
            <h1 class="search-title">
                <?php printf( esc_html__( 'Hasil Pencarian untuk: %s', 'mediman' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
            
        </header>

       

        <?php if (have_posts()) : ?>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php 
                // Memulai loop standar WordPress
                while (have_posts()) : the_post(); 
                ?>
                    <div class="col d-flex align-items-stretch">
                        <?php
                        // Menggunakan kembali desain kartu yang sama untuk konsistensi
                        get_template_part('template-parts/content-card'); 
                        ?>
                    </div>
                <?php endwhile; ?>
            </div><nav class="posts-pagination-wrap mt-5" aria-label="Navigasi Hasil Pencarian">
                <?php 
                the_posts_pagination([
                    'prev_text' => '<i class="bi bi-arrow-left"></i>',
                    'next_text' => '<i class="bi bi-arrow-right"></i>',
                ]); 
                ?>
            </nav>

        <?php else : ?>
             <div class="no-results-found">
                <p class="lead text-muted">Maaf, tidak ada hasil yang cocok dengan kata kunci pencarian Anda. Silakan coba lagi.</p>
                
            </div>
            

        <?php endif; ?>

    </main>
</div><?php get_footer(); ?>