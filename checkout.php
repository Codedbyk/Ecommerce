<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");

$user_id = $_SESSION['USER_ID'];

$result = $conn->query("
SELECT products.name,
products.price,
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

<title>Checkout</title>

<link rel="stylesheet" href="styles.css">

<style>

.checkout-container{
    display:flex;
    gap:30px;
    padding:30px;
    flex-wrap:wrap;
}

.checkout-left,
.checkout-right{
    background:white;
    padding:25px;
    border-radius:10px;
    flex:1;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.checkout-left h2,
.checkout-right h3{
    margin-bottom:20px;
}

.checkout-left input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
}

.summary-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.place-order-btn{
    width:100%;
    background:#28a745;
    color:white;
    border:none;
    padding:12px;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
}

.place-order-btn:hover{
    background:#218838;
}

.payment-box{
    margin-top:20px;
}

.payment-btn{
    width:100%;
    margin-top:10px;
    padding:10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:
        background:#ff9900;
    color:white;
}

.payment-btn:hover{
    background:#e68a00;
}

@media(max-width:768px){

    .checkout-container{
        flex-direction:column;
    }
}

</style>

</head>

<body>

<div class="checkout-container">

    <!-- LEFT -->
    <div class="checkout-left">

        <h2>🧾 Checkout</h2>

        <form action="place_order.php" method="POST">

            <input 
            type="text"
            name="name"
            placeholder="Full Name"
            required>

            <input
            type="text"
            name="address"
            placeholder="Address"
            required>

            <input
            type="text"
            name="phone"
            placeholder="Phone Number"
            required>

            <button class="place-order-btn">
                Place Order
            </button>

        </form>

        <!-- PAYMENT UI -->

        <div class="payment-box">

            <h3>Payment Options</h3>

            <button class="payment-btn">
                Cash on Delivery
            </button>

            <button class="payment-btn">
                Pay Now
            </button>

        </div>

    </div>


    <!-- RIGHT -->

    <div class="checkout-right">

        <h3>Order Summary</h3>

        <?php

        while($row = $result->fetch_assoc()){

            $subtotal =
            $row['price'] * $row['quantity'];

            $total += $subtotal;

            echo "<div class='summary-item'>";

            echo "<span>"
            .$row['name'].
            "</span>";

            echo "<span>₹"
            .$row['price'].
            " x "
            .$row['quantity'].
            "</span>";

            echo "</div>";
        }

        ?>

        <hr><br>

        <h2>
            Total: ₹<?php echo $total; ?>
        </h2>

    </div>

</div>

</body>

</html>