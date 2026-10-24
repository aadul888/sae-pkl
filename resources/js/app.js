/**
 * SAE PKL — Main JavaScript Entry Point
 */
import { openModal, closeModal, initModalListeners, initAlertDismissers, confirmAction } from './core/helpers.js';
import { defaultDataTableConfig, initDataTable, filterTable, initTableFilters } from './components/table-helper.js';
import { initLandingPage } from './pages/landing.js';

// Expose global SaeUI namespace untuk kemudahan integrasi modular
window.SaeUI = {
    openModal,
    closeModal,
    confirmAction,
    filterTable,
    initDataTable,
    defaultDataTableConfig,
};

// Inisialisasi otomatis listener global saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initModalListeners();
    initAlertDismissers();
    initTableFilters();
    initLandingPage();
});

