<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get content ID from query parameter
    $contentID = $_GET['contentID'] ?? null;

    // Validate content ID
    if (!$contentID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid content ID']);
        exit;
    }

    // Prepare SQL query to select content
    $stmt = $pdo->prepare('SELECT * FROM محتوى_إلكتروني WHERE contentID = :contentID');
    $stmt->bindParam(':contentID', $contentID);
    $stmt->execute();

    // Fetch content data
    $contentData = $stmt->fetch();

    // Return content data
    if ($contentData) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($contentData);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Content not found']);
    }
}

// Handle POST request
if ($method === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Prepare SQL query to insert content
    $stmt = $pdo->prepare('INSERT INTO محتوى_إلكتروني (title, description, created_by) VALUES (:title, :description, :created_by)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $userID);
    $stmt->execute();

    // Return inserted content ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['contentID' => $pdo->lastInsertId()]);
}

// Handle PUT request
if ($method === 'PUT') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get content ID from query parameter
    $contentID = $_GET['contentID'] ?? null;

    // Validate content ID
    if (!$contentID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid content ID']);
        exit;
    }

    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Prepare SQL query to update content
    $stmt = $pdo->prepare('UPDATE محتوى_إلكتروني SET title = :title, description = :description, updated_by = :updated_by WHERE contentID = :contentID');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':updated_by', $userID);
    $stmt->bindParam(':contentID', $contentID);
    $stmt->execute();

    // Return updated content ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['contentID' => $contentID]);
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get content ID from query parameter
    $contentID = $_GET['contentID'] ?? null;

    // Validate content ID
    if (!$contentID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid content ID']);
        exit;
    }

    // Prepare SQL query to delete content
    $stmt = $pdo->prepare('DELETE FROM محتوى_إلكتروني WHERE contentID = :contentID');
    $stmt->bindParam(':contentID', $contentID);
    $stmt->execute();

    // Return deleted content ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['contentID' => $contentID]);
}