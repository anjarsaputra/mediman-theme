<?php
/**
 * File untuk menangani semua logika Keamanan dan Lisensi Tema Mediman.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Mengunci seluruh frontend jika tema belum diaktivasi (unlocked).
 * Pengecualian diberikan untuk admin yang sedang login.
 */
function mediman_frontend_license_lock() {
    // Satu-satunya kondisi untuk lolos adalah jika tema sudah "unlocked".
    if ( mediman_is_theme_unlocked() ) {
        return;
    }
    
    // Jangan kunci halaman login agar admin bisa masuk.
    // Pengecekan is_admin() tidak diperlukan karena hook ini hanya berjalan di frontend.
    if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
        return;
    }

    // Jika belum unlocked, tampilkan layar aktivasi untuk SEMUA PENGUNJUNG.
    $login_url = wp_login_url(admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi'));
    $title     = esc_html__('Aktivasi Tema Diperlukan', 'mediman');
    $message   = '<!DOCTYPE html>
        <html ' . get_language_attributes() . '>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $title . '</title>
            <link rel="stylesheet" href="' . esc_url(get_stylesheet_uri()) . '" type="text/css" media="all" />
            ' . wp_head() . '
        </head>
        <body class="locked-theme ' . implode(' ', get_body_class()) . '">
            <div class="lock-container">
                <div class="lock-box">
                    <h1>🔒 ' . $title . '</h1>
                    <p>' . esc_html__('Untuk menggunakan tema ini, silakan masuk dan masukkan kode lisensi yang valid.', 'mediman') . '</p>
                    <a href="' . esc_url($login_url) . '">🔑 ' . esc_html__('Masuk & Aktifkan Lisensi', 'mediman') . '</a>
                </div>
            </div>
            ' . wp_footer() . '
        </body>
        </html>';
    
    echo $message;
    exit;
}


/**
 * Memeriksa apakah tema sudah "terbuka" (unlocked).
 */
// FUNGSI BARU (AMAN)
function mediman_is_theme_unlocked() {
    $license_data = get_option(MEDIMAN_LICENSE_OPTION_KEY, []);
    // === PERBAIKAN KEAMANAN KRUSIAL ADA DI SINI ===
    return ! empty($license_data['key']) && isset($license_data['status']) && $license_data['status'] === 'valid';
}
/**
 * Memeriksa apakah lisensi aktif (valid DAN tidak kedaluwarsa).
 * Digunakan KHUSUS untuk fitur update otomatis.
 */
function mediman_is_license_active() {
    // Pastikan tema sudah unlocked (jika punya proteksi file/folder)
    if (function_exists('mediman_is_theme_unlocked') && !mediman_is_theme_unlocked()) {
        return false;
    }
    $license_data = get_option('mediman_license_data', []);
    // Cek status valid
    if (empty($license_data['key']) || empty($license_data['status']) || $license_data['status'] !== 'valid') {
        return false;
    }
    // Cek expired jika ada
    if (!empty($license_data['expires'])) {
        return strtotime($license_data['expires']) > time();
    }
    return true; // Lifetime jika tidak ada expired
}

/**
 * Menampilkan notifikasi admin jika lisensi tidak aktif/kedaluwarsa.
 */
function mediman_license_admin_notice() {
    if ( (isset($_GET['page']) && 'pengaturan-tema-mediman' === $_GET['page']) || mediman_is_license_active() ) {
        return;
    }

    $license_data = get_option(MEDIMAN_LICENSE_OPTION_KEY, []);
    $message      = __('Lisensi tema Mediman Anda tidak aktif. Harap periksa statusnya untuk mendapatkan pembaruan.', 'mediman');
    $notice_class = 'notice-warning';
    
    if ( ! empty($license_data['key']) && isset($license_data['status']) && $license_data['status'] === 'valid' && ! empty($license_data['expires']) ) {
        if (strtotime($license_data['expires']) < time()) {
            $message      = __('Lisensi tema Mediman Anda telah kedaluwarsa. Harap perbarui untuk mendapatkan pembaruan otomatis.', 'mediman');
            $notice_class = 'notice-error';
        }
    } elseif (empty($license_data['key'])) {
        $message      = __('Terima kasih telah menggunakan tema Mediman! Harap aktifkan lisensi Anda.', 'mediman');
    }

    $activation_url = admin_url('admin.php?page=pengaturan-tema-mediman&tab=lisensi');

    printf(
        '<div class="notice %s is-dismissible"><p>%s <a href="%s"><strong>%s</strong></a></p></div>',
        esc_attr($notice_class),
        esc_html($message),
        esc_url($activation_url),
        esc_html__('Periksa Status Lisensi', 'mediman')
    );
}
add_action('admin_notices', 'mediman_license_admin_notice');

