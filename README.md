# core
orion system core base
Desarrollar un sistema base, desde donde se completara la programación según el sistema de gestión requerido. Donde se implementaran los archivos necesarios para el manejo de gestión por modulos, con acceso seguro mediante email y clave, con permisos por usuarios , módulos e ítems, controlados desde un sector administrativo
Este sistema base contara con un sector para facilitar la creación de los CRUDs por parte del programador, utilizando  la siguiente metodologia: se envia a un php las variables y parámetros de la consulta, el php devuelve un json para que un js  devuelva el codigo html resultante, evitando tener que repetir codigo por cada CRUD
La interfaz debe ser UX/UI responsive, con bootstrap 5.3, php8 JS, mysql/mariaDB


sistema-gestion/
│
├── 📄 index.php                    # Punto de entrada principal
├── 📄 config.php                   # Configuración global del sistema
├── 📄 database_structure.sql       # Estructura de base de datos
├── 📄 README.md                    # Documentación completa
├── 📄 .htaccess                    # Configuración Apache (a crear)
│
├── 📁 assets/                      # Recursos estáticos
│   ├── 📁 css/
│   │   ├── 📄 main.css             # Estilos principales (16KB)
│   │   └── 📄 admin.css            # Estilos específicos admin (futuro)
│   ├── 📁 js/
│   │   ├── 📄 main.js              # JavaScript principal
│   │   └── 📄 admin.js             # JS específico admin (futuro)
│   ├── 📁 images/                  # Imágenes del sistema (a crear)
│   │   ├── 📄 logo.png
│   │   └── 📄 favicon.ico
│   └── 📁 fonts/                   # Fuentes personalizadas (opcional)
│
├── 📁 includes/                    # Clases y librerías PHP
│   ├── 📄 Database.php             # Clase de conexión y operaciones BD
│   ├── 📄 Auth.php                 # Sistema de autenticación
│   ├── 📄 User.php                 # Modelo de usuario (futuro)
│   ├── 📄 Role.php                 # Modelo de roles (futuro)
│   ├── 📄 Permission.php           # Modelo de permisos (futuro)
│   ├── 📄 AuditLog.php             # Sistema de auditoría (futuro)
│   └── 📄 helpers.php              # Funciones auxiliares (futuro)
│
├── 📁 views/                       # Vistas y templates
│   ├── 📄 layout.php               # Layout principal del sistema
│   ├── 📄 login.php                # Página de login
│   ├── 📄 dashboard.php            # Dashboard principal
│   ├── 📄 error.php                # Página de errores
│   ├── 📄 usuarios.php             # Lista de usuarios (futuro)
│   ├── 📄 roles.php                # Gestión de roles (futuro)
│   ├── 📄 perfil.php               # Perfil de usuario (futuro)
│   ├── 📄 auditoria.php            # Logs de auditoría (futuro)
│   ├── 📄 configuracion.php        # Configuración sistema (futuro)
│   │
│   ├── 📁 admin/                   # Vistas del panel de administración
│   │   ├── 📄 panel.php            # Panel principal de admin
│   │   ├── 📄 usuarios.php         # Gestión avanzada usuarios
│   │   ├── 📄 roles.php            # Gestión de roles y permisos
│   │   ├── 📄 permisos.php         # Gestión de permisos
│   │   ├── 📄 configuracion.php    # Configuración del sistema
│   │   └── 📄 estadisticas.php     # Estadísticas avanzadas
│   │
│   ├── 📁 components/              # Componentes reutilizables (futuro)
│   │   ├── 📄 modal.php            # Modal genérico
│   │   ├── 📄 table.php            # Tabla con paginación
│   │   ├── 📄 form.php             # Formularios
│   │   └── 📄 alert.php            # Alertas y notificaciones
│   │
│   └── 📁 emails/                  # Templates de email (futuro)
│       ├── 📄 welcome.php          # Email de bienvenida
│       ├── 📄 password_reset.php   # Recuperación de contraseña
│       └── 📄 notification.php     # Notificaciones generales
│
├── 📁 api/                         # API REST (expansión futura)
│   ├── 📄 index.php                # Enrutador API
│   ├── 📄 auth.php                 # Endpoints de autenticación
│   ├── 📄 users.php                # Endpoints de usuarios
│   ├── 📄 roles.php                # Endpoints de roles
│   └── 📁 v1/                      # Versionado de API
│       ├── 📄 users.php
│       └── 📄 auth.php
│
├── 📁 uploads/                     # Archivos subidos por usuarios
│   ├── 📁 avatars/                 # Avatares de usuarios
│   ├── 📁 documents/               # Documentos generales
│   ├── 📁 temp/                    # Archivos temporales
│   └── 📄 .htaccess                # Protección directorio
│
├── 📁 logs/                        # Logs del sistema
│   ├── 📄 2024-11-02.log          # Logs por fecha
│   ├── 📄 error.log                # Errores específicos
│   ├── 📄 access.log               # Log de accesos
│   └── 📄 .htaccess                # Protección directorio
│
├── 📁 cache/                       # Cache del sistema (futuro)
│   ├── 📁 views/                   # Cache de vistas
│   ├── 📁 queries/                 # Cache de consultas
│   └── 📄 .htaccess                # Protección directorio
│
├── 📁 config/                      # Configuraciones adicionales (futuro)
│   ├── 📄 database.php             # Configuración BD
│   ├── 📄 mail.php                 # Configuración email
│   ├── 📄 app.php                  # Configuración aplicación
│   └── 📄 security.php             # Configuración seguridad
│
├── 📁 migrations/                  # Migraciones de BD (futuro)
│   ├── 📄 001_initial_structure.sql
│   ├── 📄 002_add_user_fields.sql
│   └── 📄 003_update_permissions.sql
│
├── 📁 backups/                     # Backups automáticos (futuro)
│   ├── 📁 database/
│   ├── 📁 files/
│   └── 📄 .htaccess
│
├── 📁 tests/                       # Tests unitarios (futuro)
│   ├── 📄 DatabaseTest.php
│   ├── 📄 AuthTest.php
│   └── 📄 UserTest.php
│
├── 📁 docs/                        # Documentación técnica (futuro)
│   ├── 📄 installation.md
│   ├── 📄 api-reference.md
│   ├── 📄 security-guide.md
│   └── 📁 images/
│
└── 📁 vendor/                      # Librerías externas (si se usan)
    └── 📄 autoload.php