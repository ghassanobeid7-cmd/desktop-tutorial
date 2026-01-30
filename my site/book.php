<?php
include 'db.php';

// Reconnect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Validate inputs
    if (empty($fullname) || empty($phone) || empty($service) || empty($date) || empty($time)) {
        $message = "يرجى ملء جميع الحقول.";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO appointments (fullname, phone, service, date, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $phone, $service, $date, $time);

        if ($stmt->execute()) {
            $message = "تم حجز الموعد بنجاح.";
        } else {
            if ($conn->errno == 1062) { // Duplicate entry error
                $message = "هذا الموعد محجوز مسبقًا.";
            } else {
                $message = "خطأ في الحجز: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز موعد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>حجز موعد</h1>
    </header>
    <main>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>معلومات الحجز</h2>
            <?php if ($message): ?>
                <p style="color: red; text-align: center;"><?php echo $message; ?></p>
            <?php endif; ?>
            <label for="fullname">الاسم الكامل:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="phone">رقم الهاتف:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="service">الخدمة:</label>
            <select id="service" name="service" required>
                <option value="">اختر الخدمة</option>
                <option value="طبيب">طبيب</option>
                <option value="حلاق">حلاق</option>
                <option value="مدرب">مدرب</option>
            </select>

            <label for="date">التاريخ:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">الوقت:</label>
            <input type="time" id="time" name="time" required>

            <button type="submit">احجز الآن</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.html" class="btn">العودة إلى الصفحة الرئيسية</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 نظام حجز المواعيد. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
