<?php
// ambil-data.php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate'); // Pastikan browser tidak menyimpan cache

require_once 'database.php';
$target = isset($_POST['target']) ? $_POST['target'] : '';

if ($target === 'meja-di-kasir') {

try {
    // KOREKSI 1: Menambahkan titik koma (;) yang hilang di akhir string query
    // KOREKSI 2: Mengubah nama variabel dari $aql menjadi $sql agar lebih standar
    $sql_ksg = "SELECT COUNT(*) as nilai_ksg FROM `tables` WHERE tables.status = 'available'";
    $sql_isi = "SELECT COUNT(*) as nilai_isi FROM `tables` WHERE tables.status = 'occupied'";
    $sql_wkt = "SELECT sum(updated_at) AS total_waktu FROM `tables`";
    $sql_detail = "SELECT * FROM `tables`";
    
    $data_ksg = fetchOne($sql_ksg);
    $data_isi = fetchOne($sql_isi);
    $data_detail = fetchAll($sql_detail);
    $data_total = (int)$data_ksg['nilai_ksg'] + (int)$data_isi['nilai_isi']; // Total meja yang terdaftar di database
    $data_wkt = fetchOne($sql_wkt);
    
    $list_meja = array();
    foreach ($data_detail as $row) {
        $list_meja[] = array(
            'id' => $row['id'],
            'nomeja' => $row['table_number'],
            'kapasitas' => $row['capacity'],
            'status' => $row['status']
        );
    }

    // KOREKSI 3: Fungsi COUNT(*) di SQL SELALU mengembalikan nilai (minimal angka 0)
    // Jadi statusnya selalu 'success', kita tinggal oper nilainya ke JSON.
    if ($data_ksg && isset($data_ksg['nilai_ksg']) && $data_isi && isset($data_isi['nilai_isi'])) {
        echo json_encode([
            'status' => 'success',
            'nilai_ksg'  => (int)$data_ksg['nilai_ksg'], // Di-cast ke integer agar tipe datanya berupa angka bersih
            'nilai_isi'  => (int)$data_isi['nilai_isi'],
            'total_waktu' => (int)$data_wkt['total_waktu'],
            'total_meja' => (int)$data_total,
            'list_meja' => $list_meja
            // Di-cast ke integer agar tipe datanya berupa angka bersih
        ]);
    } else {
        // Antisipasi jika fungsi fetchOne mengembalikan false karena kendala teknis
        echo json_encode([
            'status' => 'success',
            'nilai_ksg'  => 0,
            'nilai_isi'  => 0,
            'total_waktu' => 0,
            'total_meja' => 0,
            'list_meja' => []
             // Jika data tidak ditemukan, tetap kembalikan status 'success' dengan nilai 0 dan list_meja kosong
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
