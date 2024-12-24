<?php
    session_start();
    interface Databaseoop {
        public function connect();
        public function createDatabase();
        public function createTable();
    }
    trait Input_Validation {
        public function sanitizeInput($input) {
            return htmlspecialchars(trim($input));
        }
        public function validateEmail($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    }
    class Database implements Databaseoop {
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $database = "user_registrations";
        public $conn;
        public function connect() {
            $this->conn = new mysqli($this->servername, $this->username, $this->password);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        public function createDatabase() {
            $sql = "CREATE DATABASE IF NOT EXISTS $this->database";
            if (!$this->conn->query($sql)) {
                die("Error creating database: " . $this->conn->error);
            }
            $this->conn->select_db($this->database);
        }
        public function createTable() {
            $sql = "CREATE TABLE IF NOT EXISTS aboutuser (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                surname VARCHAR(30) NOT NULL,
                email VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                address VARCHAR(100) NOT NULL,
                photo VARCHAR(255) DEFAULT NULL,
                ip_address VARCHAR(45) NOT NULL,
                login_time DATETIME NOT NULL
            )";
            if (!$this->conn->query($sql)) {
                die("Error creating table: " . $this->conn->error);
            }
        }
    }
    class User {
        use Input_Validation;
        private $name;
        private $surname;
        private $email;
        private $password;
        private $phone;
        private $address;
        private $photoPath;
        private $ipAddress;
        private $loginTime;
        public function __construct($name, $surname, $email, $password, $phone, $address, $photoPath, $ipAddress, $loginTime) {
            $this->name = $this->sanitizeInput($name);
            $this->surname = $this->sanitizeInput($surname);
            $this->email = $this->sanitizeInput($email);
            $this->password = password_hash($this->sanitizeInput($password), PASSWORD_BCRYPT);
            $this->phone = $this->sanitizeInput($phone);
            $this->address = $this->sanitizeInput($address);
            $this->photoPath = $photoPath;
            $this->ipAddress = $ipAddress;
            $this->loginTime = $loginTime;
        }
        public function getEmail() {
            return $this->email;
        }
        public function getPassword() {
            return $this->password;
        }
        public function getDetails() {
            return [
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'password' => $this->password,
                'phone' => $this->phone,
                'address' => $this->address,
                'photo' => $this->photoPath,
                'ip_address' => $this->ipAddress,
                'login_time' => $this->loginTime
            ];
        }
    }
    class UserManager {
        private $db;
        public function __construct(Database $db) {
            $this->db = $db;
        }
        public function isEmailUsed($email) {
            $sql = "SELECT * FROM aboutuser WHERE email = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        }
        public function registerUser(User $user) {
            if ($this->isEmailUsed($user->getEmail()) || $user->getEmail() === 'admin@gmail.com') {
                $_SESSION['error'] = "Այս Էլ հասցեն արդեն առկա է բազայում!";
                return false;
            }
            $details = $user->getDetails();
            $sql = "INSERT INTO aboutuser (name, surname, email, password, phone, address, photo, ip_address, login_time) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param(
                "sssssssss",
                $details['name'],
                $details['surname'],
                $details['email'],
                $details['password'],
                $details['phone'],
                $details['address'],
                $details['photo'],
                $details['ip_address'],
                $details['login_time']
            );

            if ($stmt->execute()) {
                $_SESSION['perfect'] = "Շնորհավորում ենք դուք գրանցված եք/";
                return true;
            } else {
                $_SESSION['error'] = "Error։";
                return false;
            }
        }
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $db = new Database();
        $db->connect();
        $db->createDatabase();
        $db->createTable();

        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $phone = $_POST['phone'];
        $address = $_POST["address"];
        $password = $_POST["password"];
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photoTmpName = $_FILES['photo']['tmp_name'];
            $photoName = basename($_FILES['photo']['name']);
            $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($photoExt, $allowedExts)) {
                $newPhotoName = uniqid() . '.' . $photoExt;
                $uploadDir = 'image/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (move_uploaded_file($photoTmpName, $uploadDir . $newPhotoName)) {
                    $photoPath = $uploadDir . $newPhotoName;
                } else {
                    echo "Error նկարի ներբեռնումը սխալ է";
                    exit;
                }
            } else {
                echo "Անհամապատասխան նկարի ֆորմատ. ԸՆտրել ֆորմատը: jpg, jpeg, png, gif.";
                exit;
            }
        }
        if (empty($name) || empty($surname) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($photoPath)) {
            echo "Բոլոր ինփութները պարտադիր են!";
            exit;
        }
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $loginTime = date('Y-m-d H:i:s');
        $user = new User($name, $surname, $email, $password, $phone, $address, $photoPath, $ipAddress, $loginTime);
        $userManager = new UserManager($db);
        if ($userManager->registerUser($user)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header('Location: loginhtml.php');
        } else {
            echo $_SESSION['error'];
        }

        $db->conn->close();
    }
?>
