<?php
/**
 * Configuración principal del sistema
 * Sistema de Gestión - PHP8 + MariaDB
 */

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// ====================================================================
// CONFIGURACIÓN DE SESIONES - DEBE IR ANTES DE session_start()
// ====================================================================

// Configuración básica de sesiones (mejorada)
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 7200); // 2 horas
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Configurar directorio de sesiones personalizado si es necesario
$session_path = session_save_path();
if (empty($session_path) || !is_writable($session_path)) {
    $custom_session_path = __DIR__ . '/sessions';
    if (!is_dir($custom_session_path)) {
        mkdir($custom_session_path, 0755, true);
    }
    if (is_writable($custom_session_path)) {
        session_save_path($custom_session_path);
    }
}

// Configurar nombre de sesión personalizado
session_name('ORION_CORE_SESSION');

// ====================================================================
// CONSTANTES DEL SISTEMA
// ====================================================================

define('APP_NAME', 'Sistema de Gestión');
define('APP_VERSION', '1.0.0');
define('APP_ROOT', __DIR__);
define('APP_URL', 'http://core.orionar.cloud'); // Cambiar por la URL real

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'core');
define('DB_USER', 'global');
define('DB_PASS', 'K6uLe@4b'); // Cambiar por la contraseña real
define('DB_CHARSET', 'utf8mb4');

// Configuración de seguridad
define('SESSION_TIMEOUT', 3600); // 1 hora
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos
define('PASSWORD_MIN_LENGTH', 8);

// Configuración de archivos
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Rutas del sistema
define('VIEWS_PATH', APP_ROOT . '/views');
define('INCLUDES_PATH', APP_ROOT . '/includes');
define('ASSETS_PATH', APP_ROOT . '/assets');
define('UPLOADS_PATH', APP_ROOT . '/uploads');

// Configuración de logging
define('LOG_PATH', APP_ROOT . '/logs');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configuración de email (para futuras funcionalidades)
define('SMTP_HOST', 'srve42.controlvps.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'noreply@orionar.cloud');
define('SMTP_PASS', 'Um3mBcMh@gH');
define('SMTP_FROM', 'noreply@orionar.cloud');

// Headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Autoloader simple
spl_autoload_register(function ($class) {
    $file = INCLUDES_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// ====================================================================
// FUNCIONES DEL SISTEMA
// ====================================================================

// Función para renderizar vista con layout
function render_with_layout($content, $data = []) {
    // Extraer variables para el layout
    extract($data);
    
    // Incluir el layout principal
    $layout_file = VIEWS_PATH . '/layout.php';
    if (file_exists($layout_file)) {
        include $layout_file;
    } else {
        throw new Exception("Layout no encontrado");
    }
}

// Función para cargar vistas
function load_view($view, $data = []) {
    // Determinar la ruta del archivo
    $view_file = VIEWS_PATH . '/' . $view . '.php';
    
    // Si no existe, buscar en subdirectorios
    if (!file_exists($view_file)) {
        // Buscar en admin/
        $admin_file = VIEWS_PATH . '/admin/' . $view . '.php';
        if (file_exists($admin_file)) {
            $view_file = $admin_file;
        }
    }
    
    if (file_exists($view_file)) {
        // Extraer variables para la vista
        extract($data);
        
        // Si es una vista especial (login, error sin layout), incluir directamente
        if (in_array($view, ['login', 'error_simple'])) {
            include $view_file;
        } else {
            // Para otras vistas, usar el sistema de buffering
            ob_start();
            include $view_file;
            $content = ob_get_clean();
            
            // Si la vista ya renderizó el layout, no hacer nada más
            if (!$content) {
                return;
            }
            
            // Si hay contenido, significa que necesita layout
            render_with_layout($content, array_merge($data, [
                'current_page' => $data['current_page'] ?? basename($view),
                'title' => $data['title'] ?? 'Sistema',
                'breadcrumb' => $data['breadcrumb'] ?? 'Dashboard'
            ]));
        }
    } else {
        throw new Exception("Vista no encontrada: $view");
    }
}

// Función para redireccionar
function redirect($url, $code = 302) {
    header("Location: $url", true, $code);
    exit();
}

// Función para sanitizar datos
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// ====================================================================
// FUNCIONES CSRF MEJORADAS
// ====================================================================

// Función para generar token CSRF mejorada
function generate_csrf_token() {
    // Asegurar que la sesión esté iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // Si no existe token o es muy antiguo, generar nuevo
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) || 
        (time() - $_SESSION['csrf_token_time']) > 3600) { // 1 hora de vida
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
        
        // Debug log
        write_log('DEBUG', 'Nuevo token CSRF generado', [
            'token_length' => strlen($_SESSION['csrf_token']),
            'session_id' => session_id()
        ]);
    }
    
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF mejorada
function verify_csrf_token($token) {
    // Asegurar que la sesión esté iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // Debug log
    write_log('DEBUG', 'Verificando token CSRF', [
        'token_received' => !empty($token),
        'token_length_received' => strlen($token ?? ''),
        'session_token_exists' => isset($_SESSION['csrf_token']),
        'session_token_length' => strlen($_SESSION['csrf_token'] ?? ''),
        'session_id' => session_id(),
        'tokens_match' => isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '')
    ]);
    
    // Verificar que existan ambos tokens
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        write_log('WARNING', 'Token CSRF faltante', [
            'session_token_exists' => isset($_SESSION['csrf_token']),
            'received_token_empty' => empty($token)
        ]);
        return false;
    }
    
    // Verificar que el token no sea muy antiguo (opcional)
    if (isset($_SESSION['csrf_token_time']) && 
        (time() - $_SESSION['csrf_token_time']) > 7200) { // 2 horas máximo
        write_log('WARNING', 'Token CSRF expirado');
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
        return false;
    }
    
    // Comparación segura
    $is_valid = hash_equals($_SESSION['csrf_token'], $token);
    
    if (!$is_valid) {
        write_log('WARNING', 'Token CSRF inválido', [
            'expected_length' => strlen($_SESSION['csrf_token']),
            'received_length' => strlen($token),
            'session_id' => session_id()
        ]);
    }
    
    return $is_valid;
}

