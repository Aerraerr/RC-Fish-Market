<?php
require 'connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $productType = $_POST['product_type'];
    $productGrade = $_POST['product_grade'];
    $productPrice = $_POST['product_price'];
    $quantity = $_POST['quantity'];
    $totalAmount = $productPrice * $quantity; // Calculate total amount
    
    // Insert sold product into database
    $sql = "INSERT INTO sold_products (product_name, product_type, product_grade, product_price, quantity, total_amount) VALUES ('$productName', '$productType', '$productGrade', $productPrice, $quantity, $totalAmount)";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<?php
// Database connection
require 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch sold products grouped by sold_at
$query = "SELECT * FROM sold_products ORDER BY sold_at DESC";
$result = $conn->query($query);

$sold_products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sold_products[$row['sold_at']][] = $row;
    }
}
?>










<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Cashier - Order History</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForAdministrator.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
        #CashierMenu2{
            background-color: #2F353B;
        }
        #Dashboard{
            background-color: none;
        }
        .mt-4{
            font-family: Haettenschweiler;
            font-size:40px;
            letter-spacing:1px;
        }
        #navbarDropdown{
            padding-top:15px;
        }



        .nav-link{
            font-size:17px;
            letter-spacing:1.5px;
            height:50px;
        }
        #dateTime {
            font-size: 20px;
            font-family: poppins;
            text-align: center;
            color:white;
        }
        </style>
        <style>
        .orderhistory {
            padding: 20px;
        }

        .order {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .order h3 {
            margin: 0;
            padding: 0;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .order table {
            width: 100%;
            border-collapse: collapse;
        }

        .order table, .order th, .order td {
            border: 1px solid #ddd;
        }

        .order th, .order td {
            padding: 8px;
            text-align: left;
        }

        .order th {
            background-color: #f2f2f2;
        }

        .order .total {
            font-weight: bold;
        }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a id="RCFM" class="navbar-brand ps-3" href="ForAdministrator.php">            
            <img id="RC" src="../Img/fish.png" alt="">RC Fish Market</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <div id="dateTime"></div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="javascript:Logout()">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                    <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a style="padding-left:15px;" class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                
                                Dashboard
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="CashierDash1" class="nav-link" href="ForCashier.php">Quantity</a>
                                    <a id="CashierDash" class="nav-link" href="ForCashier2.php">Weight</a>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a id="CashierMenu" class="nav-link" href="CashierMenu.php">
                                Manage Orders
                            </a>
                            <a id="CashierMenu2" class="nav-link" href="Order History.php">
                                Orders History
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        ADMINISTRATOR
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
            <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Order History</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Order History</li>
            </ol>
        </div>
        <div class="container-fluid orderhistory">
    <?php
    foreach ($sold_products as $sold_at => $orders) {
        $order_total_amount = 0; // Total amount for the entire order

        // Convert the date string into a timestamp
        $timestamp = strtotime($sold_at);

        // Format the date and time into "Month, day year, time"
        $formatted_date = date("F j, Y, g:i A", $timestamp);

        echo "<div class='order'>";
        echo "<h3>Order placed at:<b> " . htmlspecialchars($formatted_date) . "</b></h3>";
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>
                <th>Product Name</th>
                <th>Type</th>
                <th>Grade</th>
                <th>Quantity Sold</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
            </tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($orders as $order) {
            $product_price = $order['product_price'];
            $product_total_amount = $order['quantity_sold'] * $order['product_price']; // Calculate total for this product
            $order_total_amount += $product_total_amount; // Add to order total

            echo "<tr>";
            echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($order['type']) . "</td>";
            echo "<td>" . htmlspecialchars($order['grade']) . "</td>";
            echo "<td>" . htmlspecialchars($order['quantity_sold']) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($order['product_price'], 2)) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($product_total_amount, 2)) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "<div class='total'>Order Total Amount: " . htmlspecialchars(number_format($order_total_amount, 2)) . "</div>";
        echo "</div>";
    }
    ?>
</div>
</main>
<?php
$conn->close();
?>
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="../js/Admin.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.min.js"></script>
        <script>
            function updateDateTime() {
                const dateTimeElement = document.getElementById('dateTime');
                const currentDate = new Date();
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

                const formattedDate = currentDate.toLocaleDateString('en-US', dateOptions);
                const formattedTime = currentDate.toLocaleTimeString('en-US', timeOptions);

                dateTimeElement.textContent = `${formattedDate} | ${formattedTime}`;
            }

            // Update date and time every second
            setInterval(updateDateTime, 1000);

            // Initial call to display date and time immediately
            updateDateTime();
        </script>
        <script>
    function Logout(){
    window.location.href = "../index.php";
    exit();
    }
</script>

    </body>
</html>
