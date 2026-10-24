/**
 * SAE PKL — Core UI Helpers
 * Modal, Alert Dismiss, Toast, dan Dialog Konfirmasi.
 */

/**
 * Buka modal berdasarkan ID
 * @param {string} modalId
 */
export function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Focus first interactive element
    const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusable) focusable.focus();
}

/**
 * Tutup modal berdasarkan ID
 * @param {string} modalId
 */
export function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Inisialisasi listener global untuk modal (ESC key & backdrop click)
 */
export function initModalListeners() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal-backdrop[style*="display: flex"], .modal-backdrop:not([style*="display: none"])');
            openModals.forEach((modal) => {
                if (modal.id) closeModal(modal.id);
            });
        }
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-backdrop')) {
            if (e.target.id) closeModal(e.target.id);
        }
    });
}

/**
 * Inisialisasi tombol penutup alert/notifikasi via event delegation
 */
export function initAlertDismissers() {
    document.addEventListener('click', (e) => {
        const dismissBtn = e.target.closest('[data-dismiss="alert"]');
        if (dismissBtn) {
            const alert = dismissBtn.closest('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-4px)';
                setTimeout(() => alert.remove(), 200);
            }
        }
    });
}

/**
 * Dialog konfirmasi sederhana sebelum aksi destruktif
 * ponytail: Menggunakan window.confirm bawaan peramban; upgrade ke modal custom saat diperlukan alur kompleks.
 * @param {string} message
 * @param {Function} onConfirm
 */
export function confirmAction(message, onConfirm) {
    if (window.confirm(message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?')) {
        if (typeof onConfirm === 'function') onConfirm();
        return true;
    }
    return false;
}
