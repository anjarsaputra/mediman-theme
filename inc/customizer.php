<?php
/**
 * Pengaturan WordPress Customizer untuk tema Mediman.
 * Versi ini menggunakan Panel untuk merapikan semua pengaturan header.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mediman_register_customizer_settings( $wp_customize ) {

    // PANEL UTAMA (Jika belum ada)
    if ( ! $wp_customize->get_panel( 'mediman_theme_options_panel' ) ) {
        $wp_customize->add_panel( 'mediman_theme_options_panel', [
            'title'       => __( 'Pengaturan Tema Mediman', 'mediman' ),
            'priority'    => 50,
        ] );
    }

    // =========================================================================
    // 1. PANEL PENGATURAN HEADER (Folder Utama)
    // =========================================================================
    $wp_customize->add_panel( 'mediman_header_panel', [
        'title'    => __( 'Pengaturan Header', 'mediman' ),
        'panel'    => 'mediman_theme_options_panel',
        'priority' => 201,
    ] );

    // =========================================================================
    // 1.1 SECTION: PENGATURAN MEDIA SOSIAL (Sub-menu pertama)
    // =========================================================================
    $wp_customize->add_section( 'mediman_social_icons_section', [
        'title'    => __( 'Ikon Media Sosial', 'mediman' ),
        'panel'    => 'mediman_header_panel',
        'priority' => 10,
    ] );

    // Kontrol untuk URL Media Sosial
    $wp_customize->add_setting( 'mediman_facebook_url', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control( 'mediman_facebook_url', ['label' => __( 'URL Facebook', 'mediman' ), 'section' => 'mediman_social_icons_section', 'type' => 'url']);
    
    $wp_customize->add_setting( 'mediman_twitter_url', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control( 'mediman_twitter_url', ['label' => __( 'URL Twitter/X', 'mediman' ), 'section' => 'mediman_social_icons_section', 'type' => 'url']);

    $wp_customize->add_setting( 'mediman_instagram_url', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control( 'mediman_instagram_url', ['label' => __( 'URL Instagram', 'mediman' ), 'section' => 'mediman_social_icons_section', 'type' => 'url']);
    
    // Kontrol untuk Warna Ikon Media Sosial
    $wp_customize->add_setting( 'mediman_social_icon_color', ['default' => '#000000', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mediman_social_icon_color', ['label' => __( 'Warna Ikon', 'mediman' ), 'section' => 'mediman_social_icons_section']));

    $wp_customize->add_setting( 'mediman_social_icon_hover_color', ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mediman_social_icon_hover_color', ['label' => __( 'Warna Ikon (Hover)', 'mediman' ), 'section' => 'mediman_social_icons_section']));

    // =========================================================================
    // 1.2 SECTION: PENGATURAN UMUM HEADER (Sub-menu kedua)
    // =========================================================================
    $wp_customize->add_section( 'mediman_general_header_section', [
        'title'    => __( 'Tampilan Umum', 'mediman' ),
        'panel'    => 'mediman_header_panel',
        'priority' => 20,
    ] );

    $wp_customize->add_setting( 'show_header_search', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'show_header_search', ['label' => __( 'Tampilkan Ikon Pencarian', 'mediman' ), 'section' => 'mediman_general_header_section', 'type' => 'checkbox']);

    $wp_customize->add_setting( 'show_dark_mode_toggle', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'show_dark_mode_toggle', ['label' => __( 'Tampilkan Tombol Mode Gelap', 'mediman' ), 'section' => 'mediman_general_header_section', 'type' => 'checkbox']);


        // =========================================================================
    // PANEL: PENGATURAN HALAMAN DEPAN
    // =========================================================================
    $wp_customize->add_panel( 'mediman_homepage_panel', [
        'title'    => __( 'Pengaturan Halaman Depan', 'mediman' ),
        'panel'    => 'mediman_theme_options_panel', // Masukkan ke dalam panel utama
        'priority' => 203,
    ] );

    // =========================================================================
    // SECTION PENGATURAN JUDUL "LATEST ARTICLES"
    // =========================================================================
    $wp_customize->add_section( 'mediman_latest_posts_title_section', [
        'title'       => __( 'Judul Section Artikel Terbaru', 'mediman' ),
        'description' => __( 'Atur judul dan subjudul untuk blok "Latest Articles" di halaman depan.', 'mediman' ),
        'priority'    => 40,
        'panel'    => 'mediman_homepage_panel',
    ] );

    // 1. Kontrol untuk Judul Utama
    $wp_customize->add_setting( 'latest_posts_section_title', [
        'default'           => 'LATEST ARTICLES',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'latest_posts_section_title', [
        'label'   => __( 'Judul Utama Section', 'mediman' ),
        'section' => 'mediman_latest_posts_title_section',
        'type'    => 'text',
    ] );

    // 2. Kontrol untuk Subjudul
    $wp_customize->add_setting( 'latest_posts_section_subtitle', [
        'default'           => 'Discover the most recent articles from our authors.',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'latest_posts_section_subtitle', [
        'label'   => __( 'Subjudul Section', 'mediman' ),
        'section' => 'mediman_latest_posts_title_section',
        'type'    => 'textarea',
    ] );


        // =========================================================================
    // SECTION PENGATURAN LABEL KATEGORI (Dimasukkan ke Panel Halaman Depan)
    // =========================================================================
    $wp_customize->add_section( 'mediman_category_label_section', [
        'title'       => __( 'Label Kategori', 'mediman' ),
        'description' => __( 'Atur tampilan label kategori pada kartu postingan.', 'mediman' ),
        'panel'       => 'mediman_homepage_panel', // <-- PERBAIKAN UTAMA DI SINI
        'priority'    => 50,
    ] );

    // 1. Warna Latar (dengan Alpha/Transparansi)
    $wp_customize->add_setting( 'category_label_bg_color', ['default' => 'rgba(220, 53, 69, 1)', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'category_label_bg_color', [
        'label'   => __( 'Warna Latar', 'mediman' ),
        'section' => 'mediman_category_label_section',
        'input_attrs' => [ 'data-alpha-enabled' => 'true' ],
    ] ) );

    // 2. Warna Teks
    $wp_customize->add_setting( 'category_label_text_color', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'category_label_text_color', [
        'label'   => __( 'Warna Teks', 'mediman' ),
        'section' => 'mediman_category_label_section',
    ] ) );

    // 3. Pilihan Font
    $font_choices = ['Roboto'=>'Roboto', 'Open Sans'=>'Open Sans', 'Lato'=>'Lato', 'Poppins'=>'Poppins'];
    $wp_customize->add_setting('category_label_font_family', ['default' => 'Poppins', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('category_label_font_family', [
        'label'   => __('Font Label', 'mediman'),
        'section' => 'mediman_category_label_section',
        'type'    => 'select',
        'choices' => $font_choices,
    ]);

    // 4. Ukuran Font
    $wp_customize->add_setting('category_label_font_size', ['default' => 12, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('category_label_font_size', [
        'label'   => __('Ukuran Font (px)', 'mediman'),
        'section' => 'mediman_category_label_section',
        'type'    => 'number',
        'input_attrs' => ['min' => 8, 'max' => 24, 'step' => 1],
    ]);

    // 5. Ketebalan Font
    $font_weight_choices = ['400'=>'Normal', '600'=>'Semi-Bold', '700'=>'Bold'];
    $wp_customize->add_setting('category_label_font_weight', ['default' => '600', 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('category_label_font_weight', [
        'label'   => __('Ketebalan Font', 'mediman'),
        'section' => 'mediman_category_label_section',
        'type'    => 'select',
        'choices' => $font_weight_choices,
    ]);

    // =========================================================================
    // PANEL PENGATURAN CAROUSEL
    // =========================================================================
    $wp_customize->add_section( 'mediman_carousel_section', [
        'title'       => __( 'Pengaturan Carousel/Slider', 'mediman' ),
       'panel'       => 'mediman_homepage_panel', 
        'priority'    => 35,
    ] );

    $wp_customize->add_setting( 'carousel_enable', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'carousel_enable', [
        'label'   => __( 'Aktifkan Carousel', 'mediman' ),
        'section' => 'mediman_carousel_section',
        'type'    => 'checkbox',
    ] );

    $wp_customize->add_setting( 'carousel_source', [ 'default' => 'latest', 'sanitize_callback' => 'sanitize_key' ] );
    $wp_customize->add_control( 'carousel_source', [
        'label'   => __( 'Sumber Postingan Carousel', 'mediman' ),
        'section' => 'mediman_carousel_section',
        'type'    => 'radio',
        'choices' => [
            'latest'     => __( 'Postingan Terbaru', 'mediman' ),
            'popular'    => __( 'Postingan Terpopuler (Komentar)', 'mediman' ),
        ],
    ] );

    $wp_customize->add_setting( 'carousel_post_count', [ 'default' => 4, 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( 'carousel_post_count', [
        'label'   => __( 'Jumlah Slide', 'mediman' ),
        'section' => 'mediman_carousel_section',
        'type'    => 'number',
        'input_attrs' => [ 'min' => 3, 'max' => 8 ],
    ] );

    // --- Pengaturan Warna & Tampilan Caption ---
    $wp_customize->add_setting( 'carousel_caption_bg_color', ['default' => 'rgba(0,0,0,0.5)', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'carousel_caption_bg_color', [
        'label'   => __( 'Warna Latar Kotak Teks', 'mediman' ),
        'description' => __( 'Gunakan picker dengan slider transparansi.', 'mediman'),
        'section' => 'mediman_carousel_section',
    ] ) );
    
    $wp_customize->add_setting( 'carousel_category_bg_color', ['default' => '#dc3545', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'carousel_category_bg_color', [
        'label'   => __( 'Warna Latar Tombol Kategori', 'mediman' ),
        'section' => 'mediman_carousel_section',
    ] ) );


        // Fungsi untuk membuat pengaturan Blok Kategori secara berulang
    function mediman_create_category_block_settings( $wp_customize, $block_number ) {
        $id = 'cat_block_' . $block_number;
        
        $wp_customize->add_section( 'mediman_' . $id . '_section', [
            'title'       => __( 'Blok Kategori Halaman Depan #', 'mediman' ) . $block_number,
            'panel'       => 'mediman_homepage_panel', 
            'priority'    => 50 + $block_number,
        ] );

        // Dropdown untuk memilih kategori (ini juga berfungsi sebagai pengaktif blok)
        $categories = get_categories();
        $cats = ['' => '-- Nonaktifkan Blok Ini --'];
        foreach ( $categories as $category ) {
            $cats[ $category->term_id ] = $category->name;
        }
        $wp_customize->add_setting( $id . '_category_id', [ 'default' => '', 'sanitize_callback' => 'absint' ] );
        $wp_customize->add_control( $id . '_category_id', [
            'label'   => __( 'Pilih Kategori untuk Ditampilkan', 'mediman' ),
            'section' => 'mediman_' . $id . '_section',
            'type'    => 'select',
            'choices' => $cats,
        ] );
        
        // Input untuk Judul Section Kustom
        $wp_customize->add_setting( $id . '_title', [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id . '_title', [
            'label'   => __( 'Judul Kustom (Opsional)', 'mediman' ),
            'description' => __( 'Jika kosong, akan menggunakan nama kategori.', 'mediman' ),
            'section' => 'mediman_' . $id . '_section',
            'type'    => 'text',
        ] );

        // Dropdown untuk memilih GAYA LAYOUT
        $wp_customize->add_setting( $id . '_layout_style', [ 'default' => 'magazine', 'sanitize_callback' => 'sanitize_key' ] );
        $wp_customize->add_control( $id . '_layout_style', [
            'label'   => __( 'Pilih Gaya Layout', 'mediman' ),
            'section' => 'mediman_' . $id . '_section',
            'type'    => 'select',
            'choices' => [
                'magazine' => __( 'Layout Majalah', 'mediman' ),
                'grid'     => __( 'Layout Grid ', 'mediman' ),
                'list'     => __( 'Layout Gambar di kiri', 'mediman' ),
            ],
        ] );
    }

    // Buat pengaturan untuk 3 blok kategori
    for ( $i = 1; $i <= 3; $i++ ) {
        mediman_create_category_block_settings( $wp_customize, $i );
    }

    // =========================================================================
    // PANEL PENGATURAN NEWS TICKER
    // =========================================================================
    $wp_customize->add_section( 'mediman_ticker_section', [
        'title'       => __( 'Pengaturan News Ticker', 'mediman' ),
            'panel'       => 'mediman_homepage_panel', 
        'priority'    => 30,
    ] );

    $wp_customize->add_setting( 'ticker_enable', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'ticker_enable', ['label' => __( 'Aktifkan News Ticker', 'mediman' ),'section' => 'mediman_ticker_section','type' => 'checkbox']);

    // --- PENGATURAN BARU UNTUK JUDUL TICKER ---
    $wp_customize->add_setting( 'ticker_label_text', [ 'default' => 'TRENDING', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'ticker_label_text', ['label' => __( 'Teks Label', 'mediman' ),'section' => 'mediman_ticker_section','type' => 'text']);

    $wp_customize->add_setting( 'ticker_label_icon', [ 'default' => 'bi-lightning-fill', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'ticker_label_icon', [
        'label'   => __( 'Pilih Ikon Label', 'mediman' ),
        'section' => 'mediman_ticker_section',
        'type'    => 'select',
        'choices' => [
            'bi-lightning-fill' => __( 'Petir (Trending)', 'mediman' ),
            'bi-fire'           => __( 'Api (Hot)', 'mediman' ),
            'bi-megaphone-fill' => __( 'Megafon (Info)', 'mediman' ),
            'bi-star-fill'      => __( 'Bintang (Penting)', 'mediman' ),
        ],
    ] );
    
    $wp_customize->add_setting( 'ticker_desktop_label_type', [ 'default' => 'icon_text', 'sanitize_callback' => 'sanitize_key' ] );
    $wp_customize->add_control( 'ticker_desktop_label_type', [
        'label'   => __( 'Tampilan Label (Desktop)', 'mediman' ),
        'section' => 'mediman_ticker_section',
        'type'    => 'select',
        'choices' => ['none' => 'Tanpa Label','text' => 'Hanya Teks','icon' => 'Hanya Ikon','icon_text' => 'Ikon & Teks'],
    ] );

    $wp_customize->add_setting( 'ticker_mobile_label_type', [ 'default' => 'icon', 'sanitize_callback' => 'sanitize_key' ] );
    $wp_customize->add_control( 'ticker_mobile_label_type', [
        'label'   => __( 'Tampilan Label (Mobile)', 'mediman' ),
        'section' => 'mediman_ticker_section',
        'type'    => 'select',
        'choices' => ['none' => 'Tanpa Label','text' => 'Hanya Teks','icon' => 'Hanya Ikon','icon_text' => 'Ikon & Teks'],
    ] );

    $wp_customize->add_setting( 'ticker_post_source', [ 'default' => 'popular', 'sanitize_callback' => 'sanitize_key' ] );
    $wp_customize->add_control( 'ticker_post_source', [
        'label'   => __( 'Sumber Postingan', 'mediman' ),
        'section' => 'mediman_ticker_section',
        'type'    => 'radio',
        'choices' => [
            'popular' => __( 'Terpopuler (Berdasarkan Komentar)', 'mediman' ),
            'latest'  => __( 'Terbaru', 'mediman' ),
        ],
    ] );

    $wp_customize->add_setting( 'ticker_bg_color', ['default' => '#f8f9fa', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ticker_bg_color', [
        'label'   => __( 'Warna Latar Ticker', 'mediman' ),
        'section' => 'mediman_ticker_section',
    ] ) );

    $wp_customize->add_setting( 'ticker_title_bg_color', ['default' => '#198754', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ticker_title_bg_color', [
        'label'   => __( 'Warna Latar Judul', 'mediman' ),
        'section' => 'mediman_ticker_section',
    ] ) );
    
    $wp_customize->add_setting( 'ticker_text_color', ['default' => '#212529', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ticker_text_color', [
        'label'   => __( 'Warna Teks Ticker', 'mediman' ),
        'section' => 'mediman_ticker_section',
    ] ) );

    $wp_customize->add_setting( 'ticker_show_nav', [ 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'ticker_show_nav', [
        'label'   => __( 'Tampilkan Tombol Navigasi (Next/Prev)', 'mediman' ),
        'section' => 'mediman_ticker_section',
        'type'    => 'checkbox',
    ] );



        // =========================================================================
    // PENAMBAHAN PADA SECTION BAWAAN WORDPRESS
    // =========================================================================
    $wp_customize->add_setting('light_mode_logo', ['default' => '', 'transport' => 'refresh', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'light_mode_logo', ['label' => 'Logo Light Mode', 'section' => 'title_tagline', 'settings' => 'light_mode_logo', 'priority' => 8]));
    
    $wp_customize->add_setting('dark_mode_logo', ['default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'dark_mode_logo', ['label' => 'Logo Dark Mode', 'section' => 'title_tagline', 'settings' => 'dark_mode_logo', 'priority' => 9]));


    // =========================================================================
    // SECTION PENGATURAN FITUR TAMBAHAN (SCROLL TO TOP)
    // =========================================================================
    // Ini akan menjadi satu-satunya section untuk fitur ini, dan akan berdiri sendiri.
    $wp_customize->add_panel( 'mediman_features_panel', [
        'title'       => __( 'Fitur Tambahan', 'mediman' ),
        'description' => __( 'Atur fitur-fitur tambahan untuk tema Anda.', 'mediman' ),
        'priority'    => 999,
    ]);

    // =========================================================================
    // SECTION PENGATURAN SCROLL TO TOP (BERDIRI SENDIRI)
    // =========================================================================
    $wp_customize->add_section( 'mediman_scroll_to_top_section', [
        'title'       => __( 'Pengaturan Scroll to Top', 'mediman' ),
        'description' => __( 'Atur tampilan dan fungsi untuk tombol kembali ke atas.', 'mediman' ),
        'priority'    => 140, // Atur prioritas untuk posisi di daftar menu
        'panel'       => 'mediman_features_panel', // Masukkan ke dalam panel fitur tambahan
    
    ] );

    // 1. Kontrol untuk mengaktifkan Tombol
    $wp_customize->add_setting( 'enable_scroll_to_top', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'enable_scroll_to_top', [
        'label'   => __( 'Aktifkan Tombol Scroll to Top', 'mediman' ),
        'section' => 'mediman_scroll_to_top_section',
        'type'    => 'checkbox',
    ] );
    
    // 2. Kontrol untuk Warna Latar Tombol
    $wp_customize->add_setting( 'scroll_top_bg_color', ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'scroll_top_bg_color', [
        'label'   => __( 'Warna Latar Tombol', 'mediman' ),
        'section' => 'mediman_scroll_to_top_section',
    ] ) );
    
    // 3. Kontrol untuk Warna Ikon Tombol
    $wp_customize->add_setting( 'scroll_top_icon_color', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'scroll_top_icon_color', [
        'label'   => __( 'Warna Ikon', 'mediman' ),
        'section' => 'mediman_scroll_to_top_section',
    ] ) );

    // 4. Kontrol untuk Memilih Ikon
    $wp_customize->add_setting( 'scroll_top_icon_class', [ 'default' => 'bi-arrow-up', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'scroll_top_icon_class', [
        'label'   => __( 'Pilih Ikon Tombol', 'mediman' ),
        'section' => 'mediman_scroll_to_top_section',
        'type'    => 'select',
        'choices' => [
            'bi-arrow-up'         => 'Panah Atas (Standar)',
            'bi-chevron-up'       => 'Panah Atas (Chevron)',
            'bi-arrow-up-short'   => 'Panah Atas (Pendek)',
            'bi-eject-fill'       => 'Eject',
        ],
    ] );

    // =========================================================================
    // PANEL PENGATURAN FOOTER (BERDIRI SENDIRI)
    // =========================================================================
    // PERBAIKAN 1: Menggunakan add_panel untuk membuat "folder" utama
    $wp_customize->add_panel( 'mediman_footer_panel', [
        'title'       => __( 'Pengaturan Footer', 'mediman' ),
        'priority'    => 202, // Atur prioritas untuk posisi di daftar menu
    ]);

    // =========================================================================
    // SECTION: Teks Copyright (Sub-menu di dalam Panel Footer)
    // =========================================================================
    // PERBAIKAN 2: Membuat section baru untuk menampung kontrol
    $wp_customize->add_section( 'mediman_copyright_section', [
        'title'    => __( 'Teks Copyright', 'mediman' ),
        'panel'    => 'mediman_footer_panel', // Memasukkan section ini ke dalam panel footer
        'priority' => 10,
    ]);

    // KONTROL UNTUK TEKS COPYRIGHT
    $wp_customize->add_setting( 'footer_copyright_text', [
        'default'           => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'footer_copyright_text', [
        'label'       => __( 'Teks Copyright di Footer', 'mediman' ),
        'description' => __( 'Anda bisa menggunakan tag HTML dasar seperti <a> untuk link.', 'mediman'),
        'section'     => 'mediman_copyright_section', // PERBAIKAN 3: Arahkan ke section yang benar
        'type'        => 'textarea',
    ] );

    
    // =========================================================================
// PANEL PENGATURAN SINGLE POST (BERDIRI SENDIRI)
// =========================================================================
// PERBAIKAN 1: Menggunakan add_panel untuk membuat "folder" utama
$wp_customize->add_panel( 'mediman_single_post_panel', [
    'title'       => __( 'Pengaturan Single Post', 'mediman' ),
    'priority'    => 204, // Atur prioritas untuk posisi di daftar menu
]);

// =========================================================================
// SECTION: Fitur Konten (Sub-menu di dalam Panel Single Post)
// =========================================================================
// PERBAIKAN 2: Membuat section baru untuk menampung kontrol
$wp_customize->add_section( 'mediman_content_features_section', [
    'title'    => __( 'Fitur Konten', 'mediman' ),
    'panel'    => 'mediman_single_post_panel', // Memasukkan section ini ke dalam panel
    'priority' => 10,
]);

// Kontrol untuk Daftar Isi
$wp_customize->add_setting( 'single_post_show_toc', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
$wp_customize->add_control( 'single_post_show_toc', [
    'label'   => __( 'Tampilkan Daftar Isi (Otomatis)', 'mediman' ),
    'section' => 'mediman_content_features_section',
    'type'    => 'checkbox',
] );

// Kontrol untuk Artikel Terkait
$wp_customize->add_setting('enable_related_posts', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
$wp_customize->add_control('enable_related_posts', [
    'label'   => __( 'Tampilkan "Artikel Terkait" di Tengah Konten', 'mediman' ),
    'section' => 'mediman_content_features_section',
    'type'    => 'checkbox',
]);


// =========================================================================
// SECTION: Reading Progress Bar (Sub-menu di dalam Panel Single Post)
// =========================================================================
$wp_customize->add_section( 'mediman_reading_progress_section', [
    'title'    => __( 'Reading Progress Bar', 'mediman' ),
    'panel'    => 'mediman_single_post_panel', // Memasukkan section ini ke dalam panel
    'priority' => 20,
]);

// Kontrol untuk mengaktifkan Reading Progress Bar
$wp_customize->add_setting('enable_reading_progress', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
$wp_customize->add_control('enable_reading_progress', [
    'label'   => __( 'Aktifkan Reading Progress Bar', 'mediman' ),
    'section' => 'mediman_reading_progress_section',
    'type'    => 'checkbox',
]);

// Kontrol untuk Warna Reading Progress Bar
$wp_customize->add_setting( 'reading_progress_color', [
    'default'           => '#0d6efd',
    'sanitize_callback' => 'sanitize_hex_color',
] );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'reading_progress_color', [
    'label'   => __( 'Warna Reading Progress Bar', 'mediman' ),
    'section' => 'mediman_reading_progress_section',
    'active_callback' => function() {
        return get_theme_mod('enable_reading_progress', true);
    },
] ) );


// =========================================================================
    // 1. PANEL PENGATURAN MENU MOBILE (BERDIRI SENDIRI)
    // =========================================================================
    $wp_customize->add_panel( 'mediman_mobile_menu_panel', [
        'title'    => __( 'Pengaturan Menu Mobile', 'mediman' ),
        'priority' => 205, // Atur prioritas untuk posisi yang pas
    ]);

    // =========================================================================
    // 2. SECTION: Tampilan Tombol Hamburger (Sub-menu pertama)
    // =========================================================================
    $wp_customize->add_section( 'mediman_hamburger_button_section', [
        'title'    => __( 'Tombol Hamburger', 'mediman' ),
        'panel'    => 'mediman_mobile_menu_panel', // Masuk ke dalam panel Menu Mobile
        'priority' => 10,
    ] );

    // Kontrol untuk Gaya Ikon
    $wp_customize->add_setting( 'mobile_hamburger_icon_select', ['default' => 'default', 'sanitize_callback' => 'sanitize_key']);
    $wp_customize->add_control( 'mobile_hamburger_icon_select', [
        'label'   => __( 'Pilih Gaya Ikon', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
        'type'    => 'select',
        'choices' => [
            'default' => __( 'Garis 3 (Default)', 'mediman' ),
            'plus'    => __( 'Tanda Plus (+)', 'mediman' ),
            'dots'    => __( 'Titik 3 Vertikal', 'mediman' ),
        ],
    ]);

    // Kontrol untuk Ukuran Tombol
    $wp_customize->add_setting( 'mobile_hamburger_size', ['default' => 40, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'mobile_hamburger_size', [
        'label'   => __( 'Ukuran Tombol (px)', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
        'type'    => 'number',
        'input_attrs' => ['min' => 30, 'max' => 60, 'step' => 1],
    ]);
    
    // Kontrol untuk Lebar Border
    $wp_customize->add_setting( 'mobile_hamburger_border_width', ['default' => 1, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'mobile_hamburger_border_width', [
        'label'   => __( 'Lebar Border Tombol (px)', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
        'type'    => 'number',
        'input_attrs' => ['min' => 0, 'max' => 5, 'step' => 1],
    ]);

    // Kontrol untuk Border Radius
    $wp_customize->add_setting( 'mobile_hamburger_border_radius', ['default' => 4, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'mobile_hamburger_border_radius', [
        'label'   => __( 'Border Radius Tombol (px)', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
        'type'    => 'number',
        'input_attrs' => ['min' => 0, 'max' => 50, 'step' => 1],
    ] );

    // Kontrol untuk Warna Background
    $wp_customize->add_setting( 'mobile_hamburger_bg_color', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_hamburger_bg_color', [
        'label'   => __( 'Warna Background Tombol', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
    ]));

    // Kontrol untuk Warna Border
    $wp_customize->add_setting( 'mobile_hamburger_border_color', ['default' => '#dee2e6', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_hamburger_border_color', [
        'label'   => __( 'Warna Border Tombol', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
    ]));

    // Kontrol untuk Warna Ikon
    $wp_customize->add_setting( 'mobile_hamburger_icon_color', ['default' => '#212529', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_hamburger_icon_color', [
        'label'   => __( 'Warna Ikon Hamburger', 'mediman' ),
        'section' => 'mediman_hamburger_button_section',
    ]));

    // =========================================================================
    // 2.3 SECTION: Tampilan Panel Menu (Sub-menu kedua)
    // =========================================================================
    $wp_customize->add_section( 'mediman_offcanvas_panel_section', [
        'title'    => __( 'Panel Menu (Off-Canvas)', 'mediman' ),
        'panel'    => 'mediman_mobile_menu_panel', // Masuk ke dalam panel Menu Mobile
        'priority' => 20,
    ] );

    // Kontrol untuk Posisi Menu
    $wp_customize->add_setting( 'mobile_menu_position', [ 'default' => 'start', 'sanitize_callback' => 'sanitize_key' ] );
    $wp_customize->add_control( 'mobile_menu_position', [
        'label'   => __( 'Posisi Panel Menu', 'mediman' ),
        'section' => 'mediman_offcanvas_panel_section',
        'type'    => 'radio',
        'choices' => [ 'start' => __( 'Kiri', 'mediman' ), 'end' => __( 'Kanan', 'mediman' ) ],
    ] );

    // Kontrol untuk Judul Menu
    $wp_customize->add_setting( 'mobile_menu_title', [ 'default' => get_bloginfo('name'), 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'mobile_menu_title', [
        'label'   => __( 'Judul di Header Panel Menu', 'mediman' ),
        'section' => 'mediman_offcanvas_panel_section',
        'type'    => 'text',
    ] );


    // =========================================================================
    // SECTION PENGATURAN HALAMAN BLOG / ARSIP (Dimasukkan ke Panel Halaman Depan)
    // =========================================================================
    $wp_customize->add_section( 'mediman_archive_page_section', [
        'title'       => __( 'Judul Section Semua Artikel', 'mediman' ),
        'description' => __( 'Atur judul dan deskripsi untuk halaman "Semua Artikel".', 'mediman' ),
        'panel'       => 'mediman_homepage_panel', // <-- PERBAIKAN UTAMA DI SINI
        'priority'    => 40, 
    ] );

    // 1. Kontrol untuk Judul Utama
    $wp_customize->add_setting( 'archive_page_title', [
        'default'           => 'Semua Artikel',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'archive_page_title', [
        'label'   => __( 'Judul Halaman', 'mediman' ),
        'section' => 'mediman_archive_page_section',
        'type'    => 'text',
    ] );

    // 2. Kontrol untuk Deskripsi
    $wp_customize->add_setting( 'archive_page_description', [
        'default'           => 'Jelajahi semua artikel terbaru dari kami.',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'archive_page_description', [
        'label'   => __( 'Deskripsi Halaman', 'mediman' ),
        'section' => 'mediman_archive_page_section',
        'type'    => 'textarea',
    ] );
    



    // =========================================================================
    // SECTION TIPOGRAFI ANDA (Saya pindahkan ke panel utamanya sendiri)
    // =========================================================================
    $wp_customize->add_section( 'mediman_typography_section', [
        'title'    => __( 'Tipografi (Font)', 'mediman' ),
       
        'priority' => 30,
    ] );
    

     // SECTION PENGATURAN KURSOR ANIMASI
    $wp_customize->add_section( 'mediman_cursor_section', [
        'title'       => __( 'Pengaturan Kursor Animasi', 'mediman' ),
        'priority'    => 998,
        'panel'       => 'mediman_features_panel', // Masukkan ke dalam panel fitur tambahan
    ] );

    // 1. Aktifkan Fitur
    $wp_customize->add_setting( 'enable_animated_cursor', [ 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'enable_animated_cursor', [
        'label'   => __( 'Aktifkan Kursor Animasi', 'mediman' ),
        'section' => 'mediman_cursor_section', 'type' => 'checkbox',
    ] );

    // 2. Pengaturan Kursor Dalam (Titik)
    $wp_customize->add_setting( 'cursor_inner_size', ['default' => 8, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'cursor_inner_size', ['label' => __( 'Ukuran Kursor Dalam (px)', 'mediman' ), 'section' => 'mediman_cursor_section', 'type' => 'number']);
    $wp_customize->add_setting( 'cursor_inner_color', ['default' => '#000000', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cursor_inner_color', ['label' => __( 'Warna Kursor Dalam', 'mediman' ), 'section' => 'mediman_cursor_section']));

    // 3. Pengaturan Kursor Luar (Lingkaran)
    $wp_customize->add_setting( 'cursor_outer_size', ['default' => 40, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'cursor_outer_size', ['label' => __( 'Ukuran Kursor Luar (px)', 'mediman' ), 'section' => 'mediman_cursor_section', 'type' => 'number']);
    $wp_customize->add_setting( 'cursor_outer_color', ['default' => '#000000', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cursor_outer_color', ['label' => __( 'Warna Kursor Luar', 'mediman' ), 'section' => 'mediman_cursor_section']));
    $wp_customize->add_setting( 'cursor_outer_opacity', ['default' => 20, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'cursor_outer_opacity', ['label' => __( 'Transparansi Kursor Luar (%)', 'mediman' ), 'section' => 'mediman_cursor_section', 'type' => 'range', 'input_attrs' => ['min'=>0, 'max'=>100, 'step'=>5]]);

    // 4. Pengaturan Efek Hover (Saat menyentuh link/tombol)
    $wp_customize->add_setting( 'cursor_hover_scale', ['default' => 1.5, 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control( 'cursor_hover_scale', ['label' => __( 'Skala Efek Hover (e.g., 1.5)', 'mediman' ), 'section' => 'mediman_cursor_section', 'type' => 'number', 'input_attrs' => ['min'=>1, 'max'=>3, 'step'=>0.1]]);
    $wp_customize->add_setting( 'cursor_hover_opacity', ['default' => 50, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control( 'cursor_hover_opacity', ['label' => __( 'Transparansi Efek Hover (%)', 'mediman' ), 'section' => 'mediman_cursor_section', 'type' => 'range', 'input_attrs' => ['min'=>0, 'max'=>100, 'step'=>5]]);   

    // Tambahkan Panel "Optimasi Performa" jika belum ada
    $wp_customize->add_panel('performance_panel', [
        'title'    => __('Optimasi Performa', 'mediman'),
        'priority' => 160,
    ]);

    // Tambahkan Section untuk Optimasi Gambar
    $wp_customize->add_section('image_optimization_section', [
        'title' => __('Optimasi Gambar', 'mediman'),
        'panel' => 'performance_panel',
    ]);

    // Setting untuk menonaktifkan ukuran gambar "medium_large"
    $wp_customize->add_setting('disable_medium_large_images', [
        'default'           => 0, // 0 = tidak aktif, 1 = aktif
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    // Kontrol (checkbox) untuk setting di atas
    $wp_customize->add_control('disable_medium_large_images_control', [
        'label'       => __('Nonaktifkan Ukuran Gambar "Medium Large"', 'mediman'),
        'description' => __('Mencegah WordPress membuat duplikat gambar dengan lebar 768px. Berguna jika Anda tidak menggunakan ukuran ini di tema Anda.', 'mediman'),
        'section'     => 'image_optimization_section',
        'settings'    => 'disable_medium_large_images',
        'type'        => 'checkbox',
    ]);

    // Tambahkan Section baru untuk pengaturan demo
    $wp_customize->add_section('mediman_demo_settings_section', [
        'title'    => __('Pengaturan Demo', 'mediman'),
        'priority' => 170,
    ]);

    // Setting untuk menampilkan iklan demo
    $wp_customize->add_setting('show_demo_ads', [
        'default'           => 1, // 1 = aktif/tercentang secara default
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    // Kontrol (checkbox) untuk setting di atas
    $wp_customize->add_control('show_demo_ads_control', [
        'label'       => __('Tampilkan Area Iklan Demo', 'mediman'),
        'description' => __('Centang untuk menampilkan semua area widget iklan. Hilangkan centang untuk menyembunyikannya.', 'mediman'),
        'section'     => 'mediman_demo_settings_section',
        'settings'    => 'show_demo_ads',
        'type'        => 'checkbox',
    ]);

    $wp_customize->add_section('mediman_author_settings_section', [
        'title'    => __('Pengaturan Penulis', 'mediman'),
        'priority' => 125, // Atur posisi agar muncul setelah layout, dll.
    ]);

    // 1. Setting untuk menyimpan URL gambar (tidak berubah)
    $wp_customize->add_setting('global_default_author_avatar', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    // 2. Kontrol untuk mengunggah gambar
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'global_default_author_avatar_control', [
        'label'       => __('Avatar Default untuk Penulis', 'mediman'),
        'description' => __('Unggah gambar yang akan digunakan jika seorang penulis tidak memiliki Gravatar.', 'mediman'),
        
        // --- PERUBAHAN DI SINI ---
        'section'     => 'mediman_author_settings_section', // Arahkan ke section baru
        
        'settings'    => 'global_default_author_avatar',
    ]));

    


}
add_action('customize_register', 'mediman_register_customizer_settings');


/**
 * Mengunci panel dan section di Customizer jika lisensi tidak aktif.
 *
 * @param WP_Customize_Manager $wp_customize Manajer Customizer.
 */
