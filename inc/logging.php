<?php
/**
 * File untuk implementasi logging aktivitas optimasi
 *
 * @package Mediman
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Fungsi helper untuk mendapatkan informasi waktu dan user
if (!function_exists('mediman_get_current_info')) {
    function mediman_get_current_info() {
        return array(
            'datetime_utc' => gmdate('Y-m-d H:i:s'),
            'user_login'   => wp_get_current_user()->user_login
        );
    }
}

// Implementasi untuk logging aktivitas optimasi
if (!function_exists('mediman_log_optimization_activity')) {
    function mediman_log_optimization_activity($action, $details = '') {
        $info = mediman_get_current_info();
        
        $log_entry = sprintf(
            '[%s] User: %s - Action: %s %s',
            $info['datetime_utc'],
            $info['user_login'],
            $action,
            $details ? "- Details: $details" : ''
        );

        // Log ke file khusus jika WP_DEBUG aktif
        if (WP_DEBUG) {
            error_log($log_entry . PHP_EOL, 3, WP_CONTENT_DIR . '/optimization-log.txt');
        }

        // Simpan log ke database (terbatas 100 entri terakhir)
        $logs = get_option('mediman_optimization_logs', array());
        array_unshift($logs, array(
            'datetime' => $info['datetime_utc'],
            'user'     => $info['user_login'],
            'action'   => $action,
            'details'  => $details
        ));

        // Batasi hanya 100 log terakhir
        $logs = array_slice($logs, 0, 100);
        update_option('mediman_optimization_logs', $logs);
    }
}

// Fungsi untuk melihat log aktivitas (untuk admin)
if (!function_exists('mediman_view_optimization_logs')) {
    function mediman_view_optimization_logs() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $logs = get_option('mediman_optimization_logs', array());
        
        echo '<div class="wrap">';
        echo '<h2>Optimization Activity Log</h2>';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>DateTime (UTC)</th>';
        echo '<th>User</th>';
        echo '<th>Action</th>';
        echo '<th>Details</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . esc_html($log['datetime']) . '</td>';
            echo '<td>' . esc_html($log['user']) . '</td>';
            echo '<td>' . esc_html($log['action']) . '</td>';
            echo '<td>' . esc_html($log['details']) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}

// Tambahkan menu untuk melihat log
if (!function_exists('mediman_add_logs_menu')) {
    function mediman_add_logs_menu() {
        if (current_user_can('manage_options')) {
            add_submenu_page(
                'tools.php',
                'Optimization Logs',
                'Optimization Logs',
                'manage_options',
                'mediman-optimization-logs',
                'mediman_view_optimization_logs'
            );
        }
    }
    add_action('admin_menu', 'mediman_add_logs_menu');
}