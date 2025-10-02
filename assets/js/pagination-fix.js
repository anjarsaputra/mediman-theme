/**
 * Memperbaiki masalah link pagination yang tidak bisa diklik di mobile.
 * Versi ini menggunakan event delegation yang lebih andal.
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Alih-alih menargetkan link satu per satu, kita targetkan pembungkusnya.
    const paginationWrap = document.querySelector('.posts-pagination-wrap');

    // Jika area pagination tidak ada, hentikan.
    if (!paginationWrap) {
        return;
    }

    // Fungsi untuk menangani klik atau sentuhan.
    const handlePaginationClick = function(event) {
        // Cari elemen <a> terdekat dari elemen yang diklik/disentuh.
        const link = event.target.closest('a.page-numbers');
        
        // Jika yang diklik memang link pagination...
        if (link) {
            // 1. Mencegah perilaku default (yang mungkin diblokir).
            event.preventDefault();

            // 2. Ambil URL tujuan dari link tersebut.
            const destinationUrl = link.getAttribute('href');

            // 3. Paksa browser untuk pindah ke URL tujuan.
            if (destinationUrl) {
                window.location.href = destinationUrl;
            }
        }
    };

    // Tambahkan event listener yang mendengarkan baik 'click' maupun 'touchend' (untuk mobile).
    paginationWrap.addEventListener('click', handlePaginationClick);
    paginationWrap.addEventListener('touchend', handlePaginationClick);

});