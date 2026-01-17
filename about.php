<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - Shop Gấu Bông</title>
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
            line-height: 1.6;
        }

        .about-container {
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

        h2 {
            color: #0056b3;
            font-size: 22px;
            margin: 20px 0 10px;
        }

        p {
            color: #666;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .about-content {
            padding: 20px;
        }

        .about-content i {
            color: #007bff;
            margin-right: 10px;
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
            margin: 20px auto;
            display: block;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background: linear-gradient(90deg, #5a6268, #6c757d);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .about-container {
                margin: 20px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="about-container">
        <h1>Giới thiệu về Shop Gấu Bông</h1>
        <div class="about-content">
            <h2>Chúng tôi là ai?</h2>
            <p>Shop Gấu Bông là cửa hàng trực tuyến chuyên cung cấp các sản phẩm gấu bông Capybara độc đáo và dễ thương, được thiết kế để mang lại niềm vui và sự thoải mái cho khách hàng. Chúng tôi tự hào mang đến những sản phẩm chất lượng cao với mức giá hợp lý.</p>

            <h2>Sứ mệnh của chúng tôi</h2>
            <p>Với sứ mệnh "Lan tỏa niềm vui, kết nối yêu thương", chúng tôi cam kết mang đến trải nghiệm mua sắm tuyệt vời, dịch vụ khách hàng tận tâm và những sản phẩm đáng tin cậy để làm hài lòng mọi khách hàng.</p>

            <h2>Tại sao chọn chúng tôi?</h2>
            <p>
                <i class="fas fa-check"></i> Sản phẩm đa dạng, thiết kế sáng tạo.<br>
                <i class="fas fa-check"></i> Chất lượng đảm bảo, an toàn cho mọi lứa tuổi.<br>
                <i class="fas fa-check"></i> Dịch vụ giao hàng nhanh chóng, hỗ trợ tận tình.<br>
                <i class="fas fa-check"></i> Giá cả cạnh tranh với nhiều ưu đãi hấp dẫn.
            </p>

            <h2>Liên hệ với chúng tôi</h2>
            <p>Nếu bạn có bất kỳ câu hỏi nào hoặc cần hỗ trợ, đừng ngần ngại liên hệ qua email: <strong>shopgaubong@example.com</strong> hoặc số điện thoại: <strong>0123-456-789</strong>. Chúng tôi luôn sẵn sàng phục vụ bạn!</p>
        </div>
        <a href="index.php" class="back-button">Quay lại trang chủ</a>
    </div>
</body>
</html>