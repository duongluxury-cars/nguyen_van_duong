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

// Khởi tạo giỏ hàng trong session nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm vào giỏ hàng
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: index.php");
    exit();
}

// Lấy danh sách sản phẩm từ database
$sql = "SELECT * FROM products";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Gấu Bông - Trang Chủ</title>
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
            flex-direction: column; /* Chuyển sang dọc để thêm phần dưới */
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .top-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
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

        /* Contact Section (phần trên) */
        .contact-section-top {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: 30px;
        }

        /* Contact Section (phần dưới - Thêm sp, Liên hệ, Giới thiệu) */
        .contact-section-bottom {
            display: flex;
            justify-content: center; /* Căn giữa */
            gap: 20px;
            margin-top: 10px; /* Khoảng cách với phần trên */
        }

        .contact-section-top a,
        .contact-section-bottom a {
            color: #f8f9fa;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
            padding: 8px 12px;
        }

        .contact-section-top a:hover,
        .contact-section-bottom a:hover {
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
            display: flex;
            margin: 20px auto;
            max-width: 1200px;
        }

        /* Product List */
        .product-list {
            flex-grow: 1;
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
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <div class="top-section">
                <div class="logo-section">
                    <img src="logo.png" alt="Logo" class="logo"> 
                </div>
                <div class="search-section">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Nhập sản phẩm cần tìm" class="search-input" required>
                        <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="contact-section-top">
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
                </div>
            </div>
            <div class="contact-section-bottom">
                <a href="themsp.php">Thêm sp</a>
                <a href="lienhe.php">Liên hệ</a>
                <a href="about.php">Giới thiệu</a>
            </div>
        </div>
    </header>

    <!-- Dải quảng cáo hình ảnh -->
    <div class="ad-banner">
        <div class="ad-banner-inner">
            <img src="gau-bong-banh-mi-capybara-mini-1-400x400.jpg" alt="Quảng cáo 1">
            <img src="gau-bong-capybara-rut-mui-3-400x400.jpg" alt="Quảng cáo 2">
            <img src="gaubong-capybara-tenis-mau-hong-co-men-2in1-2-400x400.jpg" alt="Quảng cáo 3">
        </div>
        <button class="ad-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="ad-next"><i class="fas fa-chevron-right"></i></button>
        <div class="ad-dots">
            <span class="dot" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
        </div>
    </div>

    <div class="container">
        <section class="product-list">
            <!-- Sản phẩm tĩnh -->
            <div class="product">
                <a href="product_detail.php?id=1001">
                    <div class="product-image">
                        <p class="product__discount">-15%</p>
                        <img src="gau-bong-capybara-om-vit-vang-1-400x400.jpg" alt="Gấu bông Capybara ôm vịt vàng">
                    </div>
                    <h3>Gấu bông Capybara ôm vịt vàng</h3>
                    <p class="price">239,000đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1001" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1002">
                    <div class="product-image">
                        <p class="product__discount">-15%</p>
                        <img src="gaubong-capybara-khoai-tay-chien-o-400x400.jpg" alt="Gấu bông Capybara khoai tây chiên">
                    </div>
                    <h3>Gấu bông Capybara khoai tây chiên</h3>
                    <p class="price">239,000đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1002" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1003">
                    <div class="product-image">
                        <img src="gau-bong-banh-mi-capybara-mini-1-400x400.jpg" alt="Gấu bông Capybara bánh mì mini">
                    </div>
                    <h3>Gấu bông Capybara bánh mì mini</h3>
                    <p class="price">299,999đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1003" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1004">
                    <div class="product-image">
                        <img src="gau-bong-capybara-hong-rut-mui-1-400x400.jpg" alt="Gấu bông Capybara hồng rút mũi">
                    </div>
                    <h3>Gấu bông Capybara hồng rút mũi</h3>
                    <p class="price">299,999đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1004" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1005">
                    <div class="product-image">
                        <img src="gau-bong-capybara-mac-dam-di-bien-4-400x400.jpg" alt="Gấu bông Capybara mặc đầm đi biển">
                    </div>
                    <h3>Gấu bông Capybara mặc đầm đi biển</h3>
                    <p class="price">299,999đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1005" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1006">
                    <div class="product-image">
                        <img src="gau-bong-capybara-deo-phao-1-400x400.jpg" alt="Gấu bông Capybara đeo phao">
                    </div>
                    <h3>Gấu bông Capybara đeo phao</h3>
                    <p class="price">299,999đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1006" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>
            <div class="product">
                <a href="product_detail.php?id=1007">
                    <div class="product-image">
                        <img src="gaubong-capybara-tenis-mau-hong-co-men-2in1-2-400x400.jpg" alt="Gấu bông Capybara tennis hồng">
                    </div>
                    <h3>Gấu bông Capybara tennis hồng</h3>
                    <p class="price">3,239,000đ</p>
                </a>
                <div class="product-buttons">
                    <button class="product__button">Mua</button>
                    <a href="?add_to_cart=1007" class="product__button">Thêm vào giỏ</a>
                </div>
            </div>

            <!-- Sản phẩm động từ database -->
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<a href='product_detail.php?id=" . $row['product_id'] . "'>";
                    echo "<div class='product-image'>";
                    echo "<img src='uploads/" . htmlspecialchars($row['product_img']) . "' alt='" . htmlspecialchars($row['product_name']) . "' onerror=\"this.src='default-image.jpg';\">";
                    echo "</div>";
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p class='price'>" . number_format($row['product_price'], 0, ',', '.') . "đ</p>";
                    echo "</a>";
                    echo "<div class='product-buttons'>";
                    echo "<button class='product__button'>Mua</button>";
                    echo "<a href='?add_to_cart=" . $row['product_id'] . "' class='product__button'>Thêm vào giỏ</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Chưa có sản phẩm nào trong cơ sở dữ liệu!</p>";
            }
            ?>
        </section>
    </div>

    <footer class="copy-right">
        <p>© 2025 Shop Gấu Bông | Liên hệ: 0123-456-789</p>
    </footer>

    <script>
        const banner = document.querySelector('.ad-banner-inner');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.querySelector('.ad-prev');
        const nextBtn = document.querySelector('.ad-next');
        let currentSlide = 0;
        const totalSlides = 3;

        function goToSlide(n) {
            currentSlide = (n + totalSlides) % totalSlides;
            banner.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
        }

        function updateDots() {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        prevBtn.addEventListener('click', () => goToSlide(currentSlide - 1));
        nextBtn.addEventListener('click', () => goToSlide(currentSlide + 1));
        dots.forEach(dot => {
            dot.addEventListener('click', () => goToSlide(parseInt(dot.dataset.slide)));
        });

        let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 5000);
        document.querySelector('.ad-banner').addEventListener('mouseenter', () => clearInterval(autoSlide));
        document.querySelector('.ad-banner').addEventListener('mouseleave', () => autoSlide = setInterval(() => goToSlide(currentSlide + 1), 5000));

        // Khởi tạo slide đầu tiên
        goToSlide(0);
    </script>
</body>
</html>

<?php
$connect->close();
?>