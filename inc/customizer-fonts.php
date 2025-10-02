<?php
/**
 * Fitur Pengaturan Font melalui WordPress Customizer.
 * Versi ini menambahkan pengaturan untuk Excerpt.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 1. Mendaftarkan semua Panel, Section, dan Control untuk Font ke Customizer.
 */
function mediman_register_font_settings( $wp_customize ) {

 
    $wp_customize->add_section( 'mediman_typography_section', [
        'title'    => __( 'Tipografi (Font)', 'mediman' ),
         'panel'   => 'mediman_features_panel', // Masukkan ke dalam panel fitur tambahan
        'priority' => 15,
    ] );

    $font_choices = [
        'Roboto'           => 'Roboto (Sans-serif)',
        'Open Sans'        => 'Open Sans (Sans-serif)',
        'Lato'             => 'Lato (Sans-serif)',
        'Poppins'          => 'Poppins (Sans-serif)',
        'Playfair Display' => 'Playfair Display (Serif)',
        'Merriweather'     => 'Merriweather (Serif)',
        'DM Serif Text'    => 'DM Serif Text (Serif)',
    ];

    // Font untuk Judul (H1, H2, H3)
    $wp_customize->add_setting('heading_font_family', ['default' => 'Playfair Display', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('heading_font_family', [
        'label'   => __('Font untuk Semua Judul', 'mediman'),
        'section' => 'mediman_typography_section',
        'type'    => 'select',
        'choices' => $font_choices,
    ]);

    // Font untuk Body & Paragraf
    $wp_customize->add_setting('body_font_family', ['default' => 'Roboto', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('body_font_family', [
        'label'   => __('Font untuk Teks Paragraf', 'mediman'),
        'section' => 'mediman_typography_section',
        'type'    => 'select',
        'choices' => $font_choices,
    ]);

    // === KONTROL BARU UNTUK EXCERPT ===
    $wp_customize->add_setting('excerpt_font_family', ['default' => 'Lato', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('excerpt_font_family', [
        'label'   => __('Font untuk Excerpt (Deskripsi Singkat)', 'mediman'),
        'section' => 'mediman_typography_section',
        'type'    => 'select',
        'choices' => $font_choices,
    ]);
    // === AKHIR KONTROL BARU ===
}
add_action('customize_register', 'mediman_register_font_settings');


/**
 * 2. Menghasilkan CSS dinamis berdasarkan pilihan di Customizer.
 */
function mediman_generate_font_css() {
    $heading_font = get_theme_mod('heading_font_family', 'Playfair Display');
    $body_font    = get_theme_mod('body_font_family', 'Roboto');
    $excerpt_font = get_theme_mod('excerpt_font_family', 'Lato'); // <-- Ambil nilai font excerpt

    ob_start();
    ?>
    /* Dynamic Font Styles */
    body, p, li, span, div {
        font-family: '<?php echo esc_attr($body_font); ?>', sans-serif;
    }
    h1, h2, h3, h4, h5, h6, .site-title, .caption-title a, .post-card-title {
        font-family: '<?php echo esc_attr($heading_font); ?>', serif;
    }
    /* PERBAIKAN DI SINI: Terapkan font ke kelas excerpt */
    .post-card-excerpt, .card-excerpt, .featured-excerpt-split, .list-post-excerpt {
        font-family: '<?php echo esc_attr($excerpt_font); ?>', sans-serif;
    }
    <?php
    return ob_get_clean();
}


/**
 * 3. Memuat Google Fonts & menerapkan CSS dinamis ke tema.
 */
function mediman_enqueue_custom_fonts() {
    // Ambil semua font yang dipilih
    $heading_font = get_theme_mod('heading_font_family', 'Playfair Display');
    $body_font    = get_theme_mod('body_font_family', 'Roboto');
    $excerpt_font = get_theme_mod('excerpt_font_family', 'Lato');


    // Buat daftar font unik yang perlu dimuat dari Google
    $fonts_to_load = array_unique([$heading_font, $body_font, $excerpt_font]);
    $font_families = [];

    foreach ($fonts_to_load as $font) {
        $font_families[] = str_replace(' ', '+', $font) . ':400,600,700';
    }

    if (!empty($font_families)) {
        $query_args = [
            'family'  => implode('|', $font_families),
            'display' => 'swap',
        ];
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        
        wp_enqueue_style('mediman-google-fonts', $fonts_url, [], null);
    }

    $custom_font_css = mediman_generate_font_css();
    if ( ! empty( $custom_font_css ) ) {
        wp_add_inline_style( 'mediman-style', $custom_font_css );
    }
}
add_action('wp_enqueue_scripts', 'mediman_enqueue_custom_fonts', 20);