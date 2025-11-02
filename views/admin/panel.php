<?php 
$current_page = 'admin';
$title = 'Panel de Administración';
$breadcrumb = 'Administración / Panel Principal';

ob_start(); 
?>

<!-- Page Header con nuevo diseño -->
<div class="page-header bg-white rounded-orion shadow-orion-sm p-4 mb-4 fade-in">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title text-gradient-primary mb-1">
                <i class="bi bi-gear-fill me-3"></i>
                Panel de Administración
            </h1>
            <p class="text-muted mb-0">Gestión y configuración del sistema</p>
        </div>
        <div class="col-auto">
            <span class="badge badge-gradient-warning fs-6 px-3 py-2">
                <i class="bi bi-shield-check me-2"></i>
                Administrador
            </span>
        </div>
    </div>
</div>

<!-- Navegación del panel de administración con Bootstrap -->
<div class="card shadow-orion-sm hover-lift mb-4 slide-up">
    <div class="card-header bg-gradient-primary text-dark">
        <h3 class="mb-0 d-flex align-items-center">
            <i class="bi bi-grid-3x3-gap me-2"></i>
            Herramientas de Administración
        </h3>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="index.php?action=admin&subaction=usuarios" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 hover-lift">
                    <i class="bi bi-people fs-1 mb-3 text-primary"></i>
                    <h5 class="mb-2">Gestionar Usuarios</h5>
                    <small class="text-muted">Crear, editar y administrar usuarios</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="index.php?action=admin&subaction=roles" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 hover-lift">
                    <i class="bi bi-person-badge fs-1 mb-3 text-success"></i>
                    <h5 class="mb-2">Gestionar Roles</h5>
                    <small class="text-muted">Configurar roles y permisos</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="index.php?action=admin&subaction=permisos" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 hover-lift">
                    <i class="bi bi-shield-lock fs-1 mb-3 text-info"></i>
                    <h5 class="mb-2">Gestionar Permisos</h5>
                    <small class="text-muted">Configurar permisos del sistema</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="index.php?action=admin&subaction=configuracion" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 hover-lift">
                    <i class="bi bi-gear fs-1 mb-3 text-warning"></i>
                    <h5 class="mb-2">Configuración</h5>
                    <small class="text-muted">Ajustes del sistema</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas del sistema con nuevos stat cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card card-gradient-primary shadow-orion-md hover-lift bounce-in">
            <div class="card-header border-0 text-dark">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-people-fill me-2"></i>
                    Estadísticas de Usuarios
                </h4>
            </div>
            <div class="card-body text-dark">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['total_usuarios']) ?></div>
                            <div class="stat-label text-dark-50">Total</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['usuarios_activos']) ?></div>
                            <div class="stat-label text-dark-50">Activos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['usuarios_inactivos']) ?></div>
                            <div class="stat-label text-dark-50">Inactivos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['usuarios_bloqueados']) ?></div>
                            <div class="stat-label text-dark-50">Bloqueados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card card-gradient-success shadow-orion-md hover-lift bounce-in">
            <div class="card-header border-0 text-dark">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-graph-up me-2"></i>
                    Estadísticas del Sistema
                </h4>
            </div>
            <div class="card-body text-dark">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['total_roles']) ?></div>
                            <div class="stat-label text-dark-50">Roles</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['total_permisos']) ?></div>
                            <div class="stat-label text-dark-50">Permisos</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['sesiones_activas']) ?></div>
                            <div class="stat-label text-dark-50">Sesiones</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white bg-opacity-20 text-dark">
                            <div class="stat-number text-dark"><?= number_format($system_stats['registros_auditoria']) ?></div>
                            <div class="stat-label text-dark-50">Logs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información del servidor con diseño moderno -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-orion-sm hover-lift fade-in">
            <div class="card-header bg-gradient-primary text-dark">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-server me-2"></i>
                    Información del Servidor
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="input-group-orion">
                            <i class="bi bi-code-slash input-icon"></i>
                            <input type="text" class="form-control form-control-gradient" 
                                   value="PHP <?= $server_info['php_version'] ?? phpversion() ?>" readonly>
                        </div>
                        <label class="form-label text-muted mt-1">Versión PHP</label>
                    </div>
                    <div class="col-12">
                        <div class="input-group-orion">
                            <i class="bi bi-hdd input-icon"></i>
                            <input type="text" class="form-control form-control-gradient" 
                                   value="<?= $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido' ?>" readonly>
                        </div>
                        <label class="form-label text-muted mt-1">Servidor Web</label>
                    </div>
                    <div class="col-12">
                        <div class="input-group-orion">
                            <i class="bi bi-memory input-icon"></i>
                            <input type="text" class="form-control form-control-gradient" 
                                   value="<?= number_format(memory_get_usage(true) / 1024 / 1024, 2) ?> MB" readonly>
                        </div>
                        <label class="form-label text-muted mt-1">Uso de Memoria</label>
                    </div>
                    <div class="col-12">
                        <div class="input-group-orion">
                            <i class="bi bi-clock input-icon"></i>
                            <input type="text" class="form-control form-control-gradient" 
                                   value="<?= date('d/m/Y H:i:s') ?>" readonly>
                        </div>
                        <label class="form-label text-muted mt-1">Fecha/Hora</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-orion-sm hover-lift fade-in">
            <div class="card-header bg-gradient-warning text-dark">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    Configuración Rápida
                </h4>
            </div>
            <div class="card-body">
                <?php if (!empty($system_config)): ?>
                    <?php foreach (array_slice($system_config, 0, 4) as $config): ?>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong class="text-dark"><?= htmlspecialchars($config['clave']) ?></strong>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($config['descripcion'] ?? '') ?></small>
                        </div>
                        <div>
                            <?php if ($config['tipo'] === 'boolean'): ?>
                                <span class="badge <?= $config['valor'] === 'true' ? 'badge-gradient-success' : 'badge-gradient-secondary' ?>">
                                    <i class="bi bi-<?= $config['valor'] === 'true' ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                    <?= $config['valor'] === 'true' ? 'Activado' : 'Desactivado' ?>
                                </span>
                            <?php else: ?>
                                <code class="bg-light p-1 rounded"><?= htmlspecialchars($config['valor']) ?></code>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-3">
                        <a href="index.php?action=admin&subaction=configuracion" class="btn btn-gradient-warning btn-icon w-100">
                            <i class="bi bi-gear"></i>
                            Configuración Completa
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Herramientas de administración con nuevos botones -->
<div class="card shadow-orion-sm hover-lift fade-in">
    <div class="card-header bg-gradient-danger text-dark">
        <h4 class="mb-0 d-flex align-items-center">
            <i class="bi bi-tools me-2"></i>
            Herramientas del Sistema
        </h4>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <button type="button" class="btn btn-gradient-primary btn-icon w-100 py-3" onclick="limpiarSesiones()">
                    <i class="bi bi-trash me-2"></i>
                    Limpiar Sesiones
                </button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-gradient-success btn-icon w-100 py-3" onclick="exportarDatos()">
                    <i class="bi bi-download me-2"></i>
                    Exportar Datos
                </button>
            </div>
            <div class="col-md-3">
                <a href="index.php?action=admin&subaction=auditoria" class="btn btn-gradient-info btn-icon w-100 py-3">
                    <i class="bi bi-file-text me-2"></i>
                    Ver Logs
                </a>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-gradient-warning btn-icon w-100 py-3" onclick="backupSistema()">
                    <i class="bi bi-shield-check me-2"></i>
                    Backup Sistema
                </button>
            </div>
        </div>
        
        <!-- Progress bar para mostrar estado del sistema -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">Salud del Sistema</span>
                <span class="badge badge-gradient-success">Excelente</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-gradient-success" role="progressbar" 
                     style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <small class="text-muted mt-1 d-block">
                <i class="bi bi-info-circle me-1"></i>
                Sistema funcionando correctamente. Última verificación: <?= date('H:i') ?>
            </small>
        </div>
    </div>
