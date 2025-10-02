<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'mediman_theme_setup' ) ) {
    function mediman_theme_setup() {
        register_nav_menus([
            'main_menu'   => __('Menu Utama', 'mediman'),
            'footer_menu' => __('Footer Menu', 'mediman')
        ]);
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        add_image_size('small_thumbs', 200, 200, true);
        add_image_size('medium_thumbs', 512, 512, true);
        add_image_size('large_thumbs', 1024, 1024, true);
    }
}
add_action('after_setup_theme', 'mediman_theme_setup');


/**
 * Mengubah nama panel "Widgets" menjadi "Widget Iklan" di Customizer.
 */
function mediman_rename_widget_customizer_panel($wp_customize) {
    // Ambil panel widget standar WordPress
    $widget_panel = $wp_customize->get_panel('widgets');

    // Jika panelnya ada, ganti judulnya
    if (is_object($widget_panel)) {
        $widget_panel->title = __('Widget Iklan', 'mediman');
    }
}
// Gunakan prioritas 20 agar berjalan setelah panel default terdaftar
add_action('customize_register', 'mediman_rename_widget_customizer_panel', 20);
/**
 * Mendaftarkan area widget khusus untuk iklan.
 */
function mediman_register_ad_widget_areas() {
    // Area 1: Iklan di Bawah Header
    register_sidebar([
        'name'          => __('Iklan di Bawah Header', 'mediman'),
        'id'            => 'ad-header',
        'description'   => __('Area untuk menampilkan iklan di bawah navigasi utama.', 'mediman'),
        'before_widget' => '<div id="%1$s" class="widget widget_ad %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title" style="display:none;">',
        'after_title'   => '</h4>',
    ]);

    // Area 2: Iklan di Bawah Judul Postingan
    register_sidebar([
        'name'          => __('Iklan di Bawah Judul Postingan', 'mediman'),
        'id'            => 'ad-post-title',
        'description'   => __('Area untuk iklan yang muncul di bawah judul pada halaman artikel tunggal.', 'mediman'),
        'before_widget' => '<div id="%1$s" class="widget widget_ad %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title" style="display:none;">',
        'after_title'   => '</h4>',
    ]);

    register_sidebar([
    'name'          => __('Iklan di Tengah Postingan', 'mediman'),
    'id'            => 'ad-in-content',
    'description'   => __('Area untuk iklan yang akan muncul secara otomatis di antara paragraf artikel.', 'mediman'),
    'before_widget' => '<div id="%1$s" class="widget widget_ad_in_content %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widget-title" style="display:none;">',
    'after_title'   => '</h4>',
    ]);

    register_sidebar([
        'name'          => __('Sidebar Utama', 'mediman'),
        'id'            => 'sidebar-main',
        'description'   => __('Sidebar utama yang muncul di samping konten.', 'mediman'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    // Area 4, 5, 6: Widget Footer
    for ($i = 1; $i <= 3; $i++) {
        register_sidebar([
            'name'          => sprintf(__('Footer Kolom %d', 'mediman'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Area widget untuk kolom %d di footer.', 'mediman'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ]);
    }


}
add_action('widgets_init', 'mediman_register_ad_widget_areas');