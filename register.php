<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';
$email = trim($input['email'] ?? '');

if (empty($username) || empty($password) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
    exit;
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS user_stats (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL UNIQUE,
        books_finished INTEGER DEFAULT 0,
        books_reading INTEGER DEFAULT 0,
        favorite_lists INTEGER DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        if ($user['username'] === $username) {
            echo json_encode(['success' => false, 'message' => 'Username sudah digunakan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar']);
        }
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, datetime('now'))");
    $stmt->execute([$username, $email, $hashedPassword]);
    
    $userId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO user_stats (user_id) VALUES (?)");
    $stmt->execute([$userId]);

    echo json_encode(['success' => true, 'message' => 'Registrasi berhasil']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>