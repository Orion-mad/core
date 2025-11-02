<?php 
$current_page = 'admin';
$title = 'Gestión de Permisos';
$breadcrumb = 'Administración / Permisos';

ob_start(); 
?>

<div class="page-header">
    <h1 class="page-title">Gestión de Permisos</h1>
    <div>
        <button type="button" class="btn btn-primary" onclick="openPermissionModal()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="12" y1="5" x2="12" y2="19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Crear Permiso
        </button>
    </div>
</div>

<!-- Estadísticas de permisos -->
<div class="row mb-4">
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number"><?= count($permisos) ?></div>
            <div class="stat-label">Total Permisos</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-primary"><?= count(array_unique(array_column($permisos, 'modulo'))) ?></div>
            <div class="stat-label">Módulos</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-success"><?= count(array_filter($permisos, fn($p) => $p['roles_asignados'] > 0)) ?></div>
            <div class="stat-label">En Uso</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card">
            <div class="stat-number text-warning"><?= count(array_filter($permisos, fn($p) => $p['roles_asignados'] == 0)) ?></div>
            <div class="stat-label">Sin Asignar</div>
        </div>
    </div>
</div>

<!-- Tabla de permisos por módulo -->
<div class="card">
    <div class="card-header">
        <h3>Permisos por Módulo</h3>
        <div class="card-actions">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar permisos..." 
                   data-table-search="permissionsTable" style="width: 250px;">
        </div>
    </div>
    <div class="card-body">
        <?php 
        $permisos_por_modulo = [];
        foreach ($permisos as $permiso) {
            $permisos_por_modulo[$permiso['modulo']][] = $permiso;
        }
        ?>
        
        <?php foreach ($permisos_por_modulo as $modulo => $permisos_modulo): ?>
        <div class="permission-module mb-4">
            <div class="module-header">
                <h4 class="module-title">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?= ucfirst($modulo) ?>
                    <span class="badge badge-secondary"><?= count($permisos_modulo) ?> permisos</span>
                </h4>
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleModule('<?= $modulo ?>')">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6,9 12,15 18,9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="module-content" id="module-<?= $modulo ?>">
                <div class="table-responsive">
                    <table class="table" id="permissionsTable">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Roles Asignados</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permisos_modulo as $permiso): ?>
                            <tr>
                                <td>
                                    <code class="permission-code"><?= htmlspecialchars($permiso['codigo']) ?></code>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($permiso['nombre']) ?></strong>
                                </td>
                                <td>
                                    <span class="text-secondary"><?= htmlspecialchars($permiso['descripcion']) ?></span>
                                </td>
                                <td>
                                    <span class="badge <?= $permiso['roles_asignados'] > 0 ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $permiso['roles_asignados'] ?> rol(es)
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success">Activo</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onclick="editPermission(<?= $permiso['id'] ?>)" title="Editar">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                onclick="viewPermissionRoles(<?= $permiso['id'] ?>)" title="Ver Roles">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 12S5 4 12 4S23 12 23 12S19 20 12 20S1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <?php if ($permiso['roles_asignados'] == 0): ?>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deletePermission(<?= $permiso['id'] ?>)" title="Eliminar">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal para crear/editar permiso -->
<div class="modal" id="permissionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="permissionModalTitle">Crear Nuevo Permiso</h3>
            <button type="button" class="btn btn-text" onclick="closePermissionModal()">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="permissionForm">
                <input type="hidden" id="permission_id" name="id">
                
                <div class="form-group">
                    <label for="permission_codigo" class="form-label">Código del Permiso *</label>
                    <input type="text" class="form-control" id="permission_codigo" name="codigo" required
                           placeholder="ej: usuarios.crear, reportes.leer" pattern="[a-z_]+\.[a-z_]+">
                    <small class="text-secondary">Formato: modulo.accion (solo minúsculas y guiones bajos)</small>
                </div>
                
                <div class="form-group">
                    <label for="permission_nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control" id="permission_nombre" name="nombre" required
                           placeholder="ej: Crear Usuario, Ver Reportes">
                </div>
                
                <div class="form-group">
                    <label for="permission_descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="permission_descripcion" name="descripcion" rows="3"
                              placeholder="Descripción detallada del permiso"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="permission_modulo" class="form-label">Módulo *</label>
                    <select class="form-control form-select" id="permission_modulo" name="modulo" required>
                        <option value="">Seleccionar módulo</option>
                        <option value="dashboard">Dashboard</option>
                        <option value="usuarios">Usuarios</option>
                        <option value="roles">Roles</option>
                        <option value="permisos">Permisos</option>
                        <option value="auditoria">Auditoría</option>
                        <option value="configuracion">Configuración</option>
                        <option value="reportes">Reportes</option>
                        <option value="sistema">Sistema</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePermissionModal()">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="savePermission()">Guardar Permiso</button>
        </div>
    </div>
</div>

<!-- Modal para ver roles asignados -->
<div class="modal" id="permissionRolesModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Roles con este Permiso</h3>
            <button type="button" class="btn btn-text" onclick="closePermissionRolesModal()">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="modal-body" id="permissionRolesContent">
            <!-- Contenido dinámico -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePermissionRolesModal()">Cerrar</button>
        </div>
    </div>
</div>

