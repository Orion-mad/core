<?php 
$current_page = 'admin';
$title = 'Auditoría y Logs del Sistema';
$breadcrumb = 'Administración / Auditoría';

ob_start(); 
?>

<div class="page-header">
    <h1 class="page-title">Auditoría y Logs del Sistema</h1>
    <div>
        <button type="button" class="btn btn-outline" onclick="exportLogs()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Exportar Logs
        </button>
    </div>
</div>

<!-- Filtros de auditoría -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="form-label">Buscar en logs</label>
                    <input type="text" class="form-control" id="logSearch" placeholder="Buscar por usuario, acción o IP..." data-table-search="auditTable">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-label">Filtrar por acción</label>
                    <select class="form-control form-select" id="actionFilter" onchange="filterByAction()">
                        <option value="">Todas las acciones</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="login_failed">Login fallido</option>
                        <option value="create">Crear</option>
                        <option value="update">Actualizar</option>
                        <option value="delete">Eliminar</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-label">Filtrar por tabla</label>
                    <select class="form-control form-select" id="tableFilter" onchange="filterByTable()">
                        <option value="">Todas las tablas</option>
                        <option value="usuarios">Usuarios</option>
                        <option value="roles">Roles</option>
                        <option value="permisos">Permisos</option>
                        <option value="configuracion_sistema">Configuración</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-label">Fecha desde</label>
                    <input type="date" class="form-control" id="dateFrom" onchange="filterByDate()">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-label">Fecha hasta</label>
                    <input type="date" class="form-control" id="dateTo" onchange="filterByDate()">
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline btn-block" onclick="clearFilters()" title="Limpiar filtros">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de auditoría -->
<div class="row mb-4">
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-info"><?= count($audit_logs) ?></div>
            <div class="stat-label">Total de Registros</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-success"><?= count(array_filter($audit_logs, fn($log) => str_contains($log['accion'], 'login') && !str_contains($log['accion'], 'failed'))) ?></div>
            <div class="stat-label">Logins Exitosos</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-danger"><?= count(array_filter($audit_logs, fn($log) => str_contains($log['accion'], 'failed'))) ?></div>
            <div class="stat-label">Intentos Fallidos</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-warning"><?= count(array_unique(array_column($audit_logs, 'ip_address'))) ?></div>
            <div class="stat-label">IPs Únicas</div>
        </div>
    </div>
</div>

<!-- Tabla de auditoría -->
<div class="card">
    <div class="card-header">
        <h3>Registro de Auditoría</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-sm btn-outline" onclick="toggleAutoRefresh()">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="23,4 23,10 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.49 15C19.9828 16.8038 18.8893 18.3897 17.3597 19.5253C15.8301 20.661 13.9448 21.2803 12.0004 21.2803C10.0559 21.2803 8.17063 20.661 6.64106 19.5253C5.11148 18.3897 4.01798 16.8038 3.51074 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="1,20 1,14 7,14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.51074 9C4.01798 7.19615 5.11148 5.61029 6.64106 4.47472C8.17063 3.33915 10.0559 2.71979 12.0004 2.71979C13.9448 2.71979 15.8301 3.33915 17.3597 4.47472C18.8893 5.61029 19.9828 7.19615 20.49 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span id="autoRefreshText">Auto-refresh OFF</span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table" id="auditTable">
                <thead>
                    <tr>
                        <th data-sort>Fecha/Hora</th>
                        <th data-sort>Usuario</th>
                        <th data-sort>Acción</th>
                        <th>Tabla</th>
                        <th>Detalles</th>
                        <th data-sort>IP</th>
                        <th>User Agent</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audit_logs as $log): ?>
                    <tr data-action="<?= htmlspecialchars($log['accion']) ?>" 
                        data-table="<?= htmlspecialchars($log['tabla_afectada'] ?? '') ?>"
                        data-date="<?= date('Y-m-d', strtotime($log['fecha_accion'])) ?>">
                        <td>
                            <strong><?= date('d/m/Y', strtotime($log['fecha_accion'])) ?></strong>
                            <br>
                            <small class="text-secondary"><?= date('H:i:s', strtotime($log['fecha_accion'])) ?></small>
                        </td>
                        <td>
                            <?php if ($log['username']): ?>
                                <span class="badge badge-primary"><?= htmlspecialchars($log['username']) ?></span>
                            <?php else: ?>
                                <span class="text-secondary">Sistema</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= getActionBadgeColor($log['accion']) ?>">
                                <?= htmlspecialchars($log['accion']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($log['tabla_afectada']): ?>
                                <code><?= htmlspecialchars($log['tabla_afectada']) ?></code>
                            <?php else: ?>
                                <span class="text-light">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($log['datos_anteriores'] || $log['datos_nuevos']): ?>
                                <button type="button" class="btn btn-outline btn-sm" onclick="showLogDetails(<?= $log['id'] ?>)">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Ver
                                </button>
                            <?php else: ?>
                                <span class="text-light">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-secondary"><?= htmlspecialchars($log['ip_address']) ?></small>
                        </td>
                        <td>
                            <small class="text-secondary" title="<?= htmlspecialchars($log['user_agent']) ?>">
                                <?= truncate_user_agent($log['user_agent']) ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php if ($log['datos_anteriores'] || $log['datos_nuevos']): ?>
                                <button type="button" class="btn btn-info btn-sm" onclick="showLogDetails(<?= $log['id'] ?>)" title="Ver detalles">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 12S5 4 12 4S23 12 23 12S19 20 12 20S1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($log['ip_address']): ?>
                                <button type="button" class="btn btn-outline btn-sm" onclick="showIpInfo('<?= htmlspecialchars($log['ip_address']) ?>')" title="Info de IP">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22C9.49872 19.2616 8.07725 15.708 8 12C8.07725 8.29203 9.49872 4.73835 12 2V2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="row mt-3">
            <div class="col-6">
                <small class="text-secondary">
                    Mostrando <?= count($audit_logs) ?> registros
                </small>
            </div>
            <div class="col-6 text-right">
                <button type="button" class="btn btn-outline btn-sm" onclick="loadMoreLogs()">
                    Cargar más registros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles del log -->
<div class="modal" id="logDetailsModal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Detalles del Registro de Auditoría</h3>
            <button type="button" class="btn-close" onclick="closeLogDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="logDetailsContent">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeLogDetailsModal()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal para información de IP -->
<div class="modal" id="ipInfoModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Información de IP</h3>
            <button type="button" class="btn-close" onclick="closeIpInfoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="ipInfoContent">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeIpInfoModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
// Variables globales
let autoRefreshEnabled = false;
let autoRefreshInterval;
let currentLogs = <?= json_encode($audit_logs) ?>;

// Filtrar por acción
function filterByAction() {
    const filter = document.getElementById('actionFilter').value;
    filterTable('data-action', filter);
}

// Filtrar por tabla
function filterByTable() {
    const filter = document.getElementById('tableFilter').value;
    filterTable('data-table', filter);
}

// Filtrar por fecha
function filterByDate() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const rows = document.querySelectorAll('#auditTable tbody tr');
    
    rows.forEach(row => {
        const rowDate = row.getAttribute('data-date');
        let show = true;
        
        if (dateFrom && rowDate < dateFrom) show = false;
        if (dateTo && rowDate > dateTo) show = false;
        
        row.style.display = show ? '' : 'none';
    });
}

