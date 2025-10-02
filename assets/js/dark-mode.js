document.addEventListener('DOMContentLoaded', function() {
    const switchToggle = document.getElementById('darkModeSwitch');
    const currentTheme = localStorage.getItem('theme');

    // Cek apakah ada tema tersimpan di localStorage
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        switchToggle.checked = true;
    }

    // Tambahkan event listener untuk toggle switch
    switchToggle.addEventListener('change', function() {
        if (switchToggle.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });
});
