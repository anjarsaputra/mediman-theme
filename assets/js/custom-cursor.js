/**
 * Logika Final untuk Kursor Animasi Sederhana
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Jangan jalankan di perangkat sentuh (mobile/tablet)
    if ('ontouchstart' in window) {
        return;
    }

    // 2. Buat elemen kursor
    const cursorOuter = document.createElement('div');
    cursorOuter.className = 'custom-cursor-outer';
    document.body.appendChild(cursorOuter);

    const cursorInner = document.createElement('div');
    cursorInner.className = 'custom-cursor-inner';
    document.body.appendChild(cursorInner);

    // 3. Logika pergerakan kursor
    let mouseX = 0, mouseY = 0;
    let outerX = 0, outerY = 0;
    const speed = 0.15; // Anda bisa atur kecepatan 'delay' di sini (0.1 - 0.2)

    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateCursor() {
        // Kalkulasi posisi kursor luar dengan efek delay
        outerX += (mouseX - outerX) * speed;
        outerY += (mouseY - outerY) * speed;
        cursorOuter.style.transform = `translate(${outerX}px, ${outerY}px)`;

        // Kursor dalam mengikuti mouse secara langsung
        cursorInner.style.transform = `translate(${mouseX}px, ${mouseY}px)`;

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // 4. Efek saat hover ke link atau tombol
    const hoverTargets = 'a, button, .btn, [role="button"], h1, h2, h3, h4, h5, h6';
    document.querySelectorAll(hoverTargets).forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorOuter.classList.add('hover-effect');
        });
        el.addEventListener('mouseleave', () => {
            cursorOuter.classList.remove('hover-effect');
        });
    });

    // Sembunyikan kursor asli
    document.body.style.cursor = 'none';
});