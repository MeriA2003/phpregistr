<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
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
        die("Դատաբազայի ստեղծման սխալ: " . $conn->error);
    }
    $conn->select_db($database);
    $tableSql = "CREATE TABLE IF NOT EXISTS lectures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        surname VARCHAR(50) NOT NULL,
        ministry VARCHAR(50) NOT NULL,
        position VARCHAR(50) NOT NULL,
        salary VARCHAR(255) NOT NULL
    )";
    if ($conn->query($tableSql) !== TRUE) {
        die("Աղյուսակի ստեղծման սխալ: " . $conn->error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $ministry =  $_POST["ministry"];  
        $position = $_POST['position'];
        $salary = $_POST['salary'];
    if (empty($name) || empty($surname) || empty($ministry) || empty($position) || empty($salary)) {
        echo "Բոլոր ֆայլերը պարտադիր են!";
        exit;
    }
        $insertSql = "INSERT INTO lectures (name, surname, ministry, position,salary)
                    VALUES ('$name', '$surname', ' $ministry',  '$position', '$salary' )";
        if ($conn->query($insertSql) !== TRUE) {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $ministry =  $_POST['ministry'];  
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $updateSql = "UPDATE lectures SET 
            name='$name', 
            surname='$surname',
            ministry = '$ministry', 
            position='$position', 
            salary='$salary' 
            WHERE id=$id";

        if ($conn->query($updateSql) === TRUE) {
            // Հաջող թարմացումից հետո վերադարնում ենք գլխավոր էջ
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Հայտնաբերվել է սխալ, խնդրում ենք լրացնել ուշադիր` " . $conn->error;
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteSql = "DELETE FROM lectures WHERE id=$id";

        if ($conn->query($deleteSql) !== TRUE) {
            echo "Error deleting record: " . $conn->error;
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
    <header></header>
    <title>Ադմին</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<a href="loginhtml.php"><button class="back-btn" style=" background-color: Crimson; color:white; font-weight: bold; margin-left: 12px;padding: 10px;border: none; margin-top: 10px;border-radius: 5px;"><i class="fa-solid fa-user"></i> Դուրս գալ</button></a>
    <h2>ԱԶԳԱՅԻՆ ԺՈՂՈՎԻ ԱՇԽԱՏԱԿԱԶՄ</h2>   
    <div class="form-container">
        <form method="POST" action="">
            <label for="name">Անուն:</label>
            <input type="text" id="name" name="name" class="num">
            <label for="surname">Ազգանուն:</label>
            <input type="text" id="surname" name="surname" class="num">
            <label for="ministry">Նախարարություն:</label>
            <input type="text" id="ministry" name="ministry" class="num">
            <label for="position">Պաշտոն:</label>
            <input type="text" id="position" name="position" class="num">
            <label for="salary">Աշխատավարձ:</label>
            <input type="number" id="salary" name="salary" class="num" placeholder="150000դրամ" min="100000" max="1000000">
            <input type="submit" name="add" class="num" id="but" value="Ավելացնել">
        </form>
    </div>
    <h2>Անձնակազմ</h2>
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
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>">Թարմացնել</a> | 
                        <a href="?delete=<?php echo $row['id']; ?>">Ջնջել</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
        <?php endif; ?>
    </table>

    <?php if (isset($_GET['edit'])): 
        $editId = $_GET['edit'];
        $conn = new mysqli($servername, $username, $password, $database);
        $editQuery = "SELECT * FROM lectures WHERE id=$editId";
        $editResult = $conn->query($editQuery);
        $editRow = $editResult->fetch_assoc();
    ?>
    <div class="form-container">
        <form method="POST" action="">
            <h2>Խմբագրել աձնակազմի տվյալները</h2>
            <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">
            <label for="name">Անուն:</label>
            <input type="text" id="name" name="name" class="num" value="<?php echo $editRow['name']; ?>">
            <label for="surname">Ազգանուն:</label>
            <input type="text" id="surname" name="surname" class="num" value="<?php echo $editRow['surname']; ?>">
            <label for="ministry">Նախարարություն:</label>
            <input type="text" id="ministry" name="ministry" class="num" value="<?php echo $editRow['ministry']; ?>">
            <label for="position">Պաշտոն:</label>
            <input type="text" id="position" name="position" class="num" value="<?php echo $editRow['position']; ?>">
            <label for="salary">Աշխատավարձ:</label>
            <input type="number" id="salary" name="salary" class="num" value="<?php echo $editRow['salary']; ?>">
            <input type="submit" name="update" value="Թարմացնել" class="num" id="but">
        </form>
    </div>
    <?php endif; ?>
</body>
</html>