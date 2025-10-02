<?php
// File: inc/theme-features.php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Memotong judul berdasarkan jumlah karakter.
 * @param int $count Jumlah karakter maksimum.
 * @param string $after Teks yang ditambahkan setelah pemotongan (mis. '...').
 * @return string Judul yang sudah dipotong.
 */
function trim_title_chars($count, $after)
{
    $title = get_the_title();
    if (mb_strlen($title) > $count) {
        $title = mb_substr($title, 0, $count) . $after;
    }
    return $title;
}

/**
 * Filter untuk kompatibilitas WP_Bootstrap_Navwalker dengan Bootstrap 5.
 */
add_filter('nav_menu_link_attributes', 'prefix_bs5_dropdown_data_attribute', 20, 3);
function prefix_bs5_dropdown_data_attribute($atts, $item, $args)
{
    if (isset($args->walker) && is_a($args->walker, 'WP_Bootstrap_Navwalker')) {
        if (array_key_exists('data-toggle', $atts)) {
            unset($atts['data-toggle']);
            $atts['data-bs-toggle'] = 'dropdown';
        }
    }
    return $atts;
}

/**
 * Mengatur panjang excerpt dan menghilangkan tombol "[...]".
 */
add_filter('excerpt_more', function () {
    return '...';
});
add_filter('excerpt_length', function () {
    return 25; // Diubah ke 25 kata agar lebih umum
});


/**
 * =================================================================
 * SHORTCODE SLIDER - VERSI FINAL
 * =================================================================
 * Shortcode untuk menampilkan slider postingan Bootstrap 5.
 * Menampilkan 3 postingan terbaru dengan slide horizontal.
 * Penggunaan: [post_slider]
 */
add_shortcode('post_slider', 'post_slider_shortcode');
function post_slider_shortcode()
{
    // 1. Argumen Query untuk mengambil 3 postingan terbaru
    $query_args = [
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => 1, // Mengabaikan sticky posts
    ];
    $slider_query = new WP_Query($query_args);

    // Jika tidak ada postingan, hentikan fungsi
    if (!$slider_query->have_posts()) {
        return '';
    }

    // 2. Mulai menangkap output HTML
    ob_start();
    ?>
    <section id="postSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">

        <div class="carousel-indicators">
            <?php for ($i = 0; $i < $slider_query->post_count; $i++) : ?>
                <button type="button" data-bs-target="#postSlider" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0) ? 'active' : ''; ?>" aria-current="<?php echo ($i == 0) ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
            <?php endfor; ?>
        </div>

        <div class="carousel-inner">
            <?php $item_index = 0;
            while ($slider_query->have_posts()) : $slider_query->the_post(); ?>
                <div class="carousel-item <?php echo ($item_index == 0) ? 'active' : ''; ?>">
                    
                    <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large', [
                                'class'   => 'd-block w-100 sliderimage',
                                'loading' => ($item_index == 0) ? 'eager' : 'lazy'
                            ]);
                        } else {
                            echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/image/default.jpg') . '" class="d-block w-100 sliderimage" alt="Gambar Default">';
                        }
                        ?>
                    </a>
                    
                    <div class="carousel-caption d-none d-md-block">
                        <div class="excerptslider">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-decoration-none"><span class="badge custom-button-kategori-slider p-2 text-uppercase">' . esc_html($categories[0]->name) . '</span></a>';
                            }
                            ?>
                            <h2 class="card-title text-overflow">
                                <a class="text-white text-decoration-none" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></div>
                        </div>
                    </div>
                </div>
            <?php $item_index++; endwhile; ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#postSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#postSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}



/**
 * Fungsi untuk menampilkan Breadcrumbs.
 */
