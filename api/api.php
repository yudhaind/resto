<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once dirname(__DIR__) . '/database.php';

function apiResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    exit;
}

function buildWhereClause($params) {
    $where = [];
    $bind = [];

    if (!empty($params['start_date'])) {
        $where[] = 'o.created_at >= ?';
        $bind[] = $params['start_date'] . ' 00:00:00';
    }

    if (!empty($params['end_date'])) {
        $where[] = 'o.created_at <= ?';
        $bind[] = $params['end_date'] . ' 23:59:59';
    }

    if (!empty($params['status'])) {
        $where[] = 'o.order_status = ?';
        $bind[] = $params['status'];
    }

    return [
        'where' => $where,
        'bind' => $bind,
    ];
}

$action = $_GET['action'] ?? $_GET['type'] ?? 'summary';
$action = strtolower(trim($action));

try {
    $filter = buildWhereClause($_GET);
    $whereSql = '';
    $bind = $filter['bind'];

    if (!empty($filter['where'])) {
        $whereSql = ' WHERE ' . implode(' AND ', $filter['where']);
    }

    if ($action === 'summary') {
        $summarySql = "SELECT
            COALESCE(SUM(o.total_amount), 0) AS total_revenue,
            COUNT(o.id) AS total_orders,
            SUM(CASE WHEN o.order_status = 'completed' THEN 1 ELSE 0 END) AS completed_orders,
            SUM(CASE WHEN o.order_status = 'pending' THEN 1 ELSE 0 END) AS pending_orders
        FROM orders o" . $whereSql;

        $summaryRow = fetchOne($summarySql, $bind);

        $itemsSql = "SELECT COALESCE(SUM(od.quantity), 0) AS total_items
            FROM orders o
            LEFT JOIN order_details od ON od.order_id = o.id" . $whereSql;

        $itemsRow = fetchOne($itemsSql, $bind);

        $summary = [
            'total_revenue' => (float) ($summaryRow['total_revenue'] ?? 0),
            'total_orders' => (int) ($summaryRow['total_orders'] ?? 0),
            'completed_orders' => (int) ($summaryRow['completed_orders'] ?? 0),
            'pending_orders' => (int) ($summaryRow['pending_orders'] ?? 0),
            'total_items' => (int) ($itemsRow['total_items'] ?? 0),
            'average_order_value' => ($summaryRow['total_orders'] > 0)
                ? (float) ($summaryRow['total_revenue'] / $summaryRow['total_orders'])
                : 0,
        ];

        apiResponse([
            'success' => true,
            'message' => 'Ringkasan pendapatan dan pesanan berhasil diambil',
            'data' => $summary,
        ]);
    }

    if ($action === 'orders' || $action === 'transactions') {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 100;
        $limit = max(1, min($limit, 500));

        $ordersSql = "SELECT
            o.id,
            o.table_id,
            o.customer_name,
            o.total_amount,
            o.order_status,
            o.created_at,
            o.updated_at,
            (SELECT COUNT(*) FROM order_details od WHERE od.order_id = o.id) AS item_count,
            (SELECT p.payment_method FROM payments p WHERE p.order_id = o.id ORDER BY p.id DESC LIMIT 1) AS payment_method,
            (SELECT p.amount_paid FROM payments p WHERE p.order_id = o.id ORDER BY p.id DESC LIMIT 1) AS amount_paid,
            (SELECT p.change_amount FROM payments p WHERE p.order_id = o.id ORDER BY p.id DESC LIMIT 1) AS change_amount
        FROM orders o" . $whereSql . "
        ORDER BY o.created_at DESC
        LIMIT ?";

        $orders = fetchAll($ordersSql, array_merge($bind, [$limit]));

        apiResponse([
            'success' => true,
            'message' => 'Data pesanan berhasil diambil',
            'data' => [
                'count' => count($orders),
                'orders' => $orders,
            ],
        ]);
    }

    apiResponse([
        'success' => false,
        'message' => 'Endpoint tidak dikenali. Gunakan action=summary atau action=orders.',
    ], 400);
} catch (Exception $e) {
    apiResponse([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengambil data API',
        'error' => $e->getMessage(),
    ], 500);
}
