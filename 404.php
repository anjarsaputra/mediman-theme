<?php
/**
 * Template untuk halaman 404 (Not Found).
 * Versi ini sudah dirapikan dan dibuat responsif.
 */

get_header(); ?>

<main class="container my-5">
    <div class="not-found-container text-center p-4 p-md-5">
        
        <figure class="not-found-image mx-auto mb-4">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/image/not-found.png'); ?>" alt="Halaman Tidak Ditemukan">
        </figure>

        <h1 class="not-found-title"><?php _e('Oops! Halaman Tidak Ditemukan', 'mediman'); ?></h1>
        <p class="not-found-subtitle lead text-muted"><?php _e('Maaf, halaman yang Anda cari tidak ada atau mungkin telah dipindahkan.', 'mediman'); ?></p>
        
        <div class="mt-4">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-success btn-lg">
                <i class="bi bi-house-door-fill me-2"></i><?php _e('Kembali ke Beranda', 'mediman'); ?>
            </a>
        </div>

    </div>
</main>

<?php get_footer(); ?>