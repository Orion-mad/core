<?php
/**
 * Script de debug para recuperación de contraseña
 * Eliminar después de solucionar el problema
 */

require_once 'config.php';
require_once INCLUDES_PATH . '/Database.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Debug Password Reset</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}";
echo ".success{color:#4ec9b0;} .error{color:#f48771;} .info{color:#9cdcfe;} .warning{color:#dcdcaa;}";
echo "pre{background:#252526;padding:15px;border-radius:5px;border-left:3px solid #007acc;overflow-x:auto;}";
echo "h2{color:#4ec9b0;border-bottom:2px solid #007acc;padding-bottom:5px;}";
echo "</style></head><body>";

echo "<h1>🔍 Debug: Recuperación de Contraseña</h1>";
echo "<p class='info'>Fecha: " . date('Y-m-d H:i:s') . "</p><hr>";

$db = Database::getInstance();

// 1. Verificar si la tabla existe
echo "<h2>1. Verificar tabla password_resets</h2>";
try {
    $result = $db->select("SHOW TABLES LIKE 'password_resets'");
    if (!empty($result)) {
        echo "<span class='success'>✅ Tabla password_resets existe</span><br>";

        // Mostrar estructura
        $structure = $db->select("DESCRIBE password_resets");
        echo "<pre>";
        echo "Estructura de la tabla:\n";
        foreach ($structure as $column) {
            echo sprintf("  %-15s %-20s %s\n", $column['Field'], $column['Type'], $column['Null']);
        }
        echo "</pre>";

        // Contar registros
        $count = $db->select("SELECT COUNT(*) as total FROM password_resets")[0]['total'];
        echo "<p>Total de registros: <strong>$count</strong></p>";

        // Mostrar registros recientes
        if ($count > 0) {
            $records = $db->select("SELECT id, usuario_id, LEFT(token, 20) as token_preview,
                                    expira_en, creado_en, usado
                                    FROM password_resets
                                    ORDER BY creado_en DESC LIMIT 5");
            echo "<pre>";
            echo "Últimos 5 registros:\n";
            print_r($records);
            echo "</pre>";
        }
    } else {
        echo "<span class='error'>❌ Tabla password_resets NO existe</span><br>";
        echo "<p class='warning'>Ejecutando creación de tabla...</p>";

        // Crear tabla
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            token VARCHAR(255) NOT NULL,
            expira_en DATETIME NOT NULL,
            creado_en DATETIME NOT NULL,
            usado TINYINT(1) DEFAULT 0,
            INDEX idx_token (token),
            INDEX idx_expiry (expira_en)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->execute($sql);
        echo "<span class='success'>✅ Tabla creada exitosamente</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Error: " . $e->getMessage() . "</span><br>";
}

// 2. Probar generación y verificación de token
echo "<h2>2. Prueba de Token</h2>";
$test_token = bin2hex(random_bytes(32));
$test_token_hash = hash('sha256', $test_token);

echo "<pre>";
echo "Token original:      $test_token\n";
echo "Token hasheado:      $test_token_hash\n";
echo "Longitud original:   " . strlen($test_token) . " caracteres\n";
echo "Longitud hasheado:   " . strlen($test_token_hash) . " caracteres\n";
echo "</pre>";

// 3. Verificar si hay tokens activos
echo "<h2>3. Tokens Activos (no expirados y no usados)</h2>";
try {
    $active_tokens = $db->select(
        "SELECT pr.id, pr.usuario_id, u.username, u.email,
                LEFT(pr.token, 20) as token_preview,
                pr.expira_en, pr.creado_en, pr.usado,
                TIMESTAMPDIFF(MINUTE, NOW(), pr.expira_en) as minutos_restantes
         FROM password_resets pr
         LEFT JOIN usuarios u ON pr.usuario_id = u.id
         WHERE pr.expira_en > NOW() AND pr.usado = 0
         ORDER BY pr.creado_en DESC"
    );

    if (!empty($active_tokens)) {
        echo "<pre>";
        print_r($active_tokens);
        echo "</pre>";
    } else {
        echo "<p class='warning'>⚠️ No hay tokens activos en este momento</p>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Error: " . $e->getMessage() . "</span>";
}

// 4. Simular búsqueda de token (como lo hace handleResetPassword)
if (isset($_GET['test_token'])) {
    echo "<h2>4. Prueba de Búsqueda de Token</h2>";
    $test_token_from_url = $_GET['test_token'];
    $test_hash = hash('sha256', $test_token_from_url);

    echo "<pre>";
    echo "Token recibido en URL:  $test_token_from_url\n";
    echo "Hash calculado:         $test_hash\n";
    echo "</pre>";

    $result = $db->select(
        "SELECT pr.*, u.username, u.email
         FROM password_resets pr
         JOIN usuarios u ON pr.usuario_id = u.id
         WHERE pr.token = :token
         AND pr.expira_en > NOW()
         AND pr.usado = 0",
        ['token' => $test_hash]
    );

    if (!empty($result)) {
        echo "<span class='success'>✅ Token encontrado y válido</span><br>";
        echo "<pre>";
        print_r($result[0]);
        echo "</pre>";
    } else {
        echo "<span class='error'>❌ Token NO encontrado o expirado</span><br>";
        echo "<span class='error'>{$test_hash}</span><br>";
        echo "<span class='error'>{$test_token_from_url}</span><br>";

        // Buscar sin restricciones para ver si existe
        $any_result = $db->select(
            "SELECT * FROM password_resets WHERE token = :token",
            ['token' => $test_hash]
        );

        if (!empty($any_result)) {
            echo "<p class='warning'>Token existe pero:</p>";
            echo "<pre>";
            $rec = $any_result[0];
            echo "Expirado: " . ($rec['expira_en'] <= date('Y-m-d H:i:s') ? 'SÍ' : 'NO') . "\n";
            echo "Usado: " . ($rec['usado'] ? 'SÍ' : 'NO') . "\n";
            echo "Expira en: " . $rec['expira_en'] . "\n";
            echo "Ahora: " . date('Y-m-d H:i:s') . "\n";
            echo "</pre>";
        }
    }
}

// 5. Información de configuración
echo "<h2>5. Configuración</h2>";
echo "<pre>";
echo "APP_URL:  " . APP_URL . "\n";
echo "DB_NAME:  " . DB_NAME . "\n";
echo "DB_HOST:  " . DB_HOST . "\n";
echo "</pre>";

echo "<hr>";
echo "<p class='info'>💡 Para probar un token específico, agrega ?test_token=TU_TOKEN a la URL</p>";
echo "<p class='warning'>⚠️ Elimina este archivo cuando termines de debuggear</p>";

echo "</body></html>";
?>
