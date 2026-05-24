<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ecommerce");

if(!isset($_SESSION['USER_ID'])){
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['USER_ID'];

$result = $conn->query("
SELECT products.name,
products.price,
products.image,
cart.quantity
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = '$user_id'
");

$total = 0;
?>

<!DOCTYPE html>
<html>

<head>

<title>My Cart</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f5f5f5;
    padding:30px;
}

h2{
    text-align:center;
    margin-bottom:30px;
    color:#ff5722;
}

.cart-container{
    max-width:900px;
    margin:auto;
}

.cart-card{
    background:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:20px;
    margin-bottom:20px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.cart-left{
    display:flex;
    align-items:center;
    gap:20px;
}

.cart-left img{
    width:100px;
    height:100px;
    object-fit:cover;
    border-radius:10px;
}

.cart-info h3{
    margin-bottom:10px;
    color:#333;
}

.cart-info p{
    color:#666;
    margin-bottom:5px;
}

.cart-right{
    text-align:right;
}

.total-box{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    text-align:center;
    margin-top:30px;
}

.total-box h3{
    margin-bottom:20px;
    color:#ff5722;
}

.checkout-btn{
    background:#ff5722;
    color:white;
    padding:12px 25px;
    text-decoration:none;
    border-radius:8px;
    font-size:18px;
}

.checkout-btn:hover{
    background:#e64a19;
}

.empty-cart{
    text-align:center;
    font-size:20px;
    color:#777;
}

</style>

</head>

<body>

<h2>🛒 Your Shopping Cart</h2>

<div class="cart-container">

<?php

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){

        $subtotal = $row['price'] * $row['quantity'];

        $total += $subtotal;

?>

<div class="cart-card">

    <div class="cart-left">

        <img src="images/<?php echo $row['image']; ?>">

        <div class="cart-info">

            <h3><?php echo $row['name']; ?></h3>

            <p>Price: ₹<?php echo $row['price']; ?></p>

            <p>Quantity: <?php echo $row['quantity']; ?></p>

        </div>

    </div>

    <div class="cart-right">

        <h3>₹<?php echo $subtotal; ?></h3>

    </div>

</div>

<?php

    }

?>

<div class="total-box">

    <h3>Total Amount: ₹<?php echo $total; ?></h3>

    <a href="checkout.php" class="checkout-btn">
        Proceed to Checkout
    </a>

</div>

<?php

} else {

    echo "<p class='empty-cart'>Your cart is empty 😢</p>";
}

?>

</div>

</body>
</html>