<?php
/**
 * Integrates with Plugin Update Checker library for theme updates.
 *
 * @package Mediman
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize the theme update checker.
 */
function mediman_initialize_theme_updater() {
    // Only run if the license is active
    if ( ! function_exists( 'mediman_is_license_active' ) || ! mediman_is_license_active() ) {
        return;
    }

    $library_path = MEDIMAN_INC_DIR . '/plugin-update-checker/plugin-update-checker.php';

    if ( file_exists( $library_path ) ) {
        require_once $library_path;
        
        $update_checker_url = MEDIMAN_LICENSE_SERVER_URL . '/wp-json/theme-update/v1/info/' . get_template();
        
        $checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            $update_checker_url,
            get_template_directory(),
            get_template()
        );

        // Optional: Add license key to update requests for validation on the server
        $checker->addQueryArgFilter( 'mediman_add_license_to_update_request' );
    }
}
add_action( 'after_setup_theme', 'mediman_initialize_theme_updater' );

/**
 * Append license key data to the update checker URL.
 *
 * @param array $query_args
 * @return array
 */
function mediman_add_license_to_update_request( $query_args ) {
    $license_data = get_site_option( MEDIMAN_LICENSE_OPTION_KEY, [] );
    if ( ! empty( $license_data['key'] ) ) {
        $query_args['license_key'] = $license_data['key'];
        $query_args['site_url']    = is_multisite() ? network_home_url() : home_url();
    }
    return $query_args;
}


/**
 * Allows forcing a theme update check via a URL parameter.
 */
function mediman_force_update_check() {
    if ( isset( $_GET['force_theme_update_check'] ) && '1' === $_GET['force_theme_update_check'] && current_user_can( 'update_themes' ) ) {
        delete_site_transient( 'update_themes' );
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
        $redirect_url = is_multisite() ? network_admin_url( 'update-core.php' ) : admin_url( 'update-core.php' );
        wp_safe_redirect( $redirect_url );
        exit;
    }
}
add_action( 'admin_init', 'mediman_force_update_check' );