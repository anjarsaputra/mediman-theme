<?php
/**
 * Template Part untuk menampilkan detail konten di halaman Single Post.
 */
?>

<section class="top-author-box mb-4">
    <div class="row align-items-center g-3">
        <div class="col-auto">
            <div class="author-avatar-top">
                <?php 
                // Memanggil fungsi avatar global yang sudah kita buat
                if ( function_exists('mediman_display_author_avatar') ) {
                    mediman_display_author_avatar( get_the_author_meta('ID'), 60, 'rounded-circle' );
                }
                ?>
            </div>
        </div>
        <div class="col">
            <div class="author-info-top">
                <h6 class="author-name-top mb-1"><?php the_author(); ?></h6>
                <p class="author-description-top text-muted mb-0"><?php echo get_the_author_meta('description'); ?></p>
            </div>
        </div>
    </div>
</section>

<div class="entry-breadcrumbs mb-4">
    <?php
    echo '<a href="' . esc_url(home_url()) . '">Home</a> <i class="bi bi-chevron-right"></i> ';
    $categories = get_the_category();
    if (!empty($categories)) {
        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
        // Menambahkan pemisah setelah kategori
        echo ' <i class="bi bi-chevron-right"></i> ';
    }
    // Menambahkan judul postingan di akhir (sebagai teks biasa, bukan link)
    the_title('<span class="breadcrumb-current">', '</span>');
    ?>

</div>

<header class="entry-header mb-4">
    <h1 class="entry-title"><?php the_title(); ?></h1>

    
    <?php if (get_theme_mod('show_demo_ads', 1) && is_single() && is_active_sidebar('ad-post-title')) : ?>
    <div class="ad-post-title-area">
        <?php dynamic_sidebar('ad-post-title'); ?>
    </div>
<?php endif; ?>
    <div class="entry-meta">
        <span class="post-date"><?php echo get_the_date(); ?></span>
        <span class="reading-time"><i class="bi bi-clock"></i> <?php echo mediman_calculate_reading_time(get_the_content()); ?> min read</span>
    </div>
</header>

<?php if (has_post_thumbnail()) : ?>
    <figure class="post-thumbnail-single mb-4">
        <?php the_post_thumbnail('full', ['class' => 'img-fluid rounded-3']); ?>
    </figure>
<?php endif; ?>

<?php
// Memanggil Daftar Isi secara manual di posisi yang tepat
if ( function_exists('mediman_display_table_of_contents') ) {
    mediman_display_table_of_contents();
}
?>

<div class="entry-content">
    <?php the_content(); ?>
</div>

<footer class="entry-footer mt-4">
<?php
    // Mengambil semua kategori dari postingan saat ini
    $post_categories = get_the_category();
    if ( ! empty( $post_categories ) ) {
        echo '<div class="category-links-footer">';
       
        foreach ( $post_categories as $category ) {
            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
        }
        echo '</div>';
    }
    ?>
</footer>