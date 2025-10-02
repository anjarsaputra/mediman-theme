document.addEventListener('DOMContentLoaded', function() {
    const themeSwitcher = document.getElementById('theme-switcher');
    if (!themeSwitcher) return;
    const themeIcon = document.getElementById('theme-icon');
    const htmlElement = document.documentElement;
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            htmlElement.classList.add('dark');
            if (themeIcon) {
                themeIcon.classList.remove('bi-moon-stars-fill');
                themeIcon.classList.add('bi-sun-fill');
            }
        } else {
            htmlElement.classList.remove('dark');
            if (themeIcon) {
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-stars-fill');
            }
        }
    };
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);
    themeSwitcher.addEventListener('click', function() {
        const newTheme = htmlElement.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    });
});