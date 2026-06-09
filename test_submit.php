<?php
// Simple test runner to invoke submit_order in post.php
session_start();
// Ensure token matches what post.php expects
$_SESSION['token'] = 'testtoken123';

// Prepare POST data for a simple order
$_POST = [
    'tokenform' => 'testtoken123',
    'action' => 'submit_order',
    'table_id' => '1',
    'cashier_id' => '1',
    'customer_name' => 'Test Customer',
    'payment_method_selected' => 'tunai',
    'is_addon' => '0',
    'product_id' => ['1'],
    'price' => ['25000'],
    'quantity' => ['1'],
    'amount_paid' => '30000',
    'notes' => ['Catatan test item']
];

// Include the post handler
require 'post.php';

echo "\n-- test_submit.php completed --\n";
