<?php
$conn = new mysqli('localhost', 'root', '', 'hotelroom'); // เชื่อมต่อกับฐานข้อมูล

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (Name, Address, Tel, Password) VALUES ('$name', '$address', '$tel', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header('Location: login.html'); // หลังจากสมัครสมาชิกเสร็จ
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