function mediman_lock_customizer($wp_customize) {
    // Jika tema sudah "unlocked", jangan lakukan apa-apa.
    if (mediman_is_theme_unlocked()) {
        return;
    }

    // Loop dan hapus semua panel yang ditambahkan oleh tema
    foreach ($wp_customize->panels() as $panel) {
        // Ganti 'prefix_panel' dengan prefix unik panel tema Anda jika ada
        if (strpos($panel->id, 'mediman_') === 0 || strpos($panel->id, 'performance_') === 0) {
            $wp_customize->remove_panel($panel->id);
        }
    }

    // Loop dan hapus semua section yang ditambahkan oleh tema
    foreach ($wp_customize->sections() as $section) {
        // Daftar section inti WordPress yang tidak ingin kita hapus
        $core_sections = ['title_tagline', 'static_front_page', 'custom_css'];
        if (in_array($section->id, $core_sections)) {
            continue;
        }
        $wp_customize->remove_section($section->id);
    }
    
    // Setelah semua dihapus, tambahkan satu section baru untuk pesan aktivasi
    $wp_customize->add_section('mediman_license_activation_section', [
        'title'    => '⭐ Aktivasi Tema Mediman',
        'priority' => 1,
    ]);

    $wp_customize->add_setting('mediman_license_dummy_setting', []);

    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'mediman_license_activation_control',
        [
            'section'  => 'mediman_license_activation_section',
            'settings' => 'mediman_license_dummy_setting',
            'type'     => 'hidden', // Tipe bisa apa saja, konten akan kita ganti
            'description' => '<div style="padding: 15px; background-color: #fff; border-left: 4px solid #00a32a; text-align: center;">' .
                             '<p style="font-size: 14px;">Untuk membuka semua pengaturan kustomisasi, harap aktifkan lisensi tema Anda.</p>' .
                             '<a href="' . admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi') . '" class="button button-primary" style="margin-top: 10px;">Aktifkan Sekarang</a>' .
                             '</div>',
        ]
    ));
}
// Gunakan prioritas tinggi (999) agar berjalan setelah semua panel/section tema terdaftar
add_action('customize_register', 'mediman_lock_customizer', 999);


/**
 * Memuat skrip JavaScript untuk live preview di Customizer.
 */
function mediman_customize_preview_js() {
    wp_enqueue_script(
        'mediman-customizer-preview', 
        get_template_directory_uri() . '/assets/js/customizer.js', 
        ['jquery', 'customize-preview'], 
        MEDIMAN_THEME_VERSION, 
        true
    );
}
add_action('customize_preview_init', 'mediman_customize_preview_js');