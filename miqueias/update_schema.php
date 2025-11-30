<?php
require_once 'config/database.php';

try {
    $pdo = getDbConnection();
    
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM employees LIKE 'role'");
    $column = $stmt->fetch();
    
    if (!$column) {
        echo "Adding 'role' column to employees table...\n";
        $pdo->exec("ALTER TABLE employees ADD COLUMN role ENUM('employee', 'admin') DEFAULT 'employee' AFTER email");
        echo "Column added successfully.\n";
    } else {
        echo "Column 'role' already exists.\n";
    }
    
    // Set user with ID 2 (Maria Oliveira Costa - Gerente de RH) as admin
    echo "Setting user ID 2 as admin...\n";
    $pdo->exec("UPDATE employees SET role = 'admin' WHERE id = 2");
    echo "User ID 2 is now an admin.\n";
    
    echo "Schema update completed successfully.\n";
    
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
