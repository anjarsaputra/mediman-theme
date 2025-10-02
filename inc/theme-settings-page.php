<?php
/**
 * Membuat halaman pengaturan kustom untuk Tema Mediman di area admin.
 *
 * @package Mediman
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Menambahkan menu utama dan semua submenu terkait untuk pengaturan tema.
 */
function mediman_add_theme_settings_page() {
    // 1. Buat menu utama "Mediman"
    add_menu_page(
        'Pengaturan Tema Mediman',
        'Mediman',
        'manage_options',
        'pengaturan-tema-mediman',      // Ini adalah Parent Slug kita
        'mediman_render_settings_page', // Fungsi untuk render halaman utama
        '',                             // Ikon via CSS
        2
    );

    // 2. Buat submenu pertama (misalnya "Dashboard") yang mengarah ke halaman utama
    add_submenu_page(
        'pengaturan-tema-mediman',      // Parent Slug
        'Dashboard Pengaturan',         // Judul Halaman
        'Dashboard',                    // Judul Menu
        'manage_options',               // Kapabilitas
        'pengaturan-tema-mediman',      // Menu Slug (sama dengan parent agar jadi link utama)
        'mediman_render_settings_page'
    );

    // 3. Tambahkan submenu "Impor Demo" HANYA jika plugin OCDI aktif
    if ( class_exists('OCDI_Plugin') ) {
        add_submenu_page(
            'pengaturan-tema-mediman',      // Parent Slug
            'Impor Demo Konten',            // Judul Halaman
            'Impor Demo',                   // Judul Menu
            'manage_options',               // Kapabilitas
            'themes.php?page=pt-one-click-demo-import' // Link LANGSUNG ke halaman OCDI
        );
    }
}
add_action( 'admin_menu', 'mediman_add_theme_settings_page' );


/**
 * Fungsi untuk me-render konten halaman pengaturan utama.
 */
function mediman_render_settings_page() {
    $settings_file = MEDIMAN_INC_DIR . '/settings-theme.php';
    if ( file_exists( $settings_file ) ) {
        require_once $settings_file;
    } else {
        echo "<div class='wrap'><h2>File Pengaturan Tidak Ditemukan</h2></div>";
    }
}


if ( ! function_exists( 'mediman_custom_admin_styles' ) ) {
    /**
     * Menambahkan CSS kustom ke area admin untuk styling menu pengaturan tema.
     */
    function mediman_custom_admin_styles() {
        $icon_url = esc_url( MEDIMAN_THEME_URI . '/assets/image/icon-setting.png' );
        ?>
        <style>
            #adminmenu .menu-top#toplevel_page_pengaturan-tema-mediman .wp-menu-image {
                display: none;
            }
            #adminmenu .menu-top#toplevel_page_pengaturan-tema-mediman > a {
                background-image: url("<?php echo $icon_url; ?>");
                background-color: #28a745;
                background-repeat: no-repeat;
                background-position: left 10px center;
                background-size: 20px auto;
                font-weight: 700;
                color: white;
            }
            #adminmenu .menu-top#toplevel_page_pengaturan-tema-mediman.current > a,
            #adminmenu .menu-top#toplevel_page_pengaturan-tema-mediman:hover > a,
            #adminmenu .menu-top#toplevel_page_pengaturan-tema-mediman .wp-submenu a:hover {
                background-color: #22913e !important;
                color: #ffffff !important;
            }
        </style>
        <?php
    }
}
add_action( 'admin_head', 'mediman_custom_admin_styles' );