<?php
$current_page = 'perfil';
$title = 'Mi Perfil';
$breadcrumb = 'Mi Perfil';
?>

<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-person-circle"></i>
        Mi Perfil
    </h1>
    <p class="text-muted">Administra tu información personal y configuración de cuenta</p>
</div>

<div class="row">
    <!-- Información del perfil -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                </div>
                <h3><?= htmlspecialchars($user['nombre'] ?? $user['username']) ?></h3>
                <p class="text-muted">@<?= htmlspecialchars($user['username']) ?></p>

                <div class="mt-3">
                    <span class="badge
                        <?php if ($user['estado'] === 'activo'): ?>
                            bg-success
                        <?php elseif ($user['estado'] === 'inactivo'): ?>
                            bg-warning
                        <?php else: ?>
                            bg-danger
                        <?php endif; ?>
                    ">
                        <?= ucfirst($user['estado']) ?>
                    </span>
                </div>

                <?php if (!empty($user['roles'])): ?>
                <div class="mt-3">
                    <strong>Rol:</strong>
                    <br>
                    <span class="badge bg-primary mt-1"><?= htmlspecialchars($user['roles']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Información de Cuenta
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Fecha de Creación</small>
                    <br>
                    <strong><?= date('d/m/Y', strtotime($user['fecha_creacion'])) ?></strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Último Acceso</small>
                    <br>
                    <strong>
                        <?php if ($user['ultimo_acceso']): ?>
                            <?= date('d/m/Y H:i:s', strtotime($user['ultimo_acceso'])) ?>
                        <?php else: ?>
                            <span class="text-secondary">Primer acceso</span>
                        <?php endif; ?>
                    </strong>
                </div>

                <?php if (isset($user['intentos_fallidos'])): ?>
                <div class="mb-0">
                    <small class="text-muted">Intentos Fallidos</small>
                    <br>
                    <strong><?= $user['intentos_fallidos'] ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Formularios de edición -->
    <div class="col-md-8">
        <!-- Información Personal -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Información Personal
                </h5>
            </div>
            <div class="card-body">
                <form id="personalInfoForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   value="<?= htmlspecialchars($user['username']) ?>" disabled>
                            <small class="text-muted">El nombre de usuario no se puede cambiar</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    Cambiar Contraseña
                </h5>
            </div>
            <div class="card-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="current_password"
                               name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password"
                               name="new_password" required>
                        <small class="text-muted">
                            Mínimo 8 caracteres, debe incluir mayúsculas, minúsculas y números
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password"
                               name="confirm_password" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-2"></i>
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preferencias (opcional, para futuro) -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Preferencias
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                        <label class="form-check-label" for="emailNotifications">
                            Recibir notificaciones por correo electrónico
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                        <label class="form-check-label" for="twoFactorAuth">
                            Autenticación de dos factores (próximamente)
                        </label>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> Algunas preferencias están en desarrollo y estarán disponibles próximamente.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Manejar formulario de información personal
document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Aquí iría la llamada AJAX para guardar los cambios
    alert('Funcionalidad en desarrollo. Los cambios se guardarán cuando esté implementada la API.');

    console.log('Datos a guardar:', Object.fromEntries(formData));
});

// Manejar formulario de cambio de contraseña
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Validar que las contraseñas coincidan
    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
    }

    // Validar requisitos de contraseña
    if (newPassword.length < 8) {
        alert('La contraseña debe tener al menos 8 caracteres');
        return;
    }

    if (!/[A-Z]/.test(newPassword)) {
        alert('La contraseña debe contener al menos una letra mayúscula');
        return;
    }

    if (!/[a-z]/.test(newPassword)) {
        alert('La contraseña debe contener al menos una letra minúscula');
        return;
    }

    if (!/[0-9]/.test(newPassword)) {
        alert('La contraseña debe contener al menos un número');
        return;
    }

    const formData = new FormData(this);

    // Aquí iría la llamada AJAX para cambiar la contraseña
    alert('Funcionalidad en desarrollo. El cambio de contraseña se implementará cuando esté lista la API.');

    console.log('Cambio de contraseña solicitado');

    // Limpiar formulario
    this.reset();
});
</script>
