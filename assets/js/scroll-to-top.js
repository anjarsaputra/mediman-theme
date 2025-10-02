/**
 * Logika untuk Tombol Scroll to Top
 */
document.addEventListener('DOMContentLoaded', function() {
    const scrollTopBtn = document.getElementById('scrollToTopBtn');

    // Jika tombol tidak ada di halaman, hentikan skrip
    if (!scrollTopBtn) {
        return;
    }

    // Tampilkan tombol saat pengguna scroll ke bawah sejauh 200px
    window.onscroll = function() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            scrollTopBtn.style.display = "block";
            setTimeout(() => {
                scrollTopBtn.style.opacity = "1";
            }, 10);
        } else {
            scrollTopBtn.style.opacity = "0";
            setTimeout(() => {
                scrollTopBtn.style.display = "none";
            }, 300); // Sesuaikan dengan durasi transisi di CSS
        }
    };

    // Saat tombol diklik, scroll ke atas dengan mulus
    scrollTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});