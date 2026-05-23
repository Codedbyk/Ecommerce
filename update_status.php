<?php

$conn = new mysqli("localhost","root","","ecommerce");

$id = $_POST['id'];
$status = $_POST['status'];

$conn->query("
UPDATE orders
SET status='$status'
WHERE id='$id'
");

header("Location: admin_orders.php");
?>