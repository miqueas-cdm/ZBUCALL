<?php
/**
 * Profile API
 * Handles employee profile data
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? 'get';
$employeeId = getCurrentEmployeeId();

switch ($action) {
    case 'get':
        getProfile();
        break;
    
    case 'update':
        updateProfile();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function getProfile() {
    global $employeeId;
    
    $employee = dbGetRow(
        "SELECT * FROM employees WHERE id = ?",
        [$employeeId]
    );
    
    if (!$employee) {
        jsonResponse(false, 'Funcionário não encontrado');
        return;
    }
    
    // Calculate time in company
    $hireDate = new DateTime($employee['hire_date']);
    $now = new DateTime();
    $interval = $hireDate->diff($now);
    
    $employee['time_in_company'] = [
        'years' => $interval->y,
        'months' => $interval->m,
        'days' => $interval->d,
        'formatted' => getTimeInCompany($employee['hire_date'])
    ];
    
    // Remove sensitive data
    unset($employee['password']);
    
    jsonResponse(true, 'Perfil carregado', ['employee' => $employee]);
}

function updateProfile() {
    global $employeeId;
    
    $allowedFields = ['phone', 'mobile', 'address_street', 'address_number', 
                      'address_complement', 'address_neighborhood', 
                      'address_city', 'address_state', 'address_zipcode'];
    
    $updates = [];
    $params = [];
    
    foreach ($allowedFields as $field) {
        if (isset($_POST[$field])) {
            $updates[] = "$field = ?";
            $params[] = $_POST[$field];
        }
    }
    
    if (empty($updates)) {
        jsonResponse(false, 'Nenhum campo para atualizar');
        return;
    }
    
    $params[] = $employeeId;
    $sql = "UPDATE employees SET " . implode(', ', $updates) . " WHERE id = ?";
    
    if (dbExecute($sql, $params)) {
        jsonResponse(true, 'Perfil atualizado com sucesso');
    } else {
        jsonResponse(false, 'Erro ao atualizar perfil');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
