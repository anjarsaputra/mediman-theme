<?php
/**
 * Renders the theme license activation page in the admin area.
 *
 * @package Mediman
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds the license menu page to the admin menu.
 */
function mediman_add_license_menu() {
    // ... (Kode fungsi mediman_add_license_menu Anda di sini) ...
}
if ( is_multisite() ) {
    add_action( 'network_admin_menu', 'mediman_add_license_menu' );
} else {
    // Pastikan menu induk 'pengaturan-tema-mediman' ada
    add_action( 'admin_menu', 'mediman_add_license_menu', 99 );
}

/**
 * Renders the HTML for the license activation page.
 */
function mediman_license_page_render() {
    // ... (Kode fungsi mediman_license_page_render Anda di sini) ...
}