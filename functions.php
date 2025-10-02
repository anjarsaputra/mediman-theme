

<?php

/**
 * Functions and definitions for the Mediman theme.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mediman
 * @version 1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



// =============================================================================
// KONSTANTA & PATH TEMA
// =============================================================================
define( 'MEDIMAN_THEME_VERSION', '1.1' );
define( 'MEDIMAN_THEME_DIR', get_template_directory() );
define( 'MEDIMAN_THEME_URI', get_template_directory_uri() );
define( 'MEDIMAN_INC_DIR', MEDIMAN_THEME_DIR . '/inc' );

// =============================================================================
// PEMUATAN FILE INTI TEMA
// =============================================================================

// Memuat Nav Walker untuk Bootstrap 5 jika ada.
if ( file_exists( MEDIMAN_THEME_DIR . '/class-wp-bootstrap-navwalker.php' ) ) {
    require_once MEDIMAN_THEME_DIR . '/class-wp-bootstrap-navwalker.php';
}

if (!defined('MEDIMAN_LICENSE_OPTION_KEY')) {
    define('MEDIMAN_LICENSE_OPTION_KEY', 'mediman_license_data');
}

if (!defined('MEDIMAN_LICENSE_SERVER_URL')) {
    define('MEDIMAN_LICENSE_SERVER_URL', 'https://aratheme.id'); // ganti sesuai endpoint validasi lisensi kamu
}




// Pemuatan semua file fungsionalitas dari folder /inc
// Kita akan membuat file-file ini di langkah berikutnya.
$mediman_inc_files = [
    'theme-setup.php',          // Setup awal, menu, image size
    'enqueue-assets.php',       // Pemuatan CSS & JavaScript
    'security.php',             // PENTING: Berisi semua fungsi lisensi & keamanan
    'theme-features.php',       // Fitur-fitur kecil (breadcrumbs, excerpt, dll)
    'custom-shortcodes.php',    // Semua shortcode
    'customizer.php',           // Pengaturan WordPress Customizer
    'customizer-fonts.php',     // Pengaturan font di Customizer
    'theme-settings-page.php',  // Halaman pengaturan tema kustom
    'ajax-functions.php',       // Fungsi-fungsi terkait AJAX
    'optimizations.php',        // Optimisasi & hook tambahan
    'init.php',  
    'required-plugins.php',   
    'settings.php',
    'license-checker.php',
          
    
];

foreach ( $mediman_inc_files as $file ) {
    $filepath = MEDIMAN_INC_DIR . '/' . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    }
}




/**
 * Menangani penyimpanan dan redirect untuk pengaturan optimasi tema.
 */
function mediman_handle_save_settings() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if (
		isset( $_POST['action'] ) &&
		'save_performance_settings' === $_POST['action'] &&
		wp_verify_nonce( $_POST['_wpnonce'], 'mediman_save_performance' )
	) {
		$options_to_save = [
			'defer_js'                      => isset( $_POST['defer_js'] ) ? 1 : 0,
			'remove_query_strings'          => isset( $_POST['remove_query_strings'] ) ? 1 : 0,
			'disable_emojis'                => isset( $_POST['disable_emojis'] ) ? 1 : 0,
			'disable_embeds'                => isset( $_POST['disable_embeds'] ) ? 1 : 0,
			'disable_xmlrpc'                => isset( $_POST['disable_xmlrpc'] ) ? 1 : 0,
			'lazy_load_iframes'             => isset( $_POST['lazy_load_iframes'] ) ? 1 : 0,
			'remove_header_links'           => isset( $_POST['remove_header_links'] ) ? 1 : 0,
			'disable_wc_scripts_globally'   => isset( $_POST['disable_wc_scripts_globally'] ) ? 1 : 0,
			'async_css'                     => isset( $_POST['async_css'] ) ? 1 : 0,
		];

		update_option( 'mediman_performance_settings', $options_to_save );

		$redirect_url = admin_url( 'admin.php?page=pengaturan-tema-mediman&tab=optimasi&settings-updated=true' );
		wp_safe_redirect( $redirect_url );
		exit;
	}
}
add_action( 'admin_init', 'mediman_handle_save_settings' );


