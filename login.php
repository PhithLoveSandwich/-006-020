<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'hotelroom'); // เชื่อมต่อกับฐานข้อมูล

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE Name = '$name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['user_id'] = $row['UserID'];
            header('Location: room.php'); // หลังจากล็อคอินสำเร็จ
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No such user found.";
    }
}

$conn->close();
?>
