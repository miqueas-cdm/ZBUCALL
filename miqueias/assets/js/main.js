// Portal do Associado - Main JavaScript

// Theme Management
const ThemeManager = {
    init() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(savedTheme);
        this.attachListeners();
    },

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('theme', theme);
        this.updateThemeIcon(theme);
    },

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    },

    updateThemeIcon(theme) {
        const themeIcon = document.getElementById('theme-icon');
        if (themeIcon) {
            themeIcon.setAttribute('data-lucide', theme === 'dark' ? 'sun' : 'moon');
            if (window.lucide) lucide.createIcons();
        }
    },

    attachListeners() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }
};

// Sidebar Management
const SidebarManager = {
    init() {
        this.sidebar = document.getElementById('sidebar');
        this.overlay = document.getElementById('sidebar-overlay');
        this.attachListeners();
    },

    toggle() {
        if (this.sidebar) {
            this.sidebar.classList.toggle('-translate-x-full');
            if (this.overlay) {
                this.overlay.classList.toggle('hidden');
            }
        }
    },

    close() {
        if (this.sidebar) {
            this.sidebar.classList.add('-translate-x-full');
            if (this.overlay) {
                this.overlay.classList.add('hidden');
            }
        }
    },

    attachListeners() {
        const menuButton = document.getElementById('mobile-menu-button');
        if (menuButton) {
            menuButton.addEventListener('click', () => this.toggle());
        }

        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.close());
        }

        // Close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.close();
            }
        });
    }
};

// Notification Manager
const NotificationManager = {
    unreadCount: 0,

    async init() {
        await this.loadNotifications();
        this.attachListeners();
    },

    async loadNotifications() {
        try {
            const response = await fetch('api/notifications.php?action=list&unread_only=true');
            const data = await response.json();

            if (data.success) {
                this.unreadCount = data.unread_count || 0;
                this.updateBadge();
                this.renderNotifications(data.notifications || []);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    },

    updateBadge() {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            badge.textContent = this.unreadCount;
            badge.classList.toggle('hidden', this.unreadCount === 0);
        }
    },

    renderNotifications(notifications) {
        const container = document.getElementById('notifications-list');
        if (!container) return;

        if (notifications.length === 0) {
            container.innerHTML = '<p class="p-4 text-center text-gray-500">Nenhuma notificação</p>';
            return;
        }

        container.innerHTML = notifications.slice(0, 5).map(notif => `
            <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700"
                 onclick="NotificationManager.markAsRead(${notif.id})">
                <div class="flex items-start gap-3">
                    <i data-lucide="${notif.icon || 'bell'}" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${notif.title}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">${notif.message}</p>
                        <p class="text-xs text-gray-400 mt-1">${this.formatDate(notif.created_at)}</p>
                    </div>
                </div>
            </div>
        `).join('');

        if (window.lucide) lucide.createIcons();
    },

    async markAsRead(id) {
        try {
            const formData = new FormData();
            formData.append('action', 'mark_read');
            formData.append('id', id);

            const response = await fetch('api/notifications.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                await this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    },

    async markAllAsRead() {
        try {
            const formData = new FormData();
            formData.append('action', 'mark_all_read');

            const response = await fetch('api/notifications.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.unreadCount = 0;
                this.updateBadge();
                await this.loadNotifications();
                Toast.show('Todas as notificações marcadas como lidas', 'success');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            Toast.show('Erro ao marcar notificações', 'error');
        }
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Agora';
        if (diffMins < 60) return `${diffMins}m atrás`;
        if (diffHours < 24) return `${diffHours}h atrás`;
        if (diffDays < 7) return `${diffDays}d atrás`;

        return date.toLocaleDateString('pt-BR');
    },

    attachListeners() {
        const bellButton = document.getElementById('notification-bell');
        const dropdown = document.getElementById('notifications-dropdown');

        if (bellButton && dropdown) {
            bellButton.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target) && !bellButton.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    }
};

// Global Search
const SearchManager = {
    debounceTimer: null,

    init() {
        const searchInput = document.getElementById('global-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    this.search(e.target.value);
                }, 300);
            });
        }
    },

    async search(query) {
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        try {
            const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.success) {
                this.showResults(data.results || []);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    },

    showResults(results) {
        const container = document.getElementById('search-results');
        if (!container) return;

        if (results.length === 0) {
            container.innerHTML = '<p class="p-4 text-sm text-gray-500">Nenhum resultado encontrado</p>';
            container.classList.remove('hidden');
            return;
        }

        container.innerHTML = results.map(result => `
            <a href="${result.url}" class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                <div class="flex items-center gap-3">
                    <i data-lucide="${result.icon}" class="w-4 h-4 text-gray-400"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${result.title}</p>
                        <p class="text-xs text-gray-500 truncate">${result.description || ''}</p>
                    </div>
                </div>
            </a>
        `).join('');

        container.classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    },

    hideResults() {
        const container = document.getElementById('search-results');
        if (container) {
            container.classList.add('hidden');
        }
    }
};

// Toast Notifications
const Toast = {
    show(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 fade-in ${this.getTypeClass(type)}`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    getTypeClass(type) {
        const classes = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        return classes[type] || classes.info;
    }
};

// Utility: Format currency
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Utility: Format date
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('pt-BR');
}

// Initialize on DOM content loaded
document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    SidebarManager.init();
    NotificationManager.init();
    SearchManager.init();

    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }

    PageTransitionManager.init();
});

// Page Transitions
const PageTransitionManager = {
    init() {
        // Fade in on load
        document.body.classList.add('loaded');

        // Handle links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && link.href.startsWith(window.location.origin) && !link.target && !link.href.includes('#')) {
                e.preventDefault();
                document.body.classList.add('fade-out');
                setTimeout(() => {
                    window.location.href = link.href;
                }, 500);
            }
        });
    }
};

// Export for use in other scripts
window.Toast = Toast;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
