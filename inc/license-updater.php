<?php
/**
 * File untuk menangani Lisensi dan Auto-Update Tema.
 * VERSI KERANGKA
 */

if (!defined('ABSPATH')) { exit; }

// --- BAGIAN 2: HALAMAN PENGATURAN LISENSI ---
// Ini akan membuat submenu "Lisensi Tema" di bawah menu "Tampilan"
add_action('admin_menu', 'mediman_add_license_page');
function mediman_add_license_page() {
    add_theme_page(
        'Lisensi Tema Mediman', // Judul halaman
        'Lisensi Tema',         // Judul menu
        'manage_options',       // Hanya untuk admin
        'mediman-license',      // Slug menu
        'mediman_render_license_page' // Fungsi untuk menampilkan konten halaman
    );
}

// Fungsi untuk menampilkan halaman lisensi
function mediman_render_license_page() {
    ?>
    <div class="wrap">
        <h1>Pengaturan Lisensi Tema Mediman</h1>
        <p>Masukkan kunci lisensi Anda untuk mengaktifkan pembaruan otomatis dan mendapatkan dukungan.</p>
        <form method="post" action="options.php">
            <?php
                settings_fields('mediman_license_group'); // Grup pengaturan
                do_settings_sections('mediman-license');  // Halaman slug
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Mendaftarkan setting untuk lisensi
add_action('admin_init', 'mediman_register_license_settings');
function mediman_register_license_settings() {
    register_setting('mediman_license_group', 'mediman_license_key', [
    'sanitize_callback' => 'sanitize_text_field'
]);

    add_settings_section(
    'mediman_license_section',
    'Lisensi',
    'mediman_license_section_callback', // ← sekarang valid
    'mediman_license_page'
);

    add_settings_field(
        'mediman_license_key_field',   // ID field
        'Kunci Lisensi',               // Label
        'mediman_license_field_callback', // Fungsi untuk menampilkan field input
        'mediman-license',             // Halaman slug
        'mediman_license_section'      // Section ID
    );
}

// Callback untuk menampilkan field input
function mediman_license_field_callback() {
    $license_key = get_option('mediman_license_key');
    echo '<input type="text" id="mediman_license_key" name="mediman_license_key" value="' . esc_attr($license_key) . '" class="regular-text">';
    // Di sini Anda bisa menambahkan logika untuk verifikasi kunci lisensi
}