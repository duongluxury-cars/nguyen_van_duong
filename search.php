<?php
session_start();

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "logins";

$connect = new mysqli($servername, $username, $password, $database);
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

// Xử lý thêm vào giỏ hàng
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?query=" . urlencode($_GET['query']));
    exit;
}

// Lấy từ khóa tìm kiếm
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$products = [];

// Danh sách sản phẩm tĩnh
$static_products = [
    [
        'product_id' => '1001',
        'product_name' => 'Gấu bông Capybara ôm vịt vàng',
        'product_price' => 239000,
        'product_img' => 'gau-bong-capybara-om-vit-vang-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara đang ôm chú vịt vàng dễ thương'
    ],
    [
        'product_id' => '1002',
        'product_name' => 'Gấu bông Capybara khoai tây chiên',
        'product_price' => 239000,
        'product_img' => 'gaubong-capybara-khoai-tay-chien-o-400x400.jpg',
        'product_description' => 'Gấu bông Capybara với khoai tây chiên ngon lành'
    ],
    [
        'product_id' => '1003',
        'product_name' => 'Gấu bông Capybara bánh mì mini',
        'product_price' => 299999,
        'product_img' => 'gau-bong-banh-mi-capybara-mini-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara bánh mì mini siêu dễ thương'
    ],
    [
        'product_id' => '1004',
        'product_name' => 'Gấu bông Capybara hồng rút mũi',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-hong-rut-mui-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara màu hồng với chức năng rút mũi'
    ],
    [
        'product_id' => '1005',
        'product_name' => 'Gấu bông Capybara mặc đầm đi biển',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-mac-dam-di-bien-4-400x400.jpg',
        'product_description' => 'Gấu bông Capybara trong bộ đầm đi biển xinh xắn'
    ],
    [
        'product_id' => '1006',
        'product_name' => 'Gấu bông Capybara đeo phao',
        'product_price' => 299999,
        'product_img' => 'gau-bong-capybara-deo-phao-1-400x400.jpg',
        'product_description' => 'Gấu bông Capybara đeo phao bơi an toàn'
    ],
    [
        'product_id' => '1007',
        'product_name' => 'Gấu bông Capybara tennis hồng',
        'product_price' => 3239000,
        'product_img' => 'gaubong-capybara-tenis-mau-hong-co-men-2in1-2-400x400.jpg',
        'product_description' => 'Gấu bông Capybara tennis màu hồng cao cấp'
    ]
];

// Tìm kiếm sản phẩm
if (!empty($search_query)) {
    // Tìm trong danh sách tĩnh
    foreach ($static_products as $product) {
        if (stripos($product['product_name'], $search_query) !== false || 
            (isset($product['product_description']) && stripos($product['product_description'], $search_query) !== false) ||
            stripos($product['product_id'], $search_query) !== false) {
            $products[$product['product_id']] = $product;
        }
    }

    // Tìm trong database
    $sql = "SELECT * FROM products 
            WHERE product_name LIKE ? 
            OR (product_description IS NOT NULL AND product_description LIKE ?) 
            OR product_id LIKE ?";
    $stmt = $connect->prepare($sql);
    if ($stmt === false) {
        die("Lỗi chuẩn bị truy vấn: " . $connect->error);
    }

    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die("Lỗi thực thi truy vấn: " . $stmt->error);
    }

    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
    }
    $stmt->close();
}

