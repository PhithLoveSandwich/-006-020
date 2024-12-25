<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'hotelroom');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อคอินหรือไม่
if (isset($_SESSION['user_id'])) {

    // ตรวจสอบว่าได้ส่ง RoomID มาหรือไม่
    if (isset($_GET['room_id']) && !empty($_GET['room_id'])) {
        $room_id = $_GET['room_id']; // รับ RoomID ที่ส่งมาจากหน้า room.php

        // ดึงข้อมูลห้องที่ผู้ใช้เลือก
        $sql = "SELECT * FROM room WHERE RoomID = $room_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $room = $result->fetch_assoc();
        } else {
            echo "<p>Room not found.</p>";
        }

    } else {
        echo "<p>No room selected. Please go back and select a room.</p>";
    }

    // เมื่อส่งฟอร์มแล้ว
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับข้อมูลจากฟอร์ม
        $checkin_date = $_POST['checkin_date']; // วันที่เช็คอิน
        $checkout_date = $_POST['checkout_date']; // วันที่เช็คเอาท์
        $user_id = $_SESSION['user_id']; // UserID ที่ล็อคอิน

        // บันทึกการจองห้อง
        $sql = "INSERT INTO book (UserID, RoomID, BookDate, CheckOut) 
                VALUES ($user_id, $room_id, '$checkin_date', '$checkout_date')";

        if ($conn->query($sql) === TRUE) {
            // หลังจากจองห้องสำเร็จ
            echo "<p>Room booked successfully!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }

} else {
    echo "<p>Please <a href='login.html'>login</a> to book a room.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-image: url('https://www.w3schools.com/w3images/hotel.jpg');
    background-size: cover;
    background-position: center;
    margin: 0;
    padding: 0;
}
        .card {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin: 10px;
            max-width: 400px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        .card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .form-container {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container input[type="date"],
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Book Your Room</h1>

    <?php if (isset($room)) { ?>
        <div class="card">
            <h3>Room: <?php echo $room['Class']; ?></h3>
            <p>Price: $<?php echo $room['Price']; ?></p>
            <p>Status: <?php echo $room['Status']; ?></p>
        </div>

        <div class="form-container">
            <form action="book.php?room_id=<?php echo $room['RoomID']; ?>" method="POST">
                <label for="checkin">Check-in Date:</label>
                <input type="date" id="checkin" name="checkin_date" required><br>

                <label for="checkout">Check-out Date:</label>
                <input type="date" id="checkout" name="checkout_date" required><br>

                <input type="submit" value="Book Room">
            </form>
        </div>
    <?php } ?>

</body>
</html>