/**
 * Memvalidasi lisensi ke server eksternal.
 */
// Di security.php tema Mediman
function mediman_validate_license_api_call($license_key) {
    $debug_mode = get_option('mediman_debug_mode', false);
    
    if ($debug_mode) {
        error_log('=== VALIDASI LISENSI MEDIMAN ===');
        error_log('License Key: ' . $license_key);
    }

    $secret_key = get_option('mediman_api_secret_key', '');
    
    if (empty($secret_key)) {
        return ['success' => false, 'message' => 'API Secret Key belum diatur di pengaturan tema.'];
    }

    $url = trailingslashit(MEDIMAN_LICENSE_SERVER_URL) . 'wp-json/alm/v1/validate';
    
    // Format data sesuai dengan yang diharapkan server ALM
    $request_data = [
        'license_key' => $license_key,
        'site_url' => home_url(),
        'timestamp' => gmdate('Y-m-d H:i:s'),
        'user_login' => wp_get_current_user()->user_login,
        'theme_version' => wp_get_theme()->get('Version'),
        'php_version' => phpversion(),
        'wp_version' => get_bloginfo('version')
    ];

    if ($debug_mode) {
        error_log('Request Data: ' . print_r($request_data, true));
    }

    $response = wp_remote_post($url, [
        'timeout' => 30,
        'headers' => [
            'X-Alm-Secret' => $secret_key,
            'Content-Type' => 'application/json'
        ],
        'body' => wp_json_encode($request_data)
    ]);

    if ($debug_mode) {
        error_log('Raw Response: ' . print_r($response, true));
    }

    if (is_wp_error($response)) {
        return [
            'success' => false,
            'message' => 'Error koneksi: ' . $response->get_error_message()
        ];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($debug_mode) {
        error_log('Decoded Response: ' . print_r($data, true));
    }

    return $data;
}
/**
 * Memformat tanggal kedaluwarsa lisensi.
 */
function mediman_format_license_expiry( $date ) {
    try {
        $expiration = new DateTime( $date );
        $now        = new DateTime();
        $output     = 'Berlaku hingga: <strong>' . date_i18n( get_option( 'date_format' ), $expiration->getTimestamp() ) . '</strong><br>';
        if ( $now > $expiration ) {
            $output .= 'Telah berakhir sejak ' . $now->diff( $expiration )->days . ' hari yang lalu.';
        } else {
            $output .= 'Sisa waktu: <strong>' . $now->diff( $expiration )->days . ' hari</strong>';
        }
        return $output;
    } catch ( Exception $e ) {
        return 'Format tanggal tidak valid.';
    }
}

function mediman_activate_license($secret_key, $license_key) {
    if (empty($secret_key) || empty($license_key)) {
        return new WP_Error(
            'missing_keys', 
            'API Secret Key dan Kode Lisensi harus diisi.'
        );
    }

    $response = mediman_validate_license_api_call($license_key);
    
    if (!$response['success']) {
        return new WP_Error(
            'validation_failed', 
            $response['message'] ?? 'Validasi lisensi gagal.'
        );
    }

    // Update data lisensi
    $license_data = [
        'key' => $license_key,
        'status' => 'valid',
        'expires' => $response['expires'] ?? null,
        'activated_at' => current_time('mysql'),
        'site_url' => home_url(),
        'last_check' => current_time('mysql')
    ];

    update_option(MEDIMAN_LICENSE_OPTION_KEY, $license_data);
    
    return $response;
}

function mediman_deactivate_license($secret_key, $license_key) {
    // Panggil API deaktivasi jika diperlukan
    
    // Update local license data
    $license_data = [
        'key' => $license_key,
        'status' => 'inactive',
        'expires' => null,
        'deactivated_at' => current_time('mysql')
    ];
    
    update_option(MEDIMAN_LICENSE_OPTION_KEY, $license_data);
    
    return true;
}


