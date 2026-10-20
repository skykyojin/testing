<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/config.php';

$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username dan password harus diisi']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $_SESSION['user']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>