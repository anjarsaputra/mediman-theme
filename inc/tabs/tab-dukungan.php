<?php
/**
 * Tab Dukungan - Mediman Theme
 * File: tab-dukungan.php
 */

if (!defined('ABSPATH')) exit;
?>

<style>
.mediman-support-table th {
    width: 180px;
    vertical-align: top;
    padding-top: 14px;
}
.mediman-support-table .mediman-support-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #007cba;
    color: #fff !important;
    border: none;
    padding: 7px 18px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 15px;
    transition: background 0.2s;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.mediman-support-table .mediman-support-btn:hover {
    background: #005177;
    color: #fff !important;
    text-decoration: none;
}
.mediman-support-table .mediman-support-list {
    margin: 0 0 0.5em 1em;
    padding-left: 18px;
    list-style: disc;
}
.mediman-support-table .description {
    margin-top: 3px;
    color: #666;
    font-size: 13px;
}
@media (max-width: 600px) {
    .mediman-support-table th,
    .mediman-support-table td {
        display: block;
        width: 100%;
    }
    .mediman-support-table tr {
        margin-bottom: 1em;
        display: block;
    }
}
</style>

<div class="notice notice-info inline" style="margin-bottom:1.5em;">
    <h3 style="margin: 0.5em 0;"><?php esc_html_e('Dukungan & Bantuan Teknis', 'mediman'); ?></h3>
    <p>
        <?php esc_html_e('Jika Anda mengalami masalah, membutuhkan bantuan teknis, atau ingin bertanya seputar theme Mediman, silakan gunakan salah satu opsi dukungan di bawah ini.', 'mediman'); ?>
    </p>
</div>

<table class="form-table mediman-support-table" role="presentation">
    <tr>
        <th scope="row"><?php esc_html_e('Panduan Pengguna', 'mediman'); ?></th>
        <td>
            <a href="https://aradevweb.com/dokumentasi/mediman" target="_blank" class="mediman-support-btn">
                <span class="dashicons dashicons-book"></span>
                <?php esc_html_e('Lihat Dokumentasi', 'mediman'); ?>
            </a>
            <div class="description"><?php esc_html_e('Panduan lengkap penggunaan, instalasi, aktivasi lisensi, dan setting theme.', 'mediman'); ?></div>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Grup Diskusi', 'mediman'); ?></th>
        <td>
            <a href="https://t.me/aradevweb" target="_blank" class="mediman-support-btn" style="background:#229ED9;">
                <span class="dashicons dashicons-groups"></span>
                <?php esc_html_e('Join Grup Telegram', 'mediman'); ?>
            </a>
            <div class="description"><?php esc_html_e('Diskusi, tanya jawab, dan update terbaru seputar theme.', 'mediman'); ?></div>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Kontak Support', 'mediman'); ?></th>
        <td>
            <a href="https://wa.me/6281234567890" target="_blank" class="mediman-support-btn" style="background:#25D366;">
                <span class="dashicons dashicons-whatsapp"></span>
                <?php esc_html_e('Chat WhatsApp', 'mediman'); ?>
            </a>
            <div class="description"><?php esc_html_e('Hubungi kami via WhatsApp untuk bantuan teknis & pertanyaan.', 'mediman'); ?></div>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php esc_html_e('Syarat & Batasan Dukungan', 'mediman'); ?></th>
        <td>
            <ul class="mediman-support-list">
                <li><?php esc_html_e('Support hanya untuk user dengan lisensi aktif.', 'mediman'); ?></li>
                <li><?php esc_html_e('Tidak meliputi permintaan custom coding, desain, atau modifikasi besar.', 'mediman'); ?></li>
                <li><?php esc_html_e('Dukungan hanya untuk theme asli, bukan versi bajakan.', 'mediman'); ?></li>
                <li><?php esc_html_e('Update theme & fitur hanya untuk lisensi yang masih aktif.', 'mediman'); ?></li>
            </ul>
        </td>
    </tr>
</table>