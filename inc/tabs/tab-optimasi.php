<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Register and sanitize settings
function mediman_register_optimization_settings() {
    register_setting(
        'mediman_performance_settings_group',
        'mediman_performance_settings',
        array(
            'sanitize_callback' => 'mediman_sanitize_performance_settings',
            'default' => array(
                'defer_js' => false,
                'lazy_load' => false,
                'minify_html' => false,
                'image_compress' => false,
                'webp_support' => false
            )
        )
    );
    add_settings_section(
        'mediman_performance_section',
        '',
        '__return_null',
        'mediman_performance_settings'
    );
}
add_action('admin_init', 'mediman_register_optimization_settings');

function mediman_sanitize_performance_settings($input) {
    if (!is_array($input)) return array();
    $sanitized_input = array();
    $boolean_options = array(
        'defer_js',
        'lazy_load',
        'minify_html',
        'image_compress',
        'webp_support'
    );
    foreach ($boolean_options as $option) {
        $sanitized_input[$option] = isset($input[$option]) ? (bool) $input[$option] : false;
    }
    return $sanitized_input;
}

if ($active_tab === 'optimasi') :
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error('mediman_performance', 'settings_updated', __('Pengaturan berhasil disimpan.', 'mediman'), 'updated');
    }
    settings_errors('mediman_performance');

    $perf_options = get_option('mediman_performance_settings', []);
?>

<div class="mediman-optimization-page">
    <div class="optimization-header">
        <div class="header-content">
            <h2>
                <span class="dashicons dashicons-performance"></span>
                <?php esc_html_e('Pengaturan Performa', 'mediman'); ?>
            </h2>
            <p class="description"><?php esc_html_e('Aktifkan fitur di bawah ini untuk meningkatkan kecepatan. Nonaktifkan jika Anda menggunakan plugin caching.', 'mediman'); ?></p>
        </div>
    </div>
    <div class="mediman-alert warning">
        <span class="dashicons dashicons-warning"></span>
        <div class="alert-content">
            <strong><?php esc_html_e('Peringatan:', 'mediman'); ?></strong>
            <p><?php esc_html_e('Jika Anda menggunakan plugin caching (WP Rocket, dll), harap nonaktifkan semua fitur di bawah ini untuk menghindari konflik.', 'mediman'); ?></p>
        </div>
    </div>
    <form method="post" action="options.php" class="optimization-form">
        <?php 
        settings_fields('mediman_performance_settings_group');
        do_settings_sections('mediman_performance_settings'); 
        ?>
        <!-- Asset Optimization Section -->
        <div class="settings-section">
            <div class="section-header">
                <span class="dashicons dashicons-admin-appearance"></span>
                <h3><?php esc_html_e('Optimasi Aset', 'mediman'); ?></h3>
            </div>
            <div class="settings-grid">
                <div class="setting-card">
                    <div class="setting-header">
                        <label class="switch">
                            <input type="checkbox" id="perf_defer_js" name="mediman_performance_settings[defer_js]" value="1" 
                                <?php checked(isset($perf_options['defer_js']) && $perf_options['defer_js']); ?>>
                            <span class="slider"></span>
                        </label>
                        <label for="perf_defer_js"><?php esc_html_e('Defer JavaScript', 'mediman'); ?></label>
                    </div>
                    <p class="setting-description"><?php esc_html_e('Menunda pemuatan JavaScript yang tidak penting untuk mempercepat rendering halaman.', 'mediman'); ?></p>
                </div>
                <div class="setting-card">
                    <div class="setting-header">
                        <label class="switch">
                            <input type="checkbox" id="perf_minify_html" name="mediman_performance_settings[minify_html]" value="1" 
                                <?php checked(isset($perf_options['minify_html']) && $perf_options['minify_html']); ?>>
                            <span class="slider"></span>
                        </label>
                        <label for="perf_minify_html"><?php esc_html_e('Minify HTML', 'mediman'); ?></label>
                    </div>
                    <p class="setting-description"><?php esc_html_e('Mengoptimasi kode HTML dengan menghapus whitespace dan komentar yang tidak perlu.', 'mediman'); ?></p>
                </div>
            </div>
        </div>
        <!-- Media Optimization Section -->
        <div class="settings-section">
            <div class="section-header">
                <span class="dashicons dashicons-format-image"></span>
                <h3><?php esc_html_e('Optimasi Media', 'mediman'); ?></h3>
            </div>
            <div class="settings-grid">
                <div class="setting-card">
                    <div class="setting-header">
                        <label class="switch">
                            <input type="checkbox" id="perf_lazy_load" name="mediman_performance_settings[lazy_load]" value="1" 
                                <?php checked(isset($perf_options['lazy_load']) && $perf_options['lazy_load']); ?>>
                            <span class="slider"></span>
                        </label>
                        <label for="perf_lazy_load"><?php esc_html_e('Lazy Load Gambar', 'mediman'); ?></label>
                    </div>
                    <p class="setting-description"><?php esc_html_e('Tunda pemuatan gambar hingga user scroll ke posisi gambar.', 'mediman'); ?></p>
                </div>
                <div class="setting-card">
                    <div class="setting-header">
                        <label class="switch">
                            <input type="checkbox" id="perf_image_compress" name="mediman_performance_settings[image_compress]" value="1" 
                                <?php checked(isset($perf_options['image_compress']) && $perf_options['image_compress']); ?>>
                            <span class="slider"></span>
                        </label>
                        <label for="perf_image_compress"><?php esc_html_e('Kompresi Gambar', 'mediman'); ?></label>
                    </div>
                    <p class="setting-description"><?php esc_html_e('Kompres gambar secara otomatis saat diunggah untuk menghemat bandwidth.', 'mediman'); ?></p>
                </div>
                <div class="setting-card">
                    <div class="setting-header">
                        <label class="switch">
                            <input type="checkbox" id="perf_webp_support" name="mediman_performance_settings[webp_support]" value="1" 
                                <?php checked(isset($perf_options['webp_support']) && $perf_options['webp_support']); ?>>
                            <span class="slider"></span>
                        </label>
                        <label for="perf_webp_support"><?php esc_html_e('Dukungan WebP', 'mediman'); ?></label>
                    </div>
                    <p class="setting-description"><?php esc_html_e('Konversi gambar ke format WebP untuk ukuran file lebih kecil.', 'mediman'); ?></p>
                </div>
            </div>
        </div>
        <div class="form-submit">
            <?php submit_button(__('Simpan Pengaturan', 'mediman'), 'primary large'); ?>
        </div>
    </form>
