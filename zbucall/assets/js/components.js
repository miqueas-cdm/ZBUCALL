// Portal do Associado - Reusable Components

// Loading Skeleton Component
const LoadingSkeleton = {
    card() {
        return `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="skeleton h-6 w-1/3 mb-4"></div>
                <div class="skeleton h-4 w-full mb-2"></div>
                <div class="skeleton h-4 w-5/6"></div>
            </div>
        `;
    },

    table(rows = 5) {
        return `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                ${Array(rows).fill(0).map(() => `
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="skeleton h-4 w-full mb-2"></div>
                        <div class="skeleton h-3 w-3/4"></div>
                    </div>
                `).join('')}
            </div>
        `;
    },

    list(items = 3) {
        return Array(items).fill(0).map(() => `
            <div class="flex items-center gap-4 p-4">
                <div class="skeleton h-12 w-12 rounded-full"></div>
                <div class="flex-1">
                    <div class="skeleton h-4 w-1/2 mb-2"></div>
                    <div class="skeleton h-3 w-3/4"></div>
                </div>
            </div>
        `).join('');
    }
};

// Modal Component
class Modal {
    constructor(id, title, content, options = {}) {
        this.id = id;
        this.title = title;
        this.content = content;
        this.options = {
            size: 'md', // sm, md, lg, xl
            closeable: true,
            footer: null,
            ...options
        };
        this.element = null;
    }

    render() {
        const sizeClasses = {
            sm: 'max-w-md',
            md: 'max-w-2xl',
            lg: 'max-w-4xl',
            xl: 'max-w-6xl'
        };

        const modalHTML = `
            <div id="${this.id}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full ${sizeClasses[this.options.size]} max-h-[90vh] overflow-hidden slide-in-right">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">${this.title}</h3>
                        ${this.options.closeable ? `
                            <button onclick="Modal.close('${this.id}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        ` : ''}
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        ${this.content}
                    </div>
                    ${this.options.footer ? `
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            ${this.options.footer}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        const template = document.createElement('div');
        template.innerHTML = modalHTML;
        this.element = template.firstElementChild;
        document.body.appendChild(this.element);

        if (window.lucide) lucide.createIcons();

        return this;
    }

    show() {
        if (this.element) {
            this.element.classList.remove('hidden');
            this.element.classList.add('flex');
        }
        return this;
    }

    hide() {
        if (this.element) {
            this.element.classList.add('hidden');
            this.element.classList.remove('flex');
        }
        return this;
    }

    static close(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
}

// Confirmation Dialog
const Confirm = {
    show(message, onConfirm, options = {}) {
        const { title = 'Confirmação', confirmText = 'Confirmar', cancelText = 'Cancelar' } = options;

        const modal = new Modal(
            'confirm-modal',
            title,
            `<p class="text-gray-700 dark:text-gray-300">${message}</p>`,
            {
                size: 'sm',
                footer: `
                    <div class="flex gap-3 justify-end">
                        <button onclick="Modal.close('confirm-modal')" 
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            ${cancelText}
                        </button>
                        <button onclick="Confirm.handleConfirm()" 
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            ${confirmText}
                        </button>
                    </div>
                `
            }
        );

        this.currentCallback = onConfirm;
        modal.render().show();
    },

    handleConfirm() {
        if (this.currentCallback) {
            this.currentCallback();
        }
        Modal.close('confirm-modal');
    }
};

// Data Table Component
class DataTable {
    constructor(containerId, data, columns, options = {}) {
        this.containerId = containerId;
        this.data = data;
        this.columns = columns;
        this.options = {
            searchable: true,
            sortable: true,
            pagination: true,
            perPage: 10,
            ...options
        };
        this.currentPage = 1;
        this.sortColumn = null;
        this.sortDirection = 'asc';
        this.searchQuery = '';
    }

    render() {
        const container = document.getElementById(this.containerId);
        if (!container) return;

        const filteredData = this.getFilteredData();
        const paginatedData = this.getPaginatedData(filteredData);

        const html = `
            ${this.options.searchable ? this.renderSearch() : ''}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            ${this.columns.map(col => `
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    onclick="dataTable.sort('${col.field}')">
                                    ${col.label}
                                    ${this.sortColumn === col.field ? (this.sortDirection === 'asc' ? '↑' : '↓') : ''}
                                </th>
                            `).join('')}
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        ${paginatedData.map(row => `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                ${this.columns.map(col => `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        ${col.render ? col.render(row[col.field], row) : row[col.field]}
                                    </td>
                                `).join('')}
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            ${this.options.pagination ? this.renderPagination(filteredData.length) : ''}
        `;

        container.innerHTML = html;
    }

    renderSearch() {
        return `
            <div class="mb-4">
                <input type="text" 
                       placeholder="Buscar..." 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       oninput="dataTable.search(this.value)">
            </div>
        `;
    }

    renderPagination(total) {
        const totalPages = Math.ceil(total / this.options.perPage);
        if (totalPages <= 1) return '';

        return `
            <div class="flex items-center justify-between mt-4">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Mostrando ${(this.currentPage - 1) * this.options.perPage + 1} a ${Math.min(this.currentPage * this.options.perPage, total)} de ${total} resultados
                </p>
                <div class="flex gap-2">
                    <button ${this.currentPage === 1 ? 'disabled' : ''} 
                            onclick="dataTable.goToPage(${this.currentPage - 1})"
                            class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50">
                        Anterior
                    </button>
                    <button ${this.currentPage === totalPages ? 'disabled' : ''} 
                            onclick="dataTable.goToPage(${this.currentPage + 1})"
                            class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50">
                        Próxima
                    </button>
                </div>
            </div>
        `;
    }

    getFilteredData() {
        if (!this.searchQuery) return this.data;

        return this.data.filter(row => {
            return this.columns.some(col => {
                const value = row[col.field];
                return value && value.toString().toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        });
    }

    getPaginatedData(data) {
        if (!this.options.pagination) return data;

        const start = (this.currentPage - 1) * this.options.perPage;
        const end = start + this.options.perPage;
        return data.slice(start, end);
    }

    sort(field) {
        if (this.sortColumn === field) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = field;
            this.sortDirection = 'asc';
        }

        this.data.sort((a, b) => {
            const aVal = a[field];
            const bVal = b[field];
            const modifier = this.sortDirection === 'asc' ? 1 : -1;

            if (aVal < bVal) return -1 * modifier;
            if (aVal > bVal) return 1 * modifier;
            return 0;
        });

        this.render();
    }

    search(query) {
        this.searchQuery = query;
        this.currentPage = 1;
        this.render();
    }

    goToPage(page) {
        this.currentPage = page;
        this.render();
    }
}

// Export components
window.LoadingSkeleton = LoadingSkeleton;
window.Modal = Modal;
window.Confirm = Confirm;
window.DataTable = DataTable;
