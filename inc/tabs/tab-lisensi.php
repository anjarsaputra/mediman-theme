<?php
/**
 * Tab Lisensi - Mediman Theme
 * File: tab-lisensi.php
 */

if (!defined('ABSPATH')) exit;

// Debug mode
$debug_mode = get_option('mediman_debug_mode', false);
if ($debug_mode) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$license_data = get_option(MEDIMAN_LICENSE_OPTION_KEY, []);
$saved_secret_key = get_option('mediman_api_secret_key', '');
$saved_license = '';
if (!empty($license_data)) {
    if (isset($license_data['license_key'])) {
        $saved_license = $license_data['license_key'];
    } elseif (isset($license_data['key'])) {
        $saved_license = $license_data['key'];
    }
}
$is_valid = isset($license_data['status']) && $license_data['status'] === 'valid';
$expires_date = isset($license_data['expires']) ? $license_data['expires'] : '';

// Prepare variable to hold user errors
$user_error_message = null;

// Handle form submissions
if (isset($_POST['mediman_action'])) {
    if (!wp_verify_nonce($_POST['mediman_license_nonce'], 'mediman_license_action')) {
        $user_error_message = __('Verifikasi keamanan gagal. Silakan reload halaman.', 'mediman');
    } else {
        $action = sanitize_text_field($_POST['mediman_action']);
        if ($debug_mode) {
            error_log('=== MEDIMAN LICENSE ACTION ===');
            error_log('Action: ' . $action);
            error_log('POST data: ' . print_r($_POST, true));
        }
        switch ($action) {
            case 'save_settings':
                if (isset($_POST['api_secret_key'])) {
                    $api_key = sanitize_text_field($_POST['api_secret_key']);
                    update_option('mediman_api_secret_key', $api_key);
                }
                if (isset($_POST['license_key'])) {
                    $license = sanitize_text_field($_POST['license_key']);
                    $license_data = [
                        'license_key' => $license,
                        'status' => 'pending',
                        'timestamp' => gmdate('Y-m-d H:i:s'),
                        'user_login' => wp_get_current_user()->user_login,
                        'site_url' => home_url(),
                        'theme_version' => wp_get_theme()->get('Version')
                    ];
                    update_option(MEDIMAN_LICENSE_OPTION_KEY, $license_data);
                }
                if (isset($_POST['alm_debug_mode'])) {
                    update_option('mediman_debug_mode', (bool)$_POST['alm_debug_mode']);
                }
                wp_cache_delete('mediman_api_secret_key', 'options');
                wp_cache_delete(MEDIMAN_LICENSE_OPTION_KEY, 'options');
                wp_redirect(add_query_arg([
                    'page' => $_GET['page'],
                    'tab' => 'lisensi',
                    'settings-updated' => 'true',
                    'ts' => time()
                ], admin_url('admin.php')));
                exit;
                break;

            case 'activate_license':
                $license_key = sanitize_text_field($saved_license);
                $checker = Mediman_License_Checker::get_instance();
                $result = $checker->activate_license($license_key);

                if (is_wp_error($result)) {
                    $error_code = $result->get_error_code();
                    $error_msg = $result->get_error_message();
                    // UX improvement: Show user-friendly error messages
                    if ($error_code === 'rate_limit') {
                        $user_error_message = __('Terlalu banyak percobaan aktivasi. Silakan tunggu beberapa menit sebelum mencoba lagi.', 'mediman');
                    } elseif ($error_code === 'validation_failed') {
                        $user_error_message = __('Kode lisensi tidak valid. Pastikan Anda memasukkan kode lisensi yang benar.', 'mediman');
                    } else {
                        $user_error_message = __('Terjadi kesalahan: ', 'mediman') . esc_html($error_msg);
                    }
                    add_settings_error(
                        'mediman_messages',
                        'license_error',
                        $user_error_message,
                        'error'
                    );
                } elseif ($result === true) {
                    // Success: update status
                    $license_data['status'] = 'valid';
                    $license_data['activated_at'] = gmdate('Y-m-d H:i:s');
                    update_option(MEDIMAN_LICENSE_OPTION_KEY, $license_data);
                    add_settings_error(
                        'mediman_messages',
                        'license_activated',
                        __('Lisensi berhasil diaktifkan!', 'mediman'),
                        'success'
                    );
                } else {
                    $user_error_message = __('Gagal mengaktifkan lisensi. Silakan coba beberapa saat lagi.', 'mediman');
                    add_settings_error(
                        'mediman_messages',
                        'license_error',
                        $user_error_message,
                        'error'
                    );
                }
                break;

            case 'deactivate_license':
                // (tambahkan UX error handling jika perlu)
                break;

            case 'test_api':
                // (tambahkan UX error handling jika perlu)
                break;
        }
    }
}

// Show debug info (only if debug)
if ($debug_mode) {
    echo '<div class="notice notice-info"><pre>';
    echo "=== CURRENT LICENSE STATE ===\n";
    echo "License Data: " . print_r($license_data, true) . "\n";
    echo "API Secret Key: " . $saved_secret_key . "\n";
    echo "License Key: " . $saved_license . "\n";
    echo "Is Valid: " . ($is_valid ? 'Yes' : 'No') . "\n";
    echo "Current Time (UTC): " . gmdate('Y-m-d H:i:s') . "\n";
    echo "Current User: " . wp_get_current_user()->user_login . "\n";
    echo '</pre></div>';
}

// Show top-level user error if available
if ($user_error_message) {
    echo '<div class="notice notice-error"><p>' . esc_html($user_error_message) . '</p></div>';
}