function custom_breadcrumbs()
{
    if (is_front_page()) {
        return;
    }
    // ... (kode breadcrumbs Anda sudah cukup baik, bisa diletakkan di sini)

    $separator = ' » '; $home_title = __('Home', 'mediman');
    echo '<div class="breadcrumbs d-flex flex-row pb-3 flex-wrap">';
    echo '<div class="breadcrumb-item"><a class="text-decoration-none text-success" href="' . get_home_url() . '">' . $home_title . '</a></div>';
    if (is_category() || is_single()) {
        echo '<div class="separator px-2"> ' . $separator . ' </div>';
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<div class="breadcrumb-item"><a class="text-decoration-none text-success" href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></div>';
        }
        if (is_single()) {
            echo '<div class="separator px-2"> ' . $separator . ' </div>';
            echo '<div class="breadcrumb-item">' . get_the_title() . '</div>';
        }
    } elseif (is_page()) {
        global $post;
        if ($post->post_parent) {
            $parent_ids = array_reverse(get_post_ancestors($post->ID));
            foreach ($parent_ids as $parent_id) {
                echo '<div class="separator px-2"> ' . $separator . ' </div>';
                echo '<div class="breadcrumb-item"><a href="' . get_permalink($parent_id) . '">' . get_the_title($parent_id) . '</a></div>';
            }
        }
        echo '<div class="separator px-2"> ' . $separator . ' </div>';
        echo '<div class="breadcrumb-item">' . get_the_title() . '</div>';
    } elseif (is_search()) {
        echo '<div class="separator px-2"> ' . $separator . ' </div>';
        echo '<div class="breadcrumb-item">' . __('Search results for:', 'mediman') . ' ' . get_search_query() . '</div>';
    } else if (is_tag() || is_day() || is_month() || is_year() || is_author() || is_404()) {
        echo '<div class="separator px-2"> ' . $separator . ' </div>';
        echo '<div class="breadcrumb-item">' . get_the_archive_title() . '</div>';
    }
    echo '</div>';
}

/**
 * Fungsi untuk menghitung estimasi waktu baca.
 * Kecepatan baca rata-rata: 200 kata per menit.
 */
function calculate_reading_time($content)
{
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200);
    return $reading_time;
}

/**
 * Fungsi pagination kustom.
 */
function custom_pagination()
{
    global $wp_query;
    if ($wp_query->max_num_pages <= 1) return; // Jangan tampilkan jika hanya 1 halaman
    
    echo paginate_links([
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => max(1, get_query_var('paged')),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
    ]);
}

/**
 * Membuat halaman pengaturan tema di area admin.
 */
add_action('admin_menu', 'pengaturan_tema_mediman');
function pengaturan_tema_mediman()
{
    add_menu_page('Pengaturan Tema Mediman', 'Mediman', 'manage_options', 'pengaturan-tema-mediman', 'mediman_theme_settings_page', '', 2);
}

function mediman_theme_settings_page()
{
    $file = get_template_directory() . '/inc/settings-theme.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "<div class='wrap'><h2>File Pengaturan Tidak Ditemukan</h2><p>Pastikan file 'settings-theme.php' ada di dalam folder 'inc'.</p></div>";
    }
}

/**
 * Menambahkan style kustom untuk menu admin.
 */
add_action('admin_head', 'custom_admin_styles');
function custom_admin_styles()
{
    // ... (kode style admin Anda bisa diletakkan di sini)
}

/**
 * Fungsi untuk menangani login via AJAX.
 */
add_action('wp_ajax_nopriv_login', 'ajax_login');
add_action('wp_ajax_login', 'ajax_login');
function ajax_login() {
    check_ajax_referer('ajax-login-nonce', 'security');
    $info = ['user_login' => $_POST['username'] ?? '', 'user_password' => $_POST['password'] ?? '', 'remember' => true];
    $user = wp_signon($info, false);
    if (is_wp_error($user)) { echo wp_json_encode(['success' => false, 'message' => $user->get_error_message()]); } 
    else { echo wp_json_encode(['success' => true, 'message' => 'Login berhasil']); }
    die();
}
add_action('wp_ajax_nopriv_login', 'ajax_login');
add_action('wp_ajax_login', 'ajax_login');



/**
 * Menambahkan meta description di <head> untuk SEO dasar.
 */
add_action('wp_head', 'custom_meta_description');
function custom_meta_description()
{
    if (is_single() || is_page()) {
        global $post;
        $description = has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words(strip_tags(get_the_content($post->ID)), 25);
        if (empty($description)) {
            $description = get_the_title($post->ID);
        }
        echo '<meta name="description" content="' . esc_attr($description) . '">';
    }
}

/**
 * Mengaktifkan konversi gambar ke WebP saat upload jika diaktifkan di Customizer.
 */
