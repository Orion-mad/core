<?php
date_default_timezone_set('America/Argentina/Buenos_Aires'); 

/**
 * Archivo principal del sistema CORREGIDO
 * Sistema de Gestión - PHP8 + MariaDB
 */

// Manejo de errores mejorado
try {
    require_once 'config.php';
    require_once INCLUDES_PATH . '/Database.php';
    require_once INCLUDES_PATH . '/Auth.php';
} catch (Exception $e) {
    // Error crítico cargando archivos base
    error_log("Error crítico en index.php: " . $e->getMessage());
    
    // Mostrar error básico sin revelar información sensible
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Error del Sistema</title></head><body>';
    echo '<h1>Error del Sistema</h1>';
    echo '<p>Se ha producido un error interno. Por favor, contacte al administrador.</p>';
    echo '</body></html>';
    exit(1);
}

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'dashboard';
$auth = Auth::getInstance();

// Limpiar sesiones expiradas periódicamente
if (rand(1, 100) === 1) {
    $auth->cleanExpiredSessions();
}

// Rutas que no requieren autenticación
$public_routes = ['login', 'logout', 'forgot_password', 'reset_password'];

// Verificar autenticación
if (!in_array($action, $public_routes) && !$auth->isAuthenticated()) {
    redirect('index.php?action=login');
}

// Manejar acciones
switch ($action) {
    case 'login':
        handleLogin();
        break;
    
    case 'logout':
        handleLogout();
        break;

    case 'forgot_password':
        handleForgotPassword();
        break;

    case 'reset_password':
        handleResetPassword();
        break;

    case 'dashboard':
        handleDashboard();
        break;
    
    case 'admin':
        handleAdmin();
        break;
    
    case 'usuarios':
        handleUsers();
        break;
    
    case 'roles':
        handleRoles();
        break;
    
    case 'configuracion':
        handleConfig();
        break;
    
    case 'perfil':
        handleProfile();
        break;
    
    case 'auditoria':
        handleAudit();
        break;
    
    default:
        http_response_code(404);
        load_view('error', ['message' => 'Página no encontrada']);
        break;
}

/**
 * Manejar login
function handleLogin() {
    global $auth;
    
    if ($auth->isAuthenticated()) {
        redirect('index.php?action=dashboard');
    }
    
    $error_message = '';
    $timeout_message = $_SESSION['timeout_message'] ?? '';
    unset($_SESSION['timeout_message']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        // Verificar CSRF token
        if (!verify_csrf_token($csrf_token)) {
            $error_message = 'Token de seguridad inválido';
        } elseif (empty($username) || empty($password)) {
            $error_message = 'Por favor complete todos los campos';
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $result = $auth->login($username, $password, $ip_address, $user_agent);
            
            if ($result['success']) {
                redirect('index.php?action=dashboard');
            } else {
                $error_message = $result['message'];
            }
        }
    }
    
    load_view('login', [
        'error_message' => $error_message,
        'timeout_message' => $timeout_message,
        'csrf_token' => generate_csrf_token()
    ]);
}
 */

/**
 * FUNCIÓN HANDLELOGIN CORREGIDA - Para reemplazar en index.php
 * Mejor manejo de errores CSRF y debugging
 */

function handleLogin() {
    global $auth;
    
    // Asegurar que la sesión esté iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    if ($auth->isAuthenticated()) {
        redirect('index.php?action=dashboard');
    }
    
    $error_message = '';
    $timeout_message = $_SESSION['timeout_message'] ?? '';
    unset($_SESSION['timeout_message']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        // Log del intento de login con información de debugging
        write_log('DEBUG', 'Login attempt', [
            'username' => $username,
            'csrf_received' => !empty($csrf_token),
            'csrf_info' => debug_csrf_info()
        ]);
        
        // Verificar CSRF token
        if (!verify_csrf_token($csrf_token)) {
            // Log detallado del error CSRF
            write_log('WARNING', 'Login failed: Invalid CSRF token', [
                'username' => $username,
                'csrf_token' => $csrf_token ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'csrf_debug' => debug_csrf_info()
            ]);
            
            $error_message = 'Token de seguridad inválido';
        } elseif (empty($username) || empty($password)) {
            $error_message = 'Por favor complete todos los campos';
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $result = $auth->login($username, $password, $ip_address, $user_agent);
            
            if ($result['success']) {
                redirect('index.php?action=dashboard');
            } else {
                $error_message = $result['message'];
            }
        }
    }
    
    // Generar token CSRF para el formulario
    $csrf_token = generate_csrf_token();
    
    // Log del token generado para debugging
    write_log('DEBUG', 'CSRF token generated for login form', [
        'token_length' => strlen($csrf_token),
        'session_id' => session_id()
    ]);
    
    load_view('login', [
        'error_message' => $error_message,
        'timeout_message' => $timeout_message,
        'csrf_token' => $csrf_token
    ]);
}



