<?php
/**
 * Clase Database - Manejo de conexiones y operaciones de base de datos
 * Sistema de Gestión - PHP8 + MariaDB
 */
	define('DB_HOST' 	,'localhost');
	define('DB_PORT'	,'3306');
	define('DB_NAME' 	,'core');
	define('DB_USER' 	,'global');
	define('DB_PASS' 	,'pkH2MCsFk#');
    define('DB_CHARSET' ,'utf8mb4'); 
    define('DB_DRIVER' 	,'mysql'); 

class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;
    private $charset;

    private function __construct() {
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->database = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET;
        
        $this->connect();
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect(): void {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset} COLLATE {$this->charset}_unicode_ci"
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
            write_log('INFO', 'Conexión a base de datos establecida');
        } catch (PDOException $e) {
            write_log('ERROR', 'Error de conexión a base de datos: ' . $e->getMessage());
            throw new Exception('Error de conexión a la base de datos');
        }
    }

    public function getConnection(): PDO {
        // Verificar si la conexión sigue activa
        try {
            $this->connection->query('SELECT 1');
        } catch (PDOException $e) {
            write_log('WARNING', 'Conexión perdida, reconectando...');
            $this->connect();
        }
        
        return $this->connection;
    }

    /**
     * Ejecutar consulta SELECT
     */
    public function select(string $query, array $params = []): array {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en SELECT: ' . $e->getMessage(), ['query' => $query, 'params' => $params]);
            throw new Exception('Error en la consulta de datos');
        }
    }

    /**
     * Ejecutar consulta SELECT para un solo registro
     */
    public function selectOne(string $query, array $params = []): ?array {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en SELECT ONE: ' . $e->getMessage(), ['query' => $query, 'params' => $params]);
            throw new Exception('Error en la consulta de datos');
        }
    }

    /**
     * Ejecutar INSERT
     */
    public function insert(string $table, array $data): int {
        try {
            $columns = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            
            return (int) $this->connection->lastInsertId();
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en INSERT: ' . $e->getMessage(), ['table' => $table, 'data' => $data]);
            throw new Exception('Error al insertar datos');
        }
    }

    /**
     * Ejecutar UPDATE
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int {
        try {
            $setClause = [];
            foreach (array_keys($data) as $column) {
                $setClause[] = "{$column} = :{$column}";
            }
            $setClause = implode(', ', $setClause);
            
            $query = "UPDATE {$table} SET {$setClause} WHERE {$where}";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(array_merge($data, $whereParams));
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en UPDATE: ' . $e->getMessage(), [
                'table' => $table, 
                'data' => $data, 
                'where' => $where,
                'whereParams' => $whereParams
            ]);
            throw new Exception('Error al actualizar datos');
        }
    }

    /**
     * Ejecutar DELETE
     */
    public function delete(string $table, string $where, array $whereParams = []): int {
        try {
            $query = "DELETE FROM {$table} WHERE {$where}";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($whereParams);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en DELETE: ' . $e->getMessage(), [
                'table' => $table, 
                'where' => $where,
                'whereParams' => $whereParams
            ]);
            throw new Exception('Error al eliminar datos');
        }
    }

    /**
     * Ejecutar consulta personalizada
     */
    public function execute(string $query, array $params = []): bool {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en EXECUTE: ' . $e->getMessage(), ['query' => $query, 'params' => $params]);
            throw new Exception('Error al ejecutar consulta');
        }
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit(): bool {
        return $this->connection->commit();
    }

    /**
     * Cancelar transacción
     */
    public function rollback(): bool {
        return $this->connection->rollback();
    }

    /**
     * Verificar si hay una transacción activa
     */
    public function inTransaction(): bool {
        return $this->connection->inTransaction();
    }

    /**
     * Obtener el último ID insertado
     */
    public function lastInsertId(): string {
        return $this->connection->lastInsertId();
    }

    /**
     * Contar registros
     */
    public function count(string $table, string $where = '1=1', array $params = []): int {
        try {
            $query = "SELECT COUNT(*) as total FROM {$table} WHERE {$where}";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (int) $result['total'];
        } catch (PDOException $e) {
            write_log('ERROR', 'Error en COUNT: ' . $e->getMessage(), [
                'table' => $table, 
                'where' => $where,
                'params' => $params
            ]);
            throw new Exception('Error al contar registros');
        }
    }

    /**
     * Verificar si existe un registro
     */
    public function exists(string $table, string $where, array $params = []): bool {
        return $this->count($table, $where, $params) > 0;
    }

    /**
     * Escapar cadena para prevenir SQL injection (aunque se recomienda usar prepared statements)
     */
    public function escape(string $string): string {
        return $this->connection->quote($string);
    }

    /**
     * Obtener información de la base de datos
     */
    public function getServerInfo(): array {
        return [
            'version' => $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION),
            'driver' => $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME),
            'connection_status' => $this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS)
        ];
    }

    /**
     * Prevenir clonación
     */
    public function __clone() {
        throw new Exception('No se puede clonar la instancia de Database');
    }

    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception('No se puede deserializar la instancia de Database');
    }

    /**
     * Cerrar conexión al destruir el objeto
     */
    public function __destruct() {
        $this->connection = null;
    }
}

?>