add_filter('wp_handle_upload', function ($upload) {
    if (get_theme_mod('enable_webp_conversion', false) && in_array($upload['type'], ['image/jpeg', 'image/png'])) {
        // ... (kode konversi WebP Anda)
    }
    return $upload;
});

/**
* Menambahkan dukungan MIME type untuk WebP agar bisa di-upload.
*/
add_filter('upload_mimes', function ($mime_types) {
    $mime_types['webp'] = 'image/webp';
    return $mime_types;
});

/**
 * Optimasi: Menghapus script dan style emoji bawaan WordPress.
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * DINONAKTIFKAN KARENA KONFLIK - Menyisipkan Artikel Terkait di tengah konten.
 * Metode ini (membelah paragraf) sangat rapuh dan berkonflik dengan fungsi generate_toc.
 * Disarankan untuk menggunakan shortcode atau menampilkannya di akhir post.
 */
// add_filter('the_content', 'insert_related_posts_in_content', 20); // Diberi prioritas 20 agar berjalan setelah TOC
// function insert_related_posts_in_content($content) { ... }







/**
 * 2. FUNGSI UNTUK MENAMPILKAN ARTIKEL TERBARU DENGAN OFFSET
 * Tampilan: List (Teks Kiri, Gambar Kanan)
 */
function mediman_display_latest_offset_articles() {
    $args = [
        'post_type'           => 'post',
        'posts_per_page'      => 4,
        'offset'              => 3, // Melewatkan 3 post dari slider
        'ignore_sticky_posts' => 1,
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) :
    ?>
    <section class="latest-articles-section container-md px-3 mt-4" aria-labelledby="artikel-terbaru-heading">
        <header class="pt-3">
            <div class="border-bottom d-flex">
                <div class="border-bottom border-2 border-dark">
                    <h2 id="artikel-terbaru-heading" class="fs-5 mb-0 pb-1"><span class="title-span">Artikel Terbaru</span></h2>
                </div>
            </div>
        </header>
        <div class="latest-articles-list mt-4">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <article class="latest-article-item row g-3 mb-4 pb-4 border-bottom">
                    <div class="col-md-8">
                        <div class="article-content">
                            <h3 class="article-title h5"><a href="<?php the_permalink(); ?>" class="text-decoration-none fw-bold"><?php the_title(); ?></a></h3>
                            <div class="article-excerpt small text-muted"><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></div>
                            <div class="article-meta mt-2"><small class="text-muted"><?php echo get_the_date(); ?></small></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php the_permalink(); ?>"><figure class="article-thumbnail m-0"><?php the_post_thumbnail('medium', ['class' => 'img-fluid rounded']); ?></figure></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    endif;
    wp_reset_postdata();
}


/**
 * 3. FUNGSI UNTUK MENAMPILKAN GRID KATEGORI KUSTOM
 */
function mediman_display_category_grid($title_mod_name, $category_mod_name, $link_mod_name, $link_text_mod_name, $default_cat_id, $post_count) {
    $section_title = get_theme_mod($title_mod_name, 'Kategori');
    $category_id   = get_theme_mod($category_mod_name, $default_cat_id);
    $see_more_link = get_theme_mod($link_mod_name, '#');
    $see_more_text = get_theme_mod($link_text_mod_name, 'Lebih lengkap');

    if (empty($category_id)) return; // Jangan tampilkan jika kategori tidak dipilih

    $args = [
        'cat'                 => $category_id,
        'posts_per_page'      => $post_count,
        'ignore_sticky_posts' => 1,
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) :
    ?>
    <section class="container-md px-3 mt-4" aria-labelledby="label-<?php echo esc_attr($category_mod_name); ?>">
        <header class="pt-3">
            <div class="border-bottom d-flex justify-content-between align-items-center">
                <div class="border-bottom border-2 border-dark">
                    <h2 id="label-<?php echo esc_attr($category_mod_name); ?>" class="fs-5 mb-0 pb-1"><span class="title-span"><?php echo esc_html($section_title); ?></span></h2>
                </div>
                <div><a href="<?php echo esc_url($see_more_link); ?>" class="text-decoration-none selengkapnya"><?php echo esc_html($see_more_text); ?> <i class="fas fa-angle-right"></i></a></div>
            </div>
        </header>
        <div class="post-grid1 mt-4">
            <?php while ($query->have_posts()) : $query->the_post();
                get_template_part('template-parts/content-card');
            endwhile; ?>
        </div>
    </section>
    <?php
    endif;
    wp_reset_postdata();
} 