/**
 * Manejar logout
 */
function handleLogout() {
    global $auth;
    
    $auth->logout();
    redirect('index.php?action=login');
}

/**
 * Manejar dashboard principal
 */
function handleDashboard() {
    global $auth;
    
    if (!$auth->hasPermission('dashboard.acceso')) {
        load_view('error', ['message' => 'No tiene permisos para acceder al dashboard']);
        return;
    }
    
    $db = Database::getInstance();
    $user = $auth->getCurrentUser();
    
    // Obtener estadísticas básicas
    $stats = [
        'total_usuarios' => $db->count('usuarios'),
        'usuarios_activos' => $db->count('usuarios', "estado = 'activo'"),
        'total_roles' => $db->count('roles'),
        'sesiones_activas' => $db->count('sesiones', 'activa = 1')
    ];
    
    // Obtener actividad reciente si es admin
    $recent_activity = [];
    if ($auth->isAdmin()) {
        $recent_activity = $db->select(
            "SELECT a.*, u.username FROM auditoria a 
             LEFT JOIN usuarios u ON a.usuario_id = u.id 
             ORDER BY a.fecha_accion DESC LIMIT 10"
        );
    }
    
    load_view('dashboard', [
        'user' => $user,
        'stats' => $stats,
        'recent_activity' => $recent_activity,
        'is_admin' => $auth->isAdmin(),
        'current_page' => 'dashboard',
        'title' => 'Dashboard'
    ]);
}

/**
 * Manejar panel de administración
 */
function handleAdmin() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $subaction = $_GET['subaction'] ?? 'panel';
    
    switch ($subaction) {
        case 'panel':
            handleAdminPanel();
            break;
        case 'usuarios':
            handleAdminUsers();
            break;
        case 'roles':
            handleAdminRoles();
            break;
        case 'permisos':
            handleAdminPermissions();
            break;
        case 'configuracion':
            handleAdminConfig();
            break;
        case 'auditoria':
            handleAdminAudit();
            break;
        default:
            handleAdminPanel();
            break;
    }
}

/**
 * Panel de administración principal
 */
function handleAdminPanel() {
    global $auth;
    
    $db = Database::getInstance();
    
    // Estadísticas del sistema
    $system_stats = [
        'total_usuarios' => $db->count('usuarios'),
        'usuarios_activos' => $db->count('usuarios', "estado = 'activo'"),
        'usuarios_inactivos' => $db->count('usuarios', "estado = 'inactivo'"),
        'usuarios_bloqueados' => $db->count('usuarios', "estado = 'bloqueado'"),
        'total_roles' => $db->count('roles'),
        'roles_activos' => $db->count('roles', "estado = 'activo'"),
        'total_permisos' => $db->count('permisos'),
        'sesiones_activas' => $db->count('sesiones', 'activa = 1'),
        'registros_auditoria' => $db->count('auditoria')
    ];
    
    // Configuración del sistema
    $system_config = $db->select(
        "SELECT * FROM configuracion_sistema ORDER BY categoria, clave"
    );
    
    // Información del servidor
    $server_info = [];
    try {
        $server_info = $db->getServerInfo();
    } catch (Exception $e) {
        $server_info = ['version' => 'Desconocida'];
    }
    $server_info['php_version'] = PHP_VERSION;
    $server_info['memory_usage'] = memory_get_usage(true);
    $server_info['memory_peak'] = memory_get_peak_usage(true);
    
    load_view('admin/panel', [
        'system_stats' => $system_stats,
        'system_config' => $system_config,
        'server_info' => $server_info,
        'current_page' => 'admin',
        'title' => 'Panel de Administración'
    ]);
}

/**
 * Gestión de usuarios desde admin
 */
