<?php
/**
 * Profile API
 * Handles profile updates
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'upload_photo':
        handleUploadPhoto();
        break;
    
    default:
        jsonResponse(false, 'Ação inválida');
}

function handleUploadPhoto() {
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(false, 'Erro no upload da imagem');
        return;
    }

    $file = $_FILES['photo'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        jsonResponse(false, 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.');
        return;
    }

    if ($file['size'] > $maxSize) {
        jsonResponse(false, 'Arquivo muito grande. Máximo de 5MB.');
        return;
    }

    $employeeId = getCurrentEmployeeId();
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $employeeId . '_' . time() . '.' . $extension;
    $uploadDir = __DIR__ . '/../assets/uploads/profiles/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destination = $uploadDir . $filename;
    $publicPath = 'assets/uploads/profiles/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Update database
        dbExecute(
            "UPDATE employees SET photo_url = ? WHERE id = ?",
            [$publicPath, $employeeId]
        );

        // Update session
        $_SESSION['employee_photo'] = $publicPath;

        jsonResponse(true, 'Foto atualizada com sucesso', [
            'photo_url' => $publicPath
        ]);
    } else {
        jsonResponse(false, 'Erro ao salvar o arquivo');
    }
}

function jsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
