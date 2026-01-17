<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "logins";

$connect = new mysqli($servername, $username, $password, $database);
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] == 'update' && isset($_GET['change'])) {
        $change = $_GET['change'];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $change;
            if ($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    } elseif ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}

// Dữ liệu cố định cho sản phẩm tĩnh
$static_products = [
    1001 => [
        'product_id' => 1001,
        'product_name' => 'Gấu bông Capybara ôm vịt vàng',
        'product_price' => 239000,
        'product_img' => 'gau-bong-capybara-om-vit-vang-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara ôm vịt vàng dễ thương, giảm giá 15%.',
        'discount' => 15
    ],
    1002 => [
        'product_id' => 1002,
        'product_name' => 'Gấu bông Capybara khoai tây chiên',
        'product_price' => 239000,
        'product_img' => 'gaubong-capybara-khoai-tay-chien-o-400x400.jpg',
        'product_description' => 'Gấu bông Capybara với khoai tây chiên, giảm giá 15%.',
        'discount' => 15
    ],
    1003 => [
        'product_id' => 1003,
        'product_name' => 'Gấu bông Capybara bánh mì mini',
        'product_price' => 299999,
        'product_img' => 'gau-bong-banh-mi-capybara-mini-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara hình bánh mì mini, kích thước nhỏ gọn.'
    ],
    1004 => [
        'product_id' => 1004,
        'product_name' => 'Gấu bông Capybara hồng rút mũi',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-hong-rut-mui-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara hồng với thiết kế rút mũi độc đáo.'
    ],
    1005 => [
        'product_id' => 1005,
        'product_name' => 'Gấu bông Capybara mặc đầm đi biển',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-mac-dam-di-bien-4-400x400.jpg',
        'product_description' => 'Gấu bông Capybara mặc đầm đi biển, phong cách dễ thương.'
    ],
    1006 => [
        'product_id' => 1006,
        'product_name' => 'Gấu bông Capybara đeo phao',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-deo-phao-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara đeo phao bơi, thiết kế ngộ nghĩnh.'
    ],
    1007 => [
        'product_id' => 1007,
        'product_name' => 'Gấu bông Capybara tennis hồng',
        'product_price' => 3239000,
        'product_img' => 'gaubong-capybara-tenis-mau-hong-co-men-2in1-2-400x400.jpg',
        'product_description' => 'Gấu bông Capybara tennis hồng cao cấp, thiết kế 2 trong 1.'
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #e9ecef; /* Màu nền giống trang tìm kiếm */
            margin: 0; 
            padding: 0; 
            color: #343a40; 
        }
        .cart-container { 
            max-width: 1000px; /* Tăng chiều rộng để phù hợp với trang tìm kiếm */
            margin: 50px auto; 
            background: #f8f9fa; /* Màu nền sản phẩm trong trang tìm kiếm */
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.1); /* Hiệu ứng bóng giống trang tìm kiếm */
            border: 1px solid #cce5ff; /* Viền nhẹ giống sản phẩm */
        }
        h1 { 
            text-align: center; 
            color: #007bff; /* Màu xanh chủ đạo */
            font-size: 28px; 
            margin-bottom: 20px; 
        }
        .cart-item { 
            display: flex; 
            align-items: center; 
            padding: 15px; 
            border-bottom: 1px solid #cce5ff; /* Viền nhẹ giống trang tìm kiếm */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; 
        }
        .cart-item:hover { 
            transform: scale(1.02); /* Hiệu ứng phóng to nhẹ */
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2); /* Hiệu ứng bóng khi hover */
            background: #fff; 
        }
        .cart-item img { 
            width: 100px; /* Tăng kích thước hình ảnh */
            height: 100px; 
            object-fit: cover; 
            border-radius: 10px; 
            border: 1px solid #66b0ff; /* Viền xanh nhạt */
        }
        .cart-item-details { 
            flex: 1; 
            padding-left: 20px; 
        }
        .cart-item-details span { 
            font-weight: bold; 
            color: #007bff; /* Màu xanh cho tên sản phẩm */
            font-size: 16px; 
        }
        .cart-item-details small { 
            display: block; 
            color: #666; 
            margin-top: 5px; 
            font-size: 14px; 
        }
        .cart-item div:last-child { 
            display: flex; 
            align-items: center; 
            gap: 15px; /* Tăng khoảng cách giữa các nút */
        }
        .cart-item button { 
            background: linear-gradient(90deg, #ff6b6b, #ff8787); /* Gradient đỏ */
            color: white; 
            border: none; 
            padding: 8px 12px; 
            cursor: pointer; 
            border-radius: 5px; 
            transition: background 0.3s ease-in-out, transform 0.2 депут

s ease; 
            font-size: 14px; 
        }
        .cart-item button:hover { 
            background: linear-gradient(90deg, #e55a5a, #ff6b6b); 
            transform: translateY(-2px); /* Hiệu ứng nâng lên */
        }
        .cart-total { 
            text-align: right; 
            font-size: 20px; 
            font-weight: bold; 
            margin-top: 25px; 
            color: #0056b3; /* Màu xanh đậm giống giá sản phẩm */
        }
        .checkout-button { 
            display: block; 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(90deg, #007bff, #66b0ff); /* Gradient xanh giống trang tìm kiếm */
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            text-align: center; 
            margin-top: 20px; 
            text-decoration: none; 
            transition: background 0.3s ease, transform 0.2s ease; 
        }
        .checkout-button:hover { 
            background: linear-gradient(90deg, #0056b3, #3399ff); 
            transform: translateY(-2px); 
        }
        .back-button { 
            display: inline-block; 
            padding: 10px 20px; 
            background: linear-gradient(90deg, #6c757d, #8e959b); /* Gradient xám */
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            text-align: center; 
            margin-bottom: 20px; 
            text-decoration: none; 
            transition: background 0.3s ease, transform 0.2s ease; 
        }
        .back-button:hover { 
            background: linear-gradient(90deg, #5a6268, #6c757d); 
            transform: translateY(-2px); 
        }
        .empty-cart { 
            text-align: center; 
            color: #666; 
            font-size: 18px; 
            padding: 20px; 
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
        <h1>Giỏ hàng của bạn</h1>
        <div id="cart-items">
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Kiểm tra sản phẩm tĩnh
                    if (isset($static_products[$product_id])) {
                        $product = $static_products[$product_id];
                        $subtotal = $product['product_price'] * $quantity;
                        $total += $subtotal;
                        echo "<div class='cart-item'>
                                <img src='{$product['product_img']}' alt='{$product['product_name']}'>
                                <div class='cart-item-details'>
                                    <span>{$product['product_name']} - " . number_format($product['product_price'], 0, ',', '.') . " VNĐ</span><br>
                                    <small>Mô tả: {$product['product_description']}</small>
                                </div>
                                <div>
                                    <a href='?action=update&id={$product_id}&change=-1'><button>-</button></a>
                                    <span> $quantity </span>
                                    <a href='?action=update&id={$product_id}&change=1'><button>+</button></a>
                                    <a href='?action=remove&id={$product_id}'><button>Xóa</button></a>
                                </div>
                              </div>";
                    } else {
                        // Kiểm tra sản phẩm động từ database
                        $sql = "SELECT * FROM products WHERE product_id = $product_id";
                        $result = $connect->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $subtotal = $row['product_price'] * $quantity;
                            $total += $subtotal;
                            echo "<div class='cart-item'>
                                    <img src='./DemoWebsite/{$row['product_img']}' alt='{$row['product_name']}'>
                                    <div class='cart-item-details'>
                                        <span>{$row['product_name']} - " . number_format($row['product_price'], 0, ',', '.') . " VNĐ</span><br>
                                        <small>Mô tả: {$row['product_description']}</small>
                                    </div>
                                    <div>
                                        <a href='?action=update&id={$row['product_id']}&change=-1'><button>-</button></a>
                                        <span> $quantity </span>
                                        <a href='?action=update&id={$row['product_id']}&change=1'><button>+</button></a>
                                        <a href='?action=remove&id={$row['product_id']}'><button>Xóa</button></a>
                                    </div>
                                  </div>";
                        }
                    }
                }
            } else {
                echo "<p class='empty-cart'>Giỏ hàng trống!</p>";
            }
            ?>
        </div>
        <div class="cart-total">Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</div>
        <a href="#" class="checkout-button" onclick="alert('Cảm ơn bạn đã mua hàng! Đơn hàng sẽ được xử lý sớm.')">Thanh toán</a>
    </div>
</body>
</html>
<?php
$connect->close();
?>