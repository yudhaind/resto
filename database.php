<?php
/**
 * Konfigurasi Database Academic System
 * Pastikan untuk mengubah nilai sesuai environment Anda
 */

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_resto');
define('DB_USER', 'adminphp');
define('DB_PASS', 'Adm1nitso');
define('DB_CHARSET', 'utf8mb4');

// Opsi koneksi PDO
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

/**
 * Fungsi untuk mendapatkan koneksi database
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
        } catch (PDOException $e) {
            // Log error untuk development
            error_log('Database Connection Error: ' . $e->getMessage());
            
            // Tampilkan pesan user-friendly
            die('Maaf, terjadi kesalahan koneksi database. 
                 Silakan hubungi administrator.');
        }
    }
    
    return $pdo;
}

/**
 * Fungsi untuk eksekusi query dengan prepared statement
 * @param string $sql
 * @param array $params
 * @return PDOStatement
 */
function query($sql, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fungsi untuk mengambil satu data
 * @param string $sql
 * @param array $params
 * @return mixed
 */
function fetchOne($sql, $params = []) {
    $stmt = query($sql, $params);
    return $stmt->fetch();
}

/**
 * Fungsi untuk mengambil semua data
 * @param string $sql
 * @param array $params
 * @return array
 */
function fetchAll($sql, $params = []) {
    $stmt = query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Fungsi untuk mengambil ID terakhir yang diinsert
 * @return int
 */
function lastInsertId() {
    return getDbConnection()->lastInsertId();
}


/**
 * Fungsi untuk mengambil semua data
 * @param string $sql
 * @param array $params
 * @return array
 */

function numRows($sql, $params = []) {
    $stmt = query($sql, $params);
    return $stmt->rowCount();
}

function beginTransaction() {
    return getDbConnection()->beginTransaction();
}

function commit() {
    return getDbConnection()->commit();
}

function rollback() {
    return getDbConnection()->rollBack();
}


/**
 * Fungsi untuk mengambil semua data
 * @param string $str
 * @return array
 */
function sc($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}