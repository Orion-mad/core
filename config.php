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

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Constantes del sistema
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
define('SMTP_USER', 'noreply@orionar.cloud"');
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

// Función para cargar vistas
function load_view($view, $data = []) {
    extract($data);
    $view_file = VIEWS_PATH . '/' . $view . '.php';
    if (file_exists($view_file)) {
        require_once $view_file;
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

// Función para generar token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Función para logging
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
    
    if (APP_ENV !== 'development') {
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Se ha producido un error interno. Por favor, contacte al administrador.';
    } else {
        echo '<h1>Error del Sistema</h1>';
        echo '<p><strong>Mensaje:</strong> ' . $exception->getMessage() . '</p>';
        echo '<p><strong>Archivo:</strong> ' . $exception->getFile() . '</p>';
        echo '<p><strong>Línea:</strong> ' . $exception->getLine() . '</p>';
        echo '<pre>' . $exception->getTraceAsString() . '</pre>';
    }
    exit();
});

// Inicializar sesión
session_start();

// Verificar timeout de sesión
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_destroy();
    session_start();
    $_SESSION['timeout_message'] = 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.';
}
$_SESSION['last_activity'] = time();

?>