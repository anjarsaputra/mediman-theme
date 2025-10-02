jQuery(document).ready(function($) {
    // Jalankan kode hanya jika kita berada di halaman OCDI
    if ( $('body').hasClass('appearance_page_one-click-demo-import') ) {

        // ========================================================================
        // BAGIAN 1: Menambahkan Tombol "Live Preview" di Halaman Awal
        // ========================================================================
        
        var previewUrl = 'https://aradevweb.com/mediman';
        var buttonContainer = $('.ocdi__button-container');

        // Cek jika kontainer tombol utama ada (ini hanya ada di halaman pertama)
        if ( buttonContainer.length ) {
            var previewButtonHTML = '<a href="' + previewUrl + '" target="_blank" class="ocdi__button button button-secondary" style="margin-right: 10px;">Live Preview</a>';
            
            // Tambahkan tombol "Live Preview" di sebelah kiri tombol "Import"
            buttonContainer.prepend(previewButtonHTML);
        }

        // ========================================================================
        // BAGIAN 2: Mengganti Teks di Halaman Konfirmasi (Langkah 2)
        // ========================================================================

        // Cari kontainer utama halaman konfirmasi
        var confirmationPage = $('.ocdi-install-plugins-content');

        // Cek jika kontainer konfirmasi ada
        if ( confirmationPage.length ) {
            // Ganti Judul Utama (H2)
            confirmationPage.find('.ocdi-install-plugins-content-header h2').text('Satu Langkah Terakhir Sebelum Impor');

            // Ganti Deskripsi (p)
            confirmationPage.find('.ocdi-install-plugins-content-header p').text('Proses ini akan mengimpor konten, gambar, dan pengaturan agar situs Anda terlihat seperti demo. Pastikan Anda sudah memiliki backup data sebelumnya.');

            // Ganti Teks Tombol "Continue & Import"
            confirmationPage.find('.js-ocdi-install-plugins-before-import').text('Ya, Lanjutkan Impor');

            // Ganti Teks Tombol "Go Back"
            confirmationPage.find('.button:not(.button-primary) span').text('Kembali');
        }
    }
});