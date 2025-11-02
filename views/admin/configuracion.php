<?php 
$current_page = 'admin';
$title = 'Configuración del Sistema';
$breadcrumb = 'Administración / Configuración';
$csrf_token = $_SESSION['csrf_token'];

ob_start(); 
?>

<div class="page-header">
    <h1 class="page-title">Configuración del Sistema</h1>
    <div>
        <button type="button" class="btn btn-outline" onclick="resetToDefaults()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polyline points="23,4 23,10 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20.49 15C19.76 15.96 18.78 16.73 17.65 17.2C16.52 17.67 15.29 17.84 14.06 17.69C12.83 17.54 11.68 17.08 10.73 16.36C9.78 15.64 9.06 14.69 8.64 13.61C8.22 12.53 8.12 11.36 8.35 10.23C8.58 9.1 9.13 8.06 9.94 7.21C10.75 6.36 11.78 5.74 12.91 5.41C14.04 5.08 15.24 5.05 16.38 5.33" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12C21 16.97 16.97 21 12 21C7.03 21 3 16.97 3 12C3 7.03 7.03 3 12 3C13.8 3 15.47 3.55 16.85 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Restaurar Valores por Defecto
        </button>
    </div>
</div>

<?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-success fade-in">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=admin&subaction=configuracion" id="configForm">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    
    <div class="row">
        <!-- Configuración General -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19.4 15C19.2669 15.3016 19.2272 15.6362 19.286 15.9606C19.3448 16.285 19.4995 16.5843 19.73 16.82L19.79 16.88C19.976 17.0657 20.1235 17.2863 20.2241 17.5291C20.3248 17.7719 20.3766 18.0322 20.3766 18.295C20.3766 18.5578 20.3248 18.8181 20.2241 19.0609C20.1235 19.3037 19.976 19.5243 19.79 19.71C19.6043 19.896 19.3837 20.0435 19.1409 20.1441C18.8981 20.2448 18.6378 20.2966 18.375 20.2966C18.1122 20.2966 17.8519 20.2448 17.6091 20.1441C17.3663 20.0435 17.1457 19.896 16.96 19.71L16.9 19.65C16.6643 19.4195 16.365 19.2648 16.0406 19.206C15.7162 19.1472 15.3816 19.1869 15.08 19.32C14.7842 19.4468 14.532 19.6572 14.3543 19.9255C14.1766 20.1938 14.0813 20.5082 14.08 20.83V21C14.08 21.5304 13.8693 22.0391 13.4942 22.4142C13.1191 22.7893 12.6104 23 12.08 23C11.5496 23 11.0409 22.7893 10.6658 22.4142C10.2907 22.0391 10.08 21.5304 10.08 21V20.91C10.0723 20.579 9.96512 20.258 9.77251 19.9887C9.5799 19.7194 9.31074 19.5143 9 19.4C8.69838 19.2669 8.36381 19.2272 8.03941 19.286C7.71502 19.3448 7.41568 19.4995 7.18 19.73L7.12 19.79C6.93425 19.976 6.71368 20.1235 6.47088 20.2241C6.22808 20.3248 5.96783 20.3766 5.705 20.3766C5.44217 20.3766 5.18192 20.3248 4.93912 20.2241C4.69632 20.1235 4.47575 19.976 4.29 19.79C4.10405 19.6043 3.95653 19.3837 3.85588 19.1409C3.75523 18.8981 3.70343 18.6378 3.70343 18.375C3.70343 18.1122 3.75523 17.8519 3.85588 17.6091C3.95653 17.3663 4.10405 17.1457 4.29 16.96L4.35 16.9C4.58054 16.6643 4.73519 16.365 4.794 16.0406C4.85282 15.7162 4.81312 15.3816 4.68 15.08C4.55324 14.7842 4.34276 14.532 4.07447 14.3543C3.80618 14.1766 3.49179 14.0813 3.17 14.08H3C2.46957 14.08 1.96086 13.8693 1.58579 13.4942C1.21071 13.1191 1 12.6104 1 12.08C1 11.5496 1.21071 11.0409 1.58579 10.6658C1.96086 10.2907 2.46957 10.08 3 10.08H3.09C3.42099 10.0723 3.742 9.96512 4.0113 9.77251C4.28059 9.5799 4.48572 9.31074 4.6 9C4.73312 8.69838 4.77282 8.36381 4.714 8.03941C4.65519 7.71502 4.50054 7.41568 4.27 7.18L4.21 7.12C4.02405 6.93425 3.87653 6.71368 3.77588 6.47088C3.67523 6.22808 3.62343 5.96783 3.62343 5.705C3.62343 5.44217 3.67523 5.18192 3.77588 4.93912C3.87653 4.69632 4.02405 4.47575 4.21 4.29C4.39575 4.10405 4.61632 3.95653 4.85912 3.85588C5.10192 3.75523 5.36217 3.70343 5.625 3.70343C5.88783 3.70343 6.14808 3.75523 6.39088 3.85588C6.63368 3.95653 6.85425 4.10405 7.04 4.29L7.1 4.35C7.33568 4.58054 7.63502 4.73519 7.95941 4.794C8.28381 4.85282 8.61838 4.81312 8.92 4.68H9C9.29577 4.55324 9.54802 4.34276 9.72569 4.07447C9.90337 3.80618 9.99872 3.49179 10 3.17V3C10 2.46957 10.2107 1.96086 10.5858 1.58579C10.9609 1.21071 11.4696 1 12 1C12.5304 1 13.0391 1.21071 13.4142 1.58579C13.7893 1.96086 14 2.46957 14 3V3.09C14.0013 3.41179 14.0966 3.72618 14.2743 3.99447C14.452 4.26276 14.7042 4.47324 15 4.6C15.3016 4.73312 15.6362 4.77282 15.9606 4.714C16.285 4.65519 16.5843 4.50054 16.82 4.27L16.88 4.21C17.0657 4.02405 17.2863 3.87653 17.5291 3.77588C17.7719 3.67523 18.0322 3.62343 18.295 3.62343C18.5578 3.62343 18.8181 3.67523 19.0609 3.77588C19.3037 3.87653 19.5243 4.02405 19.71 4.21C19.896 4.39575 20.0435 4.61632 20.1441 4.85912C20.2448 5.10192 20.2966 5.36217 20.2966 5.625C20.2966 5.88783 20.2448 6.14808 20.1441 6.39088C20.0435 6.63368 19.896 6.85425 19.71 7.04L19.65 7.1C19.4195 7.33568 19.2648 7.63502 19.206 7.95941C19.1472 8.28381 19.1869 8.61838 19.32 8.92V9C19.4468 9.29577 19.6572 9.54802 19.9255 9.72569C20.1938 9.90337 20.5082 9.99872 20.83 10H21C21.5304 10 22.0391 10.2107 22.4142 10.5858C22.7893 10.9609 23 11.4696 23 12C23 12.5304 22.7893 13.0391 22.4142 13.4142C22.0391 13.7893 21.5304 14 21 14H20.91C20.5882 14.0013 20.2738 14.0966 20.0055 14.2743C19.7372 14.452 19.5268 14.7042 19.4 15V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Configuración General
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="app_name" class="form-label">Nombre del Sistema</label>
                        <input type="text" class="form-control" id="app_name" name="config[app_name]" 
                               value="<?= APP_NAME ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="app_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="app_description" name="config[app_description]" rows="3">Sistema de gestión web desarrollado en PHP8 con MariaDB</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_timeout" class="form-label">Timeout de Sesión (minutos)</label>
                        <input type="number" class="form-control" id="session_timeout" name="config[session_timeout]" 
                               value="<?= SESSION_TIMEOUT / 60 ?>" min="5" max="480">
                    </div>
                    
                    <div class="form-group">
                        <label for="max_login_attempts" class="form-label">Máximo Intentos de Login</label>
                        <input type="number" class="form-control" id="max_login_attempts" name="config[max_login_attempts]" 
                               value="<?= MAX_LOGIN_ATTEMPTS ?>" min="3" max="10">
                    </div>
                    
                    <div class="form-group">
                        <label for="lockout_time" class="form-label">Tiempo de Bloqueo (minutos)</label>
                        <input type="number" class="form-control" id="lockout_time" name="config[lockout_time]" 
                               value="<?= LOCKOUT_TIME / 60 ?>" min="1" max="60">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Configuración de Seguridad -->
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3>

                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="16" r="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Seguridad
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="password_min_length" class="form-label">Longitud Mínima de Contraseña</label>
                        <input type="number" class="form-control" id="password_min_length" name="config[password_min_length]" 
                               value="<?= PASSWORD_MIN_LENGTH ?>" min="6" max="20">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Modo Mantenimiento</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="maintenance_mode" 
                                   name="config[maintenance_mode]" value="1">
                            <label class="form-check-label" for="maintenance_mode">
                                Activar modo mantenimiento
                            </label>
                        </div>
                        <small class="text-secondary">Solo los administradores podrán acceder al sistema</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Registro de Actividad</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="audit_logging" 
                                   name="config[audit_logging]" value="1" checked>
                            <label class="form-check-label" for="audit_logging">
                                Registrar actividad de usuarios
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="log_retention_days" class="form-label">Retención de Logs (días)</label>
                        <input type="number" class="form-control" id="log_retention_days" name="config[log_retention_days]" 
                               value="30" min="7" max="365">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Configuración de Email -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Configuración de Email
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="smtp_host" class="form-label">Servidor SMTP</label>
                        <input type="text" class="form-control" id="smtp_host" name="config[smtp_host]" 
                               value="<?= SMTP_HOST ?>">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="smtp_port" class="form-label">Puerto</label>
                        <input type="number" class="form-control" id="smtp_port" name="config[smtp_port]" 
                               value="<?= SMTP_PORT ?>" min="25" max="65535">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="smtp_user" class="form-label">Usuario SMTP</label>
                        <input type="email" class="form-control" id="smtp_user" name="config[smtp_user]" 
                               value="<?= SMTP_USER ?>">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="smtp_from" class="form-label">Email Remitente</label>
                        <input type="email" class="form-control" id="smtp_from" name="config[smtp_from]" 
                               value="<?= SMTP_FROM ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="smtp_pass" class="form-label">Contraseña SMTP</label>
                        <input type="password" class="form-control" id="smtp_pass" name="config[smtp_pass]" 
                               placeholder="••••••••" autocomplete="new-password">
                        <small class="text-secondary">Dejar en blanco para mantener la contraseña actual</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Opciones de Seguridad</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="smtp_ssl" 
                                   name="config[smtp_ssl]" value="1" checked>
                            <label class="form-check-label" for="smtp_ssl">
                                Usar SSL/TLS
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="button" class="btn btn-info btn-sm" onclick="testEmailConfig()">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 6L12 13L2 6V18C2 18.5304 2.21071 19.0391 2.58579 19.4142C2.96086 19.7893 3.46957 20 4 20H20C20.5304 20 21.0391 19.7893 21.4142 19.4142C21.7893 19.0391 22 18.5304 22 18V6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Probar Configuración de Email
                </button>
            </div>
        </div>
    </div>
    
    <!-- Información del Sistema -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Información del Sistema
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label">Versión PHP</label>
                        <input type="text" class="form-control" value="<?= phpversion() ?>" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label">Servidor Web</label>
                        <input type="text" class="form-control" value="<?= $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido' ?>" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label">Uso de Memoria</label>
                        <input type="text" class="form-control" value="<?= number_format(memory_get_usage(true) / 1024 / 1024, 2) ?> MB" readonly>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label">Versión del Sistema</label>
                        <input type="text" class="form-control" value="<?= APP_VERSION ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div class="card mt-4">
        <div class="card-body text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar Configuración
            </button>
            <a href="index.php?action=admin" class="btn btn-secondary btn-lg ml-2">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>
