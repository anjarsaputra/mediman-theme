<?php
if (!defined('ABSPATH')) {
    exit;
}

// Cache stats dalam transient
$stats_cache = get_transient('mediman_dashboard_stats');
if (false === $stats_cache) {
    $stats_cache = array(
        'posts' => wp_count_posts('post')->publish,
        'categories' => wp_count_terms('category'),
        'memory_limit' => ini_get('memory_limit'),
        'upload_max' => ini_get('upload_max_filesize')
    );
    set_transient('mediman_dashboard_stats', $stats_cache, HOUR_IN_SECONDS);
}
?>

<!-- Panel Selamat Datang -->
<div class="welcome-panel-simple">
    <h2>👋 <?php esc_html_e('Terima kasih telah menggunakan Mediman!', 'mediman'); ?></h2>
</div>

<!-- Statistik -->
<div class="stats-grid">
    <div class="stat-card wordpress">
        <div class="stat-icon">
            <span class="dashicons dashicons-wordpress"></span>
        </div>
        <div class="stat-content">
            <h3><?php echo esc_html($wp_version); ?></h3>
            <p><?php esc_html_e('WordPress', 'mediman'); ?></p>
        </div>
    </div>
    
    <div class="stat-card theme">
        <div class="stat-icon">
            <span class="dashicons dashicons-admin-appearance"></span>
        </div>
        <div class="stat-content">
            <h3><?php echo esc_html($theme->get('Version')); ?></h3>
            <p><?php esc_html_e('Tema', 'mediman'); ?></p>
        </div>
    </div>
    
    <div class="stat-card posts">
        <div class="stat-icon">
            <span class="dashicons dashicons-edit"></span>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format_i18n($stats_cache['posts']); ?></h3>
            <p><?php esc_html_e('Artikel', 'mediman'); ?></p>
        </div>
    </div>
    
    <div class="stat-card categories">
        <div class="stat-icon">
            <span class="dashicons dashicons-category"></span>
        </div>
        <div class="stat-content">
            <h3><?php echo number_format_i18n($stats_cache['categories']); ?></h3>
            <p><?php esc_html_e('Kategori', 'mediman'); ?></p>
        </div>
    </div>
</div>

<style>
.mediman-dashboard-status {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin: 20px 0;
    overflow: hidden;
}
.welcome-panel-simple {
    background: #4f46e5;
    border-radius: 10px;
    padding: 16px 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    margin-bottom: 20px;
}
.welcome-panel-simple h2 {
    margin: 0;
    font-size: 16px;
    color: #ffffffff;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
.stat-icon {
    background: #f3f4f6;
    border-radius: 12px;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon .dashicons {
    font-size: 24px;
    width: auto;
    height: auto;
    color: #4f46e5;
}
.stat-content h3 {
    font-size: 24px;
    margin: 0 0 5px;
    color: #1f2937;
}
.stat-content p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}
@media screen and (max-width: 782px) {
    .welcome-panel-modern {
        padding: 30px;
    }
    .welcome-panel-actions {
        flex-direction: column;
    }
    .action-button {
        width: 100%;
        justify-content: center;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>