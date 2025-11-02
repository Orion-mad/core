# core
orion system core base
Desarrollar un sistema base, desde donde se completara la programación según el sistema de gestión requerido. Donde se implementaran los archivos necesarios para el manejo de gestión por modulos, con acceso seguro mediante email y clave, con permisos por usuarios , módulos e ítems, controlados desde un sector administrativo
Este sistema base contara con un sector para facilitar la creación de los CRUDs por parte del programador, utilizando  la siguiente metodologia: se envia a un php las variables y parámetros de la consulta, el php devuelve un json para que un js  devuelva el codigo html resultante, evitando tener que repetir codigo por cada CRUD
La interfaz debe ser UX/UI responsive, con bootstrap 5.3, php8 JS, mysql/mariaDB

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