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
// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$product_id) {
    die("Không tìm thấy sản phẩm!");
}

// Kiểm tra sản phẩm tĩnh
if (isset($static_products[$product_id])) {
    $product = $static_products[$product_id];
    $quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
} else {
    // Kiểm tra sản phẩm động từ database
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
    } else {
        die("Sản phẩm không tồn tại!");
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Shop Gấu Bông</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .product-detail-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 107, 149, 0.1);
            display: flex;
            gap: 20px;
            position: relative; /* Để định vị nút quay lại */
        }
        .product-detail-image img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ffe6eb;
        }
        .product-detail-info {
            flex: 1;
        }
        .product-detail-info h1 {
            font-size: 24px;
            color: #ff6b95;
            margin-bottom: 10px;
        }
        .product-detail-info p {
            margin: 10px 0;
            color: #4a4a4a;
        }
        .product-detail-info .price {
            color: #ff4d6d;
            font-size: 22px;
            font-weight: bold;
        }
        .product-detail-info .quantity {
            color: #666;
        }
        .product-detail-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .back-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            padding: 8px 15px; /* Giảm kích thước nút */
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px; /* Giảm cỡ chữ */
        }
        .back-button:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="product-detail-container">
        <div class="product-detail-image">
            <img src="<?php echo isset($static_products[$product_id]) ? $product['product_img'] : './DemoWebsite/' . $product['product_img']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
        </div>
        <div class="product-detail-info">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p><strong>Mã sản phẩm:</strong> <?php echo $product['product_id']; ?></p>
            <p><strong>Tên sản phẩm:</strong> <?php echo htmlspecialchars($product['product_name']); ?></p>
            <p class="price"><strong>Giá:</strong> <?php echo number_format($product['product_price'], 0, ',', '.'); ?>đ</p>
            <p class="quantity"><strong>Số lượng trong giỏ:</strong> <?php echo $quantity; ?></p>
            <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['product_description']); ?></p>
            <div class="product-detail-buttons">
                <button class="product__button">Mua</button>
                <a href="index.php?add_to_cart=<?php echo $product_id; ?>" class="product__button">Thêm vào giỏ</a>
            </div>
        </div>
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
    </div>
</body>
</html>

<?php
$connect->close();
?>