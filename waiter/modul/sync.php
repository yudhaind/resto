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

try {
     $tables = fetchAll("SELECT id, table_number, capacity, status FROM `tables` ORDER BY id ASC");
    $tableStatuses = [];
    foreach ($tables as $table) {
        // Count pending and cooking items per table
        $counts = fetchOne(
            "SELECT 
                SUM(CASE WHEN od.cooking_status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                SUM(CASE WHEN od.cooking_status = 'cooking' THEN 1 ELSE 0 END) AS cooking_count
            FROM order_details od
            JOIN orders o ON o.id = od.order_id
            WHERE o.table_id = ? AND o.order_status != 'cancelled'",
            [$table['id']]
        );

        $pending = (isset($counts['pending_count']) && $counts['pending_count']) ? (int)$counts['pending_count'] : 0;
        $cooking = (isset($counts['cooking_count']) && $counts['cooking_count']) ? (int)$counts['cooking_count'] : 0;

        $tableStatuses[] = [
            'id' => (int)$table['id'], 
            'table_number' => $table['table_number'],
            'capacity' => (int)$table['capacity'],
            'status' => $table['status'],
            'pending_count' => $pending,
            'cooking_count' => $cooking,
        ];
    }

    // Get active orders with items
    $orderRows = fetchAll(
        "SELECT DISTINCT o.id AS order_id, t.table_number, o.table_id, o.created_at,
            (SELECT SUM(CASE WHEN od.cooking_status = 'pending' THEN 1 ELSE 0 END) 
             FROM order_details od WHERE od.order_id = o.id) AS pending_items,
            (SELECT SUM(CASE WHEN od.cooking_status = 'cooking' THEN 1 ELSE 0 END) 
             FROM order_details od WHERE od.order_id = o.id) AS cooking_items
        FROM orders o
        JOIN tables t ON t.id = o.table_id
        WHERE o.order_status != 'cancelled'
        ORDER BY o.created_at ASC"
    );

    $orders = [];
    foreach ($orderRows as $orderRow) {
        $items = fetchAll(
            "SELECT od.id, od.quantity, od.notes, od.cooking_status, p.name
             FROM order_details od
             JOIN products p ON p.id = od.product_id
             WHERE od.order_id = ?
             ORDER BY od.id ASC",
            [$orderRow['order_id']]
        );

        $pending_items = (int)($orderRow['pending_items'] ?? 0);
        $cooking_items = (int)($orderRow['cooking_items'] ?? 0);

        // Only show orders with pending or cooking items
        if ($pending_items > 0 || $cooking_items > 0) {
            $orders[] = [
                'order_id' => (int)$orderRow['order_id'],
                'table_id' => (int)$orderRow['table_id'],
                'table_number' => $orderRow['table_number'],
                'created_at' => $orderRow['created_at'],
                'pending_items' => $pending_items,
                'cooking_items' => $cooking_items,
                'items' => array_map(function ($item) {
                    return [
                        'id' => (int)$item['id'],
                        'name' => $item['name'],
                        'quantity' => (int)$item['quantity'],
                        'notes' => $item['notes'] ?? '',
                        'cooking_status' => $item['cooking_status'],
                    ];
                }, $items)
            ];
        }
    }

    echo json_encode([
        'status' => 'success',
        'list_tables' => $tableStatuses,
        'orders' => $orders,
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    error_log('Kitchen sync error: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memuat data dapur: ' . $e->getMessage()
    ]);
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