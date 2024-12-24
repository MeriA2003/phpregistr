<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="bigdiv">
        <h2>ԳՐԱՆՑՈՒՄ</h2>
        <div class="form">
            <form action="request.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Անուն"><br><br>
                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" placeholder="Ազգանուն"><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="email@example.com"><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Գաղտնաբառ"><br><br>
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" placeholder="+374-77-155-678"><br><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Հայաստան"><br><br>
                <label for="photo">Profile Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*"><br><br>
                <button type="submit" class="register-btn">Գրանցվել</button>
            </form>
            <br>
            <a href="loginhtml.php"><button class="login-btn">Գրանցված եմ՝ Մուտք գործել</button></a>
        </div>
    </div>
</body>
</html>
  

