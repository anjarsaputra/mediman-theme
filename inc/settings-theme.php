<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Initialize variables
global $wpdb, $wp_version;
$license_data = get_option(MEDIMAN_LICENSE_OPTION_KEY, []);
$saved_license = $license_data['key'] ?? '';
$current_date = current_time('mysql', true);
$current_user = wp_get_current_user();
$debug_mode = get_option('alm_debug_mode', false);
$active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'dashboard';
$theme = wp_get_theme();
$is_valid = mediman_is_license_active();
$saved_secret_key = get_option('mediman_api_secret_key', '');
$expires_date = $license_data['expires'] ?? null;

// Admin only access
if (!current_user_can('manage_options')) {
    wp_die(__('Anda tidak memiliki izin untuk mengakses halaman ini.', 'mediman'));
}

// Handle API test
if (isset($_POST['test_api'])) {
    $test_response = mediman_validate_license_api_call($saved_license);
    if (isset($test_response['success']) && $test_response['success']) {
        echo '<div class="notice notice-success"><p>Koneksi server lisensi: OK</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>Koneksi server lisensi gagal: ' . 
             esc_html($test_response['message'] ?? 'Unknown error') . '</p></div>';
    }
}

// Quick links configuration
$quick_links = [
    ['icon' => 'dashicons-format-image',   'title' => __('Logo & Identitas Situs', 'mediman'), 'text' => __('Unggah logo untuk mode terang dan gelap.', 'mediman'), 'link' => admin_url('customize.php?autofocus[section]=title_tagline')],
    ['icon' => 'dashicons-admin-appearance', 'title' => __('Warna & Tampilan', 'mediman'), 'text' => __('Atur skema warna dan tombol.', 'mediman'), 'link' => admin_url('customize.php?autofocus[panel]=mediman_styling_panel')],
    ['icon' => 'dashicons-layout', 'title' => __('Layout Halaman Depan', 'mediman'), 'text' => __('Atur Carousel dan blok kategori.', 'mediman'), 'link' => admin_url('customize.php?autofocus[panel]=mediman_homepage_panel')],
    ['icon' => 'dashicons-admin-post', 'title' => __('Tampilan Postingan', 'mediman'), 'text' => __('Aktifkan Daftar Isi, Artikel Terkait, dll.', 'mediman'), 'link' => admin_url('customize.php?autofocus[panel]=mediman_single_post_panel')],
['icon' => 'dashicons-menu-alt', 'title' => __('Atur Menu Navigasi', 'mediman'), 'text' => __('Kelola menu utama, footer, dan lainnya.', 'mediman'), 'link' => admin_url('nav-menus.php')],
    ['icon' => 'dashicons-welcome-widgets-menus', 'title' => __('Kelola Widget', 'mediman'), 'text' => __('Atur widget di sidebar dan area footer.', 'mediman'), 'link' => admin_url('widgets.php')],
];
?>

<div class="wrap mediman-settings-page">
    <!-- Header -->
    <header class="settings-header">
        <h1><?php esc_html_e('Pengaturan Tema Mediman', 'mediman'); ?></h1>
    <?php 
    // Tambahkan action hook setelah judul
    do_action('mediman_after_title'); 
    ?>
        <p class="about-text">
            <?php printf(esc_html__('Selamat datang! Atur semua fitur canggih tema Mediman dari sini. Versi tema saat ini: %s', 'mediman'), 
                esc_html($theme->get('Version'))); ?>
        </p>
    </header>

    <!-- Navigation Tabs -->
    <nav class="nav-tab-wrapper">
        <a href="?page=pengaturan-tema-mediman&tab=dashboard" 
           class="nav-tab <?php echo $active_tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Dashboard', 'mediman'); ?>
        </a>
        <a href="?page=pengaturan-tema-mediman&tab=aksi_cepat" 
           class="nav-tab <?php echo $active_tab === 'aksi_cepat' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Aksi Cepat', 'mediman'); ?>
        </a>
        <a href="?page=pengaturan-tema-mediman&tab=optimasi" 
           class="nav-tab <?php echo $active_tab === 'optimasi' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Optimasi', 'mediman'); ?>
        </a>
        <a href="?page=pengaturan-tema-mediman&tab=lisensi" 
           class="nav-tab <?php echo $active_tab === 'lisensi' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Aktivasi Lisensi', 'mediman'); ?>
            <?php if (!$is_valid) echo ' <span class="dashicons dashicons-warning" style="color:orange;"></span>'; ?>
        </a>
        <a href="?page=pengaturan-tema-mediman&tab=dukungan" 
           class="nav-tab <?php echo $active_tab === 'dukungan' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Dukungan', 'mediman'); ?>
        </a>
    </nav>

    <!-- Content Area -->
    <div class="tab-content">
        <?php settings_errors('mediman_license'); ?>
        <?php 
        // Load tab content
        switch ($active_tab) {
            case 'dashboard':
                require_once dirname(__FILE__) . '/tabs/tab-dashboard.php';
                break;
            case 'aksi_cepat':
                require_once dirname(__FILE__) . '/tabs/tab-aksi-cepat.php';
                break;
            case 'optimasi':
                require_once dirname(__FILE__) . '/tabs/tab-optimasi.php';
                break;
            case 'lisensi':
                require_once dirname(__FILE__) . '/tabs/tab-lisensi.php';
                break;
            case 'dukungan':
                require_once dirname(__FILE__) . '/tabs/tab-dukungan.php';
                break;
        }
        ?>
    </div>
</div>