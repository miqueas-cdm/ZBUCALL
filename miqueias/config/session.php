<?php
/**
 * Session Configuration and Authentication Helpers
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['employee_id']) && isset($_SESSION['employee_email']);
}

// Get current logged-in employee ID
function getCurrentEmployeeId() {
    return $_SESSION['employee_id'] ?? null;
}

// Get current logged-in employee data
function getCurrentEmployee() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['employee_id'],
        'name' => $_SESSION['employee_name'],
        'email' => $_SESSION['employee_email'],
        'position' => $_SESSION['employee_position'],
        'department' => $_SESSION['employee_department'],
        'photo_url' => $_SESSION['employee_photo'] ?? 'assets/images/default-avatar.png'
    ];
}

// Login user
function loginEmployee($employeeData) {
    $_SESSION['employee_id'] = $employeeData['id'];
    $_SESSION['employee_name'] = $employeeData['full_name'];
    $_SESSION['employee_email'] = $employeeData['email'];
    $_SESSION['employee_position'] = $employeeData['position'];
    $_SESSION['employee_department'] = $employeeData['department'];
    $_SESSION['employee_photo'] = $employeeData['photo_url'];
    $_SESSION['employee_registration'] = $employeeData['registration_number'];
    $_SESSION['employee_role'] = $employeeData['role'] ?? 'employee';
}

// Logout user
function logoutEmployee() {
    session_unset();
    session_destroy();
    session_start(); // Restart for flash messages
}

// Require authentication (redirect if not logged in)
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

// Require admin authentication
function requireAdmin() {
    requireAuth();
    if ($_SESSION['employee_role'] !== 'admin') {
        header('Location: ../dashboard.php');
        exit;
    }
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['employee_role']) && $_SESSION['employee_role'] === 'admin';
}

// Set flash message
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

// Get and clear flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Calculate time in company
function getTimeInCompany($hireDate) {
    $hire = new DateTime($hireDate);
    $now = new DateTime();
    $interval = $hire->diff($now);
    
    $years = $interval->y;
    $months = $interval->m;
    
    if ($years > 0) {
        return $years . ' ano' . ($years > 1 ? 's' : '') . 
               ($months > 0 ? ' e ' . $months . ' mes' . ($months > 1 ? 'es' : '') : '');
    } else {
        return $months . ' mes' . ($months > 1 ? 'es' : '');
    }
}

// Format currency
function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

// Format date
function formatDate($date, $format = 'd/m/Y') {
    if (!$date) return '-';
    $dt = new DateTime($date);
    return $dt->format($format);
}

// Format date with month name
function formatDateLong($date) {
    if (!$date) return '-';
    $dt = new DateTime($date);
    $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    return $dt->format('d') . ' de ' . $months[(int)$dt->format('n')] . ' de ' . $dt->format('Y');
}

// Get month name
function getMonthName($month) {
    $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    return $months[$month] ?? '';
}
