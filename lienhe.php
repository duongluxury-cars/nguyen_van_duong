<?php
session_start();

// Kết nối database (nếu cần xử lý form trong tương lai)
$servername = "localhost";
$username = "root";
$password = "";
$database = "logins";

$connect = new mysqli($servername, $username, $password, $database);
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

// Xử lý form liên hệ (chỉ là ví dụ, chưa lưu vào database)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);
    // Ở đây bạn có thể thêm logic để lưu vào database hoặc gửi email
    $success_message = "Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - Shop Gấu Bông</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #e9ecef; /* Nền giống các trang trước */
            color: #343a40;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px; /* Đồng bộ với trang giỏ hàng */
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa; /* Nền giống sản phẩm */
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.1);
            border: 1px solid #cce5ff;
        }

        h1 {
            text-align: center;
            color: #007bff; /* Xanh chủ đạo */
            font-size: 28px;
            margin-bottom: 30px;
        }

        .contact-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        .contact-info, .contact-form {
            flex: 1;
            min-width: 300px; /* Đảm bảo hiển thị tốt trên mobile */
        }

        .contact-info h2 {
            color: #0056b3; /* Xanh đậm */
            font-size: 22px;
            margin-bottom: 20px;
        }

        .contact-info p {
            color: #666;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .contact-info i {
            color: #007bff; /* Icon xanh */
            margin-right: 10px;
        }

        .contact-form h2 {
            color: #0056b3;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .contact-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-form input, .contact-form textarea {
            padding: 12px;
            border: 1px solid #cce5ff; /* Viền xanh nhạt */
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .contact-form input:focus, .contact-form textarea:focus {
            border-color: #007bff; /* Viền xanh đậm khi focus */
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .contact-form button {
            padding: 12px;
            background: linear-gradient(90deg, #007bff, #66b0ff); /* Gradient xanh */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .contact-form button:hover {
            background: linear-gradient(90deg, #0056b3, #3399ff);
            transform: translateY(-2px);
        }

        .success-message {
            text-align: center;
            color: #28a745; /* Xanh lá cho thông báo thành công */
            font-size: 18px;
            margin-top: 20px;
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

        @media (max-width: 768px) {
            .contact-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
        <h1>Liên hệ với chúng tôi</h1>
        <div class="contact-wrapper">
            <div class="contact-info">
                <h2>Thông tin liên hệ</h2>
                <p><i class="fas fa-map-marker-alt"></i> Địa chỉ: 123 Đường Gấu Bông, Quận Capybara, TP. HCM</p>
                <p><i class="fas fa-phone"></i> Số điện thoại: 0123-456-789</p>
                <p><i class="fas fa-envelope"></i> Email: shopgaubong@example.com</p>
                <p><i class="fas fa-clock"></i> Giờ làm việc: 9:00 - 17:00 (Thứ 2 - Thứ 7)</p>
            </div>
            <div class="contact-form">
                <h2>Gửi tin nhắn cho chúng tôi</h2>
                <?php if (isset($success_message)): ?>
                    <p class="success-message"><?php echo $success_message; ?></p>
                <?php else: ?>
                    <form method="POST" action="">
                        <input type="text" name="name" placeholder="Họ và tên" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="tel" name="phone" placeholder="Số điện thoại" required>
                        <textarea name="message" placeholder="Tin nhắn của bạn" required></textarea>
                        <button type="submit">Gửi liên hệ</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$connect->close();
?>