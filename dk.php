<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logins";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Sử dụng prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (id, username, password, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $username, $password, $email);

    if ($stmt->execute()) {
        $message = "<p id='message' class='success-message'>Đăng ký thành công!</p>";
    } else {
        $message = "<p id='message' class='error-message'>Đăng ký thất bại: " . $conn->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Shop Gấu Bông</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #e9ecef; /* Nền giống trang chủ */
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #f8f9fa; /* Nền giống sản phẩm trong trang chủ */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.1); /* Hiệu ứng bóng giống trang chủ */
            border: 1px solid #cce5ff; /* Viền xanh nhạt */
            text-align: center;
            width: 350px; /* Đồng bộ với trang đăng nhập */
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff; /* Xanh chủ đạo */
            font-size: 28px; /* Đồng bộ với các tiêu đề khác */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Khoảng cách giữa các trường */
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cce5ff; /* Viền xanh nhạt */
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff; /* Viền xanh đậm khi focus */
        }

        input::placeholder {
            color: #999;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #007bff, #66b0ff); /* Gradient xanh giống trang chủ */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #0056b3, #3399ff);
            transform: translateY(-2px); /* Hiệu ứng nâng lên giống trang chủ */
        }

        .error-message {
            margin-top: 10px;
            color: #ff6b6b; /* Đỏ nhạt để nổi bật */
            font-weight: bold;
            font-size: 14px;
        }

        .success-message {
            margin-top: 10px;
            color: #28a745; /* Xanh lá cho thông báo thành công */
            font-weight: bold;
            font-size: 14px;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #007bff; /* Xanh chủ đạo */
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #0056b3; /* Xanh đậm khi hover */
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(90deg, #6c757d, #8e959b); /* Gradient xám giống các trang khác */
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
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
        <h2>Đăng Ký</h2>
        <?php echo $message; ?>
        <form method="post">
            <input type="text" name="id" placeholder="ID" required>
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng Ký</button>
        </form>
        <div class="login-link">
            Đã có tài khoản? <a href="./login.php">Đăng nhập</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>