<?php 
$current_page = 'admin';
$title = 'Gestión de Usuarios';
$breadcrumb = 'Administración / Gestión de Usuarios';

ob_start(); 
?>

<div class="page-header">
    <h1 class="page-title">Gestión de Usuarios</h1>
    <div>
        <button type="button" class="btn btn-primary" onclick="openUserModal()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Nuevo Usuario
        </button>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="form-label">Buscar usuarios</label>
                    <input type="text" class="form-control" id="userSearch" placeholder="Buscar por nombre, email o usuario..." data-table-search="usersTable">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="form-label">Filtrar por estado</label>
                    <select class="form-control form-select" id="statusFilter" onchange="filterByStatus()">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="bloqueado">Bloqueados</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="form-label">Filtrar por rol</label>
                    <select class="form-control form-select" id="roleFilter" onchange="filterByRole()">
                        <option value="">Todos los roles</option>
                        <?php foreach ($roles as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['nombre']) ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline btn-block" onclick="exportUsers()">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="card">
    <div class="card-header">
        <h3>Lista de Usuarios (<?= count($users) ?>)</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table" id="usersTable">
                <thead>
                    <tr>
                        <th data-sort>ID</th>
                        <th data-sort>Usuario</th>
                        <th data-sort>Nombre Completo</th>
                        <th data-sort>Email</th>
                        <th>Roles</th>
                        <th data-sort>Estado</th>
                        <th data-sort>Último Acceso</th>
                        <th data-sort>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr data-user-id="<?= $user['id'] ?>" data-status="<?= $user['estado'] ?>" data-roles="<?= htmlspecialchars($user['roles'] ?? '') ?>">
                        <td><?= $user['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($user['username']) ?></strong>
                            <?php if ($user['intentos_fallidos'] > 0): ?>
                                <span class="badge badge-warning" title="<?= $user['intentos_fallidos'] ?> intentos fallidos">
                                    ⚠️ <?= $user['intentos_fallidos'] ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                                <?= htmlspecialchars($user['email']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($user['roles'])): ?>
                                <?php foreach (explode(', ', $user['roles']) as $rol): ?>
                                    <span class="badge badge-<?= $rol === 'administrador' ? 'warning' : ($rol === 'usuario' ? 'primary' : 'secondary') ?>">
                                        <?= htmlspecialchars($rol) ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-secondary">Sin roles</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $user['estado'] === 'activo' ? 'success' : ($user['estado'] === 'inactivo' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($user['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['ultimo_acceso']): ?>
                                <small title="<?= date('d/m/Y H:i:s', strtotime($user['ultimo_acceso'])) ?>">
                                    <?= time_ago($user['ultimo_acceso']) ?>
                                </small>
                            <?php else: ?>
                                <span class="text-secondary">Nunca</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small><?= date('d/m/Y', strtotime($user['fecha_creacion'])) ?></small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline btn-sm" onclick="editUser(<?= $user['id'] ?>)" title="Editar">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                
                                <?php if ($user['estado'] === 'activo'): ?>
                                <button type="button" class="btn btn-warning btn-sm" onclick="toggleUserStatus(<?= $user['id'] ?>, 'inactivo')" title="Desactivar">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-success btn-sm" onclick="toggleUserStatus(<?= $user['id'] ?>, 'activo')" title="Activar">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-info btn-sm" onclick="resetPassword(<?= $user['id'] ?>)" title="Resetear contraseña">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                
                                <?php if ($user['username'] !== 'admin'): ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)" title="Eliminar" data-confirm-delete>
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

<!-- Modal para crear/editar usuario -->
<div class="modal" id="userModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="userModalTitle">Nuevo Usuario</h3>
            <button type="button" class="btn-close" onclick="closeUserModal()">&times;</button>
        </div>
        <form id="userForm" data-validate>
            <div class="modal-body">
                <input type="hidden" id="userId" name="user_id">
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="username" class="form-label">Usuario *</label>
                            <input type="text" class="form-control" id="username" name="username" required minlength="3">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono">
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña <span id="passwordRequired">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" minlength="8">
                            <small class="text-secondary">Mínimo 8 caracteres, incluir mayúsculas, minúsculas, números y símbolos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">Confirmar Contraseña <span id="confirmRequired">*</span></label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" data-password-confirm="password">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control form-select" id="estado" name="estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="bloqueado">Bloqueado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Roles</label>
                            <div class="mt-2">
                                <?php foreach ($roles as $rol): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="role_<?= $rol['id'] ?>" name="roles[]" value="<?= $rol['id'] ?>">
                                    <label class="form-check-label" for="role_<?= $rol['id'] ?>">
                                        <?= htmlspecialchars($rol['nombre']) ?>
                                        <small class="text-secondary">(<?= htmlspecialchars($rol['descripcion']) ?>)</small>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeUserModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <span id="saveButtonText">Crear Usuario</span>
                    <div class="loading" id="saveButtonLoader" style="display: none;"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Variables globales
let currentUsers = <?= json_encode($users) ?>;
let isEditMode = false;