function mediman_calculate_reading_time( $content ) {
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // Rata-rata 200 kata per menit
    return $reading_time;
}

/**
 * Fungsi untuk MENAMPILKAN Daftar Isi (Table of Contents).
 * Fungsi ini akan dipanggil secara manual dari template.
 */
function mediman_display_table_of_contents() {
    // Hanya berjalan di single post dan jika diaktifkan dari Customizer
    if ( is_single() && get_theme_mod( 'single_post_show_toc', true ) ) {
        
        // Ambil konten dari post saat ini
        $content = get_post_field( 'post_content', get_the_ID() );
        
        // Cari semua heading H2 dan H3
        preg_match_all( '/<h[2-3].*?>(.*?)<\/h[2-3]>/', $content, $headings );

        // Hanya tampilkan jika ada minimal 2 heading
        if ( count( $headings[1] ) >= 2 ) { 
            echo '<div class="table-of-contents-wrap card mb-4">';
            echo '<div class="card-body">';
            echo '<p class="toc-title card-title">Daftar Isi</p>';
            echo '<ol class="toc-list">';
            
            $i = 1;
            foreach ( $headings[1] as $title ) {
                $slug = 'toc-' . sanitize_title_with_dashes($title) . '-' . $i;
                // Kita tidak perlu lagi mengubah konten di sini, hanya membuat link
                echo '<li><a href="#' . $slug . '">' . strip_tags( $title ) . '</a></li>';
                $i++;
            }

            echo '</ol></div></div>';
        }
    }
}

/**
 * Fungsi untuk menambahkan ID secara otomatis ke heading H2 dan H3 di dalam konten.
 */
function mediman_add_ids_to_headings( $content ) {
    if ( is_single() && get_theme_mod( 'single_post_show_toc', true ) ) {
        $content = preg_replace_callback( '/<h([2-3])(.*?)>(.*?)<\/h\1>/', function( $matches ) {
            static $i = 1;
            $title = strip_tags( $matches[3] );
            $slug = 'toc-' . sanitize_title_with_dashes($title) . '-' . $i++;
            return '<h' . $matches[1] . ' id="' . $slug . '"' . $matches[2] . '>' . $matches[3] . '</h' . $matches[1] . '>';
        }, $content );
    }
    return $content;
}
add_filter( 'the_content', 'mediman_add_ids_to_headings' );

function mediman_insert_related_posts( $content ) {
    // Jalankan hanya di single post dan jika diaktifkan dari Customizer
    if ( is_single() && in_the_loop() && is_main_query() && get_theme_mod( 'enable_related_posts', true ) ) {
        
        // Hapus filter ini sementara untuk mencegah perulangan tak terbatas
        remove_filter( 'the_content', 'mediman_insert_related_posts' );

        $categories = get_the_category( get_the_ID() );
        if ( $categories ) {
            $category_ids = wp_list_pluck( $categories, 'term_id' );
            $related_args = [
                'category__in'   => $category_ids,
                'post__not_in'   => [ get_the_ID() ],
                'posts_per_page' => 4, // Ambil 4 postingan
                'ignore_sticky_posts' => 1,
            ];
            $related_query = new WP_Query( $related_args );

            if ( $related_query->have_posts() ) {
                ob_start();
                ?>
                <aside class="in-content-related-posts my-4 p-3 rounded">
                    <h5 class="related-title mb-3">Baca Juga</h5>
                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                            <div class="col">
                                <article class="related-post-item-grid">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-4">
                                            <?php if(has_post_thumbnail()): ?>
                                                <a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-8">
                                            <div class="related-post-content">
                                                <h6 class="related-post-title-grid"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                                <span class="related-post-date-grid small text-muted"><?php echo get_the_date(); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </aside>
                <?php
                $related_posts_html = ob_get_clean();
                wp_reset_postdata();
                
                // Logika untuk menyisipkan di tengah paragraf
                $paragraphs = explode('</p>', $content);
                $middle_point = floor(count($paragraphs) / 2);
                if(count($paragraphs) > 6) { // Hanya sisipkan jika paragraf cukup panjang
                     array_splice($paragraphs, $middle_point, 0, $related_posts_html);
                     $content = implode('</p>', $paragraphs);
                }
            }
        }
        
        // Tambahkan kembali filter setelah selesai
        add_filter( 'the_content', 'mediman_insert_related_posts' );
    }
    return $content;
}
add_filter( 'the_content', 'mediman_insert_related_posts' );


