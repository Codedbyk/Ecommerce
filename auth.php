<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ecommerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ================= LOGIN ================= */

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            session_regenerate_id(true);

            $_SESSION['USER_ID'] = $user['id'];
            $_SESSION['USER_NAME'] = $user['name'];

            header("Location: home.php");
            exit();

        } else {
            $error = "Invalid Password!";
        }

    } else {
        $error = "User not found!";
    }
}

/* ================= REGISTER ================= */

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check Email
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){

        $error = "Email already exists!";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if($stmt->execute()){

            $success = "Registration Successful! Please Login.";

        } else {

            $error = "Registration Failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Authentication</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(to right,#ff9800,#ff5722);
}

.container{
    width:900px;
    display:flex;
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 0 20px rgba(0,0,0,0.2);
}

/* LEFT SIDE */

.left-side{
    flex:1;
    background:#ff5722;
    color:white;
    padding:50px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.left-side h1{
    font-size:40px;
    margin-bottom:20px;
}

.left-side p{
    line-height:1.8;
    margin-bottom:15px;
    font-size:18px;
}

/* RIGHT SIDE */

.right-side{
    flex:1;
    padding:40px;
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#ff5722;
}

.toggle-btns{
    display:flex;
    margin-bottom:20px;
}

.toggle-btns button{
    flex:1;
    padding:10px;
    border:none;
    cursor:pointer;
    background:#eee;
    font-size:16px;
}

.toggle-btns button.active{
    background:#ff5722;
    color:white;
}

form{
    display:flex;
    flex-direction:column;
}

input{
    padding:12px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:16px;
}

.submit-btn{
    background:#ff5722;
    color:white;
    border:none;
    padding:12px;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

.submit-btn:hover{
    background:#e64a19;
}

.message{
    text-align:center;
    margin-bottom:15px;
    color:red;
}

.success{
    color:green;
}

#registerForm{
    display:none;
}

</style>

</head>

<body>

<div class="container">

    <!-- LEFT SIDE -->

    <div class="left-side">

        <h1>🛒 MyStore</h1>

        <p>
            Welcome to our Online Shopping Website
            with Sales Analytics and Recommendation System.
        </p>

        <p>✔ Easy Shopping Experience</p>

        <p>✔ Secure Login & Registration</p>

        <p>✔ Add to Cart & Checkout</p>

        <p>✔ Smart Product Recommendations</p>

    </div>

    <!-- RIGHT SIDE -->

    <div class="right-side">

        <div class="toggle-btns">

            <button class="active" id="loginBtn" onclick="showLogin()">
                Login
            </button>

            <button id="registerBtn" onclick="showRegister()">
                Register
            </button>

        </div>

        <?php

        if(isset($error)){
            echo "<p class='message'>$error</p>";
        }

        if(isset($success)){
            echo "<p class='message success'>$success</p>";
        }

        ?>

        <!-- LOGIN FORM -->

        <form method="POST" id="loginForm">

            <h2>Login</h2>

            <input type="email" name="email" placeholder="Enter Email" required>

            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" name="login" class="submit-btn">
                Login
            </button>

        </form>

        <!-- REGISTER FORM -->

        <form method="POST" id="registerForm">

            <h2>Register</h2>

            <input type="text" name="name" placeholder="Enter Name" required>

            <input type="email" name="email" placeholder="Enter Email" required>

            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" name="register" class="submit-btn">
                Register
            </button>

        </form>

    </div>

</div>

<script>

function showRegister(){

    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "flex";

    document.getElementById("registerBtn").classList.add("active");
    document.getElementById("loginBtn").classList.remove("active");
}

function showLogin(){

    document.getElementById("loginForm").style.display = "flex";
    document.getElementById("registerForm").style.display = "none";

    document.getElementById("loginBtn").classList.add("active");
    document.getElementById("registerBtn").classList.remove("active");
}

</script>

</body>
</html>