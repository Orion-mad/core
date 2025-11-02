<?php
/**
 * VERIFICACIÓN Y CORRECCIÓN DE SESIONES
 * Ejecutar este script para diagnosticar problemas CSRF
 */

// Incluir configuración
require_once 'config.php';

echo "<h1>🔧 Diagnóstico de Problemas CSRF</h1>\n";
echo "<pre>\n";

// 1. Verificar configuración PHP de sesiones
echo "=== CONFIGURACIÓN PHP ===\n";
echo "session.auto_start: " . ini_get('session.auto_start') . "\n";
echo "session.use_cookies: " . ini_get('session.use_cookies') . "\n";
echo "session.use_only_cookies: " . ini_get('session.use_only_cookies') . "\n";
echo "session.cookie_httponly: " . ini_get('session.cookie_httponly') . "\n";
echo "session.cookie_secure: " . ini_get('session.cookie_secure') . "\n";
echo "session.gc_maxlifetime: " . ini_get('session.gc_maxlifetime') . "\n";
echo "session.save_path: " . session_save_path() . "\n";
echo "session.save_path writable: " . (is_writable(session_save_path()) ? 'SÍ' : 'NO') . "\n";
echo "\n";

// 2. Iniciar sesión y verificar
echo "=== ESTADO DE SESIÓN ===\n";
echo "Session status before start: " . session_status() . "\n";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

echo "Session status after start: " . session_status() . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session name: " . session_name() . "\n";
echo "Session cookie params: " . json_encode(session_get_cookie_params()) . "\n";
echo "\n";

// 3. Test de generación de token CSRF
echo "=== TEST TOKEN CSRF ===\n";




$token1 = generate_csrf_token();
echo "Token 1 generado: " . substr($token1, 0, 16) . "... (length: " . strlen($token1) . ")\n";

$token2 = generate_csrf_token();
echo "Token 2 generado: " . substr($token2, 0, 16) . "... (length: " . strlen($token2) . ")\n";

echo "Tokens son iguales: " . ($token1 === $token2 ? 'SÍ' : 'NO') . "\n";
echo "\n";

// 4. Test de validación
echo "=== TEST VALIDACIÓN ===\n";
$is_valid = verify_csrf_token($token1);
echo "Token válido contra sí mismo: " . ($is_valid ? 'SÍ' : 'NO') . "\n";

$is_invalid = verify_csrf_token('token_falso');
echo "Token falso es inválido: " . ($is_invalid ? 'NO (ERROR!)' : 'SÍ') . "\n";
echo "\n";

// 5. Información de debug
echo "=== DEBUG INFO ===\n";
$debug_info = debug_csrf_info();
foreach ($debug_info as $key => $value) {
    echo "$key: " . json_encode($value) . "\n";
}
echo "\n";

// 6. Variables de sesión
echo "=== VARIABLES DE SESIÓN ===\n";
echo "Session data: " . json_encode($_SESSION, JSON_PRETTY_PRINT) . "\n";
echo "\n";

// 7. Test de escritura en directorio de sesiones
echo "=== TEST ESCRITURA ===\n";
$session_path = session_save_path();
$test_file = $session_path . '/test_' . time() . '.tmp';

try {
    if (file_put_contents($test_file, 'test')) {
        echo "✅ Escritura en directorio de sesiones: OK\n";
        unlink($test_file);
    } else {
        echo "❌ Error escribiendo en directorio de sesiones\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// 8. Sugerencias de corrección
echo "\n=== SUGERENCIAS ===\n";

if (!is_writable(session_save_path())) {
    echo "🔧 PROBLEMA: Directorio de sesiones no escribible\n";
    echo "   Solución: chmod 755 " . session_save_path() . "\n";
}

if (ini_get('session.auto_start')) {
    echo "🔧 RECOMENDACIÓN: Desactivar session.auto_start en php.ini\n";
}

if (!ini_get('session.use_only_cookies')) {
    echo "🔧 RECOMENDACIÓN: Activar session.use_only_cookies en php.ini\n";
}

if (!ini_get('session.cookie_httponly')) {
    echo "🔧 RECOMENDACIÓN: Activar session.cookie_httponly en php.ini\n";
}

echo "\n";
echo "=== INSTRUCCIONES ===\n";
echo "1. Reemplazar funciones CSRF en config.php con las de csrf_fix.php\n";
echo "2. Reemplazar función handleLogin en index.php\n";
echo "3. Si hay problemas de permisos, ejecutar:\n";
echo "   chmod 755 " . session_save_path() . "\n";
echo "4. Limpiar cache del navegador (Ctrl+F5)\n";
echo "5. Revisar logs del sistema en logs/\n";

echo "</pre>\n";
?>