<?php
/**
 * File untuk mendaftarkan plugin yang direkomendasikan atau diwajibkan oleh tema.
 * Menggunakan TGM Plugin Activation.
 */

// Exit if accessed directly.
if ( ! defined('ABSPATH') ) {
    exit;
}

// Memuat library TGM yang sudah Anda letakkan di tema
require_once get_template_directory() . '/inc/lib/class-tgm-plugin-activation.php';

/**
 * Mendaftarkan plugin yang dibutuhkan oleh tema.
 */
function mediman_register_required_plugins() {
    /**
     * Array yang berisi daftar plugin.
     * Anda bisa menambahkan plugin lain di sini dengan mengikuti format yang sama.
     */
    $plugins = [
        
        // Plugin 1: One Click Demo Import (WAJIB)
        // Ini dibutuhkan agar fitur demo import bisa berjalan.
        [
            'name'      => 'One Click Demo Import',
            'slug'      => 'one-click-demo-import',
            'required'  => true, // true berarti plugin ini wajib diinstal.
        ],
        
        // Plugin 2: Contact Form 7 (REKOMENDASI)
        // Contoh jika demo Anda menggunakan formulir kontak.
        [
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false, // false berarti ini hanya rekomendasi, tidak wajib.
        ],
        
        // Anda bisa menambahkan plugin lain di sini. Contoh:
        // [
        //     'name'      => 'Nama Plugin',
        //     'slug'      => 'slug-plugin',
        //     'required'  => false,
        // ],

    ];

    /**
     * Array untuk konfigurasi tampilan notifikasi TGM.
     */
    $config = [
        'id'           => 'mediman',               // ID unik untuk tema Anda
        'default_path' => '',                      // Path default untuk plugin yang disertakan
        'menu'         => 'tgmpa-install-plugins', // Slug halaman menu
        'parent_slug'  => 'themes.php',            // Menu induk (di bawah Tampilan)
        'capability'   => 'edit_theme_options',    // Hak akses yang dibutuhkan
        'has_notices'  => true,                    // Tampilkan notifikasi admin
        'dismissable'  => true,                    // Notifikasi bisa ditutup
        'is_automatic' => false,                   // Nonaktifkan instalasi otomatis
        'message'      => '',                      // Pesan tambahan (kosongkan saja)
    ];

    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'mediman_register_required_plugins');