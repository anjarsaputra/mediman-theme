<?php
/**
 * License Client Library - Tema NamaTema
 * 
 * @package NamaTema
 * @author anjarsaputra
 * @version 1.0
 * @since 2025-01-02
 * 
 * File ini adalah INTI proteksi tema.
 * Jangan dihapus atau dimodifikasi tanpa pemahaman yang jelas!
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class NamaTema_License_Client {
    
    /**
     * URL Server Lisensi Kamu
     * GANTI dengan URL WordPress yang ada plugin license-manager
     */
    private $server_url = 'https://server-lisensi-kamu.com'; // ← GANTI INI!
    
    /**
     * Secret Key untuk API
     * Harus SAMA dengan secret key di server plugin
     */
    private $secret_key = 'your-secret-key-here'; // ← GANTI INI!
    
    /**
     * Product ID / Nama Tema
     */
    private $product_id = 'namatema';
    
    /**
     * Option names untuk menyimpan data lisensi
     */
    private $option_license_key = 'namatema_license_key';
    private $option_license_status = 'namatema_license_status';
    private $option_license_email = 'namatema_license_email';
    private $option_license_expires = 'namatema_license_expires';
    private $option_last_check = 'namatema_last_check';
    
    /**
     * Constructor
     */
    public function __construct() {
        // Schedule daily license check (heartbeat)
        add_action('wp', array($this, 'schedule_license_check'));
        add_action('namatema_daily_license_check', array($this, 'do_heartbeat_check'));
        
        // Admin notices untuk lisensi tidak aktif
        add_action('admin_notices', array($this, 'license_notice'));
        
        // AJAX handlers untuk aktivasi/deaktivasi
        add_action('wp_ajax_namatema_activate_license', array($this, 'ajax_activate_license'));
        add_action('wp_ajax_namatema_deactivate_license', array($this, 'ajax_deactivate_license'));
    }
    
    /**
     * ================================================
     * AKTIVASI LISENSI
     * ================================================
     */
    public function activate_license($license_key, $customer_email = '') {
        // Validasi input
        if (empty($license_key)) {
            return array(
                'success' => false,
                'message' => 'License key tidak boleh kosong.'
            );
        }
        
        // Sanitize input
        $license_key = sanitize_text_field($license_key);
        $customer_email = sanitize_email($customer_email);
        $site_url = get_site_url();
        
        // Kirim request ke server
        $response = wp_remote_post($this->server_url . '/wp-json/alm/v1/activate', array(
            'headers' => array(
                'X-Alm-Secret' => $this->secret_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => $license_key,
                'site_url' => $site_url,
                'product_id' => $this->product_id,
                'customer_email' => $customer_email
            )),
            'timeout' => 30,
            'sslverify' => true // Pastikan HTTPS!
        ));
        
        // Check for errors
        if (is_wp_error($response)) {
            $this->log_error('Activation failed: ' . $response->get_error_message());
            return array(
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server lisensi. Error: ' . $response->get_error_message()
            );
        }
        
        // Parse response
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // Check response
        if (!isset($data['success'])) {
            $this->log_error('Invalid response from server: ' . $body);
            return array(
                'success' => false,
                'message' => 'Response tidak valid dari server.'
            );
        }
        
        // Jika sukses, simpan data lisensi
        if ($data['success'] === true) {
            update_option($this->option_license_key, $license_key);
            update_option($this->option_license_status, 'active');
            update_option($this->option_license_email, $customer_email);
            update_option($this->option_last_check, current_time('mysql'));
            
            // Simpan expire date jika ada
            if (isset($data['expires'])) {
                update_option($this->option_license_expires, $data['expires']);
            }
            
            $this->log_info('License activated successfully for: ' . $site_url);
        }
        
        return $data;
    }
    
    /**
     * ================================================
     * DEAKTIVASI LISENSI
     * ================================================
     */
    public function deactivate_license($license_key = '') {
        // Jika tidak ada license key, ambil dari database
        if (empty($license_key)) {
            $license_key = get_option($this->option_license_key);
        }
        
        if (empty($license_key)) {
            return array(
                'success' => false,
                'message' => 'Tidak ada lisensi yang aktif.'
            );
        }
        
        $site_url = get_site_url();
        
        // Kirim request ke server
        $response = wp_remote_post($this->server_url . '/wp-json/alm/v1/deactivate', array(
            'headers' => array(
                'X-Alm-Secret' => $this->secret_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => sanitize_text_field($license_key),
                'site_url' => $site_url
            )),
            'timeout' => 30,
            'sslverify' => true
        ));
        
        // Hapus data lisensi lokal (bahkan jika request gagal)
        delete_option($this->option_license_key);
        delete_option($this->option_license_status);
        delete_option($this->option_license_email);
        delete_option($this->option_license_expires);
        delete_option($this->option_last_check);
        
        if (is_wp_error($response)) {
            $this->log_error('Deactivation failed: ' . $response->get_error_message());
            return array(
                'success' => true, // Tetap return true karena sudah hapus lokal
                'message' => 'Lisensi dihapus dari site ini (server tidak dapat dihubungi).'
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        $this->log_info('License deactivated for: ' . $site_url);
        
        return $data;
    }
    
    /**
     * ================================================
     * CEK STATUS LISENSI (HEARTBEAT)
     * ================================================
     */
    public function check_license_status() {
        $license_key = get_option($this->option_license_key);
        
        if (empty($license_key)) {
            return array(
                'success' => false,
                'message' => 'Lisensi belum diaktifkan.'
            );
        }
        
        $site_url = get_site_url();
        
        // Kirim request ke server
        $response = wp_remote_post($this->server_url . '/wp-json/alm/v1/check', array(
            'headers' => array(
                'X-Alm-Secret' => $this->secret_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'license_key' => $license_key,
                'site_url' => $site_url
            )),
            'timeout' => 30,
            'sslverify' => true
        ));
        
        if (is_wp_error($response)) {
            $this->log_error('License check failed: ' . $response->get_error_message());
            return array(
                'success' => false,
                'message' => 'Tidak dapat menghubungi server lisensi.'
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // Update local status
        if (isset($data['success'])) {
            if ($data['success'] === true) {
                update_option($this->option_license_status, 'active');
            } else {
                update_option($this->option_license_status, 'inactive');
            }
            update_option($this->option_last_check, current_time('mysql'));
        }
        
        return $data;
    }
    
    /**
     * ================================================
     * HEARTBEAT - CEK OTOMATIS SETIAP HARI
     * ================================================
     */
    public function schedule_license_check() {
        if (!wp_next_scheduled('namatema_daily_license_check')) {
            wp_schedule_event(time(), 'daily', 'namatema_daily_license_check');
        }
    }
    
    public function do_heartbeat_check() {
        $license_key = get_option($this->option_license_key);
        
        if (empty($license_key)) {
            return; // Tidak ada lisensi, skip
        }
        
        $result = $this->check_license_status();
        
        // Jika lisensi tidak valid, update status
        if (!$result['success']) {
            update_option($this->option_license_status, 'inactive');
            $this->log_warning('License validation failed during heartbeat check');
        }
    }
    
    /**
     * ================================================
     * HELPER FUNCTIONS
     * ================================================
     */
    
    /**
     * Check apakah tema sudah licensed
     */
    public function is_licensed() {
        $status = get_option($this->option_license_status);
        $license_key = get_option($this->option_license_key);
        
        return ($status === 'active' && !empty($license_key));
    }
    
    /**
     * Get license data
     */
    public function get_license_data() {
        return array(
            'license_key' => get_option($this->option_license_key, ''),
            'status' => get_option($this->option_license_status, 'inactive'),
            'email' => get_option($this->option_license_email, ''),
            'expires' => get_option($this->option_license_expires, ''),
            'last_check' => get_option($this->option_last_check, '')
        );
    }
    
    /**
     * ================================================
     * ADMIN NOTICES
     * ================================================
     */
    public function license_notice() {
        // Hanya tampilkan untuk admin
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Skip jika sudah licensed
        if ($this->is_licensed()) {
            return;
        }
        
        // Tampilkan notice
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>⚠️ Tema NamaTema belum diaktifkan!</strong><br>
                Silakan aktivasi lisensi Anda untuk mendapatkan update dan support.
            </p>
            <p>
                <a href="<?php echo admin_url('themes.php?page=namatema-license'); ?>" class="button button-primary">
                    Aktivasi Lisensi Sekarang
                </a>
            </p>
        </div>
        <?php
    }
    
    /**
     * ================================================
     * AJAX HANDLERS
     * ================================================
     */
    public function ajax_activate_license() {
        check_ajax_referer('namatema_license_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }
        
        $license_key = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        
        $result = $this->activate_license($license_key, $email);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    public function ajax_deactivate_license() {
        check_ajax_referer('namatema_license_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }
        
        $result = $this->deactivate_license();
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * ================================================
     * LOGGING
     * ================================================
     */
    private function log_info($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[NamaTema License] INFO: ' . $message);
        }
    }
    
    private function log_error($message) {
        error_log('[NamaTema License] ERROR: ' . $message);
    }
    
    private function log_warning($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[NamaTema License] WARNING: ' . $message);
        }
    }
}

// Initialize
global $namatema_license;
$namatema_license = new NamaTema_License_Client();

/**
 * Helper function untuk cek lisensi dari mana saja
 */
function namatema_is_licensed() {
    global $namatema_license;
    return $namatema_license->is_licensed();
}

/**
 * Helper function untuk get license data
 */
function namatema_get_license_data() {
    global $namatema_license;
    return $namatema_license->get_license_data();
}