/**
 * JavaScript principal del sistema
 * Sistema de Gestión - PHP8 + MariaDB
 */

// Configuración global
const SistemaGestion = {
    // Configuración
    config: {
        confirmDelete: '¿Está seguro de que desea eliminar este elemento?',
        confirmAction: '¿Está seguro de realizar esta acción?',
        loadingText: 'Cargando...',
        errorText: 'Ha ocurrido un error',
        successText: 'Operación realizada correctamente'
    },
    
    // Inicialización
    init() {
        this.setupEventListeners();
        this.initComponents();
        this.setupAjaxDefaults();
    },
    
    // Configurar event listeners globales
    setupEventListeners() {
        // Confirmaciones de eliminación
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-confirm-delete]') || e.target.closest('[data-confirm-delete]')) {
                const element = e.target.matches('[data-confirm-delete]') ? e.target : e.target.closest('[data-confirm-delete]');
                if (!confirm(this.config.confirmDelete)) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Confirmaciones de acción
            if (e.target.matches('[data-confirm]') || e.target.closest('[data-confirm]')) {
                const element = e.target.matches('[data-confirm]') ? e.target : e.target.closest('[data-confirm]');
                const message = element.dataset.confirm || this.config.confirmAction;
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        // Auto-submit en formularios
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-auto-submit]')) {
                const form = e.target.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
        
        // Toggle de elementos
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-toggle]') || e.target.closest('[data-toggle]')) {
                e.preventDefault();
                const element = e.target.matches('[data-toggle]') ? e.target : e.target.closest('[data-toggle]');
                const target = document.querySelector(element.dataset.toggle);
                if (target) {
                    target.classList.toggle('show');
                }
            }
        });
        
        // Copiar al portapapeles
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-copy]') || e.target.closest('[data-copy]')) {
                e.preventDefault();
                const element = e.target.matches('[data-copy]') ? e.target : e.target.closest('[data-copy]');
                this.copyToClipboard(element.dataset.copy);
            }
        });
    },
    
    // Inicializar componentes
    initComponents() {
        // Auto-ocultar alertas
        this.autoHideAlerts();
        
        // Inicializar tooltips (implementación básica)
        this.initTooltips();
        
        // Inicializar tablas
        this.initTables();
        
        // Inicializar formularios
        this.initForms();
    },
    
    // Configurar AJAX por defecto
    setupAjaxDefaults() {
        // Aquí se puede configurar fetch defaults si es necesario
    },
    
    // Auto-ocultar alertas
    autoHideAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.alert-error)');
        alerts.forEach(alert => {
            setTimeout(() => {
                this.fadeOut(alert);
            }, 5000);
        });
    },
    
    // Inicializar tooltips básicos
    initTooltips() {
        const elements = document.querySelectorAll('[data-tooltip]');
        elements.forEach(element => {
            element.addEventListener('mouseenter', (e) => {
                this.showTooltip(e.target, e.target.dataset.tooltip);
            });
            
            element.addEventListener('mouseleave', (e) => {
                this.hideTooltip();
            });
        });
    },
    
    // Inicializar funcionalidades de tablas
    initTables() {
        // Funcionalidad de búsqueda en tablas
        const searchInputs = document.querySelectorAll('[data-table-search]');
        searchInputs.forEach(input => {
            const tableId = input.dataset.tableSearch;
            const table = document.getElementById(tableId);
            if (table) {
                input.addEventListener('input', (e) => {
                    this.filterTable(table, e.target.value);
                });
            }
        });
        
        // Ordenamiento de tablas
        const sortableHeaders = document.querySelectorAll('[data-sort]');
        sortableHeaders.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', (e) => {
                this.sortTable(header);
            });
        });
    },
    
    // Inicializar funcionalidades de formularios
    initForms() {
        // Validación en tiempo real
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Validación en tiempo real de campos
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
            });
        });
        
        // Confirmación de contraseñas
        const passwordConfirms = document.querySelectorAll('[data-password-confirm]');
        passwordConfirms.forEach(confirm => {
            const passwordField = document.getElementById(confirm.dataset.passwordConfirm);
            if (passwordField) {
                const validatePasswords = () => {
                    if (passwordField.value !== confirm.value) {
                        confirm.setCustomValidity('Las contraseñas no coinciden');
                    } else {
                        confirm.setCustomValidity('');
                    }
                };
                
                passwordField.addEventListener('input', validatePasswords);
                confirm.addEventListener('input', validatePasswords);
            }
        });
    },
    
    // Utilidades
    fadeOut(element, callback) {
        element.style.transition = 'opacity 0.5s ease';
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.display = 'none';
            if (callback) callback();
        }, 500);
    },
    
    fadeIn(element) {
        element.style.display = 'block';
        element.style.opacity = '0';
        element.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            element.style.opacity = '1';
        }, 10);
    },
    
    showTooltip(element, text) {
        this.hideTooltip(); // Ocultar tooltip anterior
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip-custom';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            background: var(--bg-dark);
            color: var(--text-white);
            padding: var(--spacing-sm);
            border-radius: var(--border-radius-sm);
            font-size: var(--font-size-xs);
            z-index: 1000;
            pointer-events: none;
            box-shadow: var(--shadow-md);
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
        
        this.currentTooltip = tooltip;
    },
    
    hideTooltip() {
        if (this.currentTooltip) {
            this.currentTooltip.remove();
            this.currentTooltip = null;
        }
    },
    
    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showNotification('Copiado al portapapeles', 'success');
        }).catch(() => {
            this.showNotification('Error al copiar', 'error');
        });
    },
    
    // Filtrar tabla
    filterTable(table, searchTerm) {
        const rows = table.querySelectorAll('tbody tr');
        const term = searchTerm.toLowerCase();
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    },
    
    // Ordenar tabla
    sortTable(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const index = Array.from(header.parentNode.children).indexOf(header);
        const isAsc = !header.classList.contains('sort-asc');
        
        // Limpiar otras columnas
        header.parentNode.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Marcar columna actual
        header.classList.add(isAsc ? 'sort-asc' : 'sort-desc');
        
        rows.sort((a, b) => {
            const aText = a.children[index].textContent.trim();
            const bText = b.children[index].textContent.trim();
            
            // Intentar comparar como números
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAsc ? aNum - bNum : bNum - aNum;
            }
            
            // Comparar como texto
            return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });
        
        rows.forEach(row => tbody.appendChild(row));
    },
    
    // Validar formulario
    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    // Validar campo individual
    validateField(field) {
        let isValid = true;
        const value = field.value.trim();
        
        // Limpiar errores anteriores
        this.clearFieldError(field);
        
        // Validaciones requeridas
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'Este campo es obligatorio');
            isValid = false;
        }
        
        // Validación de email
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showFieldError(field, 'Ingrese un email válido');
                isValid = false;
            }
        }
        
        // Validación de longitud mínima
        if (field.hasAttribute('minlength') && value.length < parseInt(field.getAttribute('minlength'))) {
            this.showFieldError(field, `Mínimo ${field.getAttribute('minlength')} caracteres`);
            isValid = false;
        }
        
        return isValid;
    },
    
    showFieldError(field, message) {
        field.classList.add('error');
        let errorElement = field.parentNode.querySelector('.field-error');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error';

            errorElement.style.cssText = 'color: var(--color-error); font-size: var(--font-size-xs); margin-top: var(--spacing-xs);';
            field.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    },
    
    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    },
    
    // Mostrar notificación
    showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            box-shadow: var(--shadow-lg);
        `;
        
        document.body.appendChild(notification);
        
        // Auto-ocultar
        setTimeout(() => {
            this.fadeOut(notification, () => notification.remove());
        }, duration);
    },
    
    // Realizar petición AJAX
    async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            console.error('Request failed:', error);
            this.showNotification('Error de conexión', 'error');
            throw error;
        }
    },
    
    // Cargar contenido dinámico
    async loadContent(url, container) {
        try {
            const content = await this.request(url);
            document.querySelector(container).innerHTML = content;
            this.initComponents(); // Re-inicializar componentes
        } catch (error) {
            this.showNotification('Error al cargar contenido', 'error');
        }
    },
    
    // Abrir modal básico
    openModal(title, content, buttons = []) {
        const modal = document.createElement('div');
        modal.className = 'modal show';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button type="button" class="btn-close" data-modal-close>&times;</button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
                <div class="modal-footer">
                    ${buttons.map(btn => `<button type="button" class="btn btn-${btn.type || 'secondary'}" data-modal-close="${btn.close !== false}">${btn.text}</button>`).join('')}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Event listeners para cerrar
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.matches('[data-modal-close]')) {
                this.closeModal(modal);
            }
        });
        
        return modal;
    },
    
    closeModal(modal) {
        modal.remove();
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    SistemaGestion.init();
});

// Exportar para uso global
window.SistemaGestion = SistemaGestion;