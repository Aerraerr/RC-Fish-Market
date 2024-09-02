<?php
require 'connection.php';
$totalQuery = "SELECT COUNT(*) AS total_count FROM products WHERE product_type IN ('Tuna', 'Mackerel', 'Salmon', 'Sardines', 'Cod')";
$totalResult = mysqli_query($conn, $totalQuery);
$totalCount = mysqli_fetch_assoc($totalResult)['total_count'];

// Query to count premium fish products
$premiumQuery = "SELECT COUNT(*) AS premium_count FROM products WHERE product_grade = 'PRE'";
$premiumResult = mysqli_query($conn, $premiumQuery);
$premiumCount = mysqli_fetch_assoc($premiumResult)['premium_count'];

// Query to count standard fish products
$standardQuery = "SELECT COUNT(*) AS standard_count FROM products WHERE product_grade = 'STA'";
$standardResult = mysqli_query($conn, $standardQuery);
$standardCount = mysqli_fetch_assoc($standardResult)['standard_count'];

// Query to count each fish type
$tunaQuery = "SELECT COUNT(*) AS tuna_count FROM products WHERE product_type = 'Tuna'";
$tunaResult = mysqli_query($conn, $tunaQuery);
$tunaCount = mysqli_fetch_assoc($tunaResult)['tuna_count'];

$mackerelQuery = "SELECT COUNT(*) AS mackerel_count FROM products WHERE product_type = 'Mackerel'";
$mackerelResult = mysqli_query($conn, $mackerelQuery);
$mackerelCount = mysqli_fetch_assoc($mackerelResult)['mackerel_count'];

$sardinesQuery = "SELECT COUNT(*) AS sardines_count FROM products WHERE product_type = 'Sardines'";
$sardinesResult = mysqli_query($conn, $sardinesQuery);
$sardinesCount = mysqli_fetch_assoc($sardinesResult)['sardines_count'];

$salmonQuery = "SELECT COUNT(*) AS salmon_count FROM products WHERE product_type = 'Salmon'";
$salmonResult = mysqli_query($conn, $salmonQuery);
$salmonCount = mysqli_fetch_assoc($salmonResult)['salmon_count'];

$codQuery = "SELECT COUNT(*) AS cod_count FROM products WHERE product_type = 'Cod'";
$codResult = mysqli_query($conn, $codQuery);
$codCount = mysqli_fetch_assoc($codResult)['cod_count'];

// Query to calculate total revenue
$totalRevenueQuery = "SELECT SUM(product_weight * product_price) AS total_revenue FROM products";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
$totalRevenue = mysqli_fetch_assoc($totalRevenueResult)['total_revenue'];

// Format revenue with commas and ensure it always displays cents
$formattedRevenue = number_format($totalRevenue, 2);


?>
<?php
require 'connection.php'; // Assuming this file contains your database connection settings

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current date in yyyy-mm-dd format
$current_date = date('Y-m-d');

// SQL query to count orders today and calculate total amount
$sql = "SELECT 
            COUNT(*) AS orders_today, 
            SUM(total_amount) AS total_amount_today 
        FROM sold_products 
        WHERE DATE(sold_at) = '$current_date'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the single row (should be just one row due to COUNT(*) usage)
    $row = $result->fetch_assoc();
    $orders_today = $row["orders_today"];
    $total_amount_today = $row["total_amount_today"];
} else {
    $orders_today = 0;
    $total_amount_today = 0;
}
$product_count = 0;
$sql_count = "SELECT COUNT(*) AS product_count FROM sold_products";
$result_count = $conn->query($sql_count);

if ($result_count->num_rows > 0) {
    $row_count = $result_count->fetch_assoc();
    $product_count = $row_count["product_count"];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Cashier - Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForCashier.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            #CashierDash1{
                background-color: #2F353B;
            }


            .dashfirst{
    margin-left:5%;
}
.totalprod,
.premiumprod,
.standardprod {
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
}

.totalprod {
    position: absolute;
    height: 180px;
    width: 25%;
    border-radius: 5px;
    font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    padding-top: 10px;
    background-color:#ff6347;
}

.premiumprod {
    position: absolute;
    width: 25%;
    height: 180px;
    margin-left: 26%;
    border-radius: 5px;
    font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    padding-top: 10px;
    background-color:#ffa500;
}

.standardprod {
    position: absolute;
    width: 25%;
    height: 180px;
    margin-left: 52%;
    border-radius: 5px;
    font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    padding-top: 10px;
    background-color:#32cd32;
}
.totalprod,
.premiumprod,
.standardprod h3{
    color:whitesmoke;
}
.totalprod input,
.premiumprod input,
.standardprod input{
    width:50%;
    color:whitesmoke;
    background-color:transparent;
    border:none;
    font-size:60px;
    margin-top:0px;
    text-align:right;
}

.dashsecond{
    position:absolute;
    width:77%;
    height:200px;
    margin: 0 auto;
    margin-top:200px;
    margin-left:4.4%;
}
.tuna{
    position:absolute;
    width:19.5%;
    background-color:#5a8e49;
    height:65px;
    border-radius:5px;
        font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
}
.mackerel{
    position:absolute;
    width:19.5%;
    background-color:#1f3b4d;
    height:65px;
    margin-left:20%;
    border-radius:5px;
        font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
}
.sardines{
    position:absolute;
    width:19.5%;
    background-color:#9b301c;
    height:65px;
    margin-left:40%;
    border-radius:5px;
        font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
}
.salmon{
    position:absolute;
    width:19.5%;
    background-color: #e59d73;
    height:65px;
    margin-left:60%;
    border-radius:5px;
        font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
}
.cod{
    position:absolute;
    width:19.8%;
    background-color:#2b3a67;
    height:65px;
    margin-left:80%;
    border-radius:5px;
    font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
}
.dashsecond input{
    width:50%;
    position:absolute;
    background-color:transparent;
    color:whitesmoke;
    border:none;
    font-size:40px;
    margin-top:-25px;
    text-align:right;
}

