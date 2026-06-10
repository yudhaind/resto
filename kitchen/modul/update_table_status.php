<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

$table_id = isset($_POST['table_id']) ? (int)$_POST['table_id'] : 0;
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = $_POST['status'] ?? '';
$target = $_POST['target'] ?? '';

if ($target !== 'kitchen' || (!$table_id && !$order_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak valid.']);
    exit;
}

try {
    $allowed = ['cooking', 'served'];
    if (!in_array($status, $allowed)) {
        throw new Exception('Status tidak dikenali.');
    }

    // Tentukan status sumber yang akan diubah
    $from = $status === 'cooking' ? 'pending' : 'cooking';

    if ($order_id) {
        // Update items only for the specific order
        $sql = "UPDATE order_details SET cooking_status = ? WHERE cooking_status = ? AND order_id = ?";
        $stmt = query($sql, [$status, $from, $order_id]);
        $affected = $stmt->rowCount();

        echo json_encode([
            'status' => 'success',
            'message' => 'Update selesai.',
            'order_id' => $order_id,
            'updated_rows' => $affected
        ]);
    } else {
        // Update only items in orders for the given table and not cancelled
        $sql = "UPDATE order_details SET cooking_status = ? WHERE cooking_status = ? AND order_id IN (
            SELECT id FROM orders WHERE table_id = ? AND order_status != 'cancelled'
        )";

        $stmt = query($sql, [$status, $from, $table_id]);
        $affected = $stmt->rowCount();

        echo json_encode([
            'status' => 'success',
            'message' => 'Update selesai.',
            'table_id' => $table_id,
            'updated_rows' => $affected
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log('Kitchen bulk update error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan update: ' . $e->getMessage()]);
}
