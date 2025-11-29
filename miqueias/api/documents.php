<?php
/**
 * Documents API
 * Manages document uploads and downloads
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_GET['action'] ?? 'list';
$employeeId = getCurrentEmployeeId();

switch ($action) {
    case 'list':
        listDocuments();
        break;
    
    case 'upload':
        uploadDocument();
        break;
    
    case 'delete':
        deleteDocument();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function listDocuments() {
    global $employeeId;
    
    // Get employee documents
    $documents = dbGetAll(
        "SELECT * FROM documents 
         WHERE employee_id = ? OR is_public = TRUE
         ORDER BY created_at DESC",
        [$employeeId]
    );
    
    // Group by category
    $grouped = [];
    foreach ($documents as $doc) {
        $category = $doc['category'];
        if (!isset($grouped[$category])) {
            $grouped[$category] = [];
        }
        $grouped[$category][] = $doc;
    }
    
    jsonResponse(true, 'Documentos carregados', [
        'documents' => $documents,
        'grouped' => $grouped,
        'total' => count($documents)
    ]);
}

function uploadDocument() {
    global $employeeId;
    
    if (!isset($_FILES['file'])) {
        jsonResponse(false, 'Nenhum arquivo enviado');
        return;
    }
    
    $file = $_FILES['file'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $documentType = $_POST['document_type'] ?? 'other';
    $category = $_POST['category'] ?? 'other';
    
    if (empty($title)) {
        jsonResponse(false, 'Título é obrigatório');
        return;
    }
    
    // Create upload directory if not exists
    $uploadDir = __DIR__ . '/../uploads/documents/' . $employeeId . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '_' . time() . '.' . $extension;
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Save to database
        $documentId = dbInsert(
            "INSERT INTO documents (employee_id, document_type, title, description, file_name, file_path, file_size, mime_type, category, uploaded_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $employeeId,
                $documentType,
                $title,
                $description,
                $file['name'],
                'uploads/documents/' . $employeeId . '/' . $fileName,
                $file['size'],
                $file['type'],
                $category,
                $employeeId
            ]
        );
        
        if ($documentId) {
            jsonResponse(true, 'Documento enviado com sucesso', ['document_id' => $documentId]);
        } else {
            jsonResponse(false, 'Erro ao salvar documento no banco de dados');
        }
    } else {
        jsonResponse(false, 'Erro ao fazer upload do arquivo');
    }
}

function deleteDocument() {
    global $employeeId;
    
    $id = $_POST['id'] ?? 0;
    
    $document = dbGetRow(
        "SELECT * FROM documents WHERE id = ? AND employee_id = ?",
        [$id, $employeeId]
    );
    
    if (!$document) {
        jsonResponse(false, 'Documento não encontrado');
        return;
    }
    
    // Delete file
    $filePath = __DIR__ . '/../' . $document['file_path'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    // Delete from database
    if (dbExecute("DELETE FROM documents WHERE id = ?", [$id])) {
        jsonResponse(true, 'Documento excluído com sucesso');
    } else {
        jsonResponse(false, 'Erro ao excluir documento');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
