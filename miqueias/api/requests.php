<?php
/**
 * Requests API
 * Manages employee requests (vacation, HR, etc.)
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');
$employeeId = getCurrentEmployeeId();

switch ($action) {
    case 'list':
        listRequests();
        break;
    
    case 'get':
        getRequest();
        break;
    
    case 'create':
        createRequest();
        break;
    
    case 'cancel':
        cancelRequest();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function listRequests() {
    global $employeeId;
    
    $requests = dbGetAll(
        "SELECT * FROM requests 
         WHERE employee_id = ? 
         ORDER BY created_at DESC",
        [$employeeId]
    );
    
    // Group by status
    $grouped = [];
    foreach ($requests as $req) {
        $status = $req['status'];
        if (!isset($grouped[$status])) {
            $grouped[$status] = [];
        }
        $grouped[$status][] = $req;
    }
    
    jsonResponse(true, 'Solicitações carregadas', [
        'requests' => $requests,
        'grouped' => $grouped,
        'total' => count($requests)
    ]);
}

function getRequest() {
    global $employeeId;
    
    $id = $_GET['id'] ?? 0;
    
    $request = dbGetRow(
        "SELECT r.*, e.full_name as reviewer_name
         FROM requests r
         LEFT JOIN employees e ON r.reviewer_id = e.id
         WHERE r.id = ? AND r.employee_id = ?",
        [$id, $employeeId]
    );
    
    if (!$request) {
        jsonResponse(false, 'Solicitação não encontrada');
        return;
    }
    
    jsonResponse(true, 'Solicitação carregada', ['request' => $request]);
}

function createRequest() {
    global $employeeId;
    
    $requestType = $_POST['request_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;
    $priority = $_POST['priority'] ?? 'normal';
    
    if (empty($requestType) || empty($title)) {
        jsonResponse(false, 'Tipo e título são obrigatórios');
        return;
    }
    
    $requestId = dbInsert(
        "INSERT INTO requests (employee_id, request_type, title, description, start_date, end_date, priority, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')",
        [$employeeId, $requestType, $title, $description, $startDate, $endDate, $priority]
    );
    
    if ($requestId) {
        // Create notification
        dbInsert(
            "INSERT INTO notifications (employee_id, title, message, type, icon)
             VALUES (?, 'Solicitação Enviada', ?, 'success', 'check-circle')",
            [$employeeId, "Sua solicitação '{$title}' foi enviada e está aguardando análise."]
        );
        
        jsonResponse(true, 'Solicitação criada com sucesso', ['request_id' => $requestId]);
    } else {
        jsonResponse(false, 'Erro ao criar solicitação');
    }
}

function cancelRequest() {
    global $employeeId;
    
    $id = $_POST['id'] ?? 0;
    
    $request = dbGetRow(
        "SELECT * FROM requests WHERE id = ? AND employee_id = ?",
        [$id, $employeeId]
    );
    
    if (!$request) {
        jsonResponse(false, 'Solicitação não encontrada');
        return;
    }
    
    if ($request['status'] !== 'pending') {
        jsonResponse(false, 'Apenas solicitações pendentes podem ser canceladas');
        return;
    }
    
    if (dbExecute("UPDATE requests SET status = 'cancelled' WHERE id = ?", [$id])) {
        jsonResponse(true, 'Solicitação cancelada com sucesso');
    } else {
        jsonResponse(false, 'Erro ao cancelar solicitação');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
