<?php
/**
 * Clase Auth - Manejo de autenticación y autorización
 * Sistema de Gestión - PHP8 + MariaDB
 */

class Auth {
    private Database $db;
    private static $instance = null;

    private function __construct() {
        $this->db = Database::getInstance();
    }

    public static function getInstance(): Auth {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Autenticar usuario
     */
    public function login(string $username, string $password, string $ip_address = '', string $user_agent = ''): array {
        try {
            // Verificar si el usuario está bloqueado
            if ($this->isUserBlocked($username)) {
                return [
                    'success' => false,
                    'message' => 'Usuario bloqueado temporalmente por múltiples intentos fallidos',
                    'blocked' => true
                ];
            }

            // Buscar usuario
            $user = $this->db->selectOne(
                "SELECT * FROM usuarios WHERE (username = :username OR email = :email) AND estado = 'activo'",
                ['username' => $username, 'email' => $username]
            );

            if (!$user) {
                $this->recordFailedAttempt($username, $ip_address);
                return [
                    'success' => false,
                    'message' => 'Credenciales inválidas',
                    'user_found' => false
                ];
            }

            // Verificar contraseña
            if (!password_verify($password, $user['password_hash'])) {
                $this->recordFailedAttempt($username, $ip_address);
                $this->incrementFailedAttempts($user['id']);
                return [
                    'success' => false,
                    'message' => 'Credenciales inválidas',
                    'user_found' => true
                ];
            }

            // Login exitoso
            $this->resetFailedAttempts($user['id']);
            $this->updateLastAccess($user['id'], $ip_address);
            $this->createSession($user, $ip_address, $user_agent);

            write_log('INFO', "Login exitoso para usuario: {$user['username']}", [
                'user_id' => $user['id'],
                'ip' => $ip_address
            ]);

            return [
                'success' => true,
                'message' => 'Login exitoso',
                'user' => $this->getUserInfo($user['id'])
            ];

        } catch (Exception $e) {
            write_log('ERROR', 'Error en login: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del sistema'
            ];
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(): bool {
        try {
            if (isset($_SESSION['user_id']) && isset($_SESSION['session_id'])) {
                // Desactivar sesión en base de datos
                $this->db->update(
                    'sesiones',
                    ['activa' => false],
                    'id = :session_id',
                    ['session_id' => $_SESSION['session_id']]
                );

                write_log('INFO', "Logout para usuario ID: {$_SESSION['user_id']}");
            }

            // Destruir sesión
            session_destroy();
            session_start();
            
            return true;
        } catch (Exception $e) {
            write_log('ERROR', 'Error en logout: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public function isAuthenticated(): bool {
        return isset($_SESSION['user_id']) && isset($_SESSION['session_id']) && $this->isSessionValid();
    }

    /**
     * Verificar si la sesión es válida
     */
    private function isSessionValid(): bool {
        if (!isset($_SESSION['session_id'])) {
            return false;
        }

        try {
            $session = $this->db->selectOne(
                "SELECT * FROM sesiones WHERE id = :session_id AND activa = 1",
                ['session_id' => $_SESSION['session_id']]
            );

            return $session !== null;
        } catch (Exception $e) {
            write_log('ERROR', 'Error validando sesión: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener usuario actual
     */
    public function getCurrentUser(): ?array {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $this->getUserInfo($_SESSION['user_id']);
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission(string $permission_code): bool {
        if (!$this->isAuthenticated()) {
            return false;
        }

        try {
            $result = $this->db->selectOne(
                "SELECT COUNT(*) as count FROM vista_permisos_usuario 
                 WHERE usuario_id = :user_id AND permiso_codigo = :permission",
                [
                    'user_id' => $_SESSION['user_id'],
                    'permission' => $permission_code
                ]
            );

            return $result && $result['count'] > 0;
        } catch (Exception $e) {
            write_log('ERROR', 'Error verificando permiso: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role_name): bool {
        if (!$this->isAuthenticated()) {
            return false;
        }

        try {
            $result = $this->db->selectOne(
                "SELECT COUNT(*) as count FROM usuario_roles ur
                 JOIN roles r ON ur.rol_id = r.id
                 WHERE ur.usuario_id = :user_id AND r.nombre = :role_name AND r.estado = 'activo'",
                [
                    'user_id' => $_SESSION['user_id'],
                    'role_name' => $role_name
                ]
            );

            return $result && $result['count'] > 0;
        } catch (Exception $e) {
            write_log('ERROR', 'Error verificando rol: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin(): bool {
        return $this->hasRole('administrador');
    }

    /**
     * Crear hash de contraseña
     */
    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }

    /**
     * Validar fortaleza de contraseña
     */
    public function validatePassword(string $password): array {
        $errors = [];

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = "La contraseña debe tener al menos " . PASSWORD_MIN_LENGTH . " caracteres";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "La contraseña debe contener al menos una letra mayúscula";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "La contraseña debe contener al menos una letra minúscula";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un número";
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un carácter especial";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(int $user_id, string $current_password, string $new_password): array {
        try {
            // Verificar contraseña actual
            $user = $this->db->selectOne(
                "SELECT password_hash FROM usuarios WHERE id = :user_id",
                ['user_id' => $user_id]
            );

            if (!$user || !password_verify($current_password, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Contraseña actual incorrecta'
                ];
            }

            // Validar nueva contraseña
            $validation = $this->validatePassword($new_password);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Contraseña no válida',
                    'errors' => $validation['errors']
                ];
            }

            // Actualizar contraseña
            $this->db->update(
                'usuarios',
                [
                    'password_hash' => $this->hashPassword($new_password),
                    'modificado_por' => $_SESSION['user_id'] ?? null
                ],
                'id = :user_id',
                ['user_id' => $user_id]
            );

            write_log('INFO', "Contraseña cambiada para usuario ID: $user_id");

            return [
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ];

        } catch (Exception $e) {
            write_log('ERROR', 'Error cambiando contraseña: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del sistema'
            ];
        }
    }

    /**
     * Registrar intento fallido
     */
    private function recordFailedAttempt(string $username, string $ip_address): void {
        try {
            $this->db->insert('auditoria', [
                'usuario_id' => null,
                'accion' => 'login_failed',
                'tabla_afectada' => 'usuarios',
                'datos_nuevos' => json_encode(['username' => $username]),
                'ip_address' => $ip_address,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        } catch (Exception $e) {
            write_log('ERROR', 'Error registrando intento fallido: ' . $e->getMessage());
        }
    }

    /**
     * Incrementar intentos fallidos del usuario
     */
    private function incrementFailedAttempts(int $user_id): void {
        try {
            $this->db->execute(
                "UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = :user_id",
                ['user_id' => $user_id]
            );
        } catch (Exception $e) {
            write_log('ERROR', 'Error incrementando intentos fallidos: ' . $e->getMessage());
        }
    }

    /**
     * Resetear intentos fallidos
     */
    private function resetFailedAttempts(int $user_id): void {
        try {
            $this->db->update(
                'usuarios',
                ['intentos_fallidos' => 0],
                'id = :user_id',
                ['user_id' => $user_id]
            );
        } catch (Exception $e) {
            write_log('ERROR', 'Error reseteando intentos fallidos: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si el usuario está bloqueado
     */
    private function isUserBlocked(string $username): bool {
        try {
            $user = $this->db->selectOne(
                "SELECT intentos_fallidos FROM usuarios WHERE (username = :username OR email = :email)",
                ['username' => $username, 'email' => $username]
            );

            return $user && $user['intentos_fallidos'] >= MAX_LOGIN_ATTEMPTS;
        } catch (Exception $e) {
            write_log('ERROR', 'Error verificando bloqueo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar último acceso
     */
    private function updateLastAccess(int $user_id, string $ip_address): void {
        try {
            $this->db->update(
                'usuarios',
                ['ultimo_acceso' => date('Y-m-d H:i:s')],
                'id = :user_id',
                ['user_id' => $user_id]
            );
        } catch (Exception $e) {
            write_log('ERROR', 'Error actualizando último acceso: ' . $e->getMessage());
        }
    }

    /**
     * Crear sesión
     */
    private function createSession(array $user, string $ip_address, string $user_agent): void {
        try {
            $session_id = bin2hex(random_bytes(32));
            
            $this->db->insert('sesiones', [
                'id' => $session_id,
                'usuario_id' => $user['id'],
                'ip_address' => $ip_address,
                'user_agent' => $user_agent
            ]);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['session_id'] = $session_id;
            $_SESSION['username'] = $user['username'];
            $_SESSION['nombre_completo'] = $user['nombre'] . ' ' . $user['apellido'];

        } catch (Exception $e) {
            write_log('ERROR', 'Error creando sesión: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener información del usuario
     */
    private function getUserInfo(int $user_id): ?array {
        try {
            return $this->db->selectOne(
                "SELECT u.*, GROUP_CONCAT(r.nombre SEPARATOR ', ') as roles
                 FROM usuarios u
                 LEFT JOIN usuario_roles ur ON u.id = ur.usuario_id
                 LEFT JOIN roles r ON ur.rol_id = r.id AND r.estado = 'activo'
                 WHERE u.id = :user_id
                 GROUP BY u.id",
                ['user_id' => $user_id]
            );
        } catch (Exception $e) {
            write_log('ERROR', 'Error obteniendo información del usuario: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Limpiar sesiones expiradas
     */
    public function cleanExpiredSessions(): int {
        try {
            $expired_time = date('Y-m-d H:i:s', time() - SESSION_TIMEOUT);
            
            $count = $this->db->execute(
                "UPDATE sesiones SET activa = 0 WHERE fecha_actividad < :expired_time AND activa = 1",
                ['expired_time' => $expired_time]
            );

            if ($count > 0) {
                write_log('INFO', "Limpiadas $count sesiones expiradas");
            }

            return $count;
        } catch (Exception $e) {
            write_log('ERROR', 'Error limpiando sesiones expiradas: ' . $e->getMessage());
            return 0;
        }
    }
}

?>