<?php
/**
 * File untuk Menerapkan Fitur Optimasi Performa Tema Mediman.
 *
 * @package Mediman
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include logging functionality
require_once get_template_directory() . '/inc/logging.php';

// Fungsi helper untuk mendapatkan informasi waktu dan user
function mediman_get_current_info() {
    return array(
        'datetime_utc' => gmdate('Y-m-d H:i:s'), // Format: 2025-08-05 15:53:42
        'user_login'   => wp_get_current_user()->user_login // Contoh: anjarsaputra
    );
}

// Sanitasi pengaturan optimasi


// Konstanta untuk nilai default
define('MEDIMAN_CACHE_DURATION', 31536000); // 1 tahun dalam detik
define('MEDIMAN_REVISION_KEEP', 5);
define('MEDIMAN_AUTO_DRAFT_DAYS', 7);

// Ambil pengaturan sekali di bagian atas file
$perf_options = get_option('mediman_performance_settings', []);

// 1. Defer JavaScript
if (!empty($perf_options['defer_js'])) {
    function mediman_defer_parsing_of_js($url) {
        mediman_log_optimization_activity('defer_js', 'URL: ' . $url);
        if (is_user_logged_in() || strpos($url, 'jquery.min.js')) {
            return $url;
        }
        return str_replace(' src', ' defer src', $url);
    }
    add_filter('script_loader_tag', 'mediman_defer_parsing_of_js', 10);
}

// 2. Hapus Query Strings
if (!empty($perf_options['remove_query_strings'])) {
    function mediman_remove_css_js_version($src) {
        if (!is_string($src) || empty($src)) {
            return $src;
        }
        if (strpos($src, '?ver=')) {
            mediman_log_optimization_activity('remove_query_string', 'Source: ' . $src);
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('style_loader_src', 'mediman_remove_css_js_version', 9999);
    add_filter('script_loader_src', 'mediman_remove_css_js_version', 9999);
}

// 3. Matikan Emoji
if (!empty($perf_options['disable_emojis'])) {
    function mediman_disable_emojis() {
        mediman_log_optimization_activity('disable_emojis', 'Disabled WordPress emojis');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('emoji_svg_url', '__return_false');
    }
    add_action('init', 'mediman_disable_emojis');
}

// 4. Matikan Embeds
if (!empty($perf_options['disable_embeds'])) {
    function mediman_disable_embeds_init() {
        mediman_log_optimization_activity('disable_embeds', 'Disabled WordPress embeds');
        
        // Hapus fitur oEmbed
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
        
        // Hapus rewrite rules untuk oEmbed
        add_filter('rewrite_rules_array', function($rules) {
            foreach($rules as $rule => $rewrite) {
                if(strpos($rewrite, 'embed=true') !== false) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        });
    }
    add_action('init', 'mediman_disable_embeds_init', 9999);
}

// 5. Matikan XML-RPC
if (!empty($perf_options['disable_xmlrpc'])) {
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('wp_headers', function($headers) {
        mediman_log_optimization_activity('disable_xmlrpc', 'Disabled XML-RPC');
        unset($headers['X-Pingback']);
        return $headers;
    });
}

// 6. Hapus Link Header yang Tidak Perlu
if (!empty($perf_options['remove_header_links'])) {
    function mediman_remove_header_links() {
        mediman_log_optimization_activity('remove_header_links', 'Removed unnecessary header links');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'rel_canonical');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
    }
    add_action('init', 'mediman_remove_header_links');
}

// 7. Disable Image Sizes
function mediman_disable_image_sizes($sizes) {
    $is_disabled = get_theme_mod('disable_medium_large_images', 0);
    if ($is_disabled) {
        mediman_log_optimization_activity('disable_image_sizes', 'Disabled medium_large image size');
        unset($sizes['medium_large']);
    }
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'mediman_disable_image_sizes');

// 8. Lazy Loading Images
if (!empty($perf_options['lazy_load'])) {
    function mediman_add_lazy_loading($content) {
        mediman_log_optimization_activity('lazy_load', 'Added lazy loading to images');
        return preg_replace('/<img(.*?)src=/i', '<img$1loading="lazy" src=', $content);
    }
    add_filter('the_content', 'mediman_add_lazy_loading');
    add_filter('post_thumbnail_html', 'mediman_add_lazy_loading');
}

// 9. Compress HTML Output
if (!empty($perf_options['compress_html'])) {
    function mediman_compress_html($buffer) {
        mediman_log_optimization_activity('compress_html', 'Compressed HTML output');
        $search = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s'
        );
        $replace = array('>', '<', '\\1');
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
    add_action('template_redirect', function() {
        ob_start('mediman_compress_html');
    });
}

// 10. Cache Control Headers
if (!empty($perf_options['cache_control'])) {
    function mediman_add_cache_control_headers() {
        if (!is_user_logged_in() && !is_admin()) {
            mediman_log_optimization_activity('cache_control', 'Added cache control headers');
            header('Cache-Control: public, max-age=' . MEDIMAN_CACHE_DURATION);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + MEDIMAN_CACHE_DURATION) . ' GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', get_the_modified_time('U')) . ' GMT');
        }
    }
    add_action('template_redirect', 'mediman_add_cache_control_headers');
}

// 11. DNS Prefetch
if (!empty($perf_options['dns_prefetch'])) {
    function mediman_add_dns_prefetch() {
        $urls = apply_filters('mediman_dns_prefetch_urls', array(
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://ajax.googleapis.com',
            'https://apis.google.com',
            'https://www.google-analytics.com'
        ));

        mediman_log_optimization_activity('dns_prefetch', 'Added DNS prefetch for ' . count($urls) . ' domains');
        
        foreach ($urls as $url) {
            echo '<link rel="dns-prefetch" href="' . esc_url($url) . '">' . "\n";
        }
    }
    add_action('wp_head', 'mediman_add_dns_prefetch', 0);
}

// 12. Database Optimization
if (!empty($perf_options['optimize_database'])) {
    if (!wp_next_scheduled('mediman_optimize_database')) {
        wp_schedule_event(time(), 'weekly', 'mediman_optimize_database');
    }
    
    function mediman_do_database_optimization() {
        global $wpdb;
        
        mediman_log_optimization_activity('database_optimization', 'Started database optimization');
        
        // Optimize tables
        $tables = $wpdb->get_results('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $table_name) {
                $wpdb->query("OPTIMIZE TABLE $table_name");
                mediman_log_optimization_activity('optimize_table', "Optimized table: $table_name");
            }
        }
        
        // Clean up revisions
        $wpdb->query(
            "DELETE a FROM {$wpdb->posts} a
            LEFT JOIN (
                SELECT post_parent, MAX(post_date) as keep_date
                FROM {$wpdb->posts}
                WHERE post_type = 'revision'
                GROUP BY post_parent
            ) b ON a.post_parent = b.post_parent
            WHERE a.post_type = 'revision'
            AND a.post_date < b.keep_date"
        );
        
        // Clean up auto drafts
        $old_posts = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts}
                WHERE post_status = 'auto-draft'
                AND post_date < %s",
                date('Y-m-d', strtotime('-' . MEDIMAN_AUTO_DRAFT_DAYS . ' days'))
            )
        );
        
        if ($old_posts) {
            foreach ($old_posts as $post_id) {
                wp_delete_post($post_id, true);
            }
            mediman_log_optimization_activity('cleanup_drafts', 'Deleted ' . count($old_posts) . ' old auto-drafts');
        }
        
        // Clean up expired transients
        $wpdb->query(
            "DELETE FROM {$wpdb->options}
            WHERE option_name LIKE '\_transient\_%'
            AND option_value < '" . time() . "'"
        );
        
        mediman_log_optimization_activity('database_optimization', 'Completed database optimization');
    }
    add_action('mediman_optimize_database', 'mediman_do_database_optimization');
}

// Cleanup on deactivation
register_deactivation_hook(__FILE__, 'mediman_cleanup_optimization_options');
function mediman_cleanup_optimization_options() {
    wp_clear_scheduled_hook('mediman_cleanup_event');
    wp_clear_scheduled_hook('mediman_optimize_database');
    delete_option('mediman_performance_settings');
    delete_option('mediman_optimization_logs');
    mediman_log_optimization_activity('theme_deactivation', 'Cleaned up all optimization settings');
}



class Mediman_Login_Protection {
    private $login_attempts_option = 'mediman_login_attempts';
    
    public function __construct() {
        // Hook untuk menangani login gagal
        add_action('wp_login_failed', array($this, 'handle_failed_login'));
        // Hook untuk mengecek sebelum proses autentikasi
        add_filter('authenticate', array($this, 'check_login_allowed'), 30, 3);
        // Hook untuk mereset attempts setelah login berhasil
        add_action('wp_login', array($this, 'reset_login_attempts'), 10, 2);
    }

    // Fungsi untuk menangani login yang gagal
    public function handle_failed_login($username) {
        $perf_options = get_option('mediman_performance_settings', []);
        
        // Cek apakah fitur login protection aktif
        if (empty($perf_options['login_protection'])) {
            return;
        }

        $ip_address = $this->get_ip_address();
        $attempts = get_option($this->login_attempts_option, array());

        if (!isset($attempts[$ip_address])) {
            $attempts[$ip_address] = array(
                'count' => 1,
                'lockout_start' => 0
            );
        } else {
            $attempts[$ip_address]['count']++;
        }

        update_option($this->login_attempts_option, $attempts);

        // Log untuk debugging
        error_log("Failed login attempt from IP: " . $ip_address . " - Count: " . $attempts[$ip_address]['count']);
    }

    // Fungsi untuk mengecek apakah login diizinkan
    public function check_login_allowed($user, $username, $password) {
        $perf_options = get_option('mediman_performance_settings', []);
        
        // Cek apakah fitur login protection aktif
        if (empty($perf_options['login_protection'])) {
            return $user;
        }

        $ip_address = $this->get_ip_address();
        $attempts = get_option($this->login_attempts_option, array());

        // Jika tidak ada attempts, izinkan login
        if (!isset($attempts[$ip_address])) {
            return $user;
        }

        $max_attempts = isset($perf_options['login_max_attempts']) ? (int)$perf_options['login_max_attempts'] : 3;
        $lockout_duration = isset($perf_options['login_lockout_duration']) ? (int)$perf_options['login_lockout_duration'] : 300;

        // Cek jika dalam masa lockout
        if ($attempts[$ip_address]['lockout_start'] > 0) {
            $time_passed = time() - $attempts[$ip_address]['lockout_start'];
            if ($time_passed < $lockout_duration) {
                $minutes_left = ceil(($lockout_duration - $time_passed) / 60);
                return new WP_Error(
                    'too_many_attempts',
                    sprintf(
                        __('Terlalu banyak percobaan login gagal. Silakan coba lagi dalam %d menit.', 'mediman'),
                        $minutes_left
                    )
                );
            } else {
                // Reset jika lockout sudah berakhir
                $this->reset_login_attempts_for_ip($ip_address);
                return $user;
            }
        }

        // Cek jumlah percobaan
        if ($attempts[$ip_address]['count'] >= $max_attempts) {
            $attempts[$ip_address]['lockout_start'] = time();
            update_option($this->login_attempts_option, $attempts);
            
            return new WP_Error(
                'too_many_attempts',
                sprintf(
                    __('Terlalu banyak percobaan login gagal. Silakan coba lagi dalam %d menit.', 'mediman'),
                    ceil($lockout_duration / 60)
                )
            );
        }

        return $user;
    }

    // Reset attempts setelah login berhasil
    public function reset_login_attempts($user_login, $user) {
        $ip_address = $this->get_ip_address();
        $this->reset_login_attempts_for_ip($ip_address);
    }

    // Reset attempts untuk IP tertentu
    private function reset_login_attempts_for_ip($ip_address) {
        $attempts = get_option($this->login_attempts_option, array());
        if (isset($attempts[$ip_address])) {
            unset($attempts[$ip_address]);
            update_option($this->login_attempts_option, $attempts);
        }
    }

    // Fungsi untuk mendapatkan IP address
    private function get_ip_address() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($ip_keys as $key) {
            if (isset($_SERVER[$key])) {
                $ip_array = explode(',', $_SERVER[$key]);
                $ip = trim($ip_array[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '127.0.0.1'; // default localhost
    }
}

// Inisialisasi class
new Mediman_Login_Protection();

