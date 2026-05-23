<?php
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");

if(isset($_GET['product_id'])){

    $user_id = $_SESSION['USER_ID'];
    $product_id = $_GET['product_id'];
    $qty = isset($_GET['qty']) ? $_GET['qty'] : 1;

    // Get product price
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $total = $product['price'] * $qty;

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();

    $order_id = $conn->insert_id;

    // Insert order item
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $order_id, $product_id, $qty);
    $stmt->execute();

    echo "Order placed successfully! 🎉";
}
?>