#fishdash1{
    width:70px;
    margin-top:-35px;
}




.dashthird{
    position:absolute;
    width:77%;
    height:200px;
    margin: 0 auto;
    margin-top:470px;
    margin-left:4.4%;;
}
.totalrevenue{
    position:absolute;
    background-color:  #1e3163;
    width:49%;
    height:150px;
     font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
    border-radius:5px;
}
.totalrevenue h3{
    font-size:30px;
    font-family: Haettenschweiler;
    padding-top:5px;
}
.total2{
    position:absolute;
    background-color:  #dcdcdc;
    width:49.5%;
    height:150px;
     font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:#333333;
    border-radius:5px;
    margin-top:-160px;
}
.total2 h3{
    font-size:30px;
    font-family: Haettenschweiler;
    padding-top:5px;
    color:#333333;
}
.dashthird input{
    background-color:transparent;
    border:none;
    color:whitesmoke;
    font-size:60px;
    margin-top:-15px;
    padding-left:60px;
    text-align:right;
    width:75%;
}
#pesosign{
    font-size:30px;
    font-family: Haettenschweiler;
    margin-top:-80px;
    padding-left:75%;
}
#navbarDropdown{
        padding-top:15px;
    }


.nav-link{
    font-size:17px;
    letter-spacing:1.5px;
    height:60px;
}
#dateTime {
    font-size: 20px;
    font-family: poppins;
    text-align: center;
    color:white;
}
.oltag1{
    margin-top:-12px;
    font-size:15px;
    padding-left:50%;
}
.oltag2{
    font-size:22px;
    padding-left:50%;
    margin-top:-72px;
    color:whitesmoke;
    margin-left:-3.5%;
}
.chart-container {
            position:absolute;
            width: 37%;
            height:310px;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top:452px;
            margin-left:58%;
        }
        canvas {
            display: block;
            margin: auto;
        }
        </style>
        
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a id="RCFM" class="navbar-brand ps-3" href="Home2.php">     
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
                                Order History
                            </a>
                        </div>

                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        CASHIER
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
            <main>
                    <div class="container-fluid px-4">

                        <h1 style="font-family: Haettenschweiler; font-size:40px; font-weight:200;
                        letter-spacing:2px;" class="mt-4">DASHBOARD</h1>
                        <ol class="breadcrumb mb-4">
                        </ol>
                        
                    </div>   
                    <div class="dashboardcont">
                        <div class="dashfirst">
                            <div class="dashfirstcont">
                            <div class="totalprod">
                                <h3>TOTAL FISH PRODUCTS</h3>
                                <input type="number" value="<?php echo $totalCount; ?>" readonly>
                                <img id="fishdash1" src="../Img/fishdash.png" alt="">
                            </div>
                            <div class="premiumprod">
                                <h3>PREMIUM</h3>
                                <input type="number" value="<?php echo $premiumCount; ?>" readonly>
                                <img id="fishdash1" src="../Img/fishdash.png" alt="">
                            </div>
                            <div class="standardprod">
                                <h3>STANDARD</h3>
                                <input type="number" value="<?php echo $standardCount; ?>" readonly>
                                <img id="fishdash1" src="../Img/fishdash.png" alt="">
                            </div>
                            </div>
                        </div>

                        <div class="dashsecond">
                            <div class="tuna">
                                <h3>TUNA</h3>
                                <input type="number" value="<?php echo $tunaCount; ?>" readonly>

                            </div>
                            <div class="mackerel">
                                <h3>MACKEREL</h3>
                                <input type="number" value="<?php echo $mackerelCount; ?>" readonly>
                            </div>
                            <div class="sardines">
                                <h3>SARDINES</h3>
                                <input type="number" value="<?php echo $sardinesCount; ?>" readonly>
                            </div>
                            <div class="salmon">
                                <h3>SALMON</h3>
                                <input type="number" value="<?php echo $salmonCount; ?>" readonly>
                            </div>
                            <div class="cod">
                                <h3>COD</h3>
                                <input type="number" value="<?php echo $codCount; ?>" readonly>
                            </div>
                        </div>
                        <div class="dashthirdfirst">
                        </div>
                        <div class="dashthird">
                            <div style="margin-left:26%;margin-top:-160px;width:23%;" class="totalrevenue">
                                <h3>Daily Order</h3>
                                <input style="padding-right:50px;" type="text" value="<?php echo $orders_today; ?>" readonly>
                            </div>
                            <div style="width:49%;" class="totalrevenue">
                                <h3>Daily Sales</h3>
                                <input style="padding-right:10px;" type="text" value="<?php echo number_format($total_amount_today , 2); ?>" readonly>
                                <h3 id="pesosign">php</h3>
                            </div>
                            <div style="width:25%;" class="total2">
                            <h3>SOLD PRODUCTS</h3>
                                <input style=" color:#333333; width:90%; text-align:center;" type="text" value="<?php echo $product_count; ?>" readonly>
                            </div>
                            <canvas id="fishChart" width="200" height="120"></canvas>
                        </div>
                        

                        </div> 

                    </div> 
                </main>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="../js/Admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
           
        </script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Orders Today'],
                    datasets: [{
                        label: 'Total Orders Today',
                        data: [<?php echo $orders_today; ?>],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Total Amount Today',
                        data: [<?php echo $total_amount_today; ?>],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
        
    </script>
    <script>
    function Logout(){
    window.location.href = "../index.php";
    exit();
    }
</script>
    </body>
</html>
