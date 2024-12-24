<?php
    session_start();
    class UserLogin {
        private $servername = "localhost";
        private $username = "root";
        private $password_db = "";
        private $database = "user_registrations";
        private $conn;
        public function __construct() {
            $this->connectDatabase();
        }
        private function connectDatabase() {
            $this->conn = mysqli_connect($this->servername, $this->username, $this->password_db, $this->database);
            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }
        public function loginUser($username, $password) {
            if ($username === 'admin@gmail.com' && $password === 'admin') {
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = 'Admin';
                $_SESSION['email'] = $username;
                $_SESSION['role'] = 'admin';
                header('Location: admin.php');
                exit();
            }
            $username = mysqli_real_escape_string($this->conn, $username);
            $query = "SELECT * FROM aboutuser WHERE email = '$username'";
            $result = mysqli_query($this->conn, $query);
            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'user';
                    header('Location: uzer.php');
                    exit();
                }  
    } else {
        $_SESSION['error'] = "Օգտատեր չի գտնվել.";
        echo "Սխալ էլ հասցե կամ գաղտնաբառ";
    }
        }
        public function closeConnection() {
            mysqli_close($this->conn);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['email'];
        $password = $_POST['password'];
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Խնդրում ենք գրել և Էլ փոստը և գաղտնաբառը!";
            echo "Խնդրում ենք գրել և Էլ փոստը և գաղտնաբառը";
            exit();
        }
        $userLogin = new UserLogin();
        $userLogin->loginUser($username, $password);
        $userLogin->closeConnection();
    }
?>
