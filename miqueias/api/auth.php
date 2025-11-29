<?php
/**
 * Authentication API
 * Handles login and logout
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'check_cpf':
        handleCheckCpf();
        break;

    case 'login_registration':
        handleLoginRegistration();
        break;

    case 'send_otp':
        handleSendOtp();
        break;

    case 'login_otp':
        handleLoginOtp();
        break;

    case 'login':
        handleLogin();
        break;
    
    case 'logout':
        handleLogout();
        break;
    
    case 'check':
        handleCheckAuth();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function handleCheckCpf() {
    $cpf = $_POST['cpf'] ?? '';
    
    if (empty($cpf)) {
        jsonResponse(false, 'CPF é obrigatório');
        return;
    }
    
    $employee = dbGetRow(
        "SELECT id FROM employees WHERE cpf = ? AND status = 'active'",
        [$cpf]
    );
    
    if ($employee) {
        jsonResponse(true, 'CPF encontrado');
    } else {
        jsonResponse(false, 'CPF não encontrado');
    }
}

function handleLoginRegistration() {
    $cpf = $_POST['cpf'] ?? '';
    $registration = $_POST['registration_number'] ?? '';
    
    if (empty($cpf) || empty($registration)) {
        jsonResponse(false, 'CPF e Matrícula são obrigatórios');
        return;
    }
    
    $employee = dbGetRow(
        "SELECT * FROM employees WHERE cpf = ? AND status = 'active'",
        [$cpf]
    );
    
    if (!$employee) {
        jsonResponse(false, 'Credenciais inválidas');
        return;
    }
    
    // Check registration number (case insensitive)
    if (strtoupper($employee['registration_number']) !== strtoupper($registration)) {
        jsonResponse(false, 'Matrícula incorreta');
        return;
    }
    
    // Login successful
    loginEmployee($employee);
    
    jsonResponse(true, 'Login realizado com sucesso', [
        'employee' => [
            'id' => $employee['id'],
            'name' => $employee['full_name'],
            'email' => $employee['email'],
            'position' => $employee['position'],
            'department' => $employee['department'],
            'photo_url' => $employee['photo_url']
        ],
        'redirect' => 'dashboard.php'
    ]);
}

function handleSendOtp() {
    $cpf = $_POST['cpf'] ?? '';
    
    if (empty($cpf)) {
        jsonResponse(false, 'CPF é obrigatório');
        return;
    }
    
    // Check if employee exists
    $employee = dbGetRow(
        "SELECT * FROM employees WHERE cpf = ? AND status = 'active'",
        [$cpf]
    );
    
    if (!$employee) {
        // For security, don't reveal if user exists, but for this internal app we might want to be helpful
        // or just fake success. Let's return error for now as it's an internal tool.
        jsonResponse(false, 'CPF não encontrado');
        return;
    }
    
    // Generate 6-digit OTP
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
    // Save to DB
    dbExecute(
        "UPDATE employees SET otp_code = ?, otp_expires_at = ? WHERE id = ?",
        [$otp, $expires_at, $employee['id']]
    );
    
    // In a real app, we would send SMS here.
    // For this task, we return the code in the response.
    jsonResponse(true, 'Código enviado com sucesso', [
        'dev_otp' => $otp // Exposed for testing/demo purposes
    ]);
}

function handleLoginOtp() {
    $cpf = $_POST['cpf'] ?? '';
    $otp = $_POST['otp'] ?? '';
    
    if (empty($cpf) || empty($otp)) {
        jsonResponse(false, 'CPF e código são obrigatórios');
        return;
    }
    
    $employee = dbGetRow(
        "SELECT * FROM employees WHERE cpf = ? AND status = 'active'",
        [$cpf]
    );
    
    if (!$employee) {
        jsonResponse(false, 'Credenciais inválidas');
        return;
    }
    
    // Verify OTP
    if ($employee['otp_code'] !== $otp) {
        jsonResponse(false, 'Código inválido');
        return;
    }
    
    if (strtotime($employee['otp_expires_at']) < time()) {
        jsonResponse(false, 'Código expirado');
        return;
    }
    
    // Clear OTP after successful login
    dbExecute(
        "UPDATE employees SET otp_code = NULL, otp_expires_at = NULL WHERE id = ?",
        [$employee['id']]
    );
    
    // Login successful
    loginEmployee($employee);
    
    jsonResponse(true, 'Login realizado com sucesso', [
        'employee' => [
            'id' => $employee['id'],
            'name' => $employee['full_name'],
            'email' => $employee['email'],
            'position' => $employee['position'],
            'department' => $employee['department'],
            'photo_url' => $employee['photo_url']
        ],
        'redirect' => 'dashboard.php'
    ]);
}

function handleLogin() {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        jsonResponse(false, 'Email e senha são obrigatórios');
        return;
    }
    
    // Get employee from database
    $employee = dbGetRow(
        "SELECT * FROM employees WHERE email = ? AND status = 'active'",
        [$email]
    );
    
    if (!$employee) {
        jsonResponse(false, 'Credenciais inválidas');
        return;
    }
    
    // Verify password (using password_verify for hashed passwords)
    if (!password_verify($password, $employee['password'])) {
        jsonResponse(false, 'Credenciais inválidas');
        return;
    }
    
    // Login successful
    loginEmployee($employee);
    
    jsonResponse(true, 'Login realizado com sucesso', [
        'employee' => [
            'id' => $employee['id'],
            'name' => $employee['full_name'],
            'email' => $employee['email'],
            'position' => $employee['position'],
            'department' => $employee['department'],
            'photo_url' => $employee['photo_url']
        ],
        'redirect' => 'dashboard.php'
    ]);
}

function handleLogout() {
    logoutEmployee();
    jsonResponse(true, 'Logout realizado com sucesso', [
        'redirect' => 'index.php'
    ]);
}

function handleCheckAuth() {
    if (isLoggedIn()) {
        jsonResponse(true, 'Autenticado', [
            'employee' => getCurrentEmployee()
        ]);
    } else {
        jsonResponse(false, 'Não autenticado');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