// Función genérica para filtrar tabla
function filterTable(attribute, value) {
    const rows = document.querySelectorAll('#auditTable tbody tr');
    
    rows.forEach(row => {
        const attributeValue = row.getAttribute(attribute);
        if (!value || attributeValue === value || attributeValue.includes(value)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Limpiar filtros
function clearFilters() {
    document.getElementById('actionFilter').value = '';
    document.getElementById('tableFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('logSearch').value = '';
    
    const rows = document.querySelectorAll('#auditTable tbody tr');
    rows.forEach(row => row.style.display = '');
}

// Toggle auto-refresh
function toggleAutoRefresh() {
    autoRefreshEnabled = !autoRefreshEnabled;
    const text = document.getElementById('autoRefreshText');
    
    if (autoRefreshEnabled) {
        text.textContent = 'Auto-refresh ON';
        autoRefreshInterval = setInterval(refreshLogs, 10000); // 10 segundos
        SistemaGestion.showNotification('Auto-refresh activado (cada 10 segundos)', 'info');
    } else {
        text.textContent = 'Auto-refresh OFF';
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        SistemaGestion.showNotification('Auto-refresh desactivado', 'info');
    }
}

// Refrescar logs
function refreshLogs() {
    // Aquí iría la llamada AJAX para obtener nuevos logs
    console.log('Refreshing logs...');
}

// Mostrar detalles del log
function showLogDetails(logId) {
    const modal = document.getElementById('logDetailsModal');
    const content = document.getElementById('logDetailsContent');
    
    // Buscar el log en los datos
    const log = currentLogs.find(l => l.id == logId);
    if (!log) return;
    
    let html = '<div class="row">';
    
    // Información básica
    html += `
        <div class="col-6">
            <h5>Información General</h5>
            <table class="table">
                <tr><td><strong>ID:</strong></td><td>${log.id}</td></tr>
                <tr><td><strong>Fecha:</strong></td><td>${new Date(log.fecha_accion).toLocaleString()}</td></tr>
                <tr><td><strong>Usuario:</strong></td><td>${log.username || 'Sistema'}</td></tr>
                <tr><td><strong>Acción:</strong></td><td><span class="badge badge-info">${log.accion}</span></td></tr>
                <tr><td><strong>Tabla:</strong></td><td>${log.tabla_afectada || '-'}</td></tr>
                <tr><td><strong>IP:</strong></td><td>${log.ip_address}</td></tr>
            </table>
        </div>
    `;
    
    // Datos
    html += '<div class="col-6">';
    if (log.datos_anteriores) {
        html += '<h5>Datos Anteriores</h5>';
        html += '<pre class="code">' + JSON.stringify(JSON.parse(log.datos_anteriores), null, 2) + '</pre>';
    }
    if (log.datos_nuevos) {
        html += '<h5>Datos Nuevos</h5>';
        html += '<pre class="code">' + JSON.stringify(JSON.parse(log.datos_nuevos), null, 2) + '</pre>';
    }
    html += '</div>';
    
    html += '</div>';
    
    // User Agent
    if (log.user_agent) {
        html += `
            <div class="mt-3">
                <h5>User Agent</h5>
                <div class="code">${log.user_agent}</div>
            </div>
        `;
    }
    
    content.innerHTML = html;
    modal.classList.add('show');
}

// Cerrar modal de detalles
function closeLogDetailsModal() {
    document.getElementById('logDetailsModal').classList.remove('show');
}

// Mostrar información de IP
function showIpInfo(ip) {
    const modal = document.getElementById('ipInfoModal');
    const content = document.getElementById('ipInfoContent');
    
    // Simular información de IP (en un caso real harías una llamada a una API de geolocalización)
    const ipInfo = {
        ip: ip,
        country: 'Argentina',
        region: 'Buenos Aires',
        city: 'Buenos Aires',
        isp: 'Telecom Argentina',
        type: ip.startsWith('192.168.') || ip.startsWith('10.') || ip.startsWith('172.') ? 'Privada' : 'Pública'
    };
    
    const html = `
        <div class="row">
            <div class="col-12">
                <h5>Información de la IP: ${ip}</h5>
                <table class="table">
                    <tr><td><strong>Dirección IP:</strong></td><td>${ipInfo.ip}</td></tr>
                    <tr><td><strong>Tipo:</strong></td><td><span class="badge badge-${ipInfo.type === 'Privada' ? 'warning' : 'info'}">${ipInfo.type}</span></td></tr>
                    <tr><td><strong>País:</strong></td><td>${ipInfo.country}</td></tr>
                    <tr><td><strong>Región:</strong></td><td>${ipInfo.region}</td></tr>
                    <tr><td><strong>Ciudad:</strong></td><td>${ipInfo.city}</td></tr>
                    <tr><td><strong>ISP:</strong></td><td>${ipInfo.isp}</td></tr>
                </table>
                
                <div class="mt-3">
                    <h6>Actividad de esta IP</h6>
                    <p>Total de acciones: <strong>${currentLogs.filter(l => l.ip_address === ip).length}</strong></p>
                    <p>Último acceso: <strong>${new Date().toLocaleString()}</strong></p>
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = html;
    modal.classList.add('show');
}

// Cerrar modal de IP
function closeIpInfoModal() {
    document.getElementById('ipInfoModal').classList.remove('show');
}

// Cargar más logs
function loadMoreLogs() {
    SistemaGestion.showNotification('Cargando más registros...', 'info');
    
    // Simular carga de más logs
    setTimeout(() => {
        SistemaGestion.showNotification('Registros adicionales cargados', 'success');
    }, 1000);
}

// Exportar logs
function exportLogs() {
    if (confirm('¿Desea exportar todos los logs de auditoría? Esto puede tomar varios minutos.')) {
        SistemaGestion.showNotification('Preparando exportación...', 'info');
        
        setTimeout(() => {
            SistemaGestion.showNotification('Logs exportados correctamente', 'success');
        }, 2000);
    }
}

// Cerrar modales al hacer clic fuera
document.getElementById('logDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeLogDetailsModal();
});

document.getElementById('ipInfoModal').addEventListener('click', function(e) {
    if (e.target === this) closeIpInfoModal();
});

// Limpiar interval al salir
window.addEventListener('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});
</script>

<?php
// Función helper para colores de badges según la acción
function getActionBadgeColor($action) {
    if (str_contains($action, 'login') && !str_contains($action, 'failed')) return 'success';
    if (str_contains($action, 'failed')) return 'danger';
    if (str_contains($action, 'logout')) return 'secondary';
    if (str_contains($action, 'create')) return 'primary';
    if (str_contains($action, 'update')) return 'warning';
    if (str_contains($action, 'delete')) return 'danger';
    return 'info';
}

// Función helper para truncar user agent
function truncate_user_agent($user_agent, $length = 30) {
    if (strlen($user_agent) <= $length) return $user_agent;
    
    // Extraer información útil del user agent
    if (preg_match('/Chrome\/[\d.]+/', $user_agent, $matches)) {
        return 'Chrome ' . str_replace('Chrome/', '', $matches[0]);
    }
    if (preg_match('/Firefox\/[\d.]+/', $user_agent, $matches)) {
        return 'Firefox ' . str_replace('Firefox/', '', $matches[0]);
    }
    if (preg_match('/Safari\/[\d.]+/', $user_agent, $matches)) {
        return 'Safari';
    }
    
    return substr($user_agent, 0, $length) . '...';
}

$content = ob_get_clean();
render_with_layout($content, [
    'current_page' => $current_page,
    'title' => $title,
    'breadcrumb' => $breadcrumb
]); 
?>