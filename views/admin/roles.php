<?php
$current_page = 'admin';
$title = 'Gestión de Roles y Permisos';
$breadcrumb = 'Administración / Roles y Permisos';
?>

<div class="page-header">
    <h1 class="page-title">Gestión de Roles y Permisos</h1>
    <div>
        <button type="button" class="btn btn-primary" onclick="openRoleModal()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Nuevo Rol
        </button>
    </div>
</div>

<div class="row">
    <!-- Gestión de Roles -->
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>Roles del Sistema</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table" id="rolesTable">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th>Usuarios</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $rol): ?>
                            <tr data-role-id="<?= $rol['id'] ?>">
                                <td>
                                    <strong><?= htmlspecialchars($rol['nombre']) ?></strong>
                                    <br>
                                    <small class="text-secondary"><?= htmlspecialchars($rol['descripcion']) ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= $rol['total_usuarios'] ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $rol['estado'] === 'activo' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($rol['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="editRole(<?= $rol['id'] ?>)" title="Editar">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="managePermissions(<?= $rol['id'] ?>, '<?= htmlspecialchars($rol['nombre']) ?>')" title="Gestionar permisos">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <circle cx="12" cy="16" r="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <?php if (!in_array($rol['nombre'], ['administrador', 'usuario', 'invitado'])): ?>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteRole(<?= $rol['id'] ?>)" title="Eliminar" data-confirm-delete>
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
    </div>
    
    <!-- Vista de Permisos por Módulo -->
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>Permisos por Módulo</h3>
            </div>
            <div class="card-body">
                <?php 
                $permisos_por_modulo = [];
                foreach ($permisos as $permiso) {
                    $permisos_por_modulo[$permiso['modulo']][] = $permiso;
                }
                ?>
                
                <?php foreach ($permisos_por_modulo as $modulo => $permisos_modulo): ?>
                <div class="mb-4">
                    <h5 class="text-primary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12H18L15 21L9 3L6 12H2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?= ucfirst($modulo) ?>
                    </h5>
                    
                    <div class="row">
                        <?php foreach ($permisos_modulo as $permiso): ?>
                        <div class="col-6">
                            <div class="form-check mb-2">
                                <span class="badge badge-secondary"><?= htmlspecialchars($permiso['codigo']) ?></span>
                                <br>
                                <small><?= htmlspecialchars($permiso['nombre']) ?></small>
                                <?php if ($permiso['roles_asignados'] > 0): ?>
                                    <br><small class="text-info"><?= $permiso['roles_asignados'] ?> roles asignados</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar rol -->
<div class="modal" id="roleModal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="roleModalTitle">Nuevo Rol</h3>
            <button type="button" class="btn-close" onclick="closeRoleModal()">&times;</button>
        </div>
        <form id="roleForm" data-validate>
            <div class="modal-body">
                <input type="hidden" id="roleId" name="role_id">
                
                <div class="form-group">
                    <label for="roleName" class="form-label">Nombre del Rol *</label>
                    <input type="text" class="form-control" id="roleName" name="nombre" required minlength="3">
                </div>
                
                <div class="form-group">
                    <label for="roleDescription" class="form-label">Descripción *</label>
                    <textarea class="form-control" id="roleDescription" name="descripcion" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="roleStatus" class="form-label">Estado</label>
                    <select class="form-control form-select" id="roleStatus" name="estado">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeRoleModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <span id="roleButtonText">Crear Rol</span>
                    <div class="loading" id="roleButtonLoader" style="display: none;"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para gestionar permisos -->
<div class="modal" id="permissionsModal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="permissionsModalTitle">Gestionar Permisos</h3>
            <button type="button" class="btn-close" onclick="closePermissionsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="permissionsRoleId">
            
            <div class="mb-3">
                <button type="button" class="btn btn-success btn-sm" onclick="selectAllPermissions()">Seleccionar Todos</button>
                <button type="button" class="btn btn-outline btn-sm" onclick="deselectAllPermissions()">Deseleccionar Todos</button>
            </div>
            
            <form id="permissionsForm">
                <?php foreach ($permisos_por_modulo as $modulo => $permisos_modulo): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <input type="checkbox" class="form-check-input me-2" id="module_<?= $modulo ?>" onchange="toggleModulePermissions('<?= $modulo ?>')">
                            <label for="module_<?= $modulo ?>"><?= ucfirst($modulo) ?></label>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($permisos_modulo as $permiso): ?>
                            <div class="col-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input permission-checkbox" 
                                           id="perm_<?= $permiso['id'] ?>" 
                                           name="permissions[]" 
                                           value="<?= $permiso['id'] ?>"
                                           data-module="<?= $modulo ?>">
                                    <label class="form-check-label" for="perm_<?= $permiso['id'] ?>">
                                        <strong><?= htmlspecialchars($permiso['nombre']) ?></strong>
                                        <br>
                                        <small class="text-secondary"><?= htmlspecialchars($permiso['codigo']) ?></small>
                                        <br>
                                        <small><?= htmlspecialchars($permiso['descripcion']) ?></small>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closePermissionsModal()">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="savePermissions()">
                <span id="permissionsButtonText">Guardar Permisos</span>
                <div class="loading" id="permissionsButtonLoader" style="display: none;"></div>
            </button>
        </div>
    </div>
</div>

<script>
// Variables globales
let currentRoles = <?= json_encode($roles) ?>;
let isEditRoleMode = false;

// Función para abrir modal de rol
function openRoleModal(roleId = null) {
    isEditRoleMode = roleId !== null;
    const modal = document.getElementById('roleModal');
    const title = document.getElementById('roleModalTitle');
    const form = document.getElementById('roleForm');
    const saveButton = document.getElementById('roleButtonText');
    
    // Resetear formulario
    form.reset();
    document.getElementById('roleId').value = '';
    
    if (isEditRoleMode) {
        title.textContent = 'Editar Rol';
        saveButton.textContent = 'Actualizar Rol';
        loadRoleData(roleId);
    } else {
        title.textContent = 'Nuevo Rol';
        saveButton.textContent = 'Crear Rol';
    }
    
    modal.classList.add('show');
}

// Función para cerrar modal de rol
function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('show');
}

// Cargar datos del rol para edición
function loadRoleData(roleId) {
    const role = currentRoles.find(r => r.id == roleId);
    if (!role) return;
    
    document.getElementById('roleId').value = role.id;
    document.getElementById('roleName').value = role.nombre;
    document.getElementById('roleDescription').value = role.descripcion;
    document.getElementById('roleStatus').value = role.estado;
}

// Manejar envío del formulario de rol
document.getElementById('roleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const saveButton = document.getElementById('roleButtonText');
    const loader = document.getElementById('roleButtonLoader');
    
    // Mostrar loading
    saveButton.style.display = 'none';
    loader.style.display = 'inline-block';
    
    // Simular guardado
    setTimeout(() => {
        SistemaGestion.showNotification(
            isEditRoleMode ? 'Rol actualizado correctamente' : 'Rol creado correctamente', 
            'success'
        );
        
        // Ocultar loading
        saveButton.style.display = 'inline';
        loader.style.display = 'none';
        
        closeRoleModal();
    }, 1500);
});

