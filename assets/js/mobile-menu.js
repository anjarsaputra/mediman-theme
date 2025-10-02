/**
 * Mengontrol perilaku buka-tutup sub-menu di off-canvas mobile
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuItemsWithChildren = document.querySelectorAll('#offcanvasMobileMenu .menu-item-has-children > a');

    menuItemsWithChildren.forEach(function(menuItem) {
        if (!menuItem.querySelector('.dropdown-caret')) {
            menuItem.innerHTML += '<i class="bi bi-chevron-down dropdown-caret"></i>';
        }

        menuItem.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            const parentLi = this.parentElement;
            const subMenu = parentLi.querySelector('.sub-menu');
            parentLi.classList.toggle('open');
            
            const caret = this.querySelector('.dropdown-caret');
            if (parentLi.classList.contains('open')) {
                caret.classList.remove('bi-chevron-down');
                caret.classList.add('bi-chevron-up');
            } else {
                caret.classList.remove('bi-chevron-up');
                caret.classList.add('bi-chevron-down');
            }
            
            if (subMenu) {
                if (subMenu.style.maxHeight) {
                    subMenu.style.maxHeight = null;
                } else {
                    subMenu.style.maxHeight = subMenu.scrollHeight + "px";
                }
            }
        });
    });
});