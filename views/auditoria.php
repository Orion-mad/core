<?php
$current_page = 'auditoria';
$title = 'Mi Actividad';
$breadcrumb = 'Auditoría / Mi Actividad';
?>

<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-file-text"></i>
        Mi Actividad
    </h1>
    <p class="text-muted">Registro de tus acciones en el sistema</p>
</div>

<!-- Estadísticas de auditoría del usuario -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number text-info"><?= count($audit_logs) ?></div>
            <div class="stat-label">Total de Acciones</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number text-success">
                <?= count(array_filter($audit_logs, fn($log) => str_contains($log['accion'], 'login') && !str_contains($log['accion'], 'failed'))) ?>
            </div>
            <div class="stat-label">Accesos Exitosos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number text-warning">
                <?= count(array_filter($audit_logs, fn($log) => str_contains($log['accion'], 'update'))) ?>
            </div>
            <div class="stat-label">Modificaciones</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number text-primary">
                <?= count(array_unique(array_column($audit_logs, 'ip_address'))) ?>
            </div>
            <div class="stat-label">IPs Utilizadas</div>
        </div>
    </div>
</div>

<!-- Tabla de actividad del usuario -->
<div class="card">
    <div class="card-header">
        <h3>
            <i class="bi bi-clock-history me-2"></i>
            Historial de Actividad
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($audit_logs)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No hay actividad registrada aún.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Acción</th>
                            <th>Tabla Afectada</th>
                            <th>IP</th>
                            <th>Navegador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($audit_logs as $log): ?>
                        <tr>
                            <td>
                                <strong><?= date('d/m/Y', strtotime($log['fecha_accion'])) ?></strong>
                                <br>
                                <small class="text-muted"><?= date('H:i:s', strtotime($log['fecha_accion'])) ?></small>
                            </td>
                            <td>
                                <span class="badge
                                    <?php if (str_contains($log['accion'], 'login') && !str_contains($log['accion'], 'failed')): ?>
                                        bg-success
                                    <?php elseif (str_contains($log['accion'], 'failed')): ?>
                                        bg-danger
                                    <?php elseif (str_contains($log['accion'], 'update')): ?>
                                        bg-warning
                                    <?php elseif (str_contains($log['accion'], 'create')): ?>
                                        bg-primary
                                    <?php else: ?>
                                        bg-secondary
                                    <?php endif; ?>
                                ">
                                    <?= htmlspecialchars($log['accion']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($log['tabla_afectada']): ?>
                                    <code class="text-muted"><?= htmlspecialchars($log['tabla_afectada']) ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars($log['ip_address']) ?></small>
                            </td>
                            <td>
                                <small class="text-muted" title="<?= htmlspecialchars($log['user_agent']) ?>">
                                    <?php
                                    // Detectar navegador
                                    if (str_contains($log['user_agent'], 'Chrome')) {
                                        echo '<i class="bi bi-browser-chrome"></i> Chrome';
                                    } elseif (str_contains($log['user_agent'], 'Firefox')) {
                                        echo '<i class="bi bi-browser-firefox"></i> Firefox';
                                    } elseif (str_contains($log['user_agent'], 'Safari')) {
                                        echo '<i class="bi bi-browser-safari"></i> Safari';
                                    } elseif (str_contains($log['user_agent'], 'Edge')) {
                                        echo '<i class="bi bi-browser-edge"></i> Edge';
                                    } else {
                                        echo '<i class="bi bi-globe"></i> Otro';
                                    }
                                    ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($audit_logs)): ?>
    <div class="card-footer text-muted">
        <i class="bi bi-info-circle me-2"></i>
        Mostrando los últimos <?= count($audit_logs) ?> registros de actividad
    </div>
    <?php endif; ?>
</div>

<!-- Información adicional -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h5 class="alert-heading">
                <i class="bi bi-shield-check me-2"></i>
                Sobre el registro de auditoría
            </h5>
            <hr>
            <p class="mb-0">
                <strong>¿Qué se registra?</strong> Todas tus acciones en el sistema quedan registradas para garantizar
                la seguridad y trazabilidad. Esto incluye inicios de sesión, modificaciones de datos y otras operaciones importantes.
            </p>
            <p class="mb-0 mt-2">
                <strong>Seguridad:</strong> Solo tú y los administradores del sistema pueden ver tu actividad.
                Los registros son permanentes y no pueden ser modificados.
            </p>
        </div>
    </div>
</div>
