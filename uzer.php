<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'user') {
        header('Location: loginhtml.php');
        exit();
    }
    $servername = "localhost";
    $username = "root"; 
    $password = "";      
    $database = "user_registrations";
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) !== TRUE) {
        die("Դատաբազան չի ստեղծվել էրոր: " . $conn->error);
    }
    $conn->select_db($database);
    $tableSql = "CREATE TABLE IF NOT EXISTS lectures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        surname VARCHAR(50) NOT NULL,
        ministry VARCHAR(50) NOT NULL,
        position VARCHAR(50) NOT NULL,
        salary int (255) NOT NULL
    )";
    if ($conn->query($tableSql) !== TRUE) {
        die("Դատաբազան չի ստեղծվել էրոր: " . $conn->error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $ministry =  $_POST['ministry'];  
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $insertSql = "INSERT INTO lectures (name, surname, ministry, position, salary)
                    VALUES ('$name', '$surname', '$ministry', '$position', '$salary' )";
        if ($conn->query($insertSql) !== TRUE) {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    }
    $selectSql = "SELECT * FROM lectures ORDER BY id DESC";
    $result = $conn->query($selectSql);
    $conn->close();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Table</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style2.css">
    </head>
    <body>
    <a href="profile.php">
        <button class="about-me-btn"> Իմ Մասին <i class="fa-solid fa-arrow-right"></i></button>
    </a>
        <h2>ԱԶԳԱՅԻՆ ԺՈՂՈՎԻ ԱՇԽԱՏԱԿԱԶՄ</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Անուն</th>
                <th>Ազգանուն</th>
                <th>Նախարարություն</th>
                <th>Պաշտոն</th>
                <th>Աշխատավարձ</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['surname']; ?></td>
                        <td><?php echo $row['ministry']; ?></td>
                        <td><?php echo $row['position']; ?></td>
                        <td><?php echo $row['salary']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
            <?php endif; ?>
        </table>
    </body>
    </html>
