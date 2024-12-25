<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'hotelroom');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการล็อกอิน
if (isset($_SESSION['user_id'])) {
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT Name FROM user WHERE UserID = $user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['Name'];
    }
} else {
    $username = 'Guest';
}

// ดึงข้อมูลห้องทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM room";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="container">
            <h1>Hotel Room Booking</h1>
            <div class="user-info">
                <p>Welcome, <?php echo $username; ?>!</p>
            </div>
        </div>
    </header>

    <!-- Rooms Section -->
    <div class="container">
        <h2>Our Available Rooms</h2>

        <div class="card-container">
            <?php
            if ($result->num_rows > 0) {
                // แสดงห้องทั้งหมด
                while ($room = $result->fetch_assoc()) {
                    echo '
                    <div class="card">
                        <h3>' . $room['Class'] . '</h3>
                        <p><strong>Price:</strong> ' . $room['Price'] . '</p>
                        <p><strong>Status:</strong> ' . $room['Status'] . '</p>
                        <a href="book.php?room_id=' . $room['RoomID'] . '" class="btn-book">Book Now</a>
                    </div>';
                }
            } else {
                echo "<p>No rooms available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Hotel Room Booking System. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