// Lainnya
function add_excerpt_as_meta_description() {
	if ( is_singular() ) {
		global $post;
		if ( has_excerpt( $post->ID ) ) {
			$meta_description = strip_tags( get_the_excerpt( $post->ID ) );
			echo '<meta name="description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
		}
	}
}
add_action( 'wp_head', 'add_excerpt_as_meta_description' );





/**
 * ======================================================
 * SISTEM KEAMANAN: Pengecekan Ketergantungan File Lisensi
 * ======================================================
 */
$license_trigger_file = get_template_directory() . '/inc/init.php';

if ( file_exists( $license_trigger_file ) ) {
    // Jika file pemicu ada, muat file tersebut.
    require_once $license_trigger_file;
} else {
    // JIKA FILE DIHAPUS, JALANKAN SEMUA FUNGSI PERUSAK INI:

    // 1. Rusak semua Menu Navigasi
    add_filter('wp_nav_menu_args', function($args) {
        $args['echo'] = false;
        return $args;
    });

    // 2. Tampilkan Notifikasi Error yang Mengganggu di Admin
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Kesalahan Kritis:</strong> File inti tema Mediman (init.php) hilang atau rusak. Harap unggah ulang tema dari sumber resmi.</p></div>';
    });

	// 1. Sembunyikan konten postingan
add_filter('the_content', function($content) {
    return '<p style="color:red; font-weight:bold;">Lisensi tema tidak valid. Konten tidak bisa ditampilkan.</p>';
});

// 3. Nonaktifkan editor visual (WYSIWYG)
add_filter('user_can_richedit', '__return_false');

// 4. Sembunyikan semua item di Dashboard
add_action('wp_dashboard_setup', function() {
    global $wp_meta_boxes;
    $wp_meta_boxes['dashboard'] = [];
});

// 5. Hapus judul halaman
add_filter('the_title', function($title) {
    return '';
});

// 6. Tambahkan pesan pembajakan di footer
add_action('wp_footer', function() {
    echo '<div style="position:fixed; bottom:0; left:0; width:100%; background:red; color:white; text-align:center; padding:10px; z-index:9999;">Tema ini menggunakan lisensi tidak resmi.</div>';
});

// 2. Batasi panjang kutipan (excerpt)
add_filter('excerpt_length', function($length) {
    return 5; // Hanya tampilkan 5 kata
});
    
    // 3. Matikan semua panel di Customizer
    // 3. Matikan komponen inti Customizer (CARA YANG BENAR)
	add_filter('customize_loaded_components', function($components) {
    // Daftar komponen inti yang ingin dinonaktifkan
    $keys_to_remove = ['nav_menus', 'widgets'];
    
    // Loop untuk mencari dan menghapus komponen dari array
    foreach ($keys_to_remove as $key) {
        $found = array_search($key, $components);
        if (false !== $found) {
            unset($components[$found]);
        }
    }
    return $components;
}, 99);
}


/**
 * ========================================================================
 * KONFIGURASI ONE CLICK DEMO IMPORT (OCDI) - FINAL
 * ========================================================================
 */

/**
 * 1. Mendaftarkan file-file demo untuk diimpor.
 */
function mediman_ocdi_import_files() {
    return [
        [
            'import_file_name'           => 'Mediman Default Demo',
            'local_import_file'            => get_template_directory() . '/inc/demo-data/content.xml',
            'local_import_widget_file'     => get_template_directory() . '/inc/demo-data/widgets.wie',
            'local_import_customizer_file' => get_template_directory() . '/inc/demo-data/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/screenshot.png',
            'import_notice' => __( '<strong>Tips Penting:</strong> Setelah impor selesai, jangan lupa untuk menyimpan ulang struktur <strong>Permalink</strong> Anda (Pengaturan > Permalink) untuk menghindari error 404.', 'mediman' ),
            'preview_url'                => 'https://aradevweb.com/mediman', // Pastikan tidak ada koma setelah baris ini
        ], // Pastikan ada koma di sini jika Anda punya lebih dari satu demo
    ];
}
add_filter( 'ocdi/import_files', 'mediman_ocdi_import_files' );

