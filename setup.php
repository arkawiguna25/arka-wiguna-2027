<?php
$config = require __DIR__ . '/config/database.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
            throw new RuntimeException('Isi nama dan email yang valid. Kata sandi admin minimal 10 karakter.');
        }
        $serverDsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
        $pdo = new PDO($serverDsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $dbName = str_replace('`', '``', $config['database']);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbName`");
        $sql = file_get_contents(__DIR__ . '/database/schema.sql');
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) $pdo->exec($statement);
        $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        if ($count === 0) {
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'admin']);
        }
        $message = 'Instalasi selesai. Silakan masuk menggunakan akun admin yang baru dibuat. Demi keamanan, hapus atau ganti nama file setup.php.';
    } catch (Throwable $e) { $error = $e->getMessage(); }
}
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Instalasi — RuangTA</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-page"><main class="auth-card"><div class="brand-mark">R</div><p class="eyebrow">PENYIAPAN APLIKASI</p><h1>Instalasi RuangTA</h1><p class="muted">Membuat database dan akun administrator pertama.</p><?php if ($message): ?><div class="alert success"><?=e($message)?></div><a class="button primary full" href="index.php">Buka aplikasi</a><?php else: ?><?php if ($error): ?><div class="alert danger"><?=e($error)?></div><?php endif; ?><form method="post"><label>Nama administrator<input name="name" required maxlength="120" value="<?=e($_POST['name'] ?? '')?>"></label><label>Email<input type="email" name="email" required value="<?=e($_POST['email'] ?? '')?>"></label><label>Kata sandi admin<input type="password" name="password" minlength="10" required autocomplete="new-password"></label><button class="button primary full">Buat database & akun</button></form><p class="hint">Pastikan MySQL aktif di XAMPP. Konfigurasi database dapat diubah di <code>config/database.php</code>.</p><?php endif; ?></main></body></html>
