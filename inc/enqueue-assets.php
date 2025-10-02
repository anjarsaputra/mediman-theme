<?php
/**
 * Pusat Pemuatan Aset (CSS & JavaScript) untuk Tema Mediman.
 * File ini juga menangani pembuatan CSS dinamis dari Customizer.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fungsi utama untuk memuat semua style dan script tema.
 * Dihubungkan ke hook 'wp_enqueue_scripts'.
 */
function mediman_enqueue_assets() {
    
    // === STYLESHEETS ===

    // Bootstrap 5 CSS dari CDN
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3');
    // Bootstrap Icons dari CDN
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', [], '1.11.3');
    // Font Awesome dari CDN
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [], '5.15.4');

    // Style.css utama dari tema
    // Dimuat setelah Bootstrap agar bisa menimpa (override) style Bootstrap.
wp_enqueue_style('mediman-style', get_template_directory_uri() . '/style.css', ['bootstrap-css'], MEDIMAN_THEME_VERSION);
    // === JAVASCRIPTS ===
    wp_enqueue_script('jquery');
    // Bootstrap 5 JS Bundle (sudah termasuk Popper.js) dari CDN
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], '5.3.3', true);
    // Skrip untuk Mode Gelap/Terang
    wp_enqueue_script('mediman-theme-toggle', get_template_directory_uri() . '/assets/js/theme-toggle.js', [], '1.0', true);
    // Skrip untuk Ticker jika ada
    wp_enqueue_script('mediman-ticker', get_template_directory_uri() . '/assets/js/ticker.js', ['jquery'], '1.0', true);


   
    
    // === TAMBAHKAN BARIS INI UNTUK MEMUAT JS MENU BARU ===
    wp_enqueue_script('mediman-mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', [], '1.0', true);
    //typewriter js
    wp_enqueue_script('mediman-typewriter', get_template_directory_uri() . '/assets/js/typewriter.js', [], '1.0', true);

    wp_enqueue_script('mediman-pagination-fix', get_template_directory_uri() . '/assets/js/pagination-fix.js', [], '1.0', true);

        // Skrip yang hanya dimuat di halaman depan
    if ( is_front_page() ) {
        wp_enqueue_script('mediman-ticker', get_template_directory_uri() . '/assets/js/ticker.js', ['jquery'], '1.0', true);
    }

    // Hanya muat skrip Reading Progress jika kita berada di halaman single post.
    if ( is_single() ) {
        wp_enqueue_script('mediman-reading-progress', get_template_directory_uri() . '/assets/js/reading-progress.js', [], '1.0', true);
    }

    // PERBAIKAN: Muat skrip Scroll to Top hanya jika diaktifkan
    if ( get_theme_mod( 'enable_scroll_to_top', true ) ) {
        wp_enqueue_script('mediman-scroll-to-top', get_template_directory_uri() . '/assets/js/scroll-to-top.js', [], '1.0', true);
    }
    // === DYNAMIC INLINE CSS ===
    // Memanggil fungsi di bawah untuk menghasilkan CSS dari Customizer
    // dan menambahkannya ke dalam 'mediman-style'.
    $custom_css = mediman_generate_dynamic_css();
    if ( ! empty( $custom_css ) ) {
        wp_add_inline_style( 'mediman-style', $custom_css );
    }
}
add_action('wp_enqueue_scripts', 'mediman_enqueue_assets');

