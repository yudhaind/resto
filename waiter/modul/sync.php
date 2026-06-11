<?php
require_once 'database.php'; // Pastikan path ini benar sesuai struktur proyek Anda

// 1. Atur header JSON
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); 

$method = $_SERVER['REQUEST_METHOD'];
$response = [];

// 2. Logika penanganan request
if ($method === 'POST') {
    
    // Ambil token secara raw
    $globaltoken = isset($_POST['globaltoken']) ? htmlspecialchars($_POST['globaltoken']) : '';
    
    // Ambil payload stringify JSON
    $payload_raw = isset($_POST['payload']) ? $_POST['payload'] : '{}';
    $data_json = json_decode($payload_raw, true); // Diubah menjadi array asosiatif PHP

    // Ekstrak data dari dalam JSON secara aman
    $ajax = isset($data_json['ajax']) ? htmlspecialchars($data_json['ajax']) : '';
    $target = isset($data_json['target']) ? htmlspecialchars($data_json['target']) : '';

    // 3. Validasi apakah data yang diperlukan ada di dalam JSON
    if (!empty($ajax) && !empty($target)) {
        
        // --- TEMPAT LOGIKA BISNIS ATAU QUERY DATABASE ANDA ---
        // Contoh: $db->query("...");
        
        // Susun respons sukses
        http_response_code(200);
        $response = [
            "status" => "success",
            "message" => "Data berhasil diterima oleh server",
            "data" => [
                "ajax" => $ajax,
                "target" => $target,
                "token_terdeteksi" => $globaltoken
            ]
        ];

    } else {
        // Susun respons jika data di dalam payload JSON kosong/tidak lengkap
        http_response_code(400); 
        $response = [
            "status" => "error",
            "message" => "Data JSON tidak lengkap. 'ajax' dan 'target' wajib ada di dalam payload.",
            "debug_input" => $data_json
        ];
    }

} else {
    // Jika metode HTTP bukan POST
    http_response_code(405); 
    $response = [
        "status" => "error",
        "message" => "Metode HTTP tidak diizinkan."
    ];
}

// 4. Cetak output akhir dalam format JSON murni
echo json_encode($response);
exit();
?>