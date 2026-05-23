<?php
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");

$user_id = $_SESSION['USER_ID'];

$orders = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");
?>

<h2 style="padding:20px;">📦 My Orders</h2>

<?php
while($order = $orders->fetch_assoc()){
    $status = $order['status'];
?>

<div class="order-box">

    <h3>Order #<?php echo $order['id']; ?></h3>
    <p>Total: ₹<?php echo $order['total']; ?></p>

    <!-- STATUS TRACKER -->
    <div class="tracker">

        <div class="step <?php echo ($status=='Pending')?'active':''; ?>">Pending</div>
        <div class="step <?php echo ($status=='Shipped')?'active':''; ?>">Shipped</div>
        <div class="step <?php echo ($status=='Delivered')?'active':''; ?>">Delivered</div>

    </div>

</div>

<?php } ?>