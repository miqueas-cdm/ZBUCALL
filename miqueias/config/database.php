<?php
/**
 * Database Configuration
 * PDO connection for MySQL
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'zbucall');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Create PDO connection
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao conectar ao banco de dados'
            ]);
            exit;
        }
    }
    
    return $pdo;
}

// Helper function to execute a query
function dbQuery($sql, $params = []) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }
}

// Helper function to get single row
function dbGetRow($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt ? $stmt->fetch() : null;
}

// Helper function to get all rows
function dbGetAll($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}

// Helper function to insert and return last ID
function dbInsert($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    if ($stmt) {
        $pdo = getDBConnection();
        return $pdo->lastInsertId();
    }
    return false;
}

// Helper function for updates/deletes
function dbExecute($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt !== false;
}