<style>
.permission-module {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.module-header {
    background: var(--bg-secondary);
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.module-title {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.module-content {
    padding: 1rem;
    display: none;
}

.module-content.active {
    display: block;
}

.permission-code {
    background: var(--bg-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<script>
// Variables globales
let editingPermissionId = null;

// Abrir modal para crear permiso
function openPermissionModal() {
    editingPermissionId = null;
    document.getElementById('permissionModalTitle').textContent = 'Crear Nuevo Permiso';
    document.getElementById('permissionForm').reset();
    document.getElementById('permission_id').value = '';
    document.getElementById('permissionModal').style.display = 'flex';
}

// Cerrar modal de permiso
function closePermissionModal() {
    document.getElementById('permissionModal').style.display = 'none';
    editingPermissionId = null;
}

// Editar permiso
function editPermission(id) {
    editingPermissionId = id;
    document.getElementById('permissionModalTitle').textContent = 'Editar Permiso';
    
    // Aquí cargarías los datos del permiso desde la base de datos
    // Por ahora, simulamos datos
    document.getElementById('permission_id').value = id;
    document.getElementById('permission_codigo').value = 'usuarios.leer';
    document.getElementById('permission_nombre').value = 'Ver Usuarios';
    document.getElementById('permission_descripcion').value = 'Permite ver la lista de usuarios del sistema';
    document.getElementById('permission_modulo').value = 'usuarios';
    
    document.getElementById('permissionModal').style.display = 'flex';
}

// Guardar permiso
function savePermission() {
    const form = document.getElementById('permissionForm');
    const formData = new FormData(form);
    
    // Validaciones básicas
    const codigo = formData.get('codigo');
    const nombre = formData.get('nombre');
    const modulo = formData.get('modulo');
    
    if (!codigo || !nombre || !modulo) {
        alert('Por favor complete todos los campos obligatorios');
        return;
    }
    
    // Validar formato del código
    const codigoPattern = /^[a-z_]+\.[a-z_]+$/;
    if (!codigoPattern.test(codigo)) {
        alert('El código debe tener el formato: modulo.accion (solo minúsculas y guiones bajos)');
        return;
    }
    
    SistemaGestion.showNotification('Guardando permiso...', 'info');
    
    // Aquí enviarías los datos al servidor
    setTimeout(() => {
        SistemaGestion.showNotification(editingPermissionId ? 'Permiso actualizado correctamente' : 'Permiso creado correctamente', 'success');
        closePermissionModal();
        // Recargar la página o actualizar la tabla
        location.reload();
    }, 1000);
}

// Ver roles asignados a un permiso
function viewPermissionRoles(permissionId) {
    document.getElementById('permissionRolesContent').innerHTML = `
        <div class="text-center">
            <svg class="icon icon-lg text-secondary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p>Cargando roles...</p>
        </div>
    `;
    
    document.getElementById('permissionRolesModal').style.display = 'flex';
    
    // Simular carga de datos
    setTimeout(() => {
        document.getElementById('permissionRolesContent').innerHTML = `
            <div class="roles-list">
                <div class="role-item">
                    <div class="role-info">
                        <h5>Administrador</h5>
                        <p class="text-secondary">Acceso completo al sistema</p>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
                <div class="role-item">
                    <div class="role-info">
                        <h5>Usuario</h5>
                        <p class="text-secondary">Usuario estándar del sistema</p>
                    </div>
                    <span class="badge badge-success">Activo</span>
                </div>
            </div>
        `;
    }, 1000);
}

// Cerrar modal de roles
function closePermissionRolesModal() {
    document.getElementById('permissionRolesModal').style.display = 'none';
}

// Eliminar permiso
function deletePermission(id) {
    if (confirm('¿Está seguro de que desea eliminar este permiso? Esta acción no se puede deshacer.')) {
        SistemaGestion.showNotification('Eliminando permiso...', 'warning');
        
        setTimeout(() => {
            SistemaGestion.showNotification('Permiso eliminado correctamente', 'success');
            location.reload();
        }, 1000);
    }
}

// Toggle módulo
function toggleModule(moduleId) {
    const moduleContent = document.getElementById(`module-${moduleId}`);
    moduleContent.classList.toggle('active');
    
    const button = moduleContent.previousElementSibling.querySelector('button svg');
    if (moduleContent.classList.contains('active')) {
        button.style.transform = 'rotate(180deg)';
    } else {
        button.style.transform = 'rotate(0deg)';
    }
}

// Expandir todos los módulos al cargar
document.addEventListener('DOMContentLoaded', function() {
    const allModules = document.querySelectorAll('.module-content');
    allModules.forEach(module => {
        module.classList.add('active');
        const button = module.previousElementSibling.querySelector('button svg');
        button.style.transform = 'rotate(180deg)';
    });
});

// Auto-completar código basado en módulo y nombre
document.getElementById('permission_modulo').addEventListener('change', updatePermissionCode);
document.getElementById('permission_nombre').addEventListener('input', updatePermissionCode);

function updatePermissionCode() {

    const modulo = document.getElementById('permission_modulo').value;
    const nombre = document.getElementById('permission_nombre').value;
    
    if (modulo && nombre) {
        const accion = nombre.toLowerCase()
            .replace(/\s+/g, '_')
            .replace(/[^a-z_]/g, '')
            .substring(0, 10);
        
        if (accion) {
            document.getElementById('permission_codigo').value = `${modulo}.${accion}`;
        }
    }
}

// Cerrar modales al hacer clic fuera
document.getElementById('permissionModal').addEventListener('click', function(e) {
    if (e.target === this) closePermissionModal();
});

document.getElementById('permissionRolesModal').addEventListener('click', function(e) {
    if (e.target === this) closePermissionRolesModal();
});
</script>

<?php 
$content = ob_get_clean();
render_with_layout($content, [
    'current_page' => $current_page,
    'title' => $title,
    'breadcrumb' => $breadcrumb
]); 
?>