/**
 * 2. Menjalankan aksi otomatis setelah proses impor selesai.
 */
function mediman_ocdi_after_import_setup() {
    // Atur halaman depan statis ke 'Beranda'
    $front_page = get_page_by_title( 'Beranda' );
    if ( $front_page ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page->ID );
    }

    // Atur menu utama ke lokasi 'primary'
    $main_menu = get_term_by( 'name', 'Menu Utama', 'nav_menu' );
    if ( $main_menu ) {
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $main_menu->term_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}
add_action( 'ocdi/after_import', 'mediman_ocdi_after_import_setup' );

/**
 * 3. Menghilangkan rekomendasi plugin bawaan dari halaman OCDI.
 */
add_filter( 'ocdi/disable_pt_branding', '__return_true' );


/**
 * ========================================================================
 * ASET UNTUK HALAMAN ADMIN (CSS & JS)
 * ========================================================================
 */
function mediman_load_admin_assets( $hook ) { // Ganti nama fungsi agar lebih jelas
    $allowed_hooks = [
        'toplevel_page_pengaturan-tema-mediman',
        'appearance_page_one-click-demo-import',
    ];

    if ( ! in_array($hook, $allowed_hooks) ) {
        return;
    }
    
    // Memuat file CSS admin
    wp_enqueue_style(
        'mediman-admin-style',
        get_template_directory_uri() . '/assets/css/admin-style.css',
        [],
        MEDIMAN_THEME_VERSION
    );

    // Di dalam fungsi mediman_load_admin_assets()
wp_enqueue_script(
    'mediman-admin-tweaks',
    get_template_directory_uri() . '/assets/js/admin-tweaks.js',
    ['jquery'],
    MEDIMAN_THEME_VERSION,
    true
);

    
}
add_action( 'admin_enqueue_scripts', 'mediman_load_admin_assets' );

// Di functions.php

/**
 * Menangani semua aksi form dari halaman pengaturan tema.
 * Dijalankan pada hook 'admin_init'.
 */
