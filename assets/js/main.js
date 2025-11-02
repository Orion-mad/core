/**
 * Sistema de Gestión - JavaScript Principal CORREGIDO
 * Manejo definitivo de logout sin duplicados
 */

// Objeto principal del sistema
const SistemaGestion = {
    
    // Flag para evitar múltiples inicializaciones
    initialized: false,
    
    // Inicialización
    init() {
        if (this.initialized) return; // Evitar múltiples inicializaciones
        
        this.setupEventListeners();
        this.setupNotifications();
        this.setupTableSearch();
        this.setupFormValidation();
        this.setupTooltips();
        this.setupModals();
        this.setupLogout(); // Manejo centralizado de logout
        
        this.initialized = true;
    },

    // Configurar logout DEFINITIVO
    setupLogout() {
        // Remover event listeners previos para evitar duplicados
        this.removeExistingLogoutListeners();
        
        // Configurar manejo único de logout
        this.attachLogoutListeners();
    },
    
    // Remover listeners existentes
    removeExistingLogoutListeners() {
        // Remover todos los event listeners de logout existentes
        const logoutElements = document.querySelectorAll('a[href*="action=logout"], [data-action="logout"]');
        logoutElements.forEach(element => {
            // Clonar elemento para remover todos los event listeners
            const newElement = element.cloneNode(true);
            element.parentNode.replaceChild(newElement, element);
        });
    },
    
    // Adjuntar listeners únicos
    attachLogoutListeners() {
        // Usar delegación de eventos para evitar duplicados
        document.addEventListener('click', this.handleLogoutClick.bind(this), true);
    },
    
    // Manejar click de logout
    handleLogoutClick(e) {
        const target = e.target.closest('a[href*="action=logout"], [data-action="logout"]');
        
        if (target) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            this.confirmLogout();
            return false;
        }
    },

    // Confirmar logout
    confirmLogout() {
        // Usar confirm nativo para evitar duplicados
        if (confirm('¿Está seguro de que desea cerrar sesión?')) {
            this.showNotification('Cerrando sesión...', 'info', 1000);
            
            // Pequeño delay para mostrar la notificación
            setTimeout(() => {
                window.location.href = 'index.php?action=logout';
            }, 500);
        }
    },

    // Event listeners principales
    setupEventListeners() {
        // Manejar navegación
        this.setupNavigation();
        
        // Manejar forms
        this.setupForms();
        
        // Manejar botones de confirmación (EXCEPTO logout)
        this.setupConfirmButtons();
    },

    // Configurar navegación
    setupNavigation() {
        // Marcar item activo en navegación
        const currentPath = window.location.pathname + window.location.search;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.href && currentPath.includes(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });

        // Manejar colapso de sidebar en móvil
        const sidebarToggle = document.getElementById('sidebar-toggle') || document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                
                if (sidebar) sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('show');
                
                // Para layout antiguo
                document.body.classList.toggle('sidebar-collapsed');
            });
        }
    },

    // Configurar formularios
    setupForms() {
        const forms = document.querySelectorAll('form[data-ajax]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitForm(form);
            });
        });
    },

    // Configurar botones de confirmación (SIN logout)
    setupConfirmButtons() {
        document.addEventListener('click', (e) => {
            const confirmButton = e.target.closest('[data-confirm]:not([href*="logout"]):not([data-action="logout"])');
            
            if (confirmButton) {
                const message = confirmButton.getAttribute('data-confirm');
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });

        // Botones de confirmación para eliminar
        document.addEventListener('click', (e) => {
            const deleteButton = e.target.closest('[data-confirm-delete]');
            
            if (deleteButton) {
                if (!confirm('¿Está seguro de que desea eliminar este elemento? Esta acción no se puede deshacer.')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });
    },

    // Sistema de notificaciones
    setupNotifications() {
        // Crear contenedor si no existe
        if (!document.getElementById('notifications-container')) {
            const container = document.createElement('div');
            container.id = 'notifications-container';
            container.className = 'notifications-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
    },

    // Mostrar notificación
    showNotification(message, type = 'info', duration = 5000) {
        const container = document.getElementById('notifications-container');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            padding: 16px;
            position: relative;
            animation: slideIn 0.3s ease-out;
            border-left: 4px solid ${this.getNotificationColor(type)};
        `;
        
        const iconMap = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };

        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-weight: bold; color: ${this.getNotificationColor(type)};">${iconMap[type] || iconMap.info}</span>
                <span style="flex: 1;">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; opacity: 0.5;">×</button>
            </div>
        `;

        container.appendChild(notification);

        // Auto-remover después del tiempo especificado
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }
    },
    
    // Obtener color de notificación
    getNotificationColor(type) {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };
        return colors[type] || colors.info;
    },

    // Búsqueda en tablas
    setupTableSearch() {
        const searchInputs = document.querySelectorAll('[data-table-search]');
        searchInputs.forEach(input => {
            const tableId = input.getAttribute('data-table-search');
            const table = document.getElementById(tableId);
            
            if (table) {
                input.addEventListener('input', () => {
                    this.filterTable(table, input.value);
                });
            }
        });
    },

    // Filtrar tabla
    filterTable(table, searchTerm) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    },

    // Validación de formularios
    setupFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    },

    // Validar formulario
    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showFieldError(input, 'Este campo es obligatorio');
                isValid = false;
            } else {
                this.clearFieldError(input);
            }
        });

        // Validaciones específicas
        const emailInputs = form.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            if (input.value && !this.isValidEmail(input.value)) {
                this.showFieldError(input, 'Email inválido');
                isValid = false;
            }
        });

        const passwordInputs = form.querySelectorAll('input[data-min-length]');
        passwordInputs.forEach(input => {
            const minLength = parseInt(input.getAttribute('data-min-length'));
            if (input.value && input.value.length < minLength) {
                this.showFieldError(input, `Mínimo ${minLength} caracteres`);
                isValid = false;
            }
        });

        return isValid;
    },

    // Mostrar error en campo
    showFieldError(input, message) {
        this.clearFieldError(input);
        
        input.style.borderColor = '#dc3545';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 4px;';
        errorDiv.textContent = message;
        
        input.parentNode.appendChild(errorDiv);
    },

    // Limpiar error de campo
    clearFieldError(input) {
        input.style.borderColor = '';
        const errorDiv = input.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    },

    // Validar email
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    // Configurar tooltips
    setupTooltips() {
        document.addEventListener('mouseenter', (e) => {
            const element = e.target.closest('[data-tooltip]');
            if (element) {
                this.showTooltip(element, element.getAttribute('data-tooltip'));
            }
        }, true);
        
        document.addEventListener('mouseleave', (e) => {
            const element = e.target.closest('[data-tooltip]');
            if (element) {
                this.hideTooltip();
            }
        }, true);
    },

    // Mostrar tooltip
    showTooltip(element, text) {
        this.hideTooltip(); // Limpiar tooltip anterior
        
        const tooltip = document.createElement('div');
        tooltip.id = 'system-tooltip';
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 10001;
            pointer-events: none;
        `;
        tooltip.textContent = text;
        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
    },

    // Ocultar tooltip
    hideTooltip() {
        const tooltip = document.getElementById('system-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    },

    // Configurar modales
    setupModals() {
        // Cerrar modales con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal[style*="display: flex"]');
                openModals.forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        });
    },

    // Enviar formulario por AJAX
    async submitForm(form) {
        const formData = new FormData(form);
        const action = form.getAttribute('action') || window.location.href;
        
        try {
            this.showNotification('Enviando...', 'info');
            
            const response = await fetch(action, {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                const result = await response.json();
                this.showNotification(result.message || 'Operación completada', 'success');
                
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                }
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        } catch (error) {
            this.showNotification('Error al procesar la solicitud', 'error');
            console.error('Error:', error);
        }
    },

    // Utilidades de UI
    toggleElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = element.style.display === 'none' ? '' : 'none';
        }
    },

    // Confirmar acción
    confirm(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },

    // Formatear fecha
    formatDate(date, format = 'dd/mm/yyyy') {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return format
            .replace('dd', day)
            .replace('mm', month)
            .replace('yyyy', year)
            .replace('hh', hours)
            .replace('ii', minutes);
    },

    // Copiar al portapapeles
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showNotification('Copiado al portapapeles', 'success', 2000);
        } catch (err) {
            // Fallback para navegadores más antiguos
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            this.showNotification('Copiado al portapapeles', 'success', 2000);
        }
    }
};

// CSS para animaciones de notificaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    SistemaGestion.init();
});

// Exportar para uso global
window.SistemaGestion = SistemaGestion;