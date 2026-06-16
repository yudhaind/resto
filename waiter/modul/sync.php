<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
require_once 'database.php';
$target = isset($_POST['target']) ? $_POST['target'] : '';
if ($target != 'table_waiter') { 
    echo json_encode([
        'status' => 'error',
        'message' => 'Target tidak valid.'
    ]);
    exit;
}
    // Contoh query untuk mendapatkan data meja untuk waiter    
   /* $sql_count = "SELECT COUNT(*) as total_meja FROM `tables`";
    $sql_detail = "SELECT * FROM `tables`";
    $data_detail = fetchAll($sql_detail);
    $list_meja = array();
    foreach ($data_detail as $row) {
        $list_meja[] = array(
            'id' => $row['id'],
            'nomeja' => $row['table_number'],
            'kapasitas' => $row['capacity'],
            'status' => $row['status'],
            'status_makanan' => 'Isi status masakan', // Asumsikan ada kolom status_makanan di tabel
        );
    }
    $data_count = fetchOne($sql_count);   
    echo json_encode([
        'status' => 'success',
        'total_meja' => $data_count['total_meja'], // Mengirimkan jumlah total meja sebagai data
        'list_meja' => $list_meja // Mengirimkan detail meja sebagai data
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Target tidak dikenali.'
    ]);
}


Join untuk mendapatkan data meja dengan status makanan yang masih pending:
SELECT 
    t.table_number AS `Nomor Meja`,
    o.customer_name AS `Nama Pelanggan`,
    p.name AS `Nama Produk`,
    od.quantity AS `Jumlah`,
    od.price_at_order AS `Harga`,
    (od.quantity * od.price_at_order) AS `Subtotal`,
    o.order_status AS `Status Order` -- Menampilkan status untuk memastikan
FROM 
    tables t
INNER JOIN 
    orders o ON t.id = o.table_id
INNER JOIN 
    order_details od ON o.id = od.order_id
INNER JOIN 
    products p ON od.product_id = p.id 
WHERE 
    t.id = 1                          -- Filter ID Meja
    AND o.id = 4                     -- Filter ID Order (Ganti angka 10 sesuai kebutuhan)
    AND od.cooking_status = 'pending';
*/
?>