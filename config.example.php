<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_NAME = 'your_database_name';
const DB_USER = 'your_database_user';
const DB_PASS = 'your_database_password';
const APP_NAME = 'Class Buddy Finder';
const APP_URL = ''; // Optional, e.g. https://example.com

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check the credentials in config.php.');
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}
function student_required(): void {
    if (empty($_SESSION['student_id'])) redirect('login.php');
}
function admin_required(): void {
    if (empty($_SESSION['admin_id'])) redirect('login.php');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = (string)($_POST['csrf_token'] ?? '');
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        exit('Invalid form token. Please go back, refresh the page and try again.');
    }
}
function notify_user(PDO $pdo, int $userId, string $title, string $body, string $url = 'notifications.php'): void {
    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, title, body, url) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $title, $body, $url]);
}
function current_student(PDO $pdo): array {
    $stmt = $pdo->prepare('SELECT id,name,email,branch,year,section,bio,hobbies,interests,created_at FROM students WHERE id = ?');
    $stmt->execute([(int)$_SESSION['student_id']]);
    $student = $stmt->fetch();
    if (!$student) { session_destroy(); redirect('login.php'); }
    return $student;
}
