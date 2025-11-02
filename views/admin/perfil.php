<div class="page-header">
    <h1>Mi Perfil</h1>
    <p class="text-secondary">Información de su cuenta y configuraciones personales</p>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h3>Información Personal</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?action=perfil" id="profileForm">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($user['username'] ?? $_SESSION['username'] ?? '') ?>" readonly>
                                <small class="text-secondary">El nombre de usuario no se puede modificar</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? $_SESSION['email'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= htmlspecialchars($user['nombre'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?= htmlspecialchars($user['telefono'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Cambiar Contraseña -->
        <div class="card mt-4">
            <div class="card-header">
                <h3>Cambiar Contraseña</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?action=perfil" id="passwordForm">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" 
                                       minlength="<?= PASSWORD_MIN_LENGTH ?>" required>
                                <small class="text-secondary">Mínimo <?= PASSWORD_MIN_LENGTH ?> caracteres</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       minlength="<?= PASSWORD_MIN_LENGTH ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="11" width="18" height="10" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="16" r="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3>Información de la Cuenta</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-large">
                        <svg class="icon-avatar" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h4><?= htmlspecialchars($user['username'] ?? $_SESSION['username'] ?? 'Usuario') ?></h4>
                    <p class="text-secondary"><?= htmlspecialchars($user['email'] ?? $_SESSION['email'] ?? '') ?></p>

                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <strong>Estado:</strong>
                        <span class="badge badge-success"><?= ucfirst($user['estado'] ?? 'activo') ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Rol:</strong>
                        <span><?= htmlspecialchars($user['roles'] ?? 'Usuario') ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Miembro desde:</strong>
                        <span><?= isset($user['fecha_creacion']) ? date('d/m/Y', strtotime($user['fecha_creacion'])) : date('d/m/Y') ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Último acceso:</strong>
                        <span><?= isset($user['ultimo_acceso']) ? date('d/m/Y H:i', strtotime($user['ultimo_acceso'])) : date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3>Actividad Reciente</h3>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon text-success">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Inicio de sesión exitoso</div>
                            <div class="activity-time"><?= date('d/m/Y H:i') ?></div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon text-info">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V6C4 5.46957 4.21071 4.96086 4.58579 4.58579C4.96086 4.21071 5.46957 4 6 4H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Acceso al dashboard</div>
                            <div class="activity-time"><?= date('d/m/Y H:i', strtotime('-5 minutes')) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.icon-avatar {
    width: 40px;
    height: 40px;
    color: white;
}

.info-list {
    space-y: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.activity-list {
    space-y: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--color-primary-rgb), 0.1);
}

.activity-icon .icon {
    width: 16px;
    height: 16px;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.875rem;
    color: var(--text-secondary);
}
</style>

<script>
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
    }
});
</script>