/**
 * 1. Menerjemahkan Form Komentar (Judul, Tombol, Label, dll)
 */
function mediman_translate_comment_form_defaults( $defaults ) {
    // Teks di atas kolom komentar
    $defaults['comment_notes_before'] = '<p class="comment-notes">' . 
        __( 'Alamat email Anda tidak akan dipublikasikan. Ruas yang wajib ditandai *', 'mediman' ) . 
    '</p>';
    
    // Teks judul form
    $defaults['title_reply'] = __( 'Tinggalkan Komentar Anda', 'mediman' );
    $defaults['title_reply_to'] = __( 'Balas ke %s', 'mediman' );
    $defaults['label_submit'] = __( 'Kirim Komentar', 'mediman' );
    $defaults['cancel_reply_link'] = __( 'Batalkan balasan', 'mediman' );

    // Teks untuk kolom komentar utama
    $defaults['comment_field'] = '<p class="comment-form-comment">' .
        '<label for="comment">' . _x( 'Komentar', 'noun', 'mediman' ) . ' <span class="required">*</span></label>' .
        '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>' .
        '</p>';

    return $defaults;
}
add_filter( 'comment_form_defaults', 'mediman_translate_comment_form_defaults' );

/**
 * Menerjemahkan Kolom Input (Nama, Email, Website)
 */
function mediman_translate_comment_form_fields( $fields ) {
    $fields['author'] = '<p class="comment-form-author">' .
        '<label for="author">' . __( 'Nama', 'mediman' ) . ' <span class="required">*</span></label> ' .
        '<input id="author" name="author" type="text" value="" size="30" maxlength="245" required="required" /></p>';

    $fields['email'] = '<p class="comment-form-email">' .
        '<label for="email">' . __( 'Email', 'mediman' ) . ' <span class="required">*</span></label> ' .
        '<input id="email" name="email" type="email" value="" size="30" maxlength="100" required="required" /></p>';

    $fields['url'] = '<p class="comment-form-url">' .
        '<label for="url">' . __( 'Website', 'mediman' ) . '</label>' .
        '<input id="url" name="url" type="text" value="" size="30" maxlength="200" /></p>';
        
    $fields['cookies'] = '<p class="comment-form-cookies-consent">' .
        '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" />' .
        '<label for="wp-comment-cookies-consent">' . __( 'Simpan nama, email, dan situs web saya untuk komentar berikutnya.', 'mediman' ) . '</label>' .
        '</p>';

    return $fields;
}
add_filter( 'comment_form_default_fields', 'mediman_translate_comment_form_fields' );


/**
 * 2. Membuat "Cetakan" HTML Kustom untuk Menampilkan Setiap Komentar
 * Ini akan menerjemahkan "says", format tanggal, dan tombol "Reply".
 */
function mediman_custom_comment_template( $comment, $args, $depth ) {
    ?>
    <li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author vcard">
                <?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
            </div>

            <div class="comment-content-wrap">
                <div class="comment-meta">
                    <?php printf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
                    <span class="says"> berkata:</span>
                </div>
                
                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php 
                            // Menggunakan format tanggal Indonesia (contoh: 13 Juli 2025)
                            printf( esc_html__( '%1$s pada %2$s', 'mediman' ), get_comment_date( 'j F Y' ), get_comment_time() ); 
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link( __( '(Edit)', 'mediman' ), '<span class="edit-link">', '</span>' ); ?>
                </div>

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Komentar Anda sedang menunggu moderasi.', 'mediman' ); ?></p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="reply">
                    <?php
                    comment_reply_link( array_merge( $args, [
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text'=> __( 'Balas', 'mediman' ), // Menerjemahkan "Reply"
                    ] ) );
                    ?>
                </div>
            </div>
        </article>
    <?php
    // tag </li> akan ditutup otomatis oleh WordPress
}

