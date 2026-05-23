<?php
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");

// Search
$search = "";

if(isset($_GET['search'])){

    $search = $_GET['search'];

    $stmt = $conn->prepare("
    SELECT * FROM products 
    WHERE name LIKE ?
    ");

    $like = "%$search%";

    $stmt->bind_param("s", $like);

    $stmt->execute();

    $featured_products = $stmt->get_result();

}else{

    $featured_products = $conn->query("
    SELECT * FROM products
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">

    <h2>🛍 MyStore</h2>

    <form method="GET" class="search-box">

        <input 
        type="text" 
        name="search" 
        placeholder="Search products..."
        value="<?php echo $search; ?>">

        <button type="submit">Search</button>

    </form>

    <div class="nav-links">

        <a href="home.php">Home</a>

        <a href="view_cart.php">Cart 🛒</a>

        <a href="order_history.php">My Orders</a>

        <a href="logout.php">Logout</a>

    </div>

</nav>

<!-- HERO -->
<section class="hero">

    <h1>Big Sale 🎉</h1>

    <p>Best deals on all products</p>

</section>

<!-- PRODUCTS -->
<h2 class="section-title">🔥 Products</h2>

<div class="product-grid">

<?php

while($row = $featured_products->fetch_assoc()){

?>

<div class="product-card">

    <a href="product_detail.php?id=<?php echo $row['id']; ?>">

        <img src="images/<?php echo $row['image']; ?>">

        <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    </a>

    <p class="price">
        ₹<?php echo $row['price']; ?>
    </p>

    <a class="btn"
    href="add_to_cart.php?product_id=<?php echo $row['id']; ?>">
        Add to Cart
    </a>

</div>

<?php } ?>

</div>

<!-- POPULAR PRODUCTS -->
<h2 class="section-title">⭐ Popular Products</h2>

<div class="product-grid">

<?php

$popular = $conn->query("
SELECT * FROM products
LIMIT 4
");

while($row = $popular->fetch_assoc()){

?>

<div class="product-card">

    <a href="product_detail.php?id=<?php echo $row['id']; ?>">

        <img src="images/<?php echo $row['image']; ?>">

        <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    </a>

    <p class="price">
        ₹<?php echo $row['price']; ?>
    </p>

</div>

<?php } ?>

</div>

</body>
</html>