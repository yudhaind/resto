<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

$item_id = $_POST['item_id'] ?? 0;
$new_status = $_POST['status'] ?? 'cooking';
$target = $_POST['target'] ?? '';

if ($target !== 'kitchen' || !$item_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter tidak valid.'
    ]);
    exit;
}

try {
    // Validasi status value
    $valid_statuses = ['pending', 'cooking', 'done'];
    if (!in_array($new_status, $valid_statuses)) {
        throw new Exception('Status tidak dikenali.');
    }

    // Update order_details cooking_status
    $sql = "UPDATE order_details SET cooking_status = ? WHERE id = ?";
    query($sql, [$new_status, $item_id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Status item berhasil diperbarui.',
        'item_id' => (int)$item_id,
        'new_status' => $new_status
    ]);
} catch (Exception $e) {
    http_response_code(500);
    error_log('Kitchen update item error: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal update status: ' . $e->getMessage()
    ]);
}
