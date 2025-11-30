<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_POST['action'] ?? '';
$employeeId = getCurrentEmployeeId();

if ($action === 'vote') {
    handleVote($employeeId);
} else {
    echo json_encode(['success' => false, 'message' => 'Ação inválida']);
}

function handleVote($employeeId) {
    $communicationId = $_POST['communication_id'] ?? null;
    $option = $_POST['option'] ?? null;
    
    if (!$communicationId || !$option) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    try {
        $pdo = getDbConnection();
        
        // Check if already voted
        $stmt = $pdo->prepare("SELECT id FROM survey_responses WHERE communication_id = ? AND employee_id = ?");
        $stmt->execute([$communicationId, $employeeId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Você já votou nesta pesquisa']);
            return;
        }
        
        // Insert vote
        $stmt = $pdo->prepare("INSERT INTO survey_responses (communication_id, employee_id, selected_option) VALUES (?, ?, ?)");
        $stmt->execute([$communicationId, $employeeId, $option]);
        
        echo json_encode(['success' => true, 'message' => 'Voto registrado com sucesso']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao registrar voto: ' . $e->getMessage()]);
    }
}
