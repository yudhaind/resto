<?php
// ambil-data.php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate'); // Pastikan browser tidak menyimpan cache

require_once 'database.php';
$target = isset($_POST['target']) ? $_POST['target'] : '';

if ($target === 'meja') {

try {
    // KOREKSI 1: Menambahkan titik koma (;) yang hilang di akhir string query
    // KOREKSI 2: Mengubah nama variabel dari $aql menjadi $sql agar lebih standar
    $sql_ksg = "SELECT COUNT(*) as nilai_ksg FROM `tables` WHERE tables.status = 'available'";
    $sql_isi = "SELECT COUNT(*) as nilai_isi FROM `tables` WHERE tables.status = 'occupied'";
    
    $data_ksg = fetchOne($sql_ksg);
    $data_isi = fetchOne($sql_isi);
   

    // KOREKSI 3: Fungsi COUNT(*) di SQL SELALU mengembalikan nilai (minimal angka 0)
    // Jadi statusnya selalu 'success', kita tinggal oper nilainya ke JSON.
    if ($data_ksg && isset($data_ksg['nilai_ksg']) && $data_isi && isset($data_isi['nilai_isi'])) {
        echo json_encode([
            'status' => 'success',
            'nilai_ksg'  => (int)$data_ksg['nilai_ksg'], // Di-cast ke integer agar tipe datanya berupa angka bersih
            'nilai_isi'  => (int)$data_isi['nilai_isi'],
            // Di-cast ke integer agar tipe datanya berupa angka bersih
        ]);
    } else {
        // Antisipasi jika fungsi fetchOne mengembalikan false karena kendala teknis
        echo json_encode([
            'status' => 'success',
            'nilai_ksg'  => 0,
            'nilai_isi'  => 0,
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

}
?>
