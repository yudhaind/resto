SELECT 
    t.table_number AS `Nomor Meja`,
    o.customer_name AS `Nama Pelanggan`,
    p.name AS `Nama Produk`,
    od.quantity AS `Jumlah`,
    od.price_at_order AS `Harga`,
    od.cooking_status AS `Status Masak`,
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