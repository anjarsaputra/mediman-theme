<?php
/**
 * Menangani semua logika untuk aktivasi lisensi tema Mediman.
 * Versi ini sudah lengkap dengan fungsi untuk menampilkan form.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Ganti dengan URL server validasi Anda
define('MEDIMAN_LICENSE_SERVER_URL', 'https://aratheme.id');

/**
 * Mendaftarkan semua pengaturan yang dibutuhkan untuk form.
 */
function mediman_register_license_form_settings() {
     // PERBAIKAN UTAMA DI SINI:
    // Argumen ketiga sekarang dibungkus dalam sebuah array.
    $args = [
        'sanitize_callback' => 'mediman_validate_license_key',
    ];

    register_setting(
        'mediman_license_options',      // Nama grup opsi
        'mediman_license_key',          // Nama opsi di database
        $args                           // Argumen dalam bentuk array
    );

    add_settings_section(
        'mediman_license_main_section',
        'Aktivasi Lisensi Tema',
        'mediman_license_section_description',
        'mediman_license_settings_page'
    );
    add_settings_field(
        'mediman_license_key_input',
        'Kode Lisensi Anda',
        'mediman_license_key_input_callback', // <-- Fungsi ini yang akan menampilkan input
        'mediman_license_settings_page',
        'mediman_license_main_section'
    );
}
add_action('admin_init', 'mediman_register_license_form_settings');


// --- FUNGSI-FUNGSI YANG HILANG SEBELUMNYA ---

// Fungsi untuk menampilkan deskripsi section
function mediman_license_section_description() {
    echo '<p>Masukkan kode lisensi Anda untuk mengaktifkan semua fitur premium dan pembaruan otomatis.</p>';
}

// Fungsi untuk menampilkan HTML dari input field
function mediman_license_key_input_callback() {
    $license_key = get_option('mediman_license_key', '');
    $status = get_option('mediman_license_status', 'inactive');
    echo '<input type="text" name="mediman_license_key" value="' . esc_attr($license_key) . '" class="regular-text" style="width: 350px;">';
    if ($status === 'active') {
        echo '<p class="description" style="color: green; font-weight: bold;">Status: Aktif</p>';
    } else {
        echo '<p class="description" style="color: red; font-weight: bold;">Status: Tidak Aktif</p>';
    }
}

// Fungsi validasi yang berjalan saat form disubmit
function mediman_validate_license_submission( $input_key ) {
    $api_url = MEDIMAN_LICENSE_SERVER_URL . '/wp-json/alm/v1/validate';
    $response = wp_remote_post($api_url, ['body' => ['license_key' => $input_key, 'site_url' => home_url()]]);

    if (is_wp_error($response)) {
        add_settings_error('mediman_license_messages', 'license_api_error', 'Gagal terhubung ke server aktivasi.', 'error');
        update_option('mediman_license_status', 'inactive');
    } else {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($body['success']) && $body['success']) {
            add_settings_error('mediman_license_messages', 'license_activated', 'Lisensi berhasil diaktivasi!', 'updated');
            update_option('mediman_license_status', 'active');
        } else {
            $error = $body['message'] ?? 'Kode lisensi tidak valid.';
            add_settings_error('mediman_license_messages', 'license_invalid', $error, 'error');
            update_option('mediman_license_status', 'inactive');
        }
    }
    return $input_key;
}


