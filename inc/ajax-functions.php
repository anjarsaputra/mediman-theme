<?php
/**
 * Fungsi-fungsi AJAX untuk tema Mediman.
 *
 * @package Mediman
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'mediman_ajax_login' ) ) {
    /**
     * Menangani permintaan login via AJAX.
     * Memvalidasi nonce untuk keamanan.
     */
    function mediman_ajax_login() {
        // Verifikasi nonce untuk keamanan
        check_ajax_referer( 'ajax-login-nonce', 'security' );

        $info                  = [];
        $info['user_login']    = isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : '';
        $info['user_password'] = isset( $_POST['password'] ) ? $_POST['password'] : '';
        $info['remember']      = true;

        $user_signon = wp_signon( $info, false );

        if ( is_wp_error( $user_signon ) ) {
            wp_send_json_error( [
                'success' => false,
                'message' => esc_html__( 'Username atau password salah.', 'mediman' ),
            ] );
        } else {
            wp_send_json_success( [
                'success' => true,
                'message' => esc_html__( 'Login berhasil, sedang mengalihkan...', 'mediman' ),
            ] );
        }

        // die() atau wp_die() tidak diperlukan setelah wp_send_json_*
    }
}

// Hook untuk pengguna yang belum login
add_action( 'wp_ajax_nopriv_login', 'mediman_ajax_login' );
// Hook untuk pengguna yang sudah login (jika diperlukan, meskipun biasanya tidak untuk login)
add_action( 'wp_ajax_login', 'mediman_ajax_login' );