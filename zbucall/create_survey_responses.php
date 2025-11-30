<?php
require_once 'config/database.php';

try {
    $pdo = getDbConnection();
    
    echo "Creating survey_responses table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS survey_responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        communication_id INT NOT NULL,
        employee_id INT NOT NULL,
        selected_option VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (communication_id) REFERENCES communications(id) ON DELETE CASCADE,
        FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
        UNIQUE KEY unique_vote (communication_id, employee_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    echo "Table created successfully.\n";
    
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