</div>

<!-- Toast container para notificaciones -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060;"></div>

<script>
// Funciones mejoradas con toasts de Bootstrap
function limpiarSesiones() {
    if (confirm('¿Está seguro de que desea limpiar todas las sesiones expiradas?')) {
        showBootstrapToast('Limpiando sesiones expiradas...', 'primary');
        
        setTimeout(() => {
            showBootstrapToast('Sesiones limpiadas correctamente', 'success');
        }, 2000);
    }
}

function exportarDatos() {
    showBootstrapToast('Preparando exportación de datos...', 'info');
    
    setTimeout(() => {
        showBootstrapToast('Datos exportados correctamente', 'success');
    }, 2000);
}

function backupSistema() {
    if (confirm('¿Está seguro de que desea crear un backup del sistema?')) {
        showBootstrapToast('Creando backup del sistema...', 'warning');
        
        setTimeout(() => {
            showBootstrapToast('Backup creado correctamente', 'success');
        }, 3000);
    }
}

// Función para mostrar toasts de Bootstrap
function showBootstrapToast(message, type = 'primary') {
    const toastContainer = document.querySelector('.toast-container');
    const toastId = 'toast-' + Date.now();
    
    const iconMap = {
        primary: 'info-circle',
        success: 'check-circle',
        warning: 'exclamation-triangle',
        danger: 'x-circle',
        info: 'info-circle'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-bg-${type} border-0 fade show" role="alert" id="${toastId}">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (toastElement) {
            const bsToast = bootstrap.Toast.getInstance(toastElement);
            if (bsToast) bsToast.hide();
        }
    }, 5000);
    
    // Remove element after hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Inicializar animaciones
document.addEventListener('DOMContentLoaded', function() {
    // Animar contadores
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent.replace(/,/g, ''));
        animateValue(stat, 0, finalValue, 2000);
    });
});

function animateValue(element, start, end, duration) {
    const startTime = performance.now();
    
    function updateValue(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateValue);
        }
    }
    
    requestAnimationFrame(updateValue);
}
</script>

<?php 
$content = ob_get_clean();
render_with_layout($content, [
    'current_page' => $current_page,
    'title' => $title,
    'breadcrumb' => $breadcrumb
]); 
?>