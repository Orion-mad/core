<div class="page-header">
    <h1>Roles del Sistema</h1>
    <p class="text-secondary">Información sobre los roles disponibles en el sistema</p>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h3>Roles Disponibles</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Información:</strong> Esta es una vista de solo lectura. Para gestión completa de roles, necesita permisos de administrador.
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Administrador</strong></td>
                                <td>Acceso completo al sistema y todas las funcionalidades</td>
                                <td><span class="badge badge-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td><strong>Usuario</strong></td>
                                <td>Acceso estándar a las funcionalidades básicas</td>
                                <td><span class="badge badge-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td><strong>Invitado</strong></td>
                                <td>Acceso de solo lectura a información básica</td>
                                <td><span class="badge badge-success">Activo</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3>Mi Rol Actual</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <svg class="icon-large text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h4><?= $_SESSION['user_roles'] ?? 'Usuario' ?></h4>
                    <p class="text-secondary">Rol asignado actual</p>
                </div>
                
                <div class="mt-3">
                    <h5>Permisos Básicos:</h5>
                    <ul class="list-unstyled">
                        <li>✓ Acceso al dashboard</li>
                        <li>✓ Ver información del sistema</li>
                        <li>✓ Gestionar perfil personal</li>
                        <li>✓ Ver actividad propia</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>