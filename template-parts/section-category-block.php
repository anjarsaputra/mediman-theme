<?php
/**
 * Template Part "Mesin" untuk menampilkan blok kategori dinamis.
 */
$block_num = isset($args['block_number']) ? $args['block_number'] : 1;
$id_prefix = 'cat_block_' . $block_num;

$category_id = get_theme_mod($id_prefix . '_category_id');
if (empty($category_id)) { return; }

$layout_style  = get_theme_mod($id_prefix . '_layout_style', 'magazine');
$custom_title  = get_theme_mod($id_prefix . '_title');
$category_object = get_category($category_id);
$section_title = !empty($custom_title) ? $custom_title : $category_object->name;
$see_all_link = get_category_link($category_id);

// === PERBAIKAN UTAMA DI SINI ===
// Jika layout adalah grid, ambil 6 post. Jika majalah, tetap 6. Jika daftar, ambil 4.
if ($layout_style === 'grid' || $layout_style === 'magazine') {
    $post_count = 6;
} else {
    $post_count = 4; // Untuk layout 'list'
}

$args = ['cat' => $category_id, 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1];
$query = new WP_Query($args);

if ($query->have_posts()) :
?>
<section class="dynamic-category-section my-5">
    <div class="section-title-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title m-0"><span><?php echo esc_html($section_title); ?></span></h2>
        <a href="<?php echo esc_url($see_all_link); ?>" class="see-all-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>

    <?php
    // Memanggil file layout yang sesuai dari folder /layouts/
    get_template_part('template-parts/layouts/layout', $layout_style, ['query' => $query]);
    ?>
</section>
<?php
endif;
wp_reset_postdata();