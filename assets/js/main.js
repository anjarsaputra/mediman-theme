jQuery(document).ready(function($) {

    // Script untuk News Ticker
    var ticker = $('#news-ticker');
    if (ticker.length && ticker.find('li').length > 1) {
        setInterval(function() {
            ticker.find('li:first').animate({
                marginTop: '-' + ticker.height() + 'px'
            }, 500, function() {
                $(this).detach().appendTo(ticker).removeAttr('style');
            });
        }, 3000);
    }

    // Script untuk Scroll to Top
    var scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        };
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Script untuk Dark Mode Switcher
    const switchButton = document.getElementById('theme-switcher');
    if (switchButton) {
        const body = document.body;
        const themeIcon = document.getElementById('theme-icon');
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            body.classList.add('dark-mode');
            if (themeIcon) themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
        }
        switchButton.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            const isNowDark = body.classList.contains('dark-mode');
            if (themeIcon) {
                themeIcon.classList.replace(
                    isNowDark ? 'bi-sun-fill' : 'bi-moon-stars-fill',
                    isNowDark ? 'bi-moon-stars-fill' : 'bi-sun-fill'
                );
            }
            localStorage.setItem('darkMode', isNowDark.toString());
        });
    }

    // Script untuk scrolling table of content
    document.querySelectorAll('.toc-list a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({ top: target.offsetTop, behavior: 'smooth' });
            }
        });
    });

    // Script untuk Reading Progress
    var readingProgressBar = document.getElementById('reading-progress');
    if (readingProgressBar) {
        document.addEventListener('scroll', function () {
            let scrollTop = window.scrollY || document.documentElement.scrollTop;
            let docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let progress = (scrollTop / docHeight) * 100;
            readingProgressBar.style.width = progress + '%';
        });
    }

}); // Penutup jQuery(document).ready