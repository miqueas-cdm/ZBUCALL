<?php
/**
 * Communications API
 * Manages internal communications and news
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        listCommunications();
        break;
    
    case 'get':
        getCommunication();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function listCommunications() {
    $category = $_GET['category'] ?? null;
    
    $sql = "SELECT c.*, e.full_name as author_name
            FROM communications c
            LEFT JOIN employees e ON c.author_id = e.id
            WHERE c.status = 'published'
            AND (c.published_at IS NULL OR c.published_at <= NOW())
            AND (c.expires_at IS NULL OR c.expires_at > NOW())";
    
    $params = [];
    
    if ($category) {
        $sql .= " AND c.category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY c.priority DESC, c.published_at DESC LIMIT 50";
    
    $communications = dbGetAll($sql, $params);
    
    // Group by category
    $grouped = [];
    foreach ($communications as $comm) {
        $cat = $comm['category'];
        if (!isset($grouped[$cat])) {
            $grouped[$cat] = [];
        }
        $grouped[$cat][] = $comm;
    }
    
    jsonResponse(true, 'Comunicados carregados', [
        'communications' => $communications,
        'grouped' => $grouped,
        'total' => count($communications)
    ]);
}

function getCommunication() {
    $id = $_GET['id'] ?? 0;
    
    $communication = dbGetRow(
        "SELECT c.*, e.full_name as author_name
         FROM communications c
         LEFT JOIN employees e ON c.author_id = e.id
         WHERE c.id = ?",
        [$id]
    );
    
    if (!$communication) {
        jsonResponse(false, 'Comunicado não encontrado');
        return;
    }
    
    // Increment view count
    dbExecute("UPDATE communications SET views = views + 1 WHERE id = ?", [$id]);
    
    jsonResponse(true, 'Comunicado carregado', ['communication' => $communication]);
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
