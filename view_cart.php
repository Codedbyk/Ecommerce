<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ecommerce");

$user_id = $_SESSION['USER_ID'];

$result = $conn->query("
SELECT products.name, products.price, cart.quantity 
FROM cart 
JOIN products ON cart.product_id = products.id 
WHERE cart.user_id = '$user_id'
");

$total = 0;
?>

<h2>Your Cart 🛒</h2>

<?php
while($row = $result->fetch_assoc()){
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;

    echo "<p>".$row['name']." - ₹".$row['price']." x ".$row['quantity']."</p>";
}
?>

<h3>Total: ₹<?php echo $total; ?></h3>
<a href="checkout.php">Checkout</a>