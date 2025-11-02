# ORION.CORE
<h1>Sistema de Gestión Web</h1>
<h2>PHP8 + MariaDB + Diseño Minimalista</h2>
<h3>Descripción</h3>
<p>Sistema de gestión web desarrollado en PHP8 puro (sin frameworks) con base de datos MariaDB. Incluye sistema de autenticación robusto, gestión de usuarios, roles y permisos, y un panel de administración completo.</p>
<h3>Características Principales</h3>
<h4>🔐 Seguridad</h4>
<ul>
  <li>Autenticación con hash de contraseñas (Argon2ID)</li>
  <li>Protección CSRF</li>
  <li>Control de sesiones</li>
  <li>Bloqueo automático por intentos fallidos</li>
  <li>Auditoría completa de acciones</li>
</ul>
<h4>👥 Gestión de Usuarios</h4>
<ul>
  <li>CRUD completo de usuarios</li>
  <li>Sistema de roles y permisos granular</li>
  <li>Estados de usuario (activo/inactivo/bloqueado)</li>
  <li>Último acceso y seguimiento de actividad</li>
</ul>
<h4>⚙️ Panel de Administración</h4>
<ul>
  <li>Botón especial de acceso para administradores</li>
  <li>Gestión de configuración del sistema</li>
  <li>Estadísticas en tiempo real</li>
  <li>Herramientas de mantenimiento</li>
</ul>
<h4>🎨 Interfaz</h4>
<ul>
  <li>Diseño minimalista y responsivo</li>
  <li>CSS puro con variables CSS</li>
  <li>Iconos SVG integrados</li>
  <li>Experiencia de usuario optimizada</li>
</ul>
<h3>Requisitos del Sistema</h3>
<ul>
  <li><strong>PHP</strong>: 8.0 o superior</li>
  <li><strong>Base de Datos</strong>: MariaDB 10.4+ / MySQL 8.0+</li>
  <li><strong>Servidor Web</strong>: Apache/Nginx</li>
  <li><strong>Extensiones PHP</strong>:
    <ul>
      <li>PDO MySQL</li>
      <li>Session</li>
      <li>JSON</li>
      <li>Hash</li>
    </ul>
  </li>
</ul>
<h3>Instalación</h3>
<h4>1. Clonar/Descargar el Sistema</h4>
<pre># Si está en GitHub  git clone [URL-DEL-REPOSITORIO]    # O descargar y extraer los archivos  </pre>
<h4>2. Configurar Base de Datos</h4>
<ol>
  <li>Crear la base de datos:</li>
</ol>
<pre>CREATE DATABASE sistema_gestion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;  </pre>
<ol start="2">
  <li>Importar la estructura:</li>
</ol>
<pre>mysql -u usuario -p sistema_gestion &lt; database_structure.sql  </pre>
<ol start="3">
  <li>Verificar la importación:</li>
</ol>
<ul>
  <li>Usuario administrador por defecto: admin</li>
  <li>Contraseña por defecto: admin123</li>
</ul>
<h4>3. Configurar el Sistema</h4>
<ol>
  <li>Editar config.php:</li>
</ol>
<pre>// Configuración de base de datos  define('DB_HOST', 'localhost');  define('DB_PORT', '3306');  define('DB_NAME', 'sistema_gestion');  define('DB_USER', 'tu_usuario');  define('DB_PASS', 'tu_contraseña');    // URL del sistema  define('APP_URL', 'http://tu-dominio.com');  </pre>
<ol start="2">
  <li>Configurar permisos de directorios:</li>
</ol>
<pre>chmod 755 logs/  chmod 755 uploads/  chmod 644 config.php  </pre>
<ol start="3">
  <li>Configurar servidor web (Apache):</li>
</ol>
<pre>&lt;VirtualHost *:80&gt;      ServerName tu-dominio.com      DocumentRoot /ruta/al/sistema      DirectoryIndex index.php            &lt;Directory "/ruta/al/sistema"&gt;          AllowOverride All          Require all granted      &lt;/Directory&gt;  &lt;/VirtualHost&gt;  </pre>
<h4>4. Primer Acceso</h4>
<ol>
  <li>Acceder a: http://tu-dominio.com</li>
  <li>Iniciar sesión con:
    <ul>
      <li>Usuario: admin</li>
      <li>Contraseña: admin123</li>
    </ul>
  </li>
  <li><strong>¡CAMBIAR INMEDIATAMENTE LA CONTRASEÑA POR DEFECTO!</strong></li>
