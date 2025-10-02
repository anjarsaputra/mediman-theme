document.addEventListener('DOMContentLoaded', function() {
    const readingProgressBar = document.getElementById('reading-progress-bar'); // Pastikan ID ini sesuai dengan di HTML
    const postContent = document.querySelector('.entry-content'); // Targetkan area konten postingan

    if (!readingProgressBar || !postContent) return;

    function updateProgressBar() {
        const contentRect = postContent.getBoundingClientRect();
        const contentTop = contentRect.top;
        const contentHeight = contentRect.height;
        const viewportHeight = window.innerHeight;

        let progress = 0;
        if (contentTop < viewportHeight) {
            progress = ((viewportHeight - contentTop) / contentHeight) * 100;
        }
        progress = Math.min(Math.max(progress, 0), 100); // Batasi antara 0 dan 100
        readingProgressBar.style.width = progress + '%';
    }

    window.addEventListener('scroll', updateProgressBar);
    window.addEventListener('resize', updateProgressBar);
});