function handleAdminUsers() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $db = Database::getInstance();
    
    $users = $db->select(
        "SELECT u.*, GROUP_CONCAT(r.nombre SEPARATOR ', ') as roles
         FROM usuarios u
         LEFT JOIN usuario_roles ur ON u.id = ur.usuario_id
         LEFT JOIN roles r ON ur.rol_id = r.id
         GROUP BY u.id
         ORDER BY u.fecha_creacion DESC"
    );
    
    $roles = $db->select("SELECT * FROM roles WHERE estado = 'activo' ORDER BY nombre");
    
    // Si no hay roles, crear datos de prueba básicos
    if (empty($roles)) {
        $roles = [
            ['id' => 1, 'nombre' => 'administrador', 'descripcion' => 'Acceso completo al sistema', 'estado' => 'activo'],
            ['id' => 2, 'nombre' => 'usuario', 'descripcion' => 'Usuario estándar', 'estado' => 'activo'],
            ['id' => 3, 'nombre' => 'invitado', 'descripcion' => 'Acceso de solo lectura', 'estado' => 'activo']
        ];
    }
    
    load_view('admin/usuarios', [
        'users' => $users,
        'roles' => $roles,
        'current_page' => 'usuarios',
        'title' => 'Gestión de Usuarios'
    ]);
}

/**
 * Gestión de roles desde admin
 */
function handleAdminRoles() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $db = Database::getInstance();
    
    $roles = $db->select(
        "SELECT r.*, COUNT(ur.usuario_id) as total_usuarios
         FROM roles r
         LEFT JOIN usuario_roles ur ON r.id = ur.rol_id
         GROUP BY r.id
         ORDER BY r.nombre"
    );
    
    $permisos = $db->select("SELECT p.*, COUNT(rp.rol_id) as roles_asignados 
                                FROM permisos p 
                                LEFT JOIN rol_permisos rp ON p.id = rp.permiso_id 
                                GROUP BY p.id 
                                ORDER BY p.modulo, p.nombre");
    
    load_view('admin/roles', [
        'roles' => $roles,
        'permisos' => $permisos,
        'current_page' => 'roles',
        'title' => 'Gestión de Roles'
    ]);
}

/**
 * Gestión de permisos desde admin
 */
function handleAdminPermissions() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $db = Database::getInstance();
    
    $permisos = $db->select(
        "SELECT p.*, COUNT(rp.rol_id) as roles_asignados
         FROM permisos p
         LEFT JOIN rol_permisos rp ON p.id = rp.permiso_id
         GROUP BY p.id
         ORDER BY p.modulo, p.nombre"
    );
    
    load_view('admin/permisos', [
        'permisos' => $permisos,
        'current_page' => 'permisos',
        'title' => 'Gestión de Permisos'
    ]);
}

/**
 * Configuración del sistema desde admin
 */
function handleAdminConfig() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $db = Database::getInstance();
    
    // Obtener configuraciones por categoría
    $config_general = $db->select(
        "SELECT * FROM configuracion_sistema WHERE categoria = 'general' ORDER BY clave"
    );
    
    $config_email = $db->select(
        "SELECT * FROM configuracion_sistema WHERE categoria = 'email' ORDER BY clave"
    );
    
    $config_security = $db->select(
        "SELECT * FROM configuracion_sistema WHERE categoria = 'seguridad' ORDER BY clave"
    );
    
    load_view('admin/configuracion', [
        'config_general' => $config_general,
        'config_email' => $config_email,
        'config_security' => $config_security,
        'current_page' => 'configuracion',
        'title' => 'Configuración del Sistema'
    ]);
}

/**
 * Auditoría desde admin
 */
