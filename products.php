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
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <h2>🛍 MyStore</h2>

    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="view_cart.php">Cart 🛒</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<!-- TITLE -->
<h2 class="section-title">🛒 All Products</h2>

<!-- PRODUCTS GRID -->
<div class="product-grid">

<?php
if($products->num_rows > 0){
    while($row = $products->fetch_assoc()){
        echo "<div class='product-card'>";

        echo "<a href='product_detail.php?id=".$row['id']."'>";
        echo "<img src='images/" . $row['image'] . "'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "</a>";

        echo "<p class='price'>₹" . $row['price'] . "</p>";
       echo "<a class='btn' href='add_to_cart.php?product_id=".$row['id']."'>Add to Cart</a>";
       echo "</div>";
    }
} else {
    echo "<p style='text-align:center;'>No products found 😢</p>";
}
?>

</div>

</body>
</html>