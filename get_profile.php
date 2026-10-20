<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif']);
    exit;
}

try {
    $userId = $_SESSION['user']['id'];
    
    $stmt = $pdo->prepare("SELECT u.username, u.email, u.created_at, 
                           s.books_finished, s.books_reading, s.favorite_lists
                           FROM users u
                           LEFT JOIN user_stats s ON u.id = s.user_id
                           WHERE u.id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode([
            'success' => true,
            'user' => [
                'username' => $user['username'],
                'email' => $user['email'],
                'created_at' => $user['created_at'],
                'books_finished' => $user['books_finished'] ?? 0,
                'books_reading' => $user['books_reading'] ?? 0,
                'favorite_lists' => $user['favorite_lists'] ?? 0
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>