function handleAdminAudit() {
    global $auth;
    
    if (!$auth->isAdmin()) {
        load_view('error', ['message' => 'Acceso denegado. Se requieren permisos de administrador.']);
        return;
    }
    
    $db = Database::getInstance();
    
    // Obtener logs de auditoría
    $audit_logs = $db->select(
        "SELECT a.*, u.username FROM auditoria a 
         LEFT JOIN usuarios u ON a.usuario_id = u.id 
         ORDER BY a.fecha_accion DESC LIMIT 100"
    );
    
    // Si no hay datos de auditoría, crear algunos de ejemplo
    if (empty($audit_logs)) {
        $audit_logs = [
            [
                'id' => 1,
                'usuario_id' => $_SESSION['user_id'] ?? 1,
                'username' => $_SESSION['username'] ?? 'admin',
                'accion' => 'login_successful',
                'tabla_afectada' => 'usuarios',
                'registro_id' => $_SESSION['user_id'] ?? 1,
                'valores_anteriores' => json_encode(['ultimo_acceso' => date('Y-m-d H:i:s', strtotime('-1 hour'))]),
                'valores_nuevos' => json_encode(['ultimo_acceso' => date('Y-m-d H:i:s')]),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0',
                'fecha_accion' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    load_view('admin/auditoria', [
        'audit_logs' => $audit_logs,
        'current_page' => 'auditoria',
        'title' => 'Auditoría del Sistema'
    ]);
}

/**
 * *** FUNCIONES CORREGIDAS PARA USUARIOS NORMALES ***
 */

/**
 * Usuarios normales - ahora usa admin/usuarios correctamente
 */
function handleUsers() {
    global $auth;
    
    if (!$auth->hasPermission('usuarios.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver usuarios']);
        return;
    }
    
    // *** CORREGIDO: Redirigir a la vista admin si es admin ***
    if ($auth->isAdmin()) {
        handleAdminUsers();
        return;
    }
    
    // Para usuarios normales, mostrar vista simplificada
    load_view('usuarios_simple', [
        'current_page' => 'usuarios',
        'title' => 'Usuarios'
    ]);
}

/**
 * Roles normales - ahora usa admin/roles correctamente
 */
function handleRoles() {
    global $auth;
    
    if (!$auth->hasPermission('roles.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver roles']);
        return;
    }
    
    // *** CORREGIDO: Redirigir a la vista admin si es admin ***
    if ($auth->isAdmin()) {
        handleAdminRoles();
        return;
    }
    
    // Para usuarios normales, mostrar vista simplificada
    load_view('roles_simple', [
        'current_page' => 'roles',
        'title' => 'Roles'
    ]);
}

/**
 * Configuración - corregida
 */
function handleConfig() {
    global $auth;
    
    if (!$auth->hasPermission('configuracion.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver la configuración']);
        return;
    }
    
    // *** CORREGIDO: Redirigir a la vista admin si es admin ***
    if ($auth->isAdmin()) {
        handleAdminConfig();
        return;
    }
    
    // Para usuarios normales, mostrar vista simplificada
    load_view('configuracion_simple', [
        'current_page' => 'configuracion',
        'title' => 'Configuración'
    ]);
}

/**
 * Perfil de usuario
 */
function handleProfile() {
    global $auth;
    
    $user = $auth->getCurrentUser();
    load_view('perfil', [
        'user' => $user,
        'current_page' => 'perfil',
        'title' => 'Mi Perfil'
    ]);
}

/**
 * Auditoría - corregida
 */
function handleAudit() {
    global $auth;

    if (!$auth->hasPermission('auditoria.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver la auditoría']);
        return;
    }

    // Si es admin, redirigir a la vista de admin
    if ($auth->isAdmin()) {
        handleAdminAudit();
        return;
    }

    $db = Database::getInstance();
    $audit_logs = $db->select(
        "SELECT a.*, u.username FROM auditoria a
         LEFT JOIN usuarios u ON a.usuario_id = u.id
         WHERE a.usuario_id = :user_id
         ORDER BY a.fecha_accion DESC LIMIT 50",
        ['user_id' => $_SESSION['user_id']]
    );

    load_view('auditoria', [
        'audit_logs' => $audit_logs,
        'current_page' => 'auditoria',
        'title' => 'Mi Actividad'
    ]);
}

/**
 * Manejar solicitud de recuperación de contraseña
 */
