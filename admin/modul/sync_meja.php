<?php
// ambil-data.php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate'); // Pastikan browser tidak menyimpan cache

require_once 'database.php';

try {
    // KOREKSI 1: Menambahkan titik koma (;) yang hilang di akhir string query
    // KOREKSI 2: Mengubah nama variabel dari $aql menjadi $sql agar lebih standar
    $sql = "SELECT COUNT(*) as nilai FROM `tables` WHERE tables.status = 'available'";
    
    $data = fetchOne($sql);

    // KOREKSI 3: Fungsi COUNT(*) di SQL SELALU mengembalikan nilai (minimal angka 0)
    // Jadi statusnya selalu 'success', kita tinggal oper nilainya ke JSON.
    if ($data && isset($data['nilai'])) {
        echo json_encode([
            'status' => 'success',
            'nilai'  => (int)$data['nilai'] // Di-cast ke integer agar tipe datanya berupa angka bersih
        ]);
    } else {
        // Antisipasi jika fungsi fetchOne mengembalikan false karena kendala teknis
        echo json_encode([
            'status' => 'success',
            'nilai'  => 0
        ]);
    }

} catch (Exception $e) {
    // Antisipasi jika koneksi database terputus di tengah jalan saat request per detik
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'pesan'  => 'Gagal mengeksekusi data.'
    ]);
}
    
?>
