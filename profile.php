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

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user = null;
$message = "";

// Lấy thông tin người dùng từ database
$stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $message = "<p class='error-message'>Không tìm thấy thông tin tài khoản!</p>";
}
$stmt->close();

// Xử lý cập nhật thông tin (nếu có)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $stmt = $connect->prepare("UPDATE users SET email = ? WHERE username = ?");
    $stmt->bind_param("ss", $email, $_SESSION['username']);
    if ($stmt->execute()) {
        $message = "<p class='success-message'>Cập nhật thông tin thành công!</p>";
        $user['email'] = $email; // Cập nhật dữ liệu hiển thị
    } else {
        $message = "<p class='error-message'>Cập nhật thất bại: " . $connect->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - Shop Gấu Bông</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #e9ecef;
            color: #343a40;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 1000px;
            margin: 50px auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.1);
            border: 1px solid #cce5ff;
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .profile-info {
            padding: 20px;
        }

        .profile-info p {
            font-size: 16px;
            color: #666;
            margin: 10px 0;
        }

        .profile-info strong {
            color: #0056b3;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
            margin: 20px auto;
        }

        input {
            padding: 12px;
            border: 1px solid #cce5ff;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
        }

        .update-button {
            padding: 12px;
            background: linear-gradient(90deg, #007bff, #66b0ff);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .update-button:hover {
            background: linear-gradient(90deg, #0056b3, #3399ff);
            transform: translateY(-2px);
        }

        .error-message {
            text-align: center;
            color: #ff6b6b;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .success-message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(90deg, #6c757d, #8e959b);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
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
    <div class="profile-container">
        <h1>Thông tin tài khoản</h1>
        <?php echo $message; ?>
        <?php if ($user): ?>
            <div class="profile-info">
                <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
            </div>
            <form method="post">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email mới" required>
                <button type="submit" class="update-button">Cập nhật</button>
            </form>
        <?php endif; ?>
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
    </div>
</body>
</html>

<?php
$connect->close();
?>