<?php
/**
 * Template part untuk menampilkan section kategori yang bisa diatur dari Customizer.
 * Versi ini sudah diperbaiki dari error.
 */

// 1. Ambil ID kategori yang dipilih dari Customizer
$category_id = get_theme_mod('fp_cat_section_category_id');

// Jika tidak ada kategori yang dipilih, jangan tampilkan apapun
if (empty($category_id)) {
    return;
}

// 2. Ambil semua pengaturan lain dari Customizer
$section_title = get_theme_mod('fp_cat_section_title', 'Latest Articles');
$link_type = get_theme_mod('fp_cat_section_link_type', 'category');

// 3. Tentukan URL untuk link "Lihat Semua" berdasarkan pilihan di Customizer
if ('category' === $link_type) {
    // Jika dipilih "Arsip Kategori", gunakan link kategori otomatis
    $see_all_link = get_category_link($category_id);
} else {
    // Jika dipilih "Link Kustom", gunakan link yang diinput
    $see_all_link = get_theme_mod('fp_cat_section_custom_link', '#');
}

// 4. Siapkan argumen untuk mengambil 6 postingan dari kategori tersebut
$args = [
    'cat'                 => $category_id,
    'posts_per_page'      => 6,
    'ignore_sticky_posts' => 1,
];
$query = new WP_Query($args);

// 5. Mulai menampilkan section jika ada postingan
if ($query->have_posts()) :
?>
<section class="latest-posts-section my-5">
<div class="section-title-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title m-0 mb-2 mb-sm-0"><span><?php echo esc_html($section_title); ?></span></h2>
    <a href="<?php echo esc_url($see_all_link); ?>" class="see-all-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
</div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="col d-flex align-items-stretch">
                <?php get_template_part('template-parts/content-card'); ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<?php
endif;
wp_reset_postdata();