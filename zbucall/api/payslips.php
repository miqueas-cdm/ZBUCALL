<?php
/**
 * Payslips API
 * Manages payslip records
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? 'list';
$employeeId = getCurrentEmployeeId();

switch ($action) {
    case 'list':
        listPayslips();
        break;
    
    case 'get':
        getPayslip();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function listPayslips() {
    global $employeeId;
    
    $payslips = dbGetAll(
        "SELECT * FROM payslips 
         WHERE employee_id = ? 
         ORDER BY reference_year DESC, reference_month DESC",
        [$employeeId]
    );
    
    // Format payslips for display
    foreach ($payslips as &$payslip) {
        $payslip['month_name'] = getMonthName($payslip['reference_month']);
        $payslip['period'] = $payslip['month_name'] . '/' . $payslip['reference_year'];
    }
    
    jsonResponse(true, 'Holerites carregados', [
        'payslips' => $payslips,
        'count' => count($payslips)
    ]);
}

function getPayslip() {
    global $employeeId;
    
    $id = $_GET['id'] ?? 0;
    
    $payslip = dbGetRow(
        "SELECT p.*, e.full_name, e.registration_number, e.position, e.department
         FROM payslips p
         JOIN employees e ON p.employee_id = e.id
         WHERE p.id = ? AND p.employee_id = ?",
        [$id, $employeeId]
    );
    
    if (!$payslip) {
        jsonResponse(false, 'Holerite não encontrado');
        return;
    }
    
    $payslip['month_name'] = getMonthName($payslip['reference_month']);
    $payslip['period'] = $payslip['month_name'] . '/' . $payslip['reference_year'];
    
    jsonResponse(true, 'Holerite carregado', ['payslip' => $payslip]);
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
