<?php
require_once 'config/database.php';

try {
    $pdo = getDbConnection();
    
    echo "Updating schema for surveys...\n";
    
    // Check if columns exist
    $stmt = $pdo->query("SHOW COLUMNS FROM communications LIKE 'survey_question'");
    $column = $stmt->fetch();
    
    if (!$column) {
        echo "Adding 'survey_question' and 'survey_options' to communications table...\n";
        $pdo->exec("ALTER TABLE communications ADD COLUMN survey_question VARCHAR(255) DEFAULT NULL AFTER image_url");
        $pdo->exec("ALTER TABLE communications ADD COLUMN survey_options JSON DEFAULT NULL AFTER survey_question");
        echo "Columns added successfully.\n";
    } else {
        echo "Columns already exist.\n";
    }
    
    echo "Schema update completed successfully.\n";
    
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
