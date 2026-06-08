<?php
session_start();
require_once 'database.php';
$action = $_POST['action'] ?? '';
$tokenform = $_POST['tokenform'] ?? '';
if ($tokenform !== $_SESSION['token']) {
    die("Token tidak valid!");
} else {
    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $sql = "SELECT * FROM users WHERE username = ?";
        $rsl = fetchOne($sql, [$username]);
        $status=$rsl['is_active'];
        if (empty($username) || empty($password) || $status === 3) {
            if ($status===3){
                $_SESSION['error']="User Tidak Aktif";
            } else {
                $_SESSION['error']="Username dan password wajib diisi";
            }
            header('location:logout.php');
            exit;
        } 
       
        // Lakukan validasi login di sini, misalnya dengan memeriksa database
        if ($rsl && password_verify($password, $rsl['password'])) {
            // Login berhasil, simpan informasi pengguna di session
            $_SESSION['user'] = [
                'id' => $rsl['id'],
                'username' => $rsl['username'],
                'role' => $rsl['role'],
                'name' => $rsl['name']
            ];
            header('location:./');
        } else {
            $_SESSION['error']="Username atau password salah";
            header('location:logout.php');
        }
   } else if ($action === 'tambah_meja') {
        $nomor_meja = $_POST['nomor_meja'] ?? '';
        $kapasitas_meja = $_POST['kapasitas_meja'] ?? '';

        if (empty($nomor_meja) || empty($kapasitas_meja)) {
            echo '<div class="error-message">Nomor meja dan kapasitas wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `tables` WHERE table_number = ?";
            $rsl_cek = numRows($sql_cek, [$nomor_meja]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Nomor meja sudah ada</div>';
            } else {
              $sql = "INSERT INTO `tables` (table_number, capacity, status) VALUES (?, ?, 'available')";
              query($sql, [$nomor_meja, $kapasitas_meja]);
              echo '<div class="ok-message">Meja berhasil ditambahkan</div>'; 
            }
         }
    } else if ($action === 'update_meja') {
        $id = $_POST['id'] ?? '';
        $nomor_meja = $_POST['nomor_meja'] ?? '';
        $kapasitas_meja = $_POST['kapasitas_meja'] ?? '';

        if (empty($nomor_meja) || empty($kapasitas_meja)) {
            echo '<div class="error-message">Nomor meja dan kapasitas wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `tables` WHERE table_number = ? AND id != ?";
            $rsl_cek = numRows($sql_cek, [$nomor_meja, $id]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Nomor meja sudah ada</div>';
            } else {
              $sql = "UPDATE `tables` SET table_number = ?, capacity = ? WHERE id = ?";
              query($sql, [$nomor_meja, $kapasitas_meja, $id]);
              echo '<div class="ok-message">Meja berhasil diperbarui</div>'; 
            }
         }
    } else if ($action === 'tambah_user') {
        $name = $_POST['name'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $is_active = $_POST['is_active'] ?? '';

        if (empty($name) || empty($username) || empty($password) || empty($role) || empty($is_active)) {
            echo '<div class="error-message">Semua field wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `users` WHERE username = ?";
            $rsl_cek = numRows($sql_cek, [$username]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Username sudah ada</div>';
            } else {
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);
              $sql = "INSERT INTO `users` (name, username, password, role, is_active) VALUES (?, ?, ?, ?, ?)";
              query($sql, [$name, $username, $hashed_password, $role, $is_active]);
              echo '<div class="ok-message">User berhasil ditambahkan</div>'; 
            }
        }
    } else if ($action === 'edit_user') {
        $name = $_POST['name'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $is_active = $_POST['is_active'] ?? '';
        $id=$_POST['id'] ?? '';

        if (empty($name) || empty($username) || empty($role) || empty($is_active)) {
            echo '<div class="error-message">Semua field wajib diisi</div>';  

        } else {
            $sql="SELECT * FROM users WHERE username = ?";
            $rsl_cek=fetchOne($sql,[$username]);
            $count_cek=numRows($sql,[$username]);
            $iddb = $rsl_cek ? $rsl_cek['id'] : null;
            //echo $count_cek." data ditemukan, id sendiri :".$id.", id database : ".$iddb;
            if ($count_cek > 0) {
                if ($id == $iddb){
                    if (empty($password)) {
                        $sql_update="UPDATE `users` SET `username` = ?, `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $name, $role, $is_active, $id]);
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql_update="UPDATE `users` SET `username` = ?, `password` = ? , `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $hashed_password, $name, $role, $is_active, $id]);
                    }
                    echo '<div class="ok-message">Data berhasil di update</div>';
                } else {
                    echo '<div class="error-message">Username Telah di gunakan Oranglain</div>'; 
                }
            } else {
                if (empty($password)) {
                        $sql_update="UPDATE `users` SET `username` = ?, `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $name, $role, $is_active, $id]);
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql_update="UPDATE `users` SET `username` = ?, `password` = ? , `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $hashed_password, $name, $role, $is_active, $id]);
                    }
                    echo '<div class="ok-message">Data berhasil di update</div>';    
            }
            
        }
    } else if ($action==='tambah_produk') {
        $cat=$_POST['cat'] ?? '';
        $namamenu=$_POST['namamenu'];
        $price=$_POST['price'];
        $status=$_POST['status'];
        $price=str_replace(".","",$price);
        $sql="INSERT INTO products (name, category, price, is_available, images) VALUES ( ?, ?, ?, ?, '');";
        if (query($sql,[$namamenu, $cat, $price, $status])){
            echo '<div class="ok-message">Produk Berhasil ditambahkan</div>';
        } else {
            echo '<div class="error-message">Produk Gagal ditambahkan</div>';
        }
        
    } else if ($action=='ubah_produk') {
        $id_item=$_POST['id_item'];
        $cat=$_POST['cat'];
        $namamenu=$_POST['namamenu'];
        $price=$_POST['price'];
        $price=str_replace(".","",$price);
        $status=$_POST['status'];

        $sql="UPDATE `products` SET `name` = ?, `category` = ?, `price` = ?, `is_available` = ? WHERE `products`.`id` = ?";
        if (query($sql,[$namamenu, $cat, $price, $status, $id_item])) {
            echo '<div class="ok-message">Item Berhasil di ubah</div>';
        } else {
            echo '<div class="error-message">Data gagal di update</div>';
        }
    } else if ($action=='edit_namatoko') {
        $namatoko=$_POST['namatoko'] ?? '';
        $sql="UPDATE global_settings SET value = ? WHERE label = 'nama_toko'";
        if (query($sql,[$namatoko])) {
            echo '<div class="ok-message">Nama Toko Berhasil di ubah</div>';
        } else {
            echo '<div class="error-message">Data gagal di update</div>';
        }
    } else if ($action=='submit_order'){
        $table_id = $_POST['table_id'] ?? '';
        $cashier_id = $_POST['cashier_id'] ?? '';
        $customer_name = trim($_POST['customer_name'] ?? '');
        $payment_method_selected = $_POST['payment_method_selected'] ?? '';
        $is_addon = $_POST['is_addon'] ?? '0';
        $product_ids = $_POST['product_id'] ?? [];
        $prices = $_POST['price'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $amount_paid = $_POST['amount_paid'] ?? '0';

        if (empty($table_id) || empty($cashier_id) || empty($payment_method_selected) || !is_array($product_ids) || count($product_ids) === 0) {
            echo 'ERROR: Data pesanan tidak lengkap';
            exit;
        }

        $paymentMap = [
            'tunai' => 'cash',
            'qris' => 'qris',
            'transfer' => 'debit'
        ];
        if (!isset($paymentMap[$payment_method_selected])) {
            echo 'ERROR: Metode pembayaran tidak valid';
            exit;
        }
        $payment_method = $paymentMap[$payment_method_selected];

        $amount_paid = preg_replace('/[^0-9.]/', '', $amount_paid);
        if ($amount_paid === '') {
            $amount_paid = 0;
        }
        $amount_paid = number_format((float)$amount_paid, 2, '.', '');

        $countItems = count($product_ids);
        if (count($prices) !== $countItems || count($quantities) !== $countItems) {
            echo 'ERROR: Data item pesanan tidak valid';
            exit;
        }

        $total_amount = 0;
        $order_items = [];
        for ($i = 0; $i < $countItems; $i++) {
            $product_id = intval($product_ids[$i]);
            $price = str_replace(['.', ','], ['', '.'], $prices[$i]);
            $quantity = intval($quantities[$i]);

            if ($product_id <= 0 || $quantity <= 0 || floatval($price) <= 0) {
                continue;
            }

            $price = number_format((float)$price, 2, '.', '');
            $subtotal = $price * $quantity;
            $total_amount += $subtotal;
            $order_items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price_at_order' => $price
            ];
        }

        if (count($order_items) === 0) {
            echo 'ERROR: Item pesanan tidak ditemukan';
            exit;
        }

        if (floatval($amount_paid) < $total_amount) {
            echo 'ERROR: Uang bayar kurang';
            exit;
        }

        try {
            beginTransaction();

            $order_status = 'completed';
            $sqlOrder = "INSERT INTO `orders` (`table_id`, `waiter_id`, `customer_name`, `total_amount`, `order_status`) VALUES (?, ?, ?, ?, ?);";
            query($sqlOrder, [$table_id, $cashier_id, $customer_name, number_format($total_amount, 2, '.', ''), $order_status]);
            $order_id = lastInsertId();

            $sqlDetail = "INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `price_at_order`) VALUES (?, ?, ?, ?);";
            foreach ($order_items as $item) {
                query($sqlDetail, [$order_id, $item['product_id'], $item['quantity'], $item['price_at_order']]);
            }

            $change_amount = number_format(floatval($amount_paid) - $total_amount, 2, '.', '');
            $sqlPayment = "INSERT INTO `payments` (`order_id`, `cashier_id`, `payment_method`, `amount_paid`, `change_amount`) VALUES (?, ?, ?, ?, ?);";
            query($sqlPayment, [$order_id, $cashier_id, $payment_method, $amount_paid, $change_amount]);

            $change_status_table="UPDATE `tables` SET `status` = 'occupied' WHERE `tables`.`id` = ?";
            query($change_status_table,[$table_id]);

            commit();
            echo 'SUKSES';
        } catch (Exception $e) {
            rollback();
            error_log('submit_order error: ' . $e->getMessage());
            echo 'ERROR: Gagal menyimpan pesanan';
        }
    }
}