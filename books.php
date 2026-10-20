<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

try {
    $stmt = $pdo->query("SELECT * FROM books");
    $books = $stmt->fetchAll();
    echo json_encode($books);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>