<?php
// Phần PHP xử lý giữ nguyên như code của bạn
$servername = "localhost";
$username = "root";
$password = "";
$database = "logins";

$connect = new mysqli($servername, $username, $password, $database);

if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_product'])) {
    if (!empty($_POST['product_id']) && !empty($_POST['product_name']) && !empty($_POST['product_price']) 
        && !empty($_POST['quantity']) && !empty($_POST['product_description']) && !empty($_FILES['product_img']['name'])) {
        // Logic xử lý thêm sản phẩm giữ nguyên
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $quantity = $_POST['quantity'];
        $product_description = $_POST['product_description'];

        $check_sql = "SELECT * FROM products WHERE product_id = ?";
        $check_stmt = $connect->prepare($check_sql);
        $check_stmt->bind_param("s", $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "<p class='error'>❌ Mã sản phẩm đã tồn tại! Vui lòng chọn mã khác.</p>";
        } else {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $product_img = basename($_FILES['product_img']['name']);
            $target_file = $target_dir . $product_img;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["product_img"]["tmp_name"]);
            if($check === false) {
                $message = "<p class='error'>❌ File không phải là ảnh.</p>";
            } elseif ($_FILES["product_img"]["size"] > 500000) {
                $message = "<p class='error'>❌ File ảnh quá lớn.</p>";
            } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $message = "<p class='error'>❌ Chỉ cho phép file JPG, JPEG, PNG & GIF.</p>";
            } else {
                if (move_uploaded_file($_FILES['product_img']['tmp_name'], $target_file)) {
                    $sql = "INSERT INTO products (product_description, product_id, product_img, product_name, product_price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $connect->prepare($sql);
                    $stmt->bind_param("ssssdi", $product_description, $product_id, $product_img, $product_name, $product_price, $quantity);

                    if ($stmt->execute()) {
                        $message = "<p class='success'>✅ Thêm sản phẩm thành công!</p>";
                    } else {
                        $message = "<p class='error'>❌ Lỗi: " . htmlspecialchars($stmt->error) . "</p>";
                    }
                    $stmt->close();
                } else {
                    $message = "<p class='error'>❌ Lỗi: Không thể upload ảnh sản phẩm!</p>";
                }
            }
        }
        $check_stmt->close();
    } else {
        $message = "<p class='error'>❌ Vui lòng nhập đầy đủ thông tin sản phẩm!</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $delete_product_id = $_POST['delete_product_id'];
    $select_sql = "SELECT product_img FROM products WHERE product_id = ?";
    $select_stmt = $connect->prepare($select_sql);
    $select_stmt->bind_param("s", $delete_product_id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = "uploads/" . $row['product_img'];
        $delete_sql = "DELETE FROM products WHERE product_id = ?";
        $delete_stmt = $connect->prepare($delete_sql);
        $delete_stmt->bind_param("s", $delete_product_id);
        
        if ($delete_stmt->execute()) {
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $message = "<p class='success'>✅ Xóa sản phẩm thành công!</p>";
        } else {
            $message = "<p class='error'>❌ Lỗi khi xóa sản phẩm: " . htmlspecialchars($delete_stmt->error) . "</p>";
        }
        $delete_stmt->close();
    } else {
        $message = "<p class='error'>❌ Không tìm thấy sản phẩm với mã này!</p>";
    }
    $select_stmt->close();
}

$connect->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 30px;
            font-size: 28px;
        }

        h3 {
            color: #444;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 5px;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 5px rgba(26, 115, 232, 0.3);
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit {
            background: #1a73e8;
            color: white;
            flex: 1;
        }

        .btn-submit:hover {
            background: #1557b0;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.4);
        }

        .btn-back {
            background: #6c757d;
            color: white;
            flex: 1;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }

        .delete-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .btn-delete {
            width: 100%;
            background: #dc3545;
            color: white;
            padding: 14px;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý sản phẩm</h2>
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

        <!-- Form thêm sản phẩm -->
        <h3>Thêm sản phẩm mới</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Mã sản phẩm:</label>
                <input type="text" name="product_id" required>
            </div>
            <div class="form-group">
                <label>Tên sản phẩm:</label>
                <input type="text" name="product_name" required>
            </div>
            <div class="form-group">
                <label>Giá sản phẩm:</label>
                <input type="number" name="product_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Số lượng sản phẩm:</label>
                <input type="number" name="quantity" required>
            </div>
            <div class="form-group">
                <label>Ảnh sản phẩm:</label>
                <input type="file" name="product_img" required>
            </div>
            <div class="form-group">
                <label>Mô tả sản phẩm:</label>
                <input type="text" name="product_description" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-submit">Thêm sản phẩm</button>
                <button type="button" class="btn-back" onclick="history.back()">Quay lại</button>
            </div>
        </form>

        <!-- Form xóa sản phẩm -->
        <div class="delete-section">
            <h3>Xóa sản phẩm</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Mã sản phẩm cần xóa:</label>
                    <input type="text" name="delete_product_id" required>
                </div>
                <input type="hidden" name="delete_product" value="1">
                <button type="submit" class="btn-delete">Xóa sản phẩm</button>
            </form>
        </div>
    </div>
</body>
</html>