</ol>
<h3>Estructura del Proyecto</h3>
<pre>/  ├── config.php              # Configuración principal  ├── index.php              # Punto de entrada del sistema  ├── database_structure.sql  # Estructura de la base de datos  ├── README.md               # Este archivo  ├── assets/  │   ├── css/  │   │   └── main.css        # Estilos principales  │   └── js/  │       └── main.js         # JavaScript principal  ├── includes/  │   ├── Database.php        # Clase de base de datos  │   └── Auth.php           # Clase de autenticación  ├── views/  │   ├── layout.php         # Layout principal  │   ├── login.php          # Página de login  │   ├── dashboard.php      # Dashboard principal  │   ├── error.php          # Página de error  │   └── admin/  │       └── panel.php      # Panel de administración  ├── logs/                  # Logs del sistema (crear)  └── uploads/              # Archivos subidos (crear)  </pre>
<h3>Configuración de Seguridad</h3>
<h4>Variables de Entorno de Producción</h4>
<pre>// En config.php para producción  ini_set('display_errors', 0);  ini_set('display_startup_errors', 0);  error_reporting(0);    // HTTPS obligatorio  ini_set('session.cookie_secure', 1);  </pre>
<h4>Headers de Seguridad</h4>
<p>El sistema incluye headers de seguridad por defecto:</p>
<ul>
  <li>X-Content-Type-Options</li>
  <li>X-Frame-Options</li>
  <li>X-XSS-Protection</li>
  <li>Referrer-Policy</li>
</ul>
<h3>Uso del Sistema</h3>
<h4>Roles y Permisos</h4>
<p>El sistema incluye 3 roles por defecto:</p>
<ul>
  <li><strong>Administrador</strong>: Acceso completo</li>
  <li><strong>Usuario</strong>: Acceso estándar</li>
  <li><strong>Invitado</strong>: Solo lectura</li>
</ul>
<h4>Panel de Administración</h4>
<p>Los administradores tienen acceso a un botón especial que permite:</p>
<ul>
  <li>Gestionar usuarios y roles</li>
  <li>Configurar el sistema</li>
  <li>Ver estadísticas</li>
  <li>Acceder a logs de auditoría</li>
  <li>Herramientas de mantenimiento</li>
</ul>
<h4>Gestión de Configuración</h4>
<p>El sistema permite configurar:</p>
<ul>
  <li>Nombre del sistema</li>
  <li>Timeouts de sesión</li>
  <li>Límites de intentos de login</li>
  <li>Configuración de email</li>
  <li>Modo mantenimiento</li>
