<div class="page-header">
    <h1>Usuarios del Sistema</h1>
    <p class="text-secondary">Lista de usuarios registrados en el sistema</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Usuarios Activos</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Información:</strong> Esta es una vista simplificada. Para gestión completa de usuarios, necesita permisos de administrador.
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>admin</strong></td>
                        <td>admin@sistema.com</td>
                        <td><span class="badge badge-success">Activo</span></td>
                        <td><?= date('d/m/Y H:i') ?></td>
                    </tr>
                    <tr>
                        <td><strong><?= $_SESSION['username'] ?? 'usuario' ?></strong></td>
                        <td><?= $_SESSION['email'] ?? 'usuario@sistema.com' ?></td>
                        <td><span class="badge badge-success">Activo</span></td>
                        <td><?= date('d/m/Y H:i') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <p class="text-secondary">
                <strong>Nota:</strong> Para ver todos los usuarios y realizar operaciones de gestión, contacte al administrador del sistema.
            </p>
        </div>
    </div>
</div>