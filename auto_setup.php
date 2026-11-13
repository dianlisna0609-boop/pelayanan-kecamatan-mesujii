<?php
/**
 * Skrip Auto-Setup Database MySQL untuk Proyek:
 * Sistem Informasi Pelayanan Masyarakat Kantor Kecamatan Mesuji
 */

header('Content-Type: text/html; charset=UTF-8');

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';
$dbname = 'db_pelayanan_mesuji';

echo "<h2>🔧 Menjalankan Skrip Auto-Setup Database MySQL</h2>";
echo "<pre style='background:#f4f4f4; padding:15px; border-radius:8px; font-family:Consolas, monospace;'>";

try {
    // 1. Koneksi ke MySQL Server
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ [1/4] Terhubung ke MySQL Server (Host: $host:$port, User: $user)\n";

    // 2. Buat Database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "✅ [2/4] Database `$dbname` berhasil dibuat / dipastikan siap.\n";

    // 3. Impor File SQL jika tersedia
    $sqlFile = __DIR__ . '/db_pelayanan_mesuji.sql';
    if (file_exists($sqlFile)) {
        $pdo->exec("USE `$dbname`;");
        $sqlContent = file_get_contents($sqlFile);
        $pdo->exec($sqlContent);
        echo "✅ [3/4] Seluruh tabel dan dummy data berhasil diimpor dari file SQL.\n";
    } else {
        echo "ℹ️ [3/4] Menjalankan perintah Laravel Artisan Migrate...\n";
        exec('php artisan migrate:fresh --seed --force', $output, $returnVar);
        echo implode("\n", $output) . "\n";
    }

    // 4. Verifikasi Jumlah Tabel
    $pdo->exec("USE `$dbname`;");
    $stmt = $pdo->query("SHOW TABLES;");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "✅ [4/4] Status Akhir Database `$dbname`:\n";
    echo "--------------------------------------------------------\n";
    echo "Total Tabel Ditemukan: " . count($tables) . " tabel\n";
    foreach ($tables as $i => $tbl) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$tbl`")->fetchColumn();
        echo sprintf(" %2d. %-25s : %d baris data\n", $i + 1, $tbl, $count);
    }
    echo "--------------------------------------------------------\n";
    echo "🎉 Database siap digunakan! Anda dapat mengeceknya di: http://localhost/phpmyadmin/\n";
    echo "👉 Buka Website: <a href='public/' target='_blank'>http://localhost/pelayanan%20mesuji/public/</a>\n";

} catch (Exception $e) {
    echo "❌ Terjadi Kesalahan: " . $e->getMessage() . "\n";
}

echo "</pre>";

