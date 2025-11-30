<?php
require_once 'config/database.php';

try {
    $pdo = getDbConnection();
    
    echo "Starting database reset...\n";
    
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found to drop.\n";
    } else {
        echo "Dropping " . count($tables) . " tables...\n";
        foreach ($tables as $table) {
            echo "Dropping table: $table\n";
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
        }
    }
    
    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "All tables dropped successfully.\n";
    
    // Read and execute schema.sql
    echo "Loading schema.sql...\n";
    $sql = file_get_contents('schema.sql');
    
    // Execute the SQL commands
    // We need to split by semicolon to execute multiple statements if PDO doesn't support it directly in one go
    // But PDO->exec usually supports multiple statements if emulation is on.
    // Let's try executing it as a whole block first.
    
    try {
        $pdo->exec($sql);
        echo "Schema loaded successfully.\n";
    } catch (PDOException $e) {
        // If that fails, try splitting (naive split)
        echo "Bulk execution failed, trying statement by statement...\n";
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        echo "Schema loaded successfully (statement by statement).\n";
    }
    
    echo "Database reset complete!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
