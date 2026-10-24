/**
 * SAE PKL — Standard Table & DataTables Helper
 * Menyediakan konfigurasi terpusat Bahasa Indonesia dan helper filter tabel.
 */

/**
 * Konfigurasi standar DataTables berbahasa Indonesia.
 * ponytail: Disiapkan untuk integrasi DataTables; modul tinggal memanggil initDataTable(selector, options).
 */
export const defaultDataTableConfig = {
    language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        zeroRecords: "Tidak ada data yang cocok",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(disaring dari _MAX_ total data)",
        paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
        }
    },
    pageLength: 25,
    responsive: true,
    autoWidth: false
};

/**
 * Inisialisasi DataTables terstandar jika library tersedia
 * @param {string|HTMLElement} selector
 * @param {Object} options
 * @returns {Object|null}
 */
export function initDataTable(selector, options = {}) {
    if (typeof window.DataTable === 'function') {
        const mergedOptions = { ...defaultDataTableConfig, ...options };
        return new window.DataTable(selector, mergedOptions);
    }

    if (window.$ && typeof window.$.fn?.DataTable === 'function') {
        const mergedOptions = { ...defaultDataTableConfig, ...options };
        return window.$(selector).DataTable(mergedOptions);
    }

    // Library DataTables belum dimuat pada halaman ini
    console.info(`[SaeUI] DataTables library belum dimuat untuk selector: ${selector}. Menggunakan tabel HTML standar.`);
    return null;
}

/**
 * Filter tabel client-side native tanpa dependensi pihak ketiga
 * @param {string|HTMLTableElement} tableSelector
 * @param {string} query
 */
export function filterTable(tableSelector, query) {
    const table = typeof tableSelector === 'string' ? document.querySelector(tableSelector) : tableSelector;
    if (!table) return;

    const term = (query || '').toLowerCase().trim();
    const rows = table.querySelectorAll('tbody tr:not(.empty-state-row)');

    let visibleCount = 0;
    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        const matches = text.includes(term);
        row.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
    });

    // Toggle tampilan empty state bila ada
    const emptyStateRow = table.querySelector('.empty-state-row');
    if (emptyStateRow) {
        emptyStateRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
}

/**
 * Inisialisasi otomatis filter tabel via atribut data-table-filter
 */
export function initTableFilters() {
    document.addEventListener('input', (e) => {
        const filterInput = e.target.closest('[data-table-filter]');
        if (filterInput) {
            const targetTable = filterInput.getAttribute('data-table-filter');
            filterTable(targetTable, filterInput.value);
        }
    });
}