// Función para limpiar token CSRF
function clear_csrf_token() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }
}

// Función de debugging para problemas CSRF
function debug_csrf_info() {
    return [
        'session_status' => session_status(),
        'session_id' => session_id(),
        'csrf_token_exists' => isset($_SESSION['csrf_token']),
        'csrf_token_length' => isset($_SESSION['csrf_token']) ? strlen($_SESSION['csrf_token']) : 0,
        'csrf_token_time' => $_SESSION['csrf_token_time'] ?? null,
        'csrf_token_age' => isset($_SESSION['csrf_token_time']) ? (time() - $_SESSION['csrf_token_time']) : null,
        'session_data_keys' => array_keys($_SESSION ?? [])
    ];
}

// ====================================================================
// FUNCIÓN DE LOGGING
// ====================================================================

function write_log($level, $message, $context = []) {
    if (!is_dir(LOG_PATH)) {
        mkdir(LOG_PATH, 0755, true);
    }
    
    $log_file = LOG_PATH . '/' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $context_str = !empty($context) ? ' ' . json_encode($context) : '';
    $log_entry = "[$timestamp] [$level] $message$context_str" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// ====================================================================
// MANEJO DE ERRORES
// ====================================================================

// Manejo de errores personalizado
set_error_handler(function($severity, $message, $file, $line) {
    write_log('ERROR', "PHP Error: $message in $file:$line", ['severity' => $severity]);
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Manejo de excepciones no capturadas
set_exception_handler(function($exception) {
    write_log('ERROR', 'Uncaught exception: ' . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    if (ini_get('display_errors')) {
        echo '<h1>Error del Sistema</h1>';
        echo '<p><strong>Mensaje:</strong> ' . $exception->getMessage() . '</p>';
        echo '<p><strong>Archivo:</strong> ' . $exception->getFile() . '</p>';
        echo '<p><strong>Línea:</strong> ' . $exception->getLine() . '</p>';
        echo '<pre>' . $exception->getTraceAsString() . '</pre>';
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Se ha producido un error interno. Por favor, contacte al administrador.';
    }
    exit();
});

// ====================================================================
// INICIALIZAR SESIÓN - DESPUÉS DE TODAS LAS CONFIGURACIONES
// ====================================================================

// Inicializar sesión solo si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    if (!session_start()) {
        error_log("Error: No se pudo iniciar la sesión");
        die("Error interno del sistema");
    }
}

// Regenerar ID de sesión periódicamente para seguridad
if (!isset($_SESSION['session_created'])) {
    $_SESSION['session_created'] = time();
} elseif (time() - $_SESSION['session_created'] > 1800) { // 30 minutos
    session_regenerate_id(true);
    $_SESSION['session_created'] = time();
}

// Verificar IP del cliente para prevenir session hijacking (opcional)
if (!isset($_SESSION['client_ip'])) {
    $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
} elseif ($_SESSION['client_ip'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
    // IP cambió, posible hijacking - destruir sesión
    write_log('WARNING', 'Posible session hijacking detectado', [
        'original_ip' => $_SESSION['client_ip'],
        'current_ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'session_id' => session_id()
    ]);
    session_destroy();
    session_start();
    $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
}

// Verificar timeout de sesión
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    write_log('INFO', 'Sesión expirada por timeout', [
        'user_id' => $_SESSION['user_id'] ?? 'no_user',
        'last_activity' => $_SESSION['last_activity'],
        'timeout' => SESSION_TIMEOUT
    ]);
    
    session_destroy();
    session_start();
    $_SESSION['timeout_message'] = 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.';
}
$_SESSION['last_activity'] = time();

?>