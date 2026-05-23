<?php
session_start();

if(!isset($_SESSION['USER_ID'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce");


// ================= TOTAL REVENUE =================

$revenue = $conn->query("
SELECT SUM(total) as total
FROM orders
")->fetch_assoc();


// ================= TOTAL ORDERS =================

$total_orders = $conn->query("
SELECT COUNT(*) as total
FROM orders
")->fetch_assoc();


// ================= TOTAL USERS =================

$total_users = $conn->query("
SELECT COUNT(*) as total
FROM users
")->fetch_assoc();


// ================= ORDER STATUS =================

$pending = $conn->query("
SELECT COUNT(*) as total
FROM orders
WHERE status='Pending'
")->fetch_assoc()['total'];

$shipped = $conn->query("
SELECT COUNT(*) as total
FROM orders
WHERE status='Shipped'
")->fetch_assoc()['total'];

$delivered = $conn->query("
SELECT COUNT(*) as total
FROM orders
WHERE status='Delivered'
")->fetch_assoc()['total'];


// ================= CATEGORY DISTRIBUTION =================

$category_data = $conn->query("
SELECT category, COUNT(*) as total
FROM products
GROUP BY category
");

$categories = [];
$category_totals = [];

while($row = $category_data->fetch_assoc()){

    $categories[] = $row['category'];

    $category_totals[] = $row['total'];
}


// ================= SALES BY MONTH =================

$monthly_sales = $conn->query("
SELECT MONTH(created_at) as month,
SUM(total) as total_sales
FROM orders
GROUP BY MONTH(created_at)
ORDER BY MONTH(created_at)
");

$months = [];
$sales = [];

while($row = $monthly_sales->fetch_assoc()){

    $months[] = "Month " . $row['month'];

    $sales[] = $row['total_sales'];
}


// ================= CUSTOMER PURCHASE FREQUENCY =================

$customers = $conn->query("
SELECT user_id, COUNT(*) as total_orders
FROM orders
GROUP BY user_id
");

$user_ids = [];
$order_counts = [];

while($row = $customers->fetch_assoc()){

    $user_ids[] = "User " . $row['user_id'];

    $order_counts[] = $row['total_orders'];
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Analytics Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial;
    background:#f4f6f9;
}

.navbar{
    background:#111827;
    color:white;
    padding:12px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar a{
    color:white;
    text-decoration:none;
    margin-left:15px;
    font-size:14px;
}

.container{
    padding:15px;
    max-width:1200px;
    margin:auto;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.card h2{
    color:#555;
    margin-bottom:10px;
    font-size:18px;
}

.card h1{
    font-size:28px;
    color:#111827;
}

.chart-box{
    background:white;
    padding:15px;
    margin:20px auto;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    width:70%;
}

canvas{
    max-height:220px !important;
    max-width:500px !important;
    margin:auto;
}

@media(max-width:768px){

    .chart-box{
        width:100%;
    }

}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

<h2>📊 Analytics Dashboard</h2>

<div>

<a href="home.php">Home</a>

<a href="admin.php">Admin</a>

<a href="logout.php">Logout</a>

</div>

</div>



<div class="container">


<!-- SUMMARY CARDS -->

<div class="cards">

<div class="card">

<h2>Total Revenue</h2>

<h1>
₹<?php echo $revenue['total'] ? $revenue['total'] : 0; ?>
</h1>

</div>



<div class="card">

<h2>Total Orders</h2>

<h1>
<?php echo $total_orders['total']; ?>
</h1>

</div>



<div class="card">

<h2>Total Users</h2>

<h1>
<?php echo $total_users['total']; ?>
</h1>

</div>

</div>



<!-- ORDER STATUS -->

<div class="chart-box">

<h2>📊 Order Status</h2>

<canvas id="statusChart"></canvas>

</div>



<!-- CATEGORY DISTRIBUTION -->

<div class="chart-box">

<h2>🥧 Category Distribution</h2>

<canvas id="categoryChart"></canvas>

</div>



<!-- SALES BY MONTH -->

<div class="chart-box">

<h2>📈 Sales By Month</h2>

<canvas id="salesChart"></canvas>

</div>



<!-- CUSTOMER PURCHASE -->

<div class="chart-box">

<h2>🛒 Customer Purchase Frequency</h2>

<canvas id="customerChart"></canvas>

</div>

</div>



<script>


// ================= ORDER STATUS =================

new Chart(document.getElementById('statusChart'), {

    type:'bar',

    data:{

        labels:[
            'Pending',
            'Shipped',
            'Delivered'
        ],

        datasets:[{

            label:'Orders',

            data:[
                <?php echo $pending; ?>,
                <?php echo $shipped; ?>,
                <?php echo $delivered; ?>
            ],

            borderWidth:1

        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false
    }

});




// ================= CATEGORY PIE CHART =================

new Chart(document.getElementById('categoryChart'), {

    type:'pie',

    data:{

        labels:
        <?php echo json_encode($categories); ?>,

        datasets:[{

            data:
            <?php echo json_encode($category_totals); ?>,

            borderWidth:1

        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false
    }

});




// ================= SALES BY MONTH =================

new Chart(document.getElementById('salesChart'), {

    type:'line',

    data:{

        labels:
        <?php echo json_encode($months); ?>,

        datasets:[{

            label:'Sales',

            data:
            <?php echo json_encode($sales); ?>,

            borderWidth:2,
            tension:0.3

        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false
    }

});




// ================= CUSTOMER PURCHASE =================

new Chart(document.getElementById('customerChart'), {

    type:'bar',

    data:{

        labels:
        <?php echo json_encode($user_ids); ?>,

        datasets:[{

            label:'Orders',

            data:
            <?php echo json_encode($order_counts); ?>,

            borderWidth:1

        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false
    }

});

</script>

</body>
</html>