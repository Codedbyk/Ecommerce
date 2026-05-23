<?php
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");

if(!isset($_GET['id'])){
    echo "Product not found!";
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product
$stmt = $conn->prepare("
SELECT * FROM products 
WHERE id = ?
");

$stmt->bind_param("i", $product_id);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Product not found!";
    exit();
}

$product = $result->fetch_assoc();

$user_id = $_SESSION['USER_ID'];

// User activity
$conn->query("
INSERT INTO useractivity(userid, productid, action)
VALUES(
    '$user_id',
    '$product_id',
    'viewed'
)
");

// Related products
$stmt = $conn->prepare("
SELECT * FROM products
WHERE category = ?
AND id != ?
LIMIT 4
");

$stmt->bind_param("si", $product['category'], $product_id);

$stmt->execute();

$related = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>
        <?php echo htmlspecialchars($product['name']); ?>
    </title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">

    <h2>🛍 MyStore</h2>

    <div class="nav-links">

        <a href="home.php">Home</a>

        <a href="view_cart.php">Cart 🛒</a>

        <a href="order_history.php">My Orders</a>

    </div>

</nav>

<!-- PRODUCT DETAIL -->
<div class="product-detail">

    <div class="image-section">

        <img src="images/<?php echo $product['image']; ?>">

    </div>

    <div class="info-section">

        <h1>
            <?php echo htmlspecialchars($product['name']); ?>
        </h1>

        <p class="price">
            ₹<?php echo $product['price']; ?>
        </p>

        <p class="desc">

            <?php
            echo $product['description']
            ?? "No description available.";
            ?>

        </p>

        <!-- CART -->
        <form action="add_to_cart.php" method="GET">

            <input 
            type="hidden"
            name="product_id"
            value="<?php echo $product['id']; ?>">

            <label>Quantity:</label>

            <input 
            type="number"
            name="qty"
            value="1"
            min="1">

            <button class="btn">
                Add to Cart
            </button>

        </form>

        <br>



    </div>

</div>

<hr>

<!-- RELATED PRODUCTS -->
<h2 class="section-title">
    Related Products
</h2>

<div class="product-grid">

<?php while($row = $related->fetch_assoc()){ ?>

<div class="product-card">

    <a href="product_detail.php?id=<?php echo $row['id']; ?>">

        <img src="images/<?php echo $row['image']; ?>">

        <h3>
            <?php echo htmlspecialchars($row['name']); ?>
        </h3>

    </a>

    <p>
        ₹<?php echo $row['price']; ?>
    </p>

</div>

<?php } ?>

</div>

<hr>

</div>

</body>
</html>