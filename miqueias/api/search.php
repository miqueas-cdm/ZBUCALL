<?php
/**
 * Search API
 * Global search across all modules
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$query = $_GET['q'] ?? '';
$employeeId = getCurrentEmployeeId();

if (empty($query) || strlen($query) < 2) {
    echo json_encode([
        'success' => false,
        'message' => 'Digite pelo menos 2 caracteres para buscar'
    ]);
    exit;
}

$searchTerm = '%' . $query . '%';
$results = [];

// Search in benefits
$benefits = dbGetAll(
    "SELECT id, benefit_name as title, description, 'benefit' as type, benefit_type as subtype
     FROM benefits 
     WHERE employee_id = ? AND (benefit_name LIKE ? OR description LIKE ?)
     LIMIT 5",
    [$employeeId, $searchTerm, $searchTerm]
);
foreach ($benefits as $benefit) {
    $benefit['url'] = 'benefits.php';
    $benefit['icon'] = 'gift';
    $results[] = $benefit;
}

// Search in documents
$documents = dbGetAll(
    "SELECT id, title, description, 'document' as type, category as subtype
     FROM documents 
     WHERE employee_id = ? AND (title LIKE ? OR description LIKE ?)
     LIMIT 5",
    [$employeeId, $searchTerm, $searchTerm]
);
foreach ($documents as $doc) {
    $doc['url'] = 'documents.php';
    $doc['icon'] = 'file-text';
    $results[] = $doc;
}

// Search in communications
$communications = dbGetAll(
    "SELECT id, title, content as description, 'communication' as type, category as subtype
     FROM communications 
     WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)
     LIMIT 5",
    [$searchTerm, $searchTerm]
);
foreach ($communications as $comm) {
    $comm['url'] = 'communications.php?id=' . $comm['id'];
    $comm['icon'] = 'bell';
    $results[] = $comm;
}

// Search in requests
$requests = dbGetAll(
    "SELECT id, title, description, 'request' as type, request_type as subtype
     FROM requests 
     WHERE employee_id = ? AND (title LIKE ? OR description LIKE ?)
     LIMIT 5",
    [$employeeId, $searchTerm, $searchTerm]
);
foreach ($requests as $req) {
    $req['url'] = 'requests.php';
    $req['icon'] = 'send';
    $results[] = $req;
}

echo json_encode([
    'success' => true,
    'query' => $query,
    'results' => $results,
    'total' => count($results)
]);
