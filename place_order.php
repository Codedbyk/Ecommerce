<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ecommerce");

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['USER_ID'];

// Get form data
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// Get cart items
$result = $conn->query("
SELECT cart.product_id, cart.quantity, products.price 
FROM cart 
JOIN products ON cart.product_id = products.id 
WHERE cart.user_id = '$user_id'
");

$total = 0;
$items = [];

while($row = $result->fetch_assoc()){
    $total += $row['price'] * $row['quantity'];
    $items[] = $row;
}

// Insert order
$conn->query("INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')");
$order_id = $conn->insert_id;

// Insert order items
foreach($items as $item){
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity)
    VALUES ('$order_id', '".$item['product_id']."', '".$item['quantity']."')");
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id = '$user_id'");

header("Location: order_success.php");
exit();
?>