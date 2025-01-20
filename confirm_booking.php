<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'hotelroom');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to make a booking.";
    exit;
}

$user_id = $_SESSION['user_id']; // ใช้ UserID ที่ล็อกอิน

// ตรวจสอบข้อมูลที่รับมาจากฟอร์ม
if (isset($_POST['room_id'], $_POST['checkin'], $_POST['checkout'], $_POST['num_guests'])) {
    $room_id = $_POST['room_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $num_guests = $_POST['num_guests'];

    // เช็คห้องที่เลือกอยู่หรือไม่
    $sql = "SELECT * FROM room WHERE RoomID = $room_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // ตรวจสอบการจองห้อง
        $sql = "INSERT INTO book (UserID, RoomID, BookDate, CheckIn, CheckOut, NumGuests)
                VALUES ($user_id, $room_id, CURDATE(), '$checkin', '$checkout', $num_guests)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Booking confirmed! Your room is reserved.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Room not found.";
    }
} else {
    echo "Incomplete booking details.";
}

$conn->close();
?>

