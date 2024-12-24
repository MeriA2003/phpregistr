<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: loginhtml.php');
        exit();
    }
    $email = $_SESSION['email']; 
    $servername = "localhost";
    $username = "root"; 
    $password = "";      
    $database = "user_registrations";
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db($database);
    $sql = "SELECT * FROM aboutuser WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Օգտատեր չի գտնվել.";
        exit();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style3.css">
    </head>
    <body>
        <a href="uzer.php"><button class="back-btn"><i class="fa-solid fa-arrow-left"></i>  Վերադառնալ</button></a><br>
        <a href="loginhtml.php"><button class="back-btn" style=" background-color: Crimson; color:white"><i class="fa-solid fa-user"></i> Դուրս գալ</button></a>
        <h2>ԻՄ ՄԱՍԻՆ</h2> 
        <div class="profile-container">
            <?php if ($user['photo']): ?>
                <div class="profile-photo">
                    <img src="<?php echo $user['photo']; ?>" alt="Profile Photo">
                </div>
            <?php else: ?>
                <div class="no-photo">Դուք Նկար չունեք ներբեռնած</div>
            <?php endif; ?>
            <table class="profile-table">
                <tr>
                    <th>Անուն</th>
                    <th>Ազգանուն</th>
                    <th>Էլ․ փոստ</th>
                    <th>Հեռախոս</th>
                    <th>Հասցե</th>
                </tr>
                <tr>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['surname']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td><?php echo $user['address']; ?></td>
                </tr>
            </table>
        </div>
    </body>
</html>