settings_errors('mediman_messages');
?>

<div class="notice notice-info inline">
    <h3 style="margin: 0.5em 0;"><?php esc_html_e('Petunjuk Aktivasi', 'mediman'); ?></h3>
    <ol style="padding-left: 20px; margin: 0 0 1em 0;">
        <li><?php esc_html_e('Salin & tempel "API Secret Key" dan "Kode Lisensi" Anda ke dalam kolom di bawah.', 'mediman'); ?></li>
        <li>Klik tombol <strong>"<?php esc_html_e('Simpan Pengaturan', 'mediman'); ?>"</strong> terlebih dahulu untuk menyimpan kedua kunci.</li>
        <li>Setelah halaman dimuat ulang, jika lisensi belum aktif, klik tombol <strong>"<?php esc_html_e('Aktifkan Lisensi', 'mediman'); ?>"</strong>.</li>
    </ol>
</div>

<form method="post" action="" id="mediman-license-form">
    <?php wp_nonce_field('mediman_license_action', 'mediman_license_nonce'); ?>
    <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
    <input type="hidden" name="tab" value="lisensi">
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row">
                <label for="api_secret_key"><?php esc_html_e('API Secret Key', 'mediman'); ?></label>
            </th>
            <td>
                <input type="password" name="api_secret_key" id="api_secret_key" class="regular-text" value="<?php echo esc_attr($saved_secret_key); ?>">
                <p class="description"><?php esc_html_e('Masukkan secret key dari server lisensi Anda.', 'mediman'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="license_key"><?php esc_html_e('Kode Lisensi', 'mediman'); ?></label>
            </th>
            <td>
                <input type="text" name="license_key" id="license_key" class="regular-text" value="<?php echo esc_attr($saved_license); ?>" <?php echo ($is_valid && !empty($saved_license)) ? 'readonly="readonly"' : 'required'; ?>>
                <?php if ($is_valid && !empty($saved_license)) : ?>
                    <span class="dashicons dashicons-yes-alt" style="color:green; vertical-align: middle;"></span>
                <?php endif; ?>
                <p class="description">
                    <?php
                    if (empty($saved_license)) {
                        esc_html_e('Masukkan kode lisensi Anda.', 'mediman');
                    } elseif (!$is_valid) {
                        esc_html_e('Kode lisensi belum diaktifkan.', 'mediman');
                    } else {
                        esc_html_e('Lisensi aktif dan tervalidasi.', 'mediman');
                    }
                    ?>
                </p>
            </td>
        </tr>
        <?php if (current_user_can('manage_options')) : ?>
        <tr>
            <th scope="row"><?php esc_html_e('Pengaturan Pengembang', 'mediman'); ?></th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" name="alm_debug_mode" value="1" <?php checked($debug_mode); ?>>
                        <?php esc_html_e('Mode Debug', 'mediman'); ?>
                    </label>
                    <p class="description"><?php esc_html_e('Hanya untuk troubleshooting. Jangan aktifkan di website live.', 'mediman'); ?></p>
                </fieldset>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <p class="submit">
        <button type="submit" name="mediman_action" value="save_settings" class="button button-primary">
            <?php esc_html_e('Simpan Pengaturan', 'mediman'); ?>
        </button>
        <?php if ($is_valid) : ?>
            <button type="submit" name="mediman_action" value="deactivate_license" class="button">
                <?php esc_html_e('Nonaktifkan Lisensi', 'mediman'); ?>
            </button>
        <?php elseif ($saved_secret_key && $saved_license) : ?>
            <button type="submit" name="mediman_action" value="activate_license" class="button" id="activate-license-btn">
                <?php esc_html_e('Aktifkan Lisensi', 'mediman'); ?>
            </button>
        <?php endif; ?>
        <?php if (current_user_can('manage_options')) : ?>
            <button type="submit" name="test_api" value="1" class="button button-secondary">
                <?php esc_html_e('Test Koneksi API', 'mediman'); ?>
            </button>
        <?php endif; ?>
    </p>
</form>

<?php if ($is_valid) : ?>
    <hr>
    <h2><?php esc_html_e('Status Lisensi', 'mediman'); ?></h2>
    <table class="form-table">
        <tr>
            <th><?php esc_html_e('Status', 'mediman'); ?></th>
            <td><span style="color:green; font-weight:bold;"><?php esc_html_e('Aktif', 'mediman'); ?></span></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Masa Aktif', 'mediman'); ?></th>
            <td>
            <?php
            if (!$expires_date) {
                echo '<strong>' . esc_html__('Berlaku Selamanya', 'mediman') . '</strong>';
            } else {
                $expiry_time = strtotime($expires_date);
                $now = time();
                if ($expiry_time < $now) {
                    echo '<span style="color:red; font-weight:bold;">' . esc_html__('Kedaluwarsa: ', 'mediman') . mediman_format_license_expiry($expires_date) . '</span>';
                } else {
                    echo mediman_format_license_expiry($expires_date);
                }
            }
            ?>
            </td>
        </tr>
    </table>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // UX: Disable submit button during activation to prevent double submit
    var activateBtn = document.getElementById('activate-license-btn');
    if (activateBtn) {
        activateBtn.addEventListener('click', function() {
            activateBtn.disabled = true;
            activateBtn.innerText = 'Memproses...';
            setTimeout(function() {
                activateBtn.disabled = false;
                activateBtn.innerText = '<?php esc_html_e('Aktifkan Lisensi', 'mediman'); ?>';
            }, 6000); // Enable again after 6s if no redirect (fallback only)
        });
    }
});
</script>