$connect->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Gấu Bông - Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body */
        body {
            background-color: #e9ecef;
            color: #343a40;
        }

        /* Header */
        header {
            padding: 15px 50px;
            background: linear-gradient(90deg, #007bff, #66b0ff, #cce5ff);
            border-bottom: 1px solid #66b0ff;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
        }

        /* Header Container */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo-section {
            flex-shrink: 0;
            margin-right: 30px;
        }

        .logo {
            height: 50px;
            display: block;
        }

        /* Search Section */
        .search-section {
            flex-grow: 1;
            max-width: 500px;
        }

        .search-section form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 5px;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
        }

        .search-section form:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .search-input {
            padding: 12px 15px;
            border: none;
            border-radius: 20px 0 0 20px;
            width: 100%;
            font-size: 16px;
            outline: none;
            background: transparent;
            color: #fff;
        }

        .search-input::placeholder {
            color: #cce5ff;
        }

        .search-input:focus {
            border-color: #0056b3;
        }

        .search-button {
            padding: 12px 20px;
            background: linear-gradient(90deg, #0056b3, #3399ff);
            color: white;
            border: none;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 86, 179, 0.5);
        }

        /* Contact Section */
        .contact-section {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Dàn đều các mục */
            gap: 20px; /* Khoảng cách giữa các mục */
            margin-left: 30px;
            min-width: 300px; /* Đảm bảo có đủ không gian để dàn ra */
        }

        .contact-section a {
            color: #f8f9fa;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
            padding: 8px 12px; /* Thêm padding để các mục trông rộng rãi hơn */
        }

        .contact-section a:hover {
            color: #cce5ff;
        }

        .cart-count {
            background-color: #0056b3;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 5px;
        }

        /* User Menu */
        .user-menu {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .user-icon {
            font-size: 24px;
            margin-right: 5px;
            vertical-align: middle;
            color: #f8f9fa;
        }

        .user-info {
            color: #f8f9fa;
            font-weight: bold;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 123, 255, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown-menu a {
            color: #007bff;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-weight: normal;
        }

        .dropdown-menu a:hover {
            background-color: #e9ecef;
        }

        .user-menu:hover .dropdown-menu {
            display: block;
        }

        /* Container */
        .container {
            margin: 20px auto;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
        }

        /* Product List */
        .product-list {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .product {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.1);
            text-align: center;
            width: 250px;
            height: 400px;
            transition: transform 0.5s ease-out, box-shadow 0.3s ease;
            border: 1px solid #cce5ff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 15px rgba(0, 123, 255, 0.3);
        }

        .product-image {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
        }

        .product img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .product__discount {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #0056b3;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .product h3 {
            font-size: 15px;
            margin: 10px 0;
            color: #007bff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .price {
            color: #0056b3;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .product a {
            text-decoration: none;
            color: inherit;
        }

        .product-buttons {
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }

        .product__button {
            flex: 1;
            padding: 8px;
            cursor: pointer;
            border: none;
            background: linear-gradient(90deg, #007bff, #66b0ff);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            font-size: 14px;
        }

        .product__button:hover {
            background: linear-gradient(90deg, #0056b3, #3399ff);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 10px;
            background: linear-gradient(90deg, #007bff, #66b0ff);
            margin-top: 20px;
            color: #fff;
        }

        .copy-right::before {
            content: "";
            display: inline-block;
            width: 100%;
            height: 0.5px;
            background-color: rgba(255, 255, 255, 0.4);
        }

        /* Ad Banner */
        .ad-banner {
            max-width: 1000px;
            margin: 20px auto;
            height: 600px;
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
            border-radius: 10px;
        }

        .ad-banner-inner {
            display: flex;
            height: 100%;
            width: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .ad-banner-inner img {
            width: 100%;
            height: 90%;
            object-fit: contain;
            flex-shrink: 0;
            margin: auto;
        }

        .ad-prev, .ad-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 123, 255, 0.7);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 20px;
            border-radius: 50%;
            transition: background 0.3s;
            z-index: 10;
        }

        .ad-prev:hover, .ad-next:hover {
            background: rgba(0, 86, 179, 0.9);
        }

        .ad-prev {
            left: 20px;
        }

        .ad-next {
            right: 20px;
        }

        .ad-dots {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: #ccc;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s;
        }

        .dot.active {
            background: #0056b3;
        }

        /* Search Results Header */
        .search-results-header {
            width: 100%;
            padding: 20px;
            text-align: right;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .search-results-count {
            color: #666;
            font-size: 16px;
            display: block;
            margin-bottom: 10px;
        }

        .btn-back {
            display: inline-block;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #0056b3;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
            border-radius: 10px;
            width: 100%;
        }

        .no-results-icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 20px;
        }

        .no-results-title {
            font-size: 24px;
            color: #343a40;
            margin-bottom: 10px;
        }

        .no-results-message {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <div class="logo-section">
                <img src="logo.png" alt="Logo" class="logo"> 
            </div>
            <div class="search-section">
                <form action="search.php" method="GET">
                    <input type="text" name="query" placeholder="Nhập sản phẩm cần tìm" class="search-input" 
                           value="<?php echo htmlspecialchars($search_query); ?>" required>
                    <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="contact-section">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-menu">
                        <span class="user-icon"><i class="fas fa-user"></i></span>
                        <span class="user-info"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="dropdown-menu">
                            <a href="profile.php">Thông tin tài khoản</a>
                            <a href="login.php">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php">Đăng Nhập</a>
                <?php endif; ?>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="cart-count"><?php echo array_sum($_SESSION['cart']); ?></span>
                    <?php endif; ?>
                </a>
                <a href="themsp.php">Thêm sp</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="search-results-header">
            <?php if (!empty($search_query)): ?>
                <span class="search-results-count"><?php echo count($products); ?> kết quả</span>
                <a href="index.php" class="btn-back">← Trang chủ</a>
            <?php else: ?>
                <a href="index.php" class="btn-back">← Trang chủ</a>
            <?php endif; ?>
        </div>

        <section class="product-list">
            <?php if (!empty($search_query) && !empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                            <div class="product-image">
                                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                    <p class="product__discount">-<?php echo htmlspecialchars($product['discount']); ?>%</p>
                                <?php endif; ?>
                                <img src="<?php echo (strpos($product['product_img'], 'uploads/') === 0) ? htmlspecialchars($product['product_img']) : htmlspecialchars($product['product_img']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                     onerror="this.src='default-image.jpg';">
                            </div>
                            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p class="price"><?php echo number_format($product['product_price'], 0, ',', '.'); ?>đ</p>
                        </a>
                        <div class="product-buttons">
                            <button class="product__button">Mua</button>
                            <a href="?add_to_cart=<?php echo htmlspecialchars($product['product_id']); ?>&query=<?php echo urlencode($search_query); ?>" 
                               class="product__button">Thêm vào giỏ</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!empty($search_query)): ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2 class="no-results-title">Không tìm thấy kết quả</h2>
                    <p class="no-results-message">Không có sản phẩm nào phù hợp với từ khóa "<?php echo htmlspecialchars($search_query); ?>"</p>
                    <a href="index.php" class="btn-back">← Quay lại trang chủ</a>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2 class="no-results-title">Tìm kiếm sản phẩm</h2>
                    <p class="no-results-message">Nhập từ khóa vào ô tìm kiếm để bắt đầu</p>
                    <a href="index.php" class="btn-back">← Quay lại trang chủ</a>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <footer class="copy-right">
        <p>© 2025 Shop Gấu Bông | Liên hệ: 0123-456-789</p>
    </footer>
</body>
</html>