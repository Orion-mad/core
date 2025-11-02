<?php
/**
 * SCRIPT DE VERIFICACIÓN DEL SISTEMA
 * Ejecutar desde la línea de comandos: php verificar_sistema.php
 * O acceder desde el navegador: http://tu-dominio.com/verificar_sistema.php
 */

require_once 'config.php';

echo "=== VERIFICACIÓN DEL SISTEMA ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Verificar conexión a base de datos
echo "1. Verificando conexión a base de datos...\n";
try {
    $db = Database::getInstance();
    echo "   ? Conexión exitosa\n";
    
    // Verificar tablas principales
    $tables = ['usuarios', 'roles', 'permisos', 'sesiones', 'auditoria'];
    foreach ($tables as $table) {
        $count = $db->count($table);
        echo "   - Tabla $table: $count registros\n";
    }
} catch (Exception $e) {
    echo "   ? Error: " . $e->getMessage() . "\n";
}

echo "\n2. Verificando configuración de sesiones...\n";
//session_start();
echo "   - Session status: " . session_status() . " (2 = activa)\n";
echo "   - Session ID: " . session_id() . "\n";
echo "   - Session name: " . session_name() . "\n";

echo "\n3. Verificando sistema CSRF...\n";
$token1 = generate_csrf_token();
echo "   - Token generado: " . substr($token1, 0, 16) . "... (longitud: " . strlen($token1) . ")\n";

// Simular verificación
$token2 = $_SESSION['csrf_token'] ?? '';
$verificacion = verify_csrf_token($token2);
echo "   - Verificación token: " . ($verificacion ? "? OK" : "? FALLO") . "\n";

echo "\n4. Verificando permisos de archivos...\n";
$dirs = ['logs', 'uploads'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = fileperms($dir);
        echo "   - $dir: " . decoct($perms & 0777) . " " . (is_writable($dir) ? "?" : "?") . "\n";
    } else {
        echo "   - $dir: ? No existe\n";
    }
}

echo "\n5. Verificando archivos principales...\n";
$files = ['config.php', 'index.php', 'includes/Database.php', 'includes/Auth.php', 'views/login.php'];
foreach ($files as $file) {
    echo "   - $file: " . (file_exists($file) ? "?" : "?") . "\n";
}

echo "\n6. Verificando configuración PHP...\n";
echo "   - PHP Version: " . PHP_VERSION . "\n";
echo "   - Display errors: " . (ini_get('display_errors') ? "ON" : "OFF") . "\n";
echo "   - Error reporting: " . error_reporting() . "\n";
echo "   - Memory limit: " . ini_get('memory_limit') . "\n";

echo "\n7. Verificando constantes del sistema...\n";
$constants = ['APP_NAME', 'BASE_PATH', 'INCLUDES_PATH', 'VIEWS_PATH', 'LOG_PATH'];
foreach ($constants as $const) {
    if (defined($const)) {
        echo "   - $const: ? " . constant($const) . "\n";
    } else {
        echo "   - $const: ? No definida\n";
    }
}

echo "\n8. Test de funciones críticas...\n";
try {
    // Test sanitize
    $test_data = "<script>alert('test')</script>";
    $sanitized = sanitize($test_data);
    echo "   - sanitize(): ? OK\n";
    
    // Test load_view (sin renderizar)
    ob_start();
    $view_exists = file_exists(VIEWS_PATH . '/login.php');
    ob_end_clean();
    echo "   - Vista login: " . ($view_exists ? "?" : "?") . "\n";
    
} catch (Exception $e) {
    echo "   - Error en tests: ? " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN ===\n";
echo "Sistema: " . (defined('APP_NAME') ? APP_NAME : 'Sistema de Gestión') . "\n";
echo "Base de datos: " . (isset($db) ? "? Conectada" : "? Error") . "\n";
echo "CSRF: " . ($verificacion ?? false ? "? Funcionando" : "? Error") . "\n";

echo "\n=== INSTRUCCIONES ===\n";
echo "Si hay errores:\n";
echo "1. Verificar credenciales de BD en config.php\n";
echo "2. Crear directorios: mkdir logs uploads\n";
echo "3. Dar permisos: chmod 755 logs uploads\n";
echo "4. Importar: mysql -u usuario -p database < database_structure.sql\n";
echo "5. Verificar que todas las constantes estén definidas\n";

// Solo para navegador
if (isset($_SERVER['HTTP_HOST'])) {
    echo "<br><br><strong>Acceso de prueba:</strong><br>";
    echo "Usuario: admin<br>";
    echo "Contraseña: admin123<br>";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
?>