</div>

<style>
.mediman-optimization-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 0;
}
.optimization-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 25px;
    color: white;
}
.optimization-header h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 10px;
    color: white;
}
.optimization-header .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.optimization-header .description {
    margin: 0;
    opacity: 0.9;
    color: #f1f5f9;
}
.mediman-alert {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}
.mediman-alert.warning {
    background: #fff7ed;
    border: 1px solid #ffedd5;
}
.mediman-alert .dashicons {
    color: #f97316;
    font-size: 24px;
}
.alert-content strong {
    display: block;
    margin-bottom: 5px;
    color: #9a3412;
}
.alert-content p {
    margin: 0;
    color: #7c2d12;
}
.settings-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}
.section-header .dashicons {
    color: #4f46e5;
    font-size: 20px;
}
.section-header h3 {
    margin: 0;
    font-size: 18px;
}
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.setting-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
}
.setting-card:hover {
    background: #f1f5f9;
}
.setting-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
}
.setting-header label {
    font-weight: 500;
    color: #1e293b;
}
.setting-description {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e5e7eb;
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #4f46e5;
}
input:checked + .slider:before {
    transform: translateX(26px);
}
.form-submit {
    margin-top: 30px;
    text-align: right;
}
.form-submit .button {
    padding: 8px 20px;
    height: auto;
    font-size: 15px;
}
/* Responsive Design */
@media screen and (max-width: 782px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    .optimization-header {
        padding: 20px;
    }
    .settings-section {
        padding: 20px;
    }
}
</style>
<?php endif; ?>