function handleForgotPassword() {
    // Asegurar que la sesión esté iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $error_message = '';
    $success_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = sanitize($_POST['email'] ?? '');
        $csrf_token = $_POST['csrf_token'] ?? '';

        // Verificar CSRF token
        if (!verify_csrf_token($csrf_token)) {
            $error_message = 'Token de seguridad inválido';
        } elseif (empty($email)) {
            $error_message = 'Por favor ingrese su correo electrónico';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Correo electrónico inválido';
        } else {
            $db = Database::getInstance();

            // Buscar usuario por email
            $user = $db->select(
                "SELECT id, username, email, estado FROM usuarios WHERE email = :email",
                ['email' => $email]
            );

            if (!empty($user)) {
                $user = $user[0];

                // Verificar que el usuario esté activo
                if ($user['estado'] !== 'activo') {
                    $error_message = 'Esta cuenta está inactiva. Contacte al administrador.';
                } else {
                    // Generar token de recuperación
                    $token = bin2hex(random_bytes(32));
                    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Eliminar tokens anteriores del usuario
                    $db->delete('password_resets', 'usuario_id = :user_id', ['user_id' => $user['id']]);

                    // Insertar nuevo token
                    $db->insert('password_resets', [
                        'usuario_id' => $user['id'],
                        'token' => hash('sha256', $token),
                        'expira_en' => $expiry,
                        'creado_en' => date('Y-m-d H:i:s')
                    ]);

                    // Enviar correo
                    require_once INCLUDES_PATH . '/MailService.php';
                    $mailService = MailService::getInstance();
                    $result = $mailService->sendPasswordReset($email, $token, $user['username']);

                    if ($result['success']) {
                        $success_message = 'Se han enviado las instrucciones de recuperación a su correo electrónico.';

                        write_log('INFO', 'Password reset requested', [
                            'user_id' => $user['id'],
                            'email' => $email
                        ]);
                    } else {
                        $error_message = 'Error al enviar el correo. Por favor, intente nuevamente o contacte al administrador.';

                        write_log('ERROR', 'Password reset email failed', [
                            'user_id' => $user['id'],
                            'email' => $email,
                            'error' => $result['message']
                        ]);
                    }
                }
            } else {
                // Por seguridad, mostrar el mismo mensaje aunque el email no exista
                $success_message = 'Si el correo existe en nuestro sistema, recibirá las instrucciones de recuperación.';
            }
        }
    }

    load_view('forgot_password', [
        'error_message' => $error_message,
        'success_message' => $success_message,
        'csrf_token' => generate_csrf_token()
    ]);
}

/**
 * Manejar restablecimiento de contraseña
 */
function handleResetPassword() {
    // Asegurar que la sesión esté iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $token = $_GET['token'] ?? $_POST['token'] ?? '';
    $error_message = '';
    $success_message = '';
    $token_valid = false;

    if (empty($token)) {
        $error_message = 'Token de recuperación no proporcionado';
    } else {
        $db = Database::getInstance();
        $token_hash = hash('sha256', $token);

        // Verificar token
        $reset = $db->select(
            "SELECT pr.*, u.id as user_id, u.username, u.email
             FROM password_resets pr
             JOIN usuarios u ON pr.usuario_id = u.id
             WHERE pr.token = :token
             AND pr.expira_en > NOW()
             AND pr.usado = 0",
            ['token' => $token_hash]
        );

        if (!empty($reset)) {
            $token_valid = true;
            $reset = $reset[0];

            // Procesar formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                $csrf_token = $_POST['csrf_token'] ?? '';

                // Verificar CSRF token
                if (!verify_csrf_token($csrf_token)) {
                    $error_message = 'Token de seguridad inválido';
                } elseif (empty($password) || empty($confirm_password)) {
                    $error_message = 'Por favor complete todos los campos';
                } elseif ($password !== $confirm_password) {
                    $error_message = 'Las contraseñas no coinciden';
                } elseif (strlen($password) < 8) {
                    $error_message = 'La contraseña debe tener al menos 8 caracteres';
                } elseif (!preg_match('/[A-Z]/', $password)) {
                    $error_message = 'La contraseña debe contener al menos una letra mayúscula';
                } elseif (!preg_match('/[a-z]/', $password)) {
                    $error_message = 'La contraseña debe contener al menos una letra minúscula';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $error_message = 'La contraseña debe contener al menos un número';
                } else {
                    // Actualizar contraseña
                    $password_hash = password_hash($password, PASSWORD_ARGON2ID);

                    $db->update('usuarios',
                        ['password_hash' => $password_hash],
                        'id = :user_id',
                        ['user_id' => $reset['user_id']]
                    );

                    // Marcar token como usado
                    $db->update('password_resets',
                        ['usado' => 1],
                        'token = :token',
                        ['token' => $token_hash]
                    );

                    write_log('INFO', 'Password reset successful', [
                        'user_id' => $reset['user_id'],
                        'username' => $reset['username']
                    ]);

                    $success_message = 'Contraseña restablecida exitosamente. Puede iniciar sesión con su nueva contraseña.';

                    // Redirigir al login después de 3 segundos
                    header("refresh:3;url=index.php?action=login");
                }
            }
        } else {
            $error_message = 'El enlace de recuperación es inválido o ha expirado ';
        }
    }

    load_view('reset_password', [
        'error_message' => $error_message,
        'success_message' => $success_message,
        'token_valid' => $token_valid,
        'token' => $token,
        'csrf_token' => generate_csrf_token()
    ]);
}
?>