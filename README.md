# ORION.CORE

# Sistema de Gestión Web
## PHP8 + MariaDB + Diseño Minimalista

### Descripción
Sistema de gestión web desarrollado en PHP8 puro (sin frameworks) con base de datos MariaDB. Incluye sistema de autenticación robusto, gestión de usuarios, roles y permisos, y un panel de administración completo.

### Características Principales

#### 🔐 Seguridad
- Autenticación con hash de contraseñas (Argon2ID)
- Protección CSRF
- Control de sesiones
- Bloqueo automático por intentos fallidos
- Auditoría completa de acciones

#### 👥 Gestión de Usuarios
- CRUD completo de usuarios
- Sistema de roles y permisos granular
- Estados de usuario (activo/inactivo/bloqueado)
- Último acceso y seguimiento de actividad

#### ⚙️ Panel de Administración
- Botón especial de acceso para administradores
- Gestión de configuración del sistema
- Estadísticas en tiempo real
- Herramientas de mantenimiento

#### 🎨 Interfaz
- Diseño minimalista y responsivo
- CSS puro con variables CSS
- Iconos SVG integrados
- Experiencia de usuario optimizada

### Requisitos del Sistema

- **PHP**: 8.0 o superior
- **Base de Datos**: MariaDB 10.4+ / MySQL 8.0+
- **Servidor Web**: Apache/Nginx
- **Extensiones PHP**:
  - PDO MySQL
  - Session
  - JSON
  - Hash

### Instalación

#### 1. Clonar/Descargar el Sistema
```bash
# Si está en GitHub
git clone [URL-DEL-REPOSITORIO]

# O descargar y extraer los archivos
```

#### 2. Configurar Base de Datos

1. Crear la base de datos:
```sql
CREATE DATABASE sistema_gestion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importar la estructura:
```bash
mysql -u usuario -p sistema_gestion < database_structure.sql
```

3. Verificar la importación:
- Usuario administrador por defecto: `admin`
- Contraseña por defecto: `admin123`

#### 3. Configurar el Sistema

1. Editar `config.php`:
```php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'sistema_gestion');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

// URL del sistema
define('APP_URL', 'http://tu-dominio.com');
```

2. Configurar permisos de directorios:
```bash
chmod 755 logs/
chmod 755 uploads/
chmod 644 config.php
```

3. Configurar servidor web (Apache):
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    DocumentRoot /ruta/al/sistema
    DirectoryIndex index.php
    
    <Directory "/ruta/al/sistema">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### 4. Primer Acceso

1. Acceder a: `http://tu-dominio.com`
2. Iniciar sesión con:
   - Usuario: `admin`
   - Contraseña: `admin123`
3. **¡CAMBIAR INMEDIATAMENTE LA CONTRASEÑA POR DEFECTO!**

### Estructura del Proyecto

```
/
├── config.php              # Configuración principal
├── index.php              # Punto de entrada del sistema
├── database_structure.sql  # Estructura de la base de datos
├── README.md               # Este archivo
├── assets/
│   ├── css/
│   │   └── main.css        # Estilos principales
│   └── js/
│       └── main.js         # JavaScript principal
├── includes/
│   ├── Database.php        # Clase de base de datos
│   └── Auth.php           # Clase de autenticación
├── views/
│   ├── layout.php         # Layout principal
│   ├── login.php          # Página de login
│   ├── dashboard.php      # Dashboard principal
│   ├── error.php          # Página de error
│   └── admin/
│       └── panel.php      # Panel de administración
├── logs/                  # Logs del sistema (crear)
└── uploads/              # Archivos subidos (crear)
```

### Configuración de Seguridad

#### Variables de Entorno de Producción
```php
// En config.php para producción
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// HTTPS obligatorio
ini_set('session.cookie_secure', 1);
```

#### Headers de Seguridad
El sistema incluye headers de seguridad por defecto:
- X-Content-Type-Options
- X-Frame-Options
- X-XSS-Protection
- Referrer-Policy

### Uso del Sistema