// Editar rol
function editRole(roleId) {
    openRoleModal(roleId);
}

// Eliminar rol
function deleteRole(roleId) {
    if (confirm('¿Está seguro de que desea eliminar este rol? Los usuarios asignados perderán este rol.')) {
        // Aquí iría la llamada AJAX para eliminar rol
        SistemaGestion.showNotification('Rol eliminado correctamente', 'success');
        
        // Remover fila de la tabla
        const row = document.querySelector(`tr[data-role-id="${roleId}"]`);
        if (row) {
            row.remove();
        }
    }
}

// Gestionar permisos
function managePermissions(roleId, roleName) {
    const modal = document.getElementById('permissionsModal');
    const title = document.getElementById('permissionsModalTitle');
    
    title.textContent = `Permisos para: ${roleName}`;
    document.getElementById('permissionsRoleId').value = roleId;
    
    // Cargar permisos actuales del rol (aquí iría una llamada AJAX)
    loadRolePermissions(roleId);
    
    modal.classList.add('show');
}

// Cerrar modal de permisos
function closePermissionsModal() {
    document.getElementById('permissionsModal').classList.remove('show');
}

// Cargar permisos del rol
function loadRolePermissions(roleId) {
    // Aquí iría la llamada AJAX para obtener los permisos actuales del rol
    // Por ahora limpiamos todas las selecciones
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(cb => cb.checked = false);
    
    // Simular algunos permisos marcados para el rol administrador
    if (roleId == 1) { // Asumiendo que 1 es administrador
        checkboxes.forEach(cb => cb.checked = true);
        updateModuleCheckboxes();
    }
}

// Guardar permisos
function savePermissions() {
    const roleId = document.getElementById('permissionsRoleId').value;
    const saveButton = document.getElementById('permissionsButtonText');
    const loader = document.getElementById('permissionsButtonLoader');
    
    // Obtener permisos seleccionados
    const selectedPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked'))
        .map(cb => cb.value);
    
    // Mostrar loading
    saveButton.style.display = 'none';
    loader.style.display = 'inline-block';
    
    // Simular guardado
    setTimeout(() => {
        SistemaGestion.showNotification('Permisos actualizados correctamente', 'success');
        
        // Ocultar loading
        saveButton.style.display = 'inline';
        loader.style.display = 'none';
        
        closePermissionsModal();
    }, 1500);
}

// Seleccionar todos los permisos
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(cb => cb.checked = true);
    updateModuleCheckboxes();
}

// Deseleccionar todos los permisos
function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(cb => cb.checked = false);
    updateModuleCheckboxes();
}

// Toggle permisos de módulo
function toggleModulePermissions(module) {
    const moduleCheckbox = document.getElementById(`module_${module}`);
    const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
    
    permissionCheckboxes.forEach(cb => {
        cb.checked = moduleCheckbox.checked;
    });
}

// Actualizar checkboxes de módulo basado en permisos individuales
function updateModuleCheckboxes() {
    const modules = [...new Set(Array.from(document.querySelectorAll('.permission-checkbox')).map(cb => cb.dataset.module))];
    
    modules.forEach(module => {
        const moduleCheckbox = document.getElementById(`module_${module}`);
        const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
        const checkedPermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
        
        if (checkedPermissions.length === permissionCheckboxes.length) {
            moduleCheckbox.checked = true;
            moduleCheckbox.indeterminate = false;
        } else if (checkedPermissions.length > 0) {
            moduleCheckbox.checked = false;
            moduleCheckbox.indeterminate = true;
        } else {
            moduleCheckbox.checked = false;
            moduleCheckbox.indeterminate = false;
        }
    });
}

// Escuchar cambios en checkboxes de permisos individuales
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('permission-checkbox')) {
        updateModuleCheckboxes();
    }
});

// Cerrar modales al hacer clic fuera
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) closeRoleModal();
});

document.getElementById('permissionsModal').addEventListener('click', function(e) {
    if (e.target === this) closePermissionsModal();
});
</script>