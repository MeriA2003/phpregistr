<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="bigdiv">
        <h2>ՄՈՒՏՔ ԳՈՐԾԵԼ</h2>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Գրեք Ձեր էլ հասցեն">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Գաղտնաբառ"> 
            <button type="submit">Մուտք գործել</button>   
        </form>
            <a href="index1.php"><button class="login-btn">Գրանցվել</button></a>
    </div>
</body>
</html>
