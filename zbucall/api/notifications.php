<?php
/**
 * Notifications API
 * Manages user notifications
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');
$employeeId = getCurrentEmployeeId();

switch ($action) {
    case 'list':
        listNotifications();
        break;
    
    case 'mark_read':
        markAsRead();
        break;
    
    case 'mark_all_read':
        markAllAsRead();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function listNotifications() {
    global $employeeId;
    
    $limit = $_GET['limit'] ?? 50;
    $unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';
    
    $sql = "SELECT * FROM notifications WHERE employee_id = ?";
    $params = [$employeeId];
    
    if ($unreadOnly) {
        $sql .= " AND is_read = FALSE";
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT " . intval($limit);
    
    $notifications = dbGetAll($sql, $params);
    
    $unreadCount = dbGetRow(
        "SELECT COUNT(*) as count FROM notifications WHERE employee_id = ? AND is_read = FALSE",
        [$employeeId]
    )['count'] ?? 0;
    
    jsonResponse(true, 'Notificações carregadas', [
        'notifications' => $notifications,
        'unread_count' => $unreadCount,
        'total' => count($notifications)
    ]);
}

function markAsRead() {
    global $employeeId;
    
    $id = $_POST['id'] ?? 0;
    
    if (dbExecute("UPDATE notifications SET is_read = TRUE, read_at = NOW() WHERE id = ? AND employee_id = ?", [$id, $employeeId])) {
        jsonResponse(true, 'Notificação marcada como lida');
    } else {
        jsonResponse(false, 'Erro ao marcar notificação');
    }
}

function markAllAsRead() {
    global $employeeId;
    
    if (dbExecute("UPDATE notifications SET is_read = TRUE, read_at = NOW() WHERE employee_id = ? AND is_read = FALSE", [$employeeId])) {
        jsonResponse(true, 'Todas as notificações foram marcadas como lidas');
    } else {
        jsonResponse(false, 'Erro ao marcar notificações');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
