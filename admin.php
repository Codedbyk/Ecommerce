<?php
$conn = new mysqli("localhost","root","","ecommerce");

$result = $conn->query("SELECT * FROM orders");

while($row = $result->fetch_assoc()){
?>

<div class="card">
    <p>Order ID: <?php echo $row['id']; ?></p>
    <p>Status: <?php echo $row['status']; ?></p>

    <form method="POST" action="update_status.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <select name="status">
            <option>Pending</option>
            <option>Shipped</option>
            <option>Delivered</option>
            <option>Cancelled</option>
        </select>

        <button>Update</button>
    </form>
</div>

<?php } ?>