function mediman_offcanvas_header_content() {
    $light_logo = get_theme_mod('light_mode_logo');
    $dark_logo  = get_theme_mod('dark_mode_logo');

    // Cek apakah logo mode terang diatur
    if (!empty($light_logo)) {
        // Jika logo ada, tampilkan logo
        echo '<div class="offcanvas-logo" id="offcanvasMobileMenuLabel">';
        echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">';
        echo '<img id="light-mode-logo-offcanvas" src="' . esc_url($light_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo logo-light">';
        
        // Tampilkan juga logo gelap jika ada
        if (!empty($dark_logo)) {
            echo '<img id="dark-mode-logo-offcanvas" src="' . esc_url($dark_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo logo-dark">';
        }
        
        echo '</a>';
        echo '</div>';
    } else {
        // Jika tidak ada logo, tampilkan judul situs sebagai gantinya
        echo '<h5 class="offcanvas-title" id="offcanvasMobileMenuLabel">' . esc_html(get_bloginfo('name')) . '</h5>';
    }
}


function mediman_set_archive_posts_per_page( $query ) {
    // Pastikan ini hanya berjalan di query utama pada halaman arsip
    if ( ! is_admin() && $query->is_main_query() && is_archive() ) {
        // Atur jumlah postingan menjadi 9
        $query->set( 'posts_per_page', 9 );
    }
}
add_action( 'pre_get_posts', 'mediman_set_archive_posts_per_page' );


function mediman_display_author_avatar( $user_id, $size = 40 ) {
    $avatar_url = '';

    // Cek apakah pengguna punya Gravatar
    if ( get_avatar_url( $user_id ) ) {
        $avatar_data = get_avatar_data($user_id, ['size' => $size]);
        // Cek lagi jika yang didapat BUKAN gambar default "Mystery Person"
        if( ! $avatar_data['default'] ){
            $avatar_url = $avatar_data['url'];
        }
    }

    // Jika setelah dicek ternyata tidak ada Gravatar asli, gunakan fallback
    if ( empty($avatar_url) ) {
        $custom_avatar_url = get_theme_mod('global_default_author_avatar');
        if ( ! empty( $custom_avatar_url ) ) {
            $avatar_url = esc_url($custom_avatar_url);
        } else {
            $avatar_url = get_template_directory_uri() . '/assets/image/avatar-default.png';
        }
    }

    // Tampilkan gambar
    echo '<img src="' . esc_url( $avatar_url ) . '" width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" alt="Avatar Penulis" class="avatar rounded-circle">';
}

/**
 * Menyisipkan iklan dari area widget 'ad-in-content' ke tengah konten postingan.
 *
 * @param string $content Konten asli dari postingan.
 * @return string Konten yang sudah disisipi iklan.
 */
function mediman_insert_ad_in_content($content) {
    // Kondisi: Hanya jalankan di halaman artikel tunggal dan bukan di dalam loop utama (untuk menghindari duplikasi).
    if (is_single() && in_the_loop() && is_main_query()) {
        
        // Pastikan ada widget aktif di area ini sebelum melanjutkan
        if (!is_active_sidebar('ad-in-content')) {
            return $content;
        }

        // Ambil kode iklan dari area widget
        ob_start();
        dynamic_sidebar('ad-in-content');
        $ad_code = ob_get_clean();

        // Pecah konten menjadi paragraf
        $paragraphs = explode('</p>', $content);
        $paragraph_count = count($paragraphs);

        // Jangan sisipkan jika paragraf terlalu sedikit (misal, kurang dari 4)
        if ($paragraph_count < 4) {
            return $content;
        }

        // Paragraf di mana iklan akan disisipkan (setelah paragraf ke-2)
        $ad_insertion_point = 2; 

        // Loop melalui paragraf dan sisipkan iklan
        $new_content = '';
        for ($i = 0; $i < $paragraph_count; $i++) {
            // Tambahkan paragraf kembali
            $new_content .= $paragraphs[$i] . '</p>';

            // Jika ini adalah titik penyisipan, tambahkan kode iklan
            if ($i + 1 == $ad_insertion_point) {
                $new_content .= '<div class="in-content-ad-wrapper">' . $ad_code . '</div>';
            }
        }

        return $new_content;
    }

    // Jika bukan halaman artikel, kembalikan konten asli
    return $content;
}
add_filter('the_content', 'mediman_insert_ad_in_content');






?>