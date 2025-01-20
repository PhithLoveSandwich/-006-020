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

// ตรวจสอบว่า room_id ถูกส่งมาใน URL หรือไม่
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    // ดึงข้อมูลห้องที่เลือกจากฐานข้อมูล
    $sql = "SELECT * FROM room WHERE RoomID = $room_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "Room not found.";
        exit;
    }
} else {
    echo "No room selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Room</title>
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

    <!-- Booking Section -->
    <div class="container">
        <h2>Booking Details for <?php echo $room['Class']; ?> Room</h2>

        <div class="card">
            <img src="images/<?php echo ($room['Class'] == 'Standard' ? 'nor.jpg' : 'pre.jpg'); ?>" alt="<?php echo $room['Class']; ?>" class="room-image">
            <h3><?php echo $room['Class']; ?></h3>
            <p><strong>Price:</strong> <?php echo $room['Price']; ?></p>
            <p><strong>Status:</strong> <?php echo $room['Status']; ?></p>

            <!-- Form for booking -->
            <form action="confirm_booking.php" method="post">
                <input type="hidden" name="room_id" value="<?php echo $room['RoomID']; ?>">
                <label for="checkin">Check-in Date:</label>
                <input type="date" name="checkin" required><br><br>

                <label for="checkout">Check-out Date:</label>
                <input type="date" name="checkout" required><br><br>

                <label for="num_guests">Number of Guests:</label>
                <input type="number" name="num_guests" min="1" required><br><br>

                <button type="submit" class="btn-book">Confirm Booking</button>
            </form>
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
