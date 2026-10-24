/**
 * SAE PKL — Landing Page Interactive Script
 * Mengatur toggle menu mobile & navigasi keyboard untuk halaman publik welcome.
 */
export function initLandingPage() {
    const toggle = document.getElementById('navbarToggle');
    const menu = document.getElementById('navbarMenu');
    if (!toggle || !menu) return;

    function toggleMenu() {
        const isOpen = menu.classList.contains('is-open');
        if (isOpen) {
            menu.classList.remove('is-open');
            toggle.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Buka menu navigasi');
        } else {
            menu.classList.add('is-open');
            toggle.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'Tutup menu navigasi');
        }
    }

    toggle.addEventListener('click', toggleMenu);

    // Close menu on link click (mobile)
    const links = menu.querySelectorAll('a');
    links.forEach((link) => {
        link.addEventListener('click', () => {
            if (menu.classList.contains('is-open')) {
                toggleMenu();
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.classList.contains('is-open')) {
            toggleMenu();
            toggle.focus();
        }
    });
}