</form>

<script>
// Test de configuración de email
function testEmailConfig() {
    if (confirm('¿Enviar un email de prueba para verificar la configuración?')) {
        SistemaGestion.showNotification('Enviando email de prueba...', 'info');
        
        // Simular envío de email
        setTimeout(() => {
            SistemaGestion.showNotification('Email de prueba enviado correctamente', 'success');
        }, 2000);
    }
}

// Restaurar valores por defecto
function resetToDefaults() {
    if (confirm('¿Está seguro de que desea restaurar todos los valores a los predeterminados? Esta acción no se puede deshacer.')) {
        SistemaGestion.showNotification('Restaurando valores por defecto...', 'warning');
        
        // Resetear campos principales
        document.getElementById('app_name').value = '<?= APP_NAME ?>';
        document.getElementById('session_timeout').value = '<?= SESSION_TIMEOUT / 60 ?>';
        document.getElementById('max_login_attempts').value = '<?= MAX_LOGIN_ATTEMPTS ?>';
        document.getElementById('lockout_time').value = '<?= LOCKOUT_TIME / 60 ?>';
        document.getElementById('password_min_length').value = '<?= PASSWORD_MIN_LENGTH ?>';
        
        setTimeout(() => {
            SistemaGestion.showNotification('Valores restaurados a los predeterminados', 'success');
        }, 1000);
    }
}

// Validación del formulario
document.getElementById('configForm').addEventListener('submit', function(e) {
    // Validaciones básicas
    const sessionTimeout = parseInt(document.getElementById('session_timeout').value);
    const maxAttempts = parseInt(document.getElementById('max_login_attempts').value);
    const lockoutTime = parseInt(document.getElementById('lockout_time').value);
    const passwordLength = parseInt(document.getElementById('password_min_length').value);
    
    if (sessionTimeout < 5 || sessionTimeout > 480) {
        e.preventDefault();
        alert('El timeout de sesión debe estar entre 5 y 480 minutos');
        return;
    }
    
    if (maxAttempts < 3 || maxAttempts > 10) {
        e.preventDefault();
        alert('Los intentos máximos de login deben estar entre 3 y 10');
        return;
    }
    
    if (lockoutTime < 1 || lockoutTime > 60) {
        e.preventDefault();
        alert('El tiempo de bloqueo debe estar entre 1 y 60 minutos');
        return;
    }
    
    if (passwordLength < 6 || passwordLength > 20) {
        e.preventDefault();
        alert('La longitud mínima de contraseña debe estar entre 6 y 20 caracteres');
        return;
    }
    
    SistemaGestion.showNotification('Guardando configuración...', 'info');
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