</ul>
<h3>API de Base de Datos</h3>
<h4>Clase Database</h4>
<pre>$db = Database::getInstance();    // SELECT  $usuarios = $db-&gt;select("SELECT * FROM usuarios WHERE estado = ?", ['activo']);    // INSERT  $id = $db-&gt;insert('usuarios', [      'username' =&gt; 'nuevo_usuario',      'email' =&gt; 'email@ejemplo.com',      'password_hash' =&gt; $hash  ]);    // UPDATE  $affected = $db-&gt;update('usuarios',       ['estado' =&gt; 'inactivo'],       'id = ?',       [$user_id]  );  </pre>
<h4>Clase Auth</h4>
<pre>$auth = Auth::getInstance();    // Verificar autenticación  if ($auth-&gt;isAuthenticated()) {      // Usuario autenticado  }    // Verificar permisos  if ($auth-&gt;hasPermission('usuarios.editar')) {      // Usuario tiene permiso  }    // Verificar rol  if ($auth-&gt;isAdmin()) {      // Usuario es administrador  }  </pre>
<h3>Personalización</h3>
<h4>Tema y Colores</h4>
<p>Editar variables CSS en assets/css/main.css:</p>
<pre>:root {      --color-primary: #2563eb;      /* Color principal */      --color-secondary: #6b7280;    /* Color secundario */      --bg-primary: #ffffff;         /* Fondo principal */      /* ... más variables */  }  </pre>
<h4>Agregar Nuevos Permisos</h4>
<pre>INSERT INTO permisos (codigo, nombre, descripcion, modulo)   VALUES ('mi_modulo.accion', 'Mi Permiso', 'Descripción del permiso', 'mi_modulo');  </pre>
<h3>Logs y Monitoreo</h3>
<h4>Ubicación de Logs</h4>
<ul>
  <li>Sistema: logs/YYYY-MM-DD.log</li>
  <li>Auditoría: Tabla auditoria en BD</li>
</ul>
<h4>Niveles de Log</h4>
<ul>
  <li>DEBUG: Información detallada</li>
  <li>INFO: Información general</li>
  <li>WARNING: Advertencias</li>
  <li>ERROR: Errores del sistema</li>
</ul>
<h3>Solución de Problemas</h3>
<h4>Problemas Comunes</h4>
<ol>
  <li>
    <p><strong>Error de conexión a BD</strong></p>
    <ul>
      <li>Verificar credenciales en config.php</li>
      <li>Comprobar que MariaDB esté corriendo</li>
      <li>Validar permisos del usuario de BD</li>
    </ul>
  </li>
  <li>
    <p><strong>Sesión no funciona</strong></p>
    <ul>
      <li>Verificar permisos del directorio de sesiones</li>
      <li>Comprobar configuración de PHP sessions</li>
    </ul>
  </li>
  <li>
    <p><strong>Permisos de archivos</strong></p>
    <pre>find . -type f -exec chmod 644 {} \;  find . -type d -exec chmod 755 {} \;  chmod 755 logs/ uploads/  </pre>
  </li>
  <li>
    <p><strong>Error "Class not found"</strong></p>
    <ul>
      <li>Verificar que todos los archivos estén subidos</li>
      <li>Comprobar rutas en config.php</li>
    </ul>
  </li>
</ol>
<h3>Actualización</h3>
<p>Para actualizar el sistema:</p>
<ol>
  <li>Hacer backup de la base de datos</li>
  <li>Hacer backup de config.php</li>
  <li>Reemplazar archivos del sistema</li>
  <li>Ejecutar scripts de migración si los hay</li>
  <li>Verificar configuración</li>
</ol>
<h3>Seguridad en Producción</h3>
<h4>Lista de Verificación</h4>
<ul>
  <li>[ ] Cambiar contraseña de admin por defecto</li>
  <li>[ ] Configurar HTTPS</li>
  <li>[ ] Configurar backup automático</li>
  <li>[ ] Revisar permisos de archivos</li>
  <li>[ ] Configurar firewall</li>
  <li>[ ] Deshabilitar errores de PHP en pantalla</li>
  <li>[ ] Configurar logs de acceso del servidor web</li>
</ul>
<h3>Soporte y Desarrollo</h3>
<h4>Estructura para Nuevas Funcionalidades</h4>
<ol>
  <li>Crear nuevos permisos en BD</li>
  <li>Agregar rutas en index.php</li>
  <li>Crear vistas en views/</li>
  <li>Actualizar navegación en layout.php</li>
</ol>
<h4>Convenciones de Código</h4>
<ul>
  <li>PSR-4 para autoloading</li>
  <li>camelCase para métodos</li>
  <li>snake_case para base de datos</li>
  <li>Comentarios en español</li>
  <li>Variables en inglés</li>
</ul>
<h3>Licencia</h3>
<p>[Especificar licencia aquí]</p>
<h3>Changelog</h3>
<ul>
  <li>v1.0.0: Versión inicial con funcionalidades básicas</li>
</ul>
<hr>
<p>Para más información o soporte, contactar al desarrollador.</p>
<h1>Sistema de Gestión Web</h1>
<h2>PHP8 + MariaDB + Diseño Minimalista</h2>
<h3>Descripción</h3>
<p>Sistema de gestión web desarrollado en PHP8 puro (sin frameworks) con base de datos MariaDB. Incluye sistema de autenticación robusto, gestión de usuarios, roles y permisos, y un panel de administración completo.</p>
<h3>Características Principales</h3>
<h4>🔐 Seguridad</h4>
<ul>
  <li>Autenticación con hash de contraseñas (Argon2ID)</li>
  <li>Protección CSRF</li>
  <li>Control de sesiones</li>
  <li>Bloqueo automático por intentos fallidos</li>
  <li>Auditoría completa de acciones</li>
</ul>
<h4>👥 Gestión de Usuarios</h4>
<ul>
  <li>CRUD completo de usuarios</li>
  <li>Sistema de roles y permisos granular</li>
  <li>Estados de usuario (activo/inactivo/bloqueado)</li>
  <li>Último acceso y seguimiento de actividad</li>
</ul>
<h4>⚙️ Panel de Administración</h4>
<ul>
  <li>Botón especial de acceso para administradores</li>
  <li>Gestión de configuración del sistema</li>
  <li>Estadísticas en tiempo real</li>
  <li>Herramientas de mantenimiento</li>
</ul>
<h4>🎨 Interfaz</h4>
<ul>
  <li>Diseño minimalista y responsivo</li>
  <li>CSS puro con variables CSS</li>
  <li>Iconos SVG integrados</li>
  <li>Experiencia de usuario optimizada</li>
</ul>
<h3>Requisitos del Sistema</h3>
<ul>
  <li><strong>PHP</strong>: 8.0 o superior</li>
  <li><strong>Base de Datos</strong>: MariaDB 10.4+ / MySQL 8.0+</li>
  <li><strong>Servidor Web</strong>: Apache/Nginx</li>
  <li><strong>Extensiones PHP</strong>:
    <ul>
      <li>PDO MySQL</li>
      <li>Session</li>
      <li>JSON</li>
      <li>Hash</li>
    </ul>
  </li>
</ul>
<h3>Instalación</h3>
<h4>1. Clonar/Descargar el Sistema</h4>
<pre># Si está en GitHub  git clone [URL-DEL-REPOSITORIO]    # O descargar y extraer los archivos  </pre>
<h4>2. Configurar Base de Datos</h4>
<ol>
  <li>Crear la base de datos:</li>
</ol>
<pre>CREATE DATABASE sistema_gestion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;  </pre>
<ol start="2">
  <li>Importar la estructura:</li>
</ol>
<pre>mysql -u usuario -p sistema_gestion &lt; database_structure.sql  </pre>
<ol start="3">
  <li>Verificar la importación:</li>
</ol>
<ul>
  <li>Usuario administrador por defecto: admin</li>
  <li>Contraseña por defecto: admin123</li>
</ul>
<h4>3. Configurar el Sistema</h4>
<ol>
  <li>Editar config.php:</li>
</ol>
<pre>// Configuración de base de datos  define('DB_HOST', 'localhost');  define('DB_PORT', '3306');  define('DB_NAME', 'sistema_gestion');  define('DB_USER', 'tu_usuario');  define('DB_PASS', 'tu_contraseña');    // URL del sistema  define('APP_URL', 'http://tu-dominio.com');  </pre>
<ol start="2">
  <li>Configurar permisos de directorios:</li>
</ol>
<pre>chmod 755 logs/  chmod 755 uploads/  chmod 644 config.php  </pre>
<ol start="3">
  <li>Configurar servidor web (Apache):</li>
</ol>
<pre>&lt;VirtualHost *:80&gt;      ServerName tu-dominio.com      DocumentRoot /ruta/al/sistema      DirectoryIndex index.php            &lt;Directory "/ruta/al/sistema"&gt;          AllowOverride All          Require all granted      &lt;/Directory&gt;  &lt;/VirtualHost&gt;  </pre>
<h4>4. Primer Acceso</h4>
<ol>
  <li>Acceder a: http://tu-dominio.com</li>
  <li>Iniciar sesión con:
    <ul>
      <li>Usuario: admin</li>
      <li>Contraseña: admin123</li>
    </ul>
  </li>
  <li><strong>¡CAMBIAR INMEDIATAMENTE LA CONTRASEÑA POR DEFECTO!</strong></li>
</ol>
<h3>Estructura del Proyecto</h3>
<pre>/  ├── config.php              # Configuración principal  ├── index.php              # Punto de entrada del sistema  ├── database_structure.sql  # Estructura de la base de datos  ├── README.md               # Este archivo  ├── assets/  │   ├── css/  │   │   └── main.css        # Estilos principales  │   └── js/  │       └── main.js         # JavaScript principal  ├── includes/  │   ├── Database.php        # Clase de base de datos  │   └── Auth.php           # Clase de autenticación  ├── views/  │   ├── layout.php         # Layout principal  │   ├── login.php          # Página de login  │   ├── dashboard.php      # Dashboard principal  │   ├── error.php          # Página de error  │   └── admin/  │       └── panel.php      # Panel de administración  ├── logs/                  # Logs del sistema (crear)  └── uploads/              # Archivos subidos (crear)  </pre>
<h3>Configuración de Seguridad</h3>
<h4>Variables de Entorno de Producción</h4>
<pre>// En config.php para producción  ini_set('display_errors', 0);  ini_set('display_startup_errors', 0);  error_reporting(0);    // HTTPS obligatorio  ini_set('session.cookie_secure', 1);  </pre>
<h4>Headers de Seguridad</h4>
<p>El sistema incluye headers de seguridad por defecto:</p>
<ul>
  <li>X-Content-Type-Options</li>
  <li>X-Frame-Options</li>
  <li>X-XSS-Protection</li>
  <li>Referrer-Policy</li>
</ul>
<h3>Uso del Sistema</h3>
<h4>Roles y Permisos</h4>
<p>El sistema incluye 3 roles por defecto:</p>
<ul>
  <li><strong>Administrador</strong>: Acceso completo</li>
  <li><strong>Usuario</strong>: Acceso estándar</li>
  <li><strong>Invitado</strong>: Solo lectura</li>
</ul>
<h4>Panel de Administración</h4>
<p>Los administradores tienen acceso a un botón especial que permite:</p>
<ul>
  <li>Gestionar usuarios y roles</li>
  <li>Configurar el sistema</li>
  <li>Ver estadísticas</li>
  <li>Acceder a logs de auditoría</li>
  <li>Herramientas de mantenimiento</li>
</ul>
<h4>Gestión de Configuración</h4>
<p>El sistema permite configurar:</p>
<ul>
  <li>Nombre del sistema</li>
  <li>Timeouts de sesión</li>
  <li>Límites de intentos de login</li>
  <li>Configuración de email</li>
  <li>Modo mantenimiento</li>
</ul>
<h3>API de Base de Datos</h3>
<h4>Clase Database</h4>
<pre>$db = Database::getInstance();    // SELECT  $usuarios = $db-&gt;select("SELECT * FROM usuarios WHERE estado = ?", ['activo']);    // INSERT  $id = $db-&gt;insert('usuarios', [      'username' =&gt; 'nuevo_usuario',      'email' =&gt; 'email@ejemplo.com',      'password_hash' =&gt; $hash  ]);    // UPDATE  $affected = $db-&gt;update('usuarios',       ['estado' =&gt; 'inactivo'],       'id = ?',       [$user_id]  );  </pre>
<h4>Clase Auth</h4>
<pre>$auth = Auth::getInstance();    // Verificar autenticación  if ($auth-&gt;isAuthenticated()) {      // Usuario autenticado  }    // Verificar permisos  if ($auth-&gt;hasPermission('usuarios.editar')) {      // Usuario tiene permiso  }    // Verificar rol  if ($auth-&gt;isAdmin()) {      // Usuario es administrador  }  </pre>
<h3>Personalización</h3>
<h4>Tema y Colores</h4>
<p>Editar variables CSS en assets/css/main.css:</p>
<pre>:root {      --color-primary: #2563eb;      /* Color principal */      --color-secondary: #6b7280;    /* Color secundario */      --bg-primary: #ffffff;         /* Fondo principal */      /* ... más variables */  }  </pre>
<h4>Agregar Nuevos Permisos</h4>
<pre>INSERT INTO permisos (codigo, nombre, descripcion, modulo)   VALUES ('mi_modulo.accion', 'Mi Permiso', 'Descripción del permiso', 'mi_modulo');  </pre>
<h3>Logs y Monitoreo</h3>
<h4>Ubicación de Logs</h4>
<ul>
  <li>Sistema: logs/YYYY-MM-DD.log</li>
  <li>Auditoría: Tabla auditoria en BD</li>
</ul>
<h4>Niveles de Log</h4>
<ul>
  <li>DEBUG: Información detallada</li>
  <li>INFO: Información general</li>
  <li>WARNING: Advertencias</li>
  <li>ERROR: Errores del sistema</li>
</ul>
<h3>Solución de Problemas</h3>
<h4>Problemas Comunes</h4>
<ol>
  <li>
    <p><strong>Error de conexión a BD</strong></p>
    <ul>
      <li>Verificar credenciales en config.php</li>
      <li>Comprobar que MariaDB esté corriendo</li>
      <li>Validar permisos del usuario de BD</li>
    </ul>
  </li>
  <li>
    <p><strong>Sesión no funciona</strong></p>
    <ul>
      <li>Verificar permisos del directorio de sesiones</li>
      <li>Comprobar configuración de PHP sessions</li>
    </ul>
  </li>
  <li>
    <p><strong>Permisos de archivos</strong></p>
    <pre>find . -type f -exec chmod 644 {} \;  find . -type d -exec chmod 755 {} \;  chmod 755 logs/ uploads/  </pre>
  </li>
  <li>
    <p><strong>Error "Class not found"</strong></p>
    <ul>
      <li>Verificar que todos los archivos estén subidos</li>
      <li>Comprobar rutas en config.php</li>
    </ul>
  </li>
</ol>
<h3>Actualización</h3>
<p>Para actualizar el sistema:</p>
<ol>
  <li>Hacer backup de la base de datos</li>
  <li>Hacer backup de config.php</li>
  <li>Reemplazar archivos del sistema</li>
  <li>Ejecutar scripts de migración si los hay</li>
  <li>Verificar configuración</li>
</ol>
<h3>Seguridad en Producción</h3>
<h4>Lista de Verificación</h4>
<ul>
  <li>[ ] Cambiar contraseña de admin por defecto</li>
  <li>[ ] Configurar HTTPS</li>
  <li>[ ] Configurar backup automático</li>
  <li>[ ] Revisar permisos de archivos</li>
  <li>[ ] Configurar firewall</li>
  <li>[ ] Deshabilitar errores de PHP en pantalla</li>
  <li>[ ] Configurar logs de acceso del servidor web</li>
</ul>
<h3>Soporte y Desarrollo</h3>
<h4>Estructura para Nuevas Funcionalidades</h4>
<ol>
  <li>Crear nuevos permisos en BD</li>
  <li>Agregar rutas en index.php</li>
  <li>Crear vistas en views/</li>
  <li>Actualizar navegación en layout.php</li>
</ol>
<h4>Convenciones de Código</h4>
<ul>
  <li>PSR-4 para autoloading</li>
  <li>camelCase para métodos</li>
  <li>snake_case para base de datos</li>
  <li>Comentarios en español</li>
  <li>Variables en inglés</li>
</ul>
<h3>Licencia</h3>
<p>[Especificar licencia aquí]</p>
<h3>Changelog</h3>
<ul>
  <li>v1.0.0: Versión inicial con funcionalidades básicas</li>
</ul>
<hr>
<p>Para más información o soporte, contactar al desarrollador.</p>


sistema-gestion/<br>
│<br>
├── 📄 index.php                    # Punto de entrada principal<br>
├── 📄 config.php                   # Configuración global del sistema<br>
├── 📄 database_structure.sql       # Estructura de base de datos<br>
├── 📄 README.md                    # Documentación completa<br>
├── 📄 .htaccess                    # Configuración Apache (a crear)<br>
│<br>
├── 📁 assets/                      # Recursos estáticos<br>
│   ├── 📁 css/<br>
│   │   ├── 📄 main.css             # Estilos principales (16KB)<br>
│   │   └── 📄 admin.css            # Estilos específicos admin (futuro)<br>
│   ├── 📁 js/<br>
│   │   ├── 📄 main.js              # JavaScript principal<br>
│   │   └── 📄 admin.js             # JS específico admin (futuro)<br>
│   ├── 📁 images/                  # Imágenes del sistema (a crear)<br>
│   │   ├── 📄 logo.png<br>
│   │   └── 📄 favicon.ico<br>
│   └── 📁 fonts/                   # Fuentes personalizadas (opcional)<br>
│<br>
├── 📁 includes/                    # Clases y librerías PHP<br>
│   ├── 📄 Database.php             # Clase de conexión y operaciones BD<br>
│   ├── 📄 Auth.php                 # Sistema de autenticación<br>
│   ├── 📄 User.php                 # Modelo de usuario (futuro)<br>
│   ├── 📄 Role.php                 # Modelo de roles (futuro)<br>
│   ├── 📄 Permission.php           # Modelo de permisos (futuro)<br>
│   ├── 📄 AuditLog.php             # Sistema de auditoría (futuro)<br>
│   └── 📄 helpers.php              # Funciones auxiliares (futuro)<br>
│<br>
├── 📁 views/                       # Vistas y templates<br>
│   ├── 📄 layout.php               # Layout principal del sistema<br>
│   ├── 📄 login.php                # Página de login<br>
│   ├── 📄 dashboard.php            # Dashboard principal<br>
│   ├── 📄 error.php                # Página de errores<br>
│   ├── 📄 usuarios.php             # Lista de usuarios (futuro)<br>
│   ├── 📄 roles.php                # Gestión de roles (futuro)<br>
│   ├── 📄 perfil.php               # Perfil de usuario (futuro)<br>
│   ├── 📄 auditoria.php            # Logs de auditoría (futuro)<br>
│   ├── 📄 configuracion.php        # Configuración sistema (futuro)<br>
│   │<br>
│   ├── 📁 admin/                   # Vistas del panel de administración<br>
│   │   ├── 📄 panel.php            # Panel principal de admin<br>
│   │   ├── 📄 usuarios.php         # Gestión avanzada usuarios<br>
│   │   ├── 📄 roles.php            # Gestión de roles y permisos<br>
│   │   ├── 📄 permisos.php         # Gestión de permisos<br>
│   │   ├── 📄 configuracion.php    # Configuración del sistema<br>
│   │   └── 📄 estadisticas.php     # Estadísticas avanzadas<br>
│   │<br>
│   ├── 📁 components/              # Componentes reutilizables (futuro)<br>
│   │   ├── 📄 modal.php            # Modal genérico<br>
│   │   ├── 📄 table.php            # Tabla con paginación<br>
│   │   ├── 📄 form.php             # Formularios<br>
│   │   └── 📄 alert.php            # Alertas y notificaciones<br>
│   │<br>
│   └── 📁 emails/                  # Templates de email (futuro)<br>
│       ├── 📄 welcome.php          # Email de bienvenida<br>
│       ├── 📄 password_reset.php   # Recuperación de contraseña<br>
│       └── 📄 notification.php     # Notificaciones generales<br>
│<br>
├── 📁 api/                         # API REST (expansión futura)<br>
│   ├── 📄 index.php                # Enrutador API<br>
│   ├── 📄 auth.php                 # Endpoints de autenticación<br>
│   ├── 📄 users.php                # Endpoints de usuarios<br>
│   ├── 📄 roles.php                # Endpoints de roles<br>
│   └── 📁 v1/                      # Versionado de API<br>
│       ├── 📄 users.php<br>
│       └── 📄 auth.php<br>
│<br>
├── 📁 uploads/                     # Archivos subidos por usuarios<br>
│   ├── 📁 avatars/                 # Avatares de usuarios<br>
│   ├── 📁 documents/               # Documentos generales<br>
│   ├── 📁 temp/                    # Archivos temporales<br>
│   └── 📄 .htaccess                # Protección directorio<br>
│<br>
├── 📁 logs/                        # Logs del sistema<br>
│   ├── 📄 2024-11-02.log          # Logs por fecha<br>
│   ├── 📄 error.log                # Errores específicos<br>
│   ├── 📄 access.log               # Log de accesos<br>
│   └── 📄 .htaccess                # Protección directorio<br>
│<br>
├── 📁 cache/                       # Cache del sistema (futuro)<br>
│   ├── 📁 views/                   # Cache de vistas<br>
│   ├── 📁 queries/                 # Cache de consultas<br>
│   └── 📄 .htaccess                # Protección directorio<br>
│<br>
├── 📁 config/                      # Configuraciones adicionales (futuro)<br>
│   ├── 📄 database.php             # Configuración BD<br>
│   ├── 📄 mail.php                 # Configuración email<br>
│   ├── 📄 app.php                  # Configuración aplicación<br>
│   └── 📄 security.php             # Configuración seguridad<br>
│<br>
├── 📁 migrations/                  # Migraciones de BD (futuro)<br>
│   ├── 📄 001_initial_structure.sql<br>
│   ├── 📄 002_add_user_fields.sql<br>
│   └── 📄 003_update_permissions.sql<br>
│<br>
├── 📁 backups/                     # Backups automáticos (futuro)<br>
│   ├── 📁 database/<br>
│   ├── 📁 files/<br>
│   └── 📄 .htaccess<br>
│<br>
├── 📁 tests/                       # Tests unitarios (futuro)<br>
│   ├── 📄 DatabaseTest.php<br>
│   ├── 📄 AuthTest.php<br>
│   └── 📄 UserTest.php<br>
│<br>
├── 📁 docs/                        # Documentación técnica (futuro)<br>
│   ├── 📄 installation.md<br>
│   ├── 📄 api-reference.md<br>
│   ├── 📄 security-guide.md<br>
│   └── 📁 images/<br>
│<br>
└── 📁 vendor/                      # Librerías externas (si se usan)<br>
└── 📄 autoload.php