// Memuat Class Navigasi Bootstrap
if (file_exists(get_template_directory() . '/class-wp-bootstrap-navwalker.php')) {
    require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
/**
 * Fungsi untuk Menghasilkan CSS Dinamis dari Customizer.
 * Fungsi ini mengambil nilai dari Customizer dan mengubahnya menjadi string CSS.
 *
 * @return string The generated CSS.
 */
function mediman_generate_dynamic_css() {
    // Mulai "menangkap" output PHP ke dalam sebuah string
    ob_start();

    // Ambil semua nilai dari Customizer
    $h1_font = get_theme_mod('h1_font_family', 'Playfair Display');
    $h2_font = get_theme_mod('h2_font_family', 'Playfair Display');
    $p_font  = get_theme_mod('paragraph_font_family', 'Roboto');
    
    $mobile_menu_bg   = get_theme_mod('mobile_menu_bg_color', '#ffffff');
    $mobile_menu_text = get_theme_mod('mobile_menu_text_color', '#212529');

    $social_icon_color = get_theme_mod('mediman_social_icon_color', '#000000');
    $social_icon_hover_color = get_theme_mod('mediman_social_icon_hover_color', '#0d6efd');


    // Ambil nilai dari Customizer (hanya sekali, tidak ada duplikasi)
    $hamburger_bg           = get_theme_mod('mobile_hamburger_bg_color', '#ffffff');
    $hamburger_border_color = get_theme_mod('mobile_hamburger_border_color', '#dee2e6');
    $hamburger_icon_color   = get_theme_mod('mobile_hamburger_icon_color', '#212529');
    $hamburger_size         = get_theme_mod('mobile_hamburger_size', 40);
    $hamburger_padding      = get_theme_mod('mobile_hamburger_padding', 8);
    $hamburger_border_width = get_theme_mod('mobile_hamburger_border_width', 1);
    $hamburger_border_radius= get_theme_mod('mobile_hamburger_border_radius', 4); // <--- Variabel BARU
    $hamburger_icon_select  = get_theme_mod('mobile_hamburger_icon_select', 'default');
    $hamburger_border       = get_theme_mod('mobile_hamburger_border_color', '#dee2e6');


   // === AMBIL NILAI LABEL KATEGORI DARI CUSTOMIZER ===
    $cat_bg         = get_theme_mod('category_label_bg_color', '#dc3545');
    $cat_text       = get_theme_mod('category_label_text_color', '#ffffff');
    $cat_font       = get_theme_mod('category_label_font_family', 'Poppins');
    $cat_weight     = get_theme_mod('category_label_font_weight', '600');
    
    // Logika untuk memilih ikon SVG
    $icon_svg_color = str_replace('#', '%23', $hamburger_icon_color);
    $svg_path = '';
    switch ($hamburger_icon_select) {
        case 'plus':
            $svg_path = "path stroke='" . $icon_svg_color . "' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M5 15h20M15 5v20'";
            break;
        case 'dots':
            $svg_path = "circle cx='15' cy='7' r='2' fill='" . $icon_svg_color . "'/%3e%3ccircle cx='15' cy='15' r='2' fill='" . $icon_svg_color . "'/%3e%3ccircle cx='15' cy='23' r='2' fill='" . $icon_svg_color . "'";
            break;
        default: // 'default'
            $svg_path = "path stroke='" . $icon_svg_color . "' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'";
            break;
    }
    $hamburger_icon_svg = "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3c" . $svg_path . "/%3e%3c/svg%3e\")";
    // Mulai tulis CSS di sini


        

    // Ambil warna dari Customizer
    $ticker_bg = get_theme_mod('ticker_bg_color', '#f8f9fa');
    $ticker_title_bg = get_theme_mod('ticker_title_bg_color', '#198754');
    $ticker_text = get_theme_mod('ticker_text_color', '#212529');

     // === Logika untuk Warna Carousel ===
     $caption_bg = get_theme_mod('carousel_caption_bg_color', 'rgba(0,0,0,0.5)');
     $category_bg = get_theme_mod('carousel_category_bg_color', '#dc3545');

    
    // === AMBIL NILAI LABEL KATEGORI DARI CUSTOMIZER ===
    $cat_bg         = get_theme_mod('category_label_bg_color', 'rgba(220, 53, 69, 1)');
    $cat_text       = get_theme_mod('category_label_text_color', '#ffffff');
    $cat_font       = get_theme_mod('category_label_font_family', 'Poppins');
    $cat_size       = get_theme_mod('category_label_font_size', 12);
    $cat_weight     = get_theme_mod('category_label_font_weight', '600');

    // Ambil nilai warna dari Customizer
    $scroll_bg   = get_theme_mod('scroll_top_bg_color', '#0d6efd');
    $scroll_icon = get_theme_mod('scroll_top_icon_color', '#ffffff');

 // Muat skrip Kursor Animasi hanya jika diaktifkan
    if ( get_theme_mod( 'enable_animated_cursor', false ) ) {
        wp_enqueue_script('mediman-custom-cursor', get_template_directory_uri() . '/assets/js/custom-cursor.js', [], '1.0', true);
    }

    // Ambil nilai dari Customizer
    $cursor_inner_size = get_theme_mod('cursor_inner_size', 8);
    $cursor_inner_color = get_theme_mod('cursor_inner_color', '#000000');
    $cursor_outer_size = get_theme_mod('cursor_outer_size', 40);
    $cursor_outer_color = get_theme_mod('cursor_outer_color', '#000000');
    $cursor_outer_opacity = get_theme_mod('cursor_outer_opacity', 20) / 100; // ubah 20 -> 0.2
    $cursor_hover_scale = get_theme_mod('cursor_hover_scale', 1.5);
    $cursor_hover_opacity = get_theme_mod('cursor_hover_opacity', 50) / 100; // ubah 50 -> 0.5
    
    // Konversi warna HEX kursor luar ke RGBA untuk transparansi
    list($r, $g, $b) = sscanf($cursor_outer_color, "#%02x%02x%02x");
    $cursor_outer_rgba = "rgba({$r}, {$g}, {$b}, {$cursor_outer_opacity})";


    

    ?>
    /* -- Dynamic Styles from Customizer -- */

    /* Tipografi */
    h1 { font-family: '<?php echo esc_attr($h1_font); ?>', serif; }
    h2 { font-family: '<?php echo esc_attr($h2_font); ?>', serif; }
    p, body { font-family: '<?php echo esc_attr($p_font); ?>', sans-serif; }

    /* Pengaturan Menu Mobile */
    .offcanvas {
        background-color: <?php echo esc_attr($mobile_menu_bg); ?>;
        color: <?php echo esc_attr($mobile_menu_text); ?>;
    }
    .offcanvas .offcanvas-title,
    .offcanvas .offcanvas-body a,
    .offcanvas .navbar-nav .nav-link {
        color: <?php echo esc_attr($mobile_menu_text); ?>;
    }
    .offcanvas .btn-close {
        filter: invert(<?php echo ('#ffffff' === strtolower($mobile_menu_bg)) ? '0' : '1'; ?>);
    }

    /* Pengaturan Ikon Media Sosial */
    .social-icons a {
        color: <?php echo esc_attr($social_icon_color); ?>;
    }
    .social-icons a:hover {
        color: <?php echo esc_attr($social_icon_hover_color); ?>;
    }


    /* Pengaturan Tombol Hamburger Mobile */
    .navbar-toggler {
        background-color: <?php echo esc_attr($hamburger_bg); ?> !important;
        border: 1px solid <?php echo esc_attr($hamburger_border); ?> !important;
    }

    .navbar-toggler-icon {
        background-image: <?php echo $hamburger_icon_svg; ?> !important;
    }


    /* Pengaturan Tombol Hamburger Mobile (Satu Blok CSS yang Benar) */
    .navbar-toggler {
        background-color: <?php echo esc_attr($hamburger_bg); ?> !important;
        border: <?php echo esc_attr($hamburger_border_width); ?>px solid <?php echo esc_attr($hamburger_border_color); ?> !important;
        width: <?php echo esc_attr($hamburger_size); ?>px !important;
        height: <?php echo esc_attr($hamburger_size); ?>px !important;
        padding: <?php echo esc_attr($hamburger_padding); ?>px !important;
        border-radius: <?php echo esc_attr($hamburger_border_radius); ?>px !important; /* <--- Properti CSS BARU */
        line-height: 1;
    }

    .navbar-toggler-icon {
        background-image: <?php echo $hamburger_icon_svg; ?> !important;
        width: 100%;
        height: 100%;
    }

    .typewriter-ticker-container { background-color: <?php echo esc_attr($ticker_bg); ?>; }
    .ticker-title-typewriter { background-color: <?php echo esc_attr($ticker_title_bg); ?>; }
    .typewriter-wrap a { color: <?php echo esc_attr($ticker_text); ?>; }

    /* -- Dynamic Carousel Styles -- */
    .caption-content-box {
        background-color: <?php echo esc_attr($caption_bg); ?>;
    }
    .caption-category {
        background-color: <?php echo esc_attr($category_bg); ?>;
    }

    /* -- Dynamic Category Label Styles -- */
    .card-category-tag {
        background-color: <?php echo esc_attr($cat_bg); ?>;
        color: <?php echo esc_attr($cat_text); ?>;
        font-family: '<?php echo esc_attr($cat_font); ?>', sans-serif;
        font-weight: <?php echo esc_attr($cat_weight); ?>;
    }

    /* -- Dynamic Category Label Styles -- */
    .card-category-tag {
        background-color: <?php echo esc_attr($cat_bg); ?>;
        color: <?php echo esc_attr($cat_text); ?> !important; /* Paksa warna teks */
        font-family: '<?php echo esc_attr($cat_font); ?>', sans-serif;
        font-size: <?php echo esc_attr($cat_size); ?>px;
        font-weight: <?php echo esc_attr($cat_weight); ?>;
        padding: 0.3em 0.8em; /* Padding yang lebih proporsional */
        border-radius: 4px;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
    }

    .scroll-to-top-button {
        background-color: <?php echo esc_attr($scroll_bg); ?>;
        color: <?php echo esc_attr($scroll_icon); ?>;
    }

     /* -- Dynamic Cursor Styles -- */
    .custom-cursor-inner {
        width: <?php echo esc_attr($cursor_inner_size); ?>px;
        height: <?php echo esc_attr($cursor_inner_size); ?>px;
        margin-left: -<?php echo esc_attr($cursor_inner_size / 2); ?>px;
        margin-top: -<?php echo esc_attr($cursor_inner_size / 2); ?>px;
        background-color: <?php echo esc_attr($cursor_inner_color); ?>;
    }
    .custom-cursor-outer {
        width: <?php echo esc_attr($cursor_outer_size); ?>px;
        height: <?php echo esc_attr($cursor_outer_size); ?>px;
        margin-left: -<?php echo esc_attr($cursor_outer_size / 2); ?>px;
        margin-top: -<?php echo esc_attr($cursor_outer_size / 2); ?>px;
        background-color: <?php echo esc_attr($cursor_outer_rgba); ?>;
    }
    .custom-cursor-outer.hover-effect,
    .custom-cursor-outer.inverted {
        transform: scale(<?php echo esc_attr($cursor_hover_scale); ?>);
        opacity: <?php echo esc_attr($cursor_hover_opacity); ?>;
    }
    
    


    /* -- End Dynamic Styles -- */







    <?php
    
    // Kembalikan semua output yang ditangkap sebagai satu string CSS
    return ob_get_clean();
}

/**
 * 2. Fungsi TERPISAH untuk membuat dan menerapkan CSS dinamis.
 * Fungsi ini dijalankan pada hook yang berbeda untuk mencegah loop.
 */
function mediman_apply_dynamic_styles() {
    
    // Ambil semua font yang dibutuhkan
    $cat_label_font = get_theme_mod('category_label_font_family', 'Poppins');
    $fonts_to_load = array_unique([$cat_label_font]); // Tambahkan font lain jika perlu
    
    if (!empty($fonts_to_load)) {
        $font_families = [];
        foreach ($fonts_to_load as $font) {
            $font_families[] = str_replace(' ', '+', $font) . ':400,600,700';
        }
        $query_args = ['family' => implode('|', $font_families), 'display' => 'swap'];
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        // Muat Google Fonts
        wp_enqueue_style('mediman-google-fonts', $fonts_url, [], null);
    }
    
    // Ambil nilai dari Customizer
    $cat_bg         = get_theme_mod('category_label_bg_color', 'rgba(220, 53, 69, 1)');
    $cat_text       = get_theme_mod('category_label_text_color', '#ffffff');
    $cat_font       = get_theme_mod('category_label_font_family', 'Poppins');
    $cat_size       = get_theme_mod('category_label_font_size', 12);
    $cat_weight     = get_theme_mod('category_label_font_weight', '600');

    // Buat string CSS
    $custom_css = "
        .card-category-tag {
            background-color: " . esc_attr($cat_bg) . ";
            color: " . esc_attr($cat_text) . " !important;
            font-family: '" . esc_attr($cat_font) . "', sans-serif;
            font-size: " . esc_attr($cat_size) . "px;
            font-weight: " . esc_attr($cat_weight) . ";
        }
    ";
    
    // Terapkan CSS dinamis
    wp_add_inline_style( 'mediman-style', $custom_css );
}
// PERBAIKAN UTAMA: Gunakan hook 'wp_enqueue_scripts' dengan prioritas lebih tinggi (setelah style utama dimuat)
add_action('wp_enqueue_scripts', 'mediman_apply_dynamic_styles', 20);


// functions.php tema

require_once get_template_directory() . '/inc/license-client.php';

// Cek lisensi saat tema diaktifkan
$license_client = new Theme_License_Client();

if (!$license_client->is_licensed()) {
    // Redirect ke halaman aktivasi lisensi
    add_action('admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p><strong>Tema belum diaktifkan!</strong> Silakan aktivasi lisensi Anda.</p>
            <p><a href="<?php echo admin_url('admin.php?page=theme-license'); ?>" class="button button-primary">Aktivasi Sekarang</a></p>
        </div>
        <?php
    });
}
/**
 * Memuat stylesheet dan script khusus untuk halaman admin yang relevan.
 */