#### Roles y Permisos
El sistema incluye 3 roles por defecto:
- **Administrador**: Acceso completo
- **Usuario**: Acceso estándar
- **Invitado**: Solo lectura

#### Panel de Administración
Los administradores tienen acceso a un botón especial que permite:
- Gestionar usuarios y roles
- Configurar el sistema
- Ver estadísticas
- Acceder a logs de auditoría
- Herramientas de mantenimiento

#### Gestión de Configuración
El sistema permite configurar:
- Nombre del sistema
- Timeouts de sesión
- Límites de intentos de login
- Configuración de email
- Modo mantenimiento

### API de Base de Datos

#### Clase Database
```php
$db = Database::getInstance();

// SELECT
$usuarios = $db->select("SELECT * FROM usuarios WHERE estado = ?", ['activo']);

// INSERT
$id = $db->insert('usuarios', [
    'username' => 'nuevo_usuario',
    'email' => 'email@ejemplo.com',
    'password_hash' => $hash
]);

// UPDATE
$affected = $db->update('usuarios', 
    ['estado' => 'inactivo'], 
    'id = ?', 
    [$user_id]
);
```

#### Clase Auth
```php
$auth = Auth::getInstance();

// Verificar autenticación
if ($auth->isAuthenticated()) {
    // Usuario autenticado
}

// Verificar permisos
if ($auth->hasPermission('usuarios.editar')) {
    // Usuario tiene permiso
}

// Verificar rol
if ($auth->isAdmin()) {
    // Usuario es administrador
}
```

### Personalización

#### Tema y Colores
Editar variables CSS en `assets/css/main.css`:
```css
:root {
    --color-primary: #2563eb;      /* Color principal */
    --color-secondary: #6b7280;    /* Color secundario */
    --bg-primary: #ffffff;         /* Fondo principal */
    /* ... más variables */
}
```

#### Agregar Nuevos Permisos
```sql
INSERT INTO permisos (codigo, nombre, descripcion, modulo) 
VALUES ('mi_modulo.accion', 'Mi Permiso', 'Descripción del permiso', 'mi_modulo');
```

### Logs y Monitoreo

#### Ubicación de Logs
- Sistema: `logs/YYYY-MM-DD.log`
- Auditoría: Tabla `auditoria` en BD

#### Niveles de Log
- DEBUG: Información detallada
- INFO: Información general
- WARNING: Advertencias
- ERROR: Errores del sistema

### Solución de Problemas

#### Problemas Comunes

1. **Error de conexión a BD**
   - Verificar credenciales en `config.php`
   - Comprobar que MariaDB esté corriendo
   - Validar permisos del usuario de BD

2. **Sesión no funciona**
   - Verificar permisos del directorio de sesiones
   - Comprobar configuración de PHP sessions

3. **Permisos de archivos**
   ```bash
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod 755 logs/ uploads/
   ```

4. **Error "Class not found"**
   - Verificar que todos los archivos estén subidos
   - Comprobar rutas en `config.php`

### Actualización

Para actualizar el sistema:
1. Hacer backup de la base de datos
2. Hacer backup de `config.php`
3. Reemplazar archivos del sistema
4. Ejecutar scripts de migración si los hay
5. Verificar configuración

### Seguridad en Producción

#### Lista de Verificación
- [ ] Cambiar contraseña de admin por defecto
- [ ] Configurar HTTPS
- [ ] Configurar backup automático
- [ ] Revisar permisos de archivos
- [ ] Configurar firewall
- [ ] Deshabilitar errores de PHP en pantalla
- [ ] Configurar logs de acceso del servidor web

### Soporte y Desarrollo

#### Estructura para Nuevas Funcionalidades
1. Crear nuevos permisos en BD
2. Agregar rutas en `index.php`
3. Crear vistas en `views/`
4. Actualizar navegación en `layout.php`

#### Convenciones de Código
- PSR-4 para autoloading
- camelCase para métodos
- snake_case para base de datos
- Comentarios en español
- Variables en inglés

### Licencia
[Especificar licencia aquí]

### Changelog
- v1.0.0: Versión inicial con funcionalidades básicas

---

Para más información o soporte, contactar al desarrollador.
