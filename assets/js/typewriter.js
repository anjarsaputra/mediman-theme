document.addEventListener('DOMContentLoaded', function() {
    if (typeof tickerData === 'undefined' || tickerData.length === 0) {
        return;
    }

    const textElement = document.getElementById('typewriter-text');
    const linkElement = document.getElementById('typewriter-link');
    const prevButton = document.getElementById('ticker-prev');
    const nextButton = document.getElementById('ticker-next');
    
    let postIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let timeoutId; // Untuk menyimpan ID dari setTimeout

    function type() {
        if (!textElement || !linkElement) return;
        
        const currentPost = tickerData[postIndex];
        const currentText = currentPost.title;
        linkElement.href = currentPost.link;

        if (isDeleting) {
            textElement.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;
        } else {
            textElement.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;
        }

        let typeSpeed = isDeleting ? 10 : 10;

        if (!isDeleting && charIndex === currentText.length) {
            typeSpeed = 3000; // Jeda setelah selesai mengetik
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            postIndex = (postIndex + 1) % tickerData.length;
            typeSpeed = 300;
        }

        timeoutId = setTimeout(type, typeSpeed);
    }

    function changeSlide(direction) {
        clearTimeout(timeoutId); // Hentikan auto-play saat tombol diklik
        isDeleting = false;
        charIndex = 0;
        
        if (direction === 'next') {
            postIndex = (postIndex + 1) % tickerData.length;
        } else {
            postIndex = (postIndex - 1 + tickerData.length) % tickerData.length;
        }
        type(); // Mulai mengetik judul yang baru
    }

    // Tambahkan event listener jika tombol ada
    if (nextButton) {
        nextButton.addEventListener('click', () => changeSlide('next'));
    }
    if (prevButton) {
        prevButton.addEventListener('click', () => changeSlide('prev'));
    }

    // Mulai auto-play
    type();
});