// Función para abrir modal de usuario
function openUserModal(userId = null) {
    isEditMode = userId !== null;
    const modal = document.getElementById('userModal');
    const title = document.getElementById('userModalTitle');
    const form = document.getElementById('userForm');
    const saveButton = document.getElementById('saveButtonText');
    
    // Resetear formulario
    form.reset();
    document.getElementById('userId').value = '';
    
    if (isEditMode) {
        title.textContent = 'Editar Usuario';
        saveButton.textContent = 'Actualizar Usuario';
        loadUserData(userId);
        
        // Hacer contraseña opcional en edición
        document.getElementById('password').removeAttribute('required');
        document.getElementById('confirmPassword').removeAttribute('required');
        document.getElementById('passwordRequired').style.display = 'none';
        document.getElementById('confirmRequired').style.display = 'none';
    } else {
        title.textContent = 'Nuevo Usuario';
        saveButton.textContent = 'Crear Usuario';
        
        // Hacer contraseña obligatoria en creación
        document.getElementById('password').setAttribute('required', 'required');
        document.getElementById('confirmPassword').setAttribute('required', 'required');
        document.getElementById('passwordRequired').style.display = 'inline';
        document.getElementById('confirmRequired').style.display = 'inline';
    }
    
    modal.classList.add('show');
}

// Función para cerrar modal
function closeUserModal() {
    document.getElementById('userModal').classList.remove('show');
}

// Cargar datos del usuario para edición
function loadUserData(userId) {
    const user = currentUsers.find(u => u.id == userId);
    if (!user) return;
    
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('email').value = user.email;
    document.getElementById('nombre').value = user.nombre;
    document.getElementById('apellido').value = user.apellido;
    document.getElementById('telefono').value = user.telefono || '';
    document.getElementById('estado').value = user.estado;
    
    // Cargar roles (esto requeriría una llamada AJAX para obtener los roles del usuario)
    // Por ahora simulamos
    if (user.roles) {
        const userRoles = user.roles.split(', ');
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        roleCheckboxes.forEach(checkbox => {
            const roleLabel = checkbox.nextElementSibling.textContent.trim().split('(')[0].trim();
            checkbox.checked = userRoles.includes(roleLabel);
        });
    }
}

// Manejar envío del formulario
document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const saveButton = document.getElementById('saveButtonText');
    const loader = document.getElementById('saveButtonLoader');
    
    // Mostrar loading
    saveButton.style.display = 'none';
    loader.style.display = 'inline-block';
    
    // Simular guardado (aquí iría la llamada AJAX real)
    setTimeout(() => {
        SistemaGestion.showNotification(
            isEditMode ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente', 
            'success'
        );
        
        // Ocultar loading
        saveButton.style.display = 'inline';
        loader.style.display = 'none';
        
        closeUserModal();
        
        // Aquí recargarías la tabla con los datos actualizados
        // location.reload(); // Temporal
    }, 1500);
});

// Editar usuario
function editUser(userId) {
    openUserModal(userId);
}

// Cambiar estado del usuario
function toggleUserStatus(userId, newStatus) {
    const action = newStatus === 'activo' ? 'activar' : 'desactivar';
    
    if (confirm(`¿Está seguro de que desea ${action} este usuario?`)) {
        // Aquí iría la llamada AJAX para cambiar el estado
        SistemaGestion.showNotification(`Usuario ${action}do correctamente`, 'success');
        
        // Actualizar la fila de la tabla
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            const statusBadge = row.querySelector('.badge');
            statusBadge.className = `badge badge-${newStatus === 'activo' ? 'success' : 'warning'}`;
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            row.setAttribute('data-status', newStatus);
        }
    }
}

// Resetear contraseña
function resetPassword(userId) {
    if (confirm('¿Está seguro de que desea resetear la contraseña de este usuario? Se enviará una nueva contraseña por email.')) {
        // Aquí iría la llamada AJAX para resetear contraseña
        SistemaGestion.showNotification('Nueva contraseña enviada por email', 'success');
    }
}

// Eliminar usuario
function deleteUser(userId) {
    if (confirm('¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
        // Aquí iría la llamada AJAX para eliminar usuario
        SistemaGestion.showNotification('Usuario eliminado correctamente', 'success');
        
        // Remover fila de la tabla
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            row.remove();
        }
    }
}

// Filtrar por estado
function filterByStatus() {
    const filter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        if (!filter || status === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Filtrar por rol
function filterByRole() {
    const filter = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const roles = row.getAttribute('data-roles');
        if (!filter || roles.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Exportar usuarios
function exportUsers() {
    SistemaGestion.showNotification('Preparando exportación...', 'info');
    // Aquí iría la lógica para exportar usuarios a CSV/Excel
}

// Función helper para tiempo relativo
function timeAgo(date) {
    // Implementación simple de tiempo relativo
    return 'hace un momento'; // Placeholder
}

// Cerrar modal al hacer clic fuera
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUserModal();
    }
});
</script>

<?php
// Función helper para tiempo relativo
function time_ago($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'hace un momento';
    if ($time < 3600) return 'hace ' . floor($time/60) . ' min';
    if ($time < 86400) return 'hace ' . floor($time/3600) . ' hrs';
    if ($time < 2592000) return 'hace ' . floor($time/86400) . ' días';
    
    return date('d/m/Y', strtotime($datetime));
}

$content = ob_get_clean();
render_with_layout($content, [
    'current_page' => $current_page,
    'title' => $title,
    'breadcrumb' => $breadcrumb
]); 
?>