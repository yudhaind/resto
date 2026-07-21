<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once 'database.php';

$target = $_POST['target'] ?? '';

if ($target !== 'dashboard') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Target tidak valid.'
    ]);
    exit;
}

try {
    $revenueData = fetchOne(
        "SELECT COALESCE(SUM(total_amount), 0) AS total_revenue
         FROM orders
         WHERE DATE(updated_at) = CURDATE()"
    );

    $paidData = fetchOne(
        "SELECT COUNT(*) AS total_paid
         FROM payments
         WHERE DATE(paid_at) = CURDATE()"
    );

    $pendingData = fetchOne(
        "SELECT COUNT(DISTINCT table_id) AS pending_tables
         FROM orders
         WHERE order_status = 'pending'
           AND DATE(created_at) = CURDATE()"
    );

    echo json_encode([
        'status' => 'success',
        'revenue' => (float)($revenueData['total_revenue'] ?? 0),
        'paid_transactions' => (int)($paidData['total_paid'] ?? 0),
        'pending_tables' => (int)($pendingData['pending_tables'] ?? 0)
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memuat data dashboard.'
    ]);
}
?>