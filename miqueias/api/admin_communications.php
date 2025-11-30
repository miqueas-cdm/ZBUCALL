<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

// Check admin auth
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'create') {
    handleCreateCommunication();
} else {
    echo json_encode(['success' => false, 'message' => 'Ação inválida']);
}

function handleCreateCommunication() {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? 'news';
    $priority = $_POST['priority'] ?? 'normal';
    $authorId = getCurrentEmployeeId();
    
    if (empty($title) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Título e conteúdo são obrigatórios']);
        return;
    }
    
    // Handle Image Upload
    $imageUrl = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/uploads/communications/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExt, $allowed)) {
            $fileName = uniqid('comm_') . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imageUrl = 'assets/uploads/communications/' . $fileName;
            }
        }
    }
    
    // Handle Survey
    $surveyQuestion = null;
    $surveyOptions = null;
    
    if (isset($_POST['has_survey']) && $_POST['has_survey'] === 'on') {
        $surveyQuestion = $_POST['survey_question'] ?? null;
        $optionsRaw = $_POST['survey_options'] ?? '';
        
        if ($surveyQuestion && $optionsRaw) {
            $options = array_map('trim', explode(',', $optionsRaw));
            $options = array_filter($options); // Remove empty
            if (!empty($options)) {
                $surveyOptions = json_encode(array_values($options));
            }
        }
    }
    
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            INSERT INTO communications (
                title, content, category, priority, image_url, 
                survey_question, survey_options, author_id, published_at, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'published')
        ");
        
        $stmt->execute([
            $title, $content, $category, $priority, $imageUrl,
            $surveyQuestion, $surveyOptions, $authorId
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Comunicado criado com sucesso']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
    }
}