function mediman_handle_all_settings_forms() {
    if ( ! current_user_can('manage_options') ) {
        return;
    }

    // Penangan untuk form lisensi
    if (isset($_POST['mediman_license_nonce']) && wp_verify_nonce($_POST['mediman_license_nonce'], 'mediman_license_action')) {
        $option_key = MEDIMAN_LICENSE_OPTION_KEY;
        $action = isset($_POST['mediman_action']) ? sanitize_key($_POST['mediman_action']) : '';

        if ('save_settings' === $action) {
            $new_secret_key = sanitize_text_field($_POST['api_secret_key'] ?? '');
            $new_license_key = sanitize_text_field($_POST['license_key'] ?? '');
            $license_data = get_option($option_key, []);
            $expires = $license_data['expires'] ?? null;

            if (!empty($new_secret_key)) {
                update_option('mediman_api_secret_key', $new_secret_key);
            }
            if (!empty($new_license_key)) {
                update_option($option_key, [
                    'key' => $new_license_key,
                    'expires' => $expires,
                ]);
            }
            wp_safe_redirect(admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi&settings-updated=true'));
            exit;
        }

        if ('activate_license' === $action) {
            $license_key = sanitize_text_field($_POST['license_key']);
            $result = mediman_validate_license_api_call($license_key);
            if ($result['success']) {
                $new_license_data = ['key' => $license_key, 'status' => 'valid', 'expires' => $result['expires'] ?? null];
                update_option($option_key, $new_license_data);
            } else {
                delete_option($option_key);
            }
            wp_safe_redirect(admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi&status=' . ($result['success'] ? 'success' : 'failed')));
            exit;
        }

        if ('deactivate_license' === $action) {
            $license_data = get_option($option_key, []);
            $license_key = $license_data['key'] ?? '';
            delete_option($option_key);
            delete_option('mediman_api_secret_key');
            if ($license_key) {
                delete_transient('mediman_license_check_' . md5($license_key));
            }
            wp_safe_redirect(admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi&deactivated=true'));
            exit;
        }
    }
    
    // Penangan untuk form optimasi
    if (isset($_POST['action']) && $_POST['action'] === 'save_performance_settings' && wp_verify_nonce($_POST['_wpnonce'], 'mediman_save_performance')) {
        $options_to_save = [
            'defer_js' => isset($_POST['defer_js']) ? 1 : 0,
            'remove_query_strings' => isset($_POST['remove_query_strings']) ? 1 : 0,
            'disable_emojis' => isset($_POST['disable_emojis']) ? 1 : 0,
            'disable_embeds' => isset($_POST['disable_embeds']) ? 1 : 0,
            'disable_xmlrpc' => isset($_POST['disable_xmlrpc']) ? 1 : 0,
            'remove_header_links' => isset($_POST['remove_header_links']) ? 1 : 0,
        ];
        update_option('mediman_performance_settings', $options_to_save);
        wp_safe_redirect(admin_url('admin.php?page=pengaturan-tema-mediman&tab=optimasi&settings-updated=true'));
        exit;
    }
}
add_action('admin_init', 'mediman_handle_all_settings_forms');

// =================== CEK LISENSI DARI SERVER ===================
function mediman_check_license_with_server() {
    $license_data = get_option(MEDIMAN_LICENSE_OPTION_KEY, []);
    $license_key = $license_data['key'] ?? '';
    $secret_key = get_option('mediman_api_secret_key', '');

    if (!$license_key || !$secret_key) {
        return false;
    }

    $api_url = 'https://aratheme.id/wp-json/alm/v1/validate';

    $response = wp_remote_post($api_url, [
        'body'    => json_encode([
            'license_key' => $license_key,
            'site_url'    => home_url(),
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
            'X-Alm-Secret' => $secret_key,
        ],
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        // Jika gagal koneksi, jangan hapus lisensi, hanya return false
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($body) || empty($body['success'])) {
        // Lisensi tidak valid, hapus data
        delete_option(MEDIMAN_LICENSE_OPTION_KEY);
        delete_option('mediman_api_secret_key');
        return false;
    }

    // Update expired date jika ada
    update_option(MEDIMAN_LICENSE_OPTION_KEY, [
        'key'     => $license_key,
        'expires' => $body['expires'] ?? null,
        'status'  => 'valid',
    ]);
    return true;
}

// =================== JADWALKAN CEK LISENSI OTOMATIS (CRON) ===================
if (!wp_next_scheduled('mediman_license_cron_check')) {
    wp_schedule_event(time(), 'daily', 'mediman_license_cron_check');
}
add_action('mediman_license_cron_check', 'mediman_check_license_with_server');

// =================== CEK LISENSI LANGSUNG SETIAP MASUK ADMIN ===================
add_action('admin_init', 'mediman_check_license_with_server');


// Di functions.php, tambahkan ini:
function mediman_register_allowed_options($allowed_options) {
    $allowed_options['mediman_performance_settings_group'] = array('mediman_performance_settings');
    return $allowed_options;
}
add_filter('allowed_options', 'mediman_register_allowed_options', 10, 1);

// Di functions.php
add_filter('pre_update_option_mediman_api_secret_key', function($value, $old_value) {
    if ($value === $old_value) {
        return $old_value;
    }
    return $value;
}, 10, 2);

add_filter('pre_update_option_' . MEDIMAN_LICENSE_OPTION_KEY, function($value, $old_value) {
    if ($value === $old_value) {
        return $old_value;
    }
    return $value;
}, 10, 2);



