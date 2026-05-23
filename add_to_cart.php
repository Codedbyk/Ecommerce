<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ecommerce");

// Check login
if(!isset($_SESSION['USER_ID'])){
    echo "Please login first!";
    exit();
}

if(isset($_GET['product_id'])){
    
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['USER_ID'];

    // Check if product already exists
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        // Increase quantity
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'");
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    header("Location: products.php");
    exit();
}
?>