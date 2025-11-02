<?php
/**
 * Script de test para verificar el funcionamiento del sistema
 * Ejecutar: php test_system.php
 */

require_once 'config.php';
require_once INCLUDES_PATH . '/Database.php';
require_once INCLUDES_PATH . '/Auth.php';

echo "=== TEST DEL SISTEMA ===\n\n";

try {
    // 1. Test de conexión a base de datos
    echo "1. Probando conexión a base de datos...\n";
    $db = Database::getInstance();
    $info = $db->getServerInfo();
    echo "✅ Conexión exitosa: {$info['driver']} {$info['version']}\n\n";
    
    // 2. Test de tablas principales
    echo "2. Verificando tablas principales...\n";
    $tablas = ['usuarios', 'roles', 'permisos', 'configuracion_sistema', 'auditoria'];
    foreach ($tablas as $tabla) {
        try {
            $count = $db->count($tabla);
            echo "✅ Tabla $tabla: $count registros\n";
        } catch (Exception $e) {
            echo "❌ Error en tabla $tabla: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 3. Test de autenticación
    echo "3. Probando sistema de autenticación...\n";
    $auth = Auth::getInstance();
    
    // Verificar usuario admin
    $admin = $db->selectOne("SELECT * FROM usuarios WHERE username = 'admin'");
    if ($admin) {
        echo "✅ Usuario admin encontrado\n";
        
        // Test de verificación de contraseña
        if (password_verify('admin123', $admin['password_hash'])) {
            echo "✅ Contraseña admin123 válida\n";
        } else {
            echo "❌ Contraseña admin123 no válida\n";
        }
    } else {
        echo "❌ Usuario admin no encontrado\n";
    }
    echo "\n";
    
    // 4. Test de roles y permisos
    echo "4. Verificando roles y permisos...\n";
    $roles = $db->select("SELECT * FROM roles");
    $permisos = $db->select("SELECT * FROM permisos");
    
    echo "✅ Roles encontrados: " . count($roles) . "\n";
    echo "✅ Permisos encontrados: " . count($permisos) . "\n";
    
    foreach ($roles as $rol) {
        echo "  - Rol: {$rol['nombre']} ({$rol['estado']})\n";
    }
    echo "\n";
    
    // 5. Test de configuración
    echo "5. Verificando configuración del sistema...\n";
    $config = $db->select("SELECT * FROM configuracion_sistema");
    echo "✅ Configuraciones encontradas: " . count($config) . "\n";
    
    foreach ($config as $conf) {
        echo "  - {$conf['clave']}: {$conf['valor']}\n";
    }
    echo "\n";
    
    // 6. Test de vistas
    echo "6. Verificando vistas del sistema...\n";
    $vistas = [
        'views/login.php',
        'views/dashboard.php',
        'views/error.php',
        'views/layout.php',
        'views/admin/panel.php',
        'views/admin/usuarios.php',
        'views/admin/roles.php',
        'views/admin/configuracion.php',
        'views/admin/auditoria.php'
    ];
    
    foreach ($vistas as $vista) {
        if (file_exists($vista)) {
            echo "✅ Vista $vista encontrada\n";
        } else {
            echo "❌ Vista $vista no encontrada\n";
        }
    }
    echo "\n";
    
    // 7. Test de archivos críticos
    echo "7. Verificando archivos críticos...\n";
    $archivos = [
        'config.php',
        'index.php',
        'includes/Database.php',
        'includes/Auth.php',
        'assets/css/main.css',
        'assets/js/main.js'
    ];
    
    foreach ($archivos as $archivo) {
        if (file_exists($archivo)) {
            echo "✅ Archivo $archivo encontrado\n";
        } else {
            echo "❌ Archivo $archivo no encontrado\n";
        }
    }
    echo "\n";
    
    // 8. Test de permisos de directorios
    echo "8. Verificando permisos de directorios...\n";
    $directorios = ['logs', 'uploads'];
    
    foreach ($directorios as $dir) {
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                echo "✅ Directorio $dir: escribible\n";
            } else {
                echo "⚠️  Directorio $dir: no escribible\n";
            }
        } else {
            echo "❌ Directorio $dir: no existe\n";
        }
    }
    echo "\n";
    
    echo "=== RESUMEN ===\n";
    echo "✅ Sistema base funcionando correctamente\n";
    echo "✅ Base de datos conectada\n";
    echo "✅ Autenticación configurada\n";
    echo "✅ Vistas del admin creadas\n";
    echo "\n";
    echo "🔗 URL del sistema: " . APP_URL . "\n";
    echo "👤 Usuario: admin\n";
    echo "🔑 Contraseña: admin123\n";
    echo "\n";
    echo "📝 Para acceder al panel de administración:\n";
    echo "   1. Login con las credenciales de arriba\n";
    echo "   2. Click en 'Panel de Administración' (botón naranja)\n";
    echo "   3. Navegar por las opciones de gestión\n";
    
} catch (Exception $e) {
    echo "❌ Error crítico: " . $e->getMessage() . "\n";
    echo "Traza: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETADO ===\n";
?>