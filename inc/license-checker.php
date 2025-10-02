<?php
// File: inc/license-checker.php

if (!defined('ABSPATH')) exit;

class Mediman_License_Checker {
    private static $instance = null;
    private $rate_limit_attempts = 5; // Maksimal 5 kali cek
    private $rate_limit_period = HOUR_IN_SECONDS; // Dalam 1 jam
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check rate limiting
     */
    private function check_rate_limit($license_key) {
        if (empty($license_key)) {
            return false;
        }

        $cache_key = 'mediman_license_check_' . md5($license_key);
        $attempts = get_transient($cache_key);
        
        if (false === $attempts) {
            set_transient($cache_key, 1, $this->rate_limit_period);
            return true;
        }

        if ($attempts >= $this->rate_limit_attempts) {
            return false; // Rate limited
        }

        set_transient($cache_key, $attempts + 1, $this->rate_limit_period);
        return true;
    }

    /**
     * Validate license
     */
    public function validate_license($license_key) {
        // Cek rate limit dulu
        if (!$this->check_rate_limit($license_key)) {
            return new WP_Error(
                'rate_limit',
                sprintf(
                    'Terlalu banyak percobaan. Silakan tunggu %s menit.',
                    ceil($this->rate_limit_period / 60)
                )
            );
        }

        // Lakukan validasi lisensi ke server
        $response = wp_remote_post('https://your-license-server.com/api/validate', [
            'body' => [
                'license_key' => $license_key,
                'domain' => $_SERVER['HTTP_HOST']
            ]
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || !isset($data['valid'])) {
            return new WP_Error('invalid_response', 'Invalid response from license server');
        }

        return $data;
    }

    /**
     * Activate license
     */
    public function activate_license($license_key) {
        // Cek rate limit
        if (!$this->check_rate_limit($license_key)) {
            return new WP_Error(
                'rate_limit',
                'Terlalu banyak percobaan aktivasi. Silakan tunggu beberapa saat.'
            );
        }

        // Simpan lisensi jika valid
        if ($this->validate_license($license_key)) {
            update_option('mediman_license_key', $license_key);
            return true;
        }

        return false;
    }
}

// Initialize the checker
function mediman_init_license_checker() {
    Mediman_License_Checker::get_instance();
}
add_action('init', 'mediman_init_license_checker');