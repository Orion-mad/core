<div class="page-header">
    <h1>Configuración del Sistema</h1>
    <p class="text-secondary">Información general sobre la configuración del sistema</p>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>Información del Sistema</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Información:</strong> Esta es una vista de solo lectura. Para modificar configuraciones, necesita permisos de administrador.
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nombre del Sistema</label>
                    <input type="text" class="form-control" value="<?= APP_NAME ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Versión</label>
                    <input type="text" class="form-control" value="<?= APP_VERSION ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Entorno</label>
                    <input type="text" class="form-control" value="<?= APP_ENV ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Timeout de Sesión</label>
                    <input type="text" class="form-control" value="<?= SESSION_TIMEOUT / 60 ?> minutos" readonly>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3>Información Técnica</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Versión PHP</label>
                    <input type="text" class="form-control" value="<?= phpversion() ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Servidor Web</label>
                    <input type="text" class="form-control" value="<?= $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido' ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Zona Horaria</label>
                    <input type="text" class="form-control" value="<?= date_default_timezone_get() ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Fecha/Hora Actual</label>
                    <input type="text" class="form-control" value="<?= date('d/m/Y H:i:s') ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Uso de Memoria</label>
                    <input type="text" class="form-control" value="<?= number_format(memory_get_usage(true) / 1024 / 1024, 2) ?> MB" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3>Configuraciones de Seguridad</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="form-label">Intentos Máximos de Login</label>
                    <input type="text" class="form-control" value="<?= MAX_LOGIN_ATTEMPTS ?>" readonly>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="form-label">Tiempo de Bloqueo</label>
                    <input type="text" class="form-control" value="<?= LOCKOUT_TIME / 60 ?> minutos" readonly>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="form-label">Longitud Mínima de Contraseña</label>
                    <input type="text" class="form-control" value="<?= PASSWORD_MIN_LENGTH ?> caracteres" readonly>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <p class="text-secondary">
                <strong>Nota:</strong> Para modificar estas configuraciones, contacte al administrador del sistema.
            </p>
        </div>
    </div>
</div>