


<main id="main-content" class="container-md my-4">

   <div id="semua-artikel" class="archive-header text-center mb-5">
        <?php
        // Ambil nilai dari Customizer, berikan nilai default jika kosong
        $archive_title = get_theme_mod('archive_page_title', 'Semua Artikel');
        $archive_description = get_theme_mod('archive_page_description', 'Jelajahi semua artikel terbaru dari kami.');
        ?>
        <h1 class="archive-title"><?php echo esc_html($archive_title); ?></h1>
        <p class="archive-description"><?php echo esc_html($archive_description); ?></p>
    </div>

    <?php
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'paged'          => $paged,
    );
    $custom_query = new WP_Query($args);

    if ($custom_query->have_posts()) :
    ?>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                <div class="col d-flex align-items-stretch">
                    <?php get_template_part('template-parts/content-card'); ?>
                </div>
            <?php endwhile; ?>
        </div>

        <nav class="posts-pagination-wrap mt-5 text-center" aria-label="Navigasi Postingan">
            <?php
            // PERBAIKAN UTAMA DI SINI
            
            // Cek jika tampilan mobile, gunakan argumen yang lebih ringkas
            if ( wp_is_mobile() ) {
                $pagination_args = [
                    'total'        => $custom_query->max_num_pages,
                    'current'      => max( 1, $paged ),
                    'prev_text'    => '<i class="bi bi-arrow-left"></i>',
                    'next_text'    => '<i class="bi bi-arrow-right"></i>',
                    'mid_size'     => 1, // Tampilkan 1 nomor di kiri & kanan halaman aktif
                    'end_size'     => 1, // Tampilkan 1 nomor di awal & akhir
                    'add_fragment' => '#semua-artikel',
                ];
            } else {
                // Jika desktop, gunakan argumen lengkap
                $pagination_args = [
                    'total'        => $custom_query->max_num_pages,
                    'current'      => max( 1, $paged ),
                    'prev_text'    => '<i class="bi bi-arrow-left"></i><span class="d-none d-sm-inline"> Sebelumnya</span>',
                    'next_text'    => '<span class="d-none d-sm-inline">Selanjutnya </span><i class="bi bi-arrow-right"></i>',
                    'add_fragment' => '#semua-artikel',
                ];
            }

            // Menampilkan pagination dengan argumen yang sudah ditentukan
            echo paginate_links($pagination_args);
            ?>
        </nav>

    <?php 
    wp_reset_postdata();
    else : ?>
        <p class="text-center py-5">Belum ada postingan untuk ditampilkan.</p>
    <?php endif; ?>

</main>

