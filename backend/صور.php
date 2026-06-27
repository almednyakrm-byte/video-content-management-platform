<?php

// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST request
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Validate input data
if (!isset($input['id']) && !isset($input['title']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input data
$input['id'] = (int) $input['id'];
$input['title'] = trim($input['title']);
$input['description'] = trim($input['description']);

// Select all records
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM صور');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    exit;
}

// Select a record by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM صور WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Record not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Insert a new record
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        $stmt = $pdo->prepare('INSERT INTO صور (title, description) VALUES (:title, :description)');
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        $id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    exit;
}

// Update a record
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        $stmt = $pdo->prepare('UPDATE صور SET title = :title, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    exit;
}

// Delete a record
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        $stmt = $pdo->prepare('DELETE FROM صور WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    exit;
}

http_response_code(404);
echo json_encode(array('error' => 'Not found'));
exit;