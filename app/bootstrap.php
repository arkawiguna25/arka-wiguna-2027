<?php
$config = require __DIR__ . '/../config/database.php';
function db(): PDO {
    static $pdo = null;
    global $config;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']);
        $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
    }
    return $pdo;
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('ruangta_session');
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) { header('Location: index.php?page=login'); exit; } }
function require_role(array $roles): void { require_login(); if (!in_array(current_user()['role'], $roles, true)) { http_response_code(403); exit('Akses tidak diizinkan.'); } }
function is_role(string $role): bool { return (current_user()['role'] ?? '') === $role; }
function csrf_field(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">'; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Sesi formulir tidak valid. Muat ulang halaman dan coba lagi.'); } }
function flash(string $type, string $text): void { $_SESSION['flash'] = [$type, $text]; }
function redirect(string $url = 'index.php'): never { header('Location: '.$url); exit; }
function can_access_submission(int $id): bool {
    if (in_array(current_user()['role'] ?? '', ['admin','dosen'], true)) return true;
    $stmt = db()->prepare('SELECT student_id FROM submissions WHERE id=?'); $stmt->execute([$id]);
    return (int)$stmt->fetchColumn() === (int)(current_user()['id'] ?? 0);
}
function status_label(string $status): string { return ['diajukan'=>'Diajukan','review'=>'Dalam review','proposal'=>'Seminar proposal','penelitian'=>'Penelitian','sidang'=>'Sidang','selesai'=>'Selesai','revisi'=>'Revisi'][$status] ?? $status; }
