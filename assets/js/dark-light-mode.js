document.addEventListener('DOMContentLoaded', function () {
    const lightLogo = document.querySelector('.light-mode-logo');
    const darkLogo = document.querySelector('.dark-mode-logo');
   

    // Fungsi untuk mengganti logo berdasarkan mode preferensi pengguna
    function updateLogo() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Mode gelap
            if (darkLogo) {
                darkLogo.style.display = 'block';
            }
            if (lightLogo) {
                lightLogo.style.display = 'none';
            }
        } else {
            // Mode terang
            if (darkLogo) {
                darkLogo.style.display = 'none';
            }
            if (lightLogo) {
                lightLogo.style.display = 'block';
            }
        }
    }

    // Jalankan saat pertama kali memuat
    updateLogo();

    // Periksa perubahan mode secara real-time
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateLogo);
});
