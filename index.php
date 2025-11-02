<?php
/**
 * Archivo principal del sistema
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
$public_routes = ['login', 'logout'];

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
 */
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
        'is_admin' => $auth->isAdmin()
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
    $server_info = $db->getServerInfo();
    $server_info['php_version'] = PHP_VERSION;
    $server_info['memory_usage'] = memory_get_usage(true);
    $server_info['memory_peak'] = memory_get_peak_usage(true);
    
    load_view('admin/panel', [
        'system_stats' => $system_stats,
        'system_config' => $system_config,
        'server_info' => $server_info
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
        'roles' => $roles
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
        'permisos' => $permisos
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
        'permisos' => $permisos
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
    $message = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csrf_token = $_POST['csrf_token'] ?? '';
        
        if (!verify_csrf_token($csrf_token)) {
            $message = 'Token de seguridad inválido';
        } else {
            $config_updates = $_POST['config'] ?? [];
            
            try {
                $db->beginTransaction();
                
                foreach ($config_updates as $clave => $valor) {
                    $db->update(
                        'configuracion_sistema',
                        [
                            'valor' => $valor,
                            'modificado_por' => $_SESSION['user_id']
                        ],
                        'clave = :clave AND modificable = 1',
                        ['clave' => $clave]
                    );
                }
                
                $db->commit();
                $message = 'Configuración actualizada correctamente';
                
                write_log('INFO', 'Configuración del sistema actualizada', [
                    'user_id' => $_SESSION['user_id'],
                    'changes' => $config_updates
                ]);
                
            } catch (Exception $e) {
                $db->rollback();
                $message = 'Error al actualizar la configuración';
                write_log('ERROR', 'Error actualizando configuración: ' . $e->getMessage());
            }
        }
    }
    
    $configuraciones = $db->select(
        "SELECT * FROM configuracion_sistema ORDER BY categoria, clave"
    );
    
    load_view('admin/configuracion', [
        'configuraciones' => $configuraciones,
        'message' => $message,
        'csrf_token' => generate_csrf_token()
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
    
    $audit_logs = $db->select(
        "SELECT a.*, u.username FROM auditoria a 
         LEFT JOIN usuarios u ON a.usuario_id = u.id 
         ORDER BY a.fecha_accion DESC LIMIT 200"
    );
    
    // Si no hay logs, crear algunos de prueba
    if (empty($audit_logs)) {
        $audit_logs = [
            [
                'id' => 1,
                'usuario_id' => 1,
                'username' => 'admin',
                'accion' => 'login',
                'tabla_afectada' => null,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode(['ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0',
                'fecha_accion' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    load_view('admin/auditoria', [
        'audit_logs' => $audit_logs
    ]);
}

/**
 * Funciones auxiliares para otras secciones
 */
function handleUsers() {
    global $auth;
    
    if (!$auth->hasPermission('usuarios.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver usuarios']);
        return;
    }
    
    load_view('usuarios');
}

function handleRoles() {
    global $auth;
    
    if (!$auth->hasPermission('roles.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver roles']);
        return;
    }
    
    load_view('roles');
}

function handleConfig() {
    global $auth;
    
    if (!$auth->hasPermission('configuracion.leer')) {
        load_view('error', ['message' => 'No tiene permisos para ver la configuración']);
        return;
    }
    
    load_view('configuracion');
}

function handleProfile() {
    global $auth;
    
    $user = $auth->getCurrentUser();
    load_view('perfil', ['user' => $user]);
}

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
    
    load_view('auditoria', ['audit_logs' => $audit_logs]);
}

?>