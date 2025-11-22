<?php
/**
 * Generador manual de enlace de recuperación
 * ELIMINAR después de solucionar el problema
 */

require_once 'config.php';
require_once INCLUDES_PATH . '/Database.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Generar Enlace de Recuperación</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:0 auto;}";
echo ".success{color:green;background:#e8f5e9;padding:15px;border-left:4px solid green;margin:10px 0;word-break:break-all;}";
echo ".error{color:red;background:#ffebee;padding:15px;border-left:4px solid red;margin:10px 0;}";
echo ".info{color:#1976d2;background:#e3f2fd;padding:15px;border-left:4px solid #1976d2;margin:10px 0;}";
echo "pre{background:#f5f5f5;padding:15px;border-radius:5px;overflow-x:auto;word-break:break-all;white-space:pre-wrap;}";
echo "input,button{padding:10px;margin:5px;font-size:14px;}";
echo "input[type=email]{width:300px;}";
echo "</style></head><body>";

echo "<h1>🔧 Generador Manual de Enlace de Recuperación</h1>";
echo "<hr>";

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        echo "<div class='error'>❌ Por favor ingresa un email</div>";
    } else {
        // Buscar usuario
        $user = $db->select(
            "SELECT id, username, email FROM usuarios WHERE email = :email",
            ['email' => $email]
        );

        if (empty($user)) {
            echo "<div class='error'>❌ Usuario no encontrado con ese email</div>";
        } else {
            $user = $user[0];

            // Generar nuevo token
            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            echo "<div class='info'>";
            echo "<h3>📊 Información del Token</h3>";
            echo "<pre>";
            echo "Usuario ID:       " . $user['id'] . "\n";
            echo "Username:         " . $user['username'] . "\n";
            echo "Email:            " . $user['email'] . "\n";
            echo "Token generado:   " . $token . "\n";
            echo "Token hasheado:   " . $token_hash . "\n";
            echo "Expira en:        " . $expiry . "\n";
            echo "</pre>";
            echo "</div>";

            // Eliminar tokens anteriores
            $deleted = $db->delete('password_resets', 'usuario_id = :user_id', ['user_id' => $user['id']]);
            echo "<p>🗑️ Tokens anteriores eliminados: " . ($deleted ? 'Sí' : 'No') . "</p>";

            // Insertar nuevo token
            $db->insert('password_resets', [
                'usuario_id' => $user['id'],
                'token' => $token_hash,
                'expira_en' => $expiry,
                'creado_en' => date('Y-m-d H:i:s'),
                'usado' => 0
            ]);

            echo "<div class='success'>";
            echo "<h3>✅ Token creado exitosamente</h3>";
            echo "</div>";

            // Generar enlace
            $reset_link = APP_URL . "/index.php?action=reset_password&token=" . urlencode($token);

            echo "<div class='success'>";
            echo "<h3>🔗 Enlace de Recuperación</h3>";
            echo "<p><strong>Copia este enlace y úsalo en tu navegador:</strong></p>";
            echo "<pre>" . htmlspecialchars($reset_link) . "</pre>";
            echo "<p><a href='" . $reset_link . "' target='_blank' style='background:#667eea;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>Abrir Enlace</a></p>";
            echo "</div>";

            // Verificar en BD
            echo "<div class='info'>";
            echo "<h3>🔍 Verificación en Base de Datos</h3>";
            $verify = $db->select(
                "SELECT * FROM password_resets WHERE usuario_id = :user_id ORDER BY creado_en DESC LIMIT 1",
                ['user_id' => $user['id']]
            );
            echo "<pre>";
            print_r($verify[0]);
            echo "</pre>";
            echo "</div>";
        }
    }
}

// Mostrar tokens existentes
echo "<h2>📋 Tokens Existentes en la Base de Datos</h2>";
$tokens = $db->select(
    "SELECT pr.*, u.username, u.email
     FROM password_resets pr
     LEFT JOIN usuarios u ON pr.usuario_id = u.id
     ORDER BY pr.creado_en DESC
     LIMIT 10"
);

if (!empty($tokens)) {
    echo "<table border='1' cellpadding='10' style='width:100%;border-collapse:collapse;'>";
    echo "<tr style='background:#667eea;color:white;'>";
    echo "<th>ID</th><th>Usuario</th><th>Email</th><th>Token (primeros 20)</th><th>Expira</th><th>Usado</th>";
    echo "</tr>";
    foreach ($tokens as $t) {
        $expired = strtotime($t['expira_en']) < time();
        $rowStyle = $expired ? 'background:#ffebee;' : ($t['usado'] ? 'background:#fff3e0;' : 'background:#e8f5e9;');
        echo "<tr style='$rowStyle'>";
        echo "<td>" . $t['id'] . "</td>";
        echo "<td>" . htmlspecialchars($t['username'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($t['email'] ?? 'N/A') . "</td>";
        echo "<td>" . substr($t['token'], 0, 20) . "...</td>";
        echo "<td>" . $t['expira_en'] . ($expired ? ' ❌' : ' ✅') . "</td>";
        echo "<td>" . ($t['usado'] ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='info'>No hay tokens en la base de datos</p>";
}

echo "<hr>";
echo "<h2>➕ Generar Nuevo Enlace</h2>";
echo "<form method='POST'>";
echo "<input type='email' name='email' placeholder='email@ejemplo.com' required>";
echo "<button type='submit' name='generate' style='background:#667eea;color:white;border:none;cursor:pointer;'>Generar Enlace</button>";
echo "</form>";

echo "<hr>";
echo "<div class='error'>";
echo "<strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (generate_reset_link.php) cuando termines de debuggear por seguridad.";
echo "</div>";

echo "</body></html>";
?>
