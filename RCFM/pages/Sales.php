<?php
require 'connection.php';
$totalQuery = "SELECT COUNT(*) AS total_count FROM products WHERE product_type IN ('Tuna', 'Mackerel', 'Salmon', 'Sardines', 'Cod')";
$totalResult = mysqli_query($conn, $totalQuery);
$totalCount = mysqli_fetch_assoc($totalResult)['total_count'];
//count products
$premiumQuery = "SELECT COUNT(*) AS premium_count FROM products WHERE product_grade = 'PRE'";
$premiumResult = mysqli_query($conn, $premiumQuery);
$premiumCount = mysqli_fetch_assoc($premiumResult)['premium_count'];


$standardQuery = "SELECT COUNT(*) AS standard_count FROM products WHERE product_grade = 'STA'";
$standardResult = mysqli_query($conn, $standardQuery);
$standardCount = mysqli_fetch_assoc($standardResult)['standard_count'];

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

//calculate total revenue
$totalRevenueQuery = "SELECT SUM(product_weight * product_price) AS total_revenue FROM products";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
$totalRevenue = mysqli_fetch_assoc($totalRevenueResult)['total_revenue'];
$formattedRevenue = number_format($totalRevenue, 2);
?>


<?php
require 'connection.php'; // Include your database connection file

// Calculate total sold amount
$total_sold_amount = 0;
$sql_total = "SELECT SUM(total_amount) AS total_amount_sum FROM sold_products";
$result_total = $conn->query($sql_total);

if ($result_total->num_rows > 0) {
    $row = $result_total->fetch_assoc();
    $total_sold_amount = $row["total_amount_sum"];
}

// Count the number of products sold
$product_count = 0;
$sql_count = "SELECT COUNT(*) AS product_count FROM sold_products";
$result_count = $conn->query($sql_count);

if ($result_count->num_rows > 0) {
    $row_count = $result_count->fetch_assoc();
    $product_count = $row_count["product_count"];
}

// Handle form submission for adding new sold product
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
        
        // Recalculate the total sold amount after inserting the new record
        $sql_total = "SELECT SUM(total_amount) AS total_amount_sum FROM sold_products";
        $result_total = $conn->query($sql_total);

        if ($result_total->num_rows > 0) {
            $row = $result_total->fetch_assoc();
            $total_sold_amount = $row["total_amount_sum"];
        }
        
        // Recalculate the product count after inserting the new record
        $sql_count = "SELECT COUNT(*) AS product_count FROM sold_products";
        $result_count = $conn->query($sql_count);

        if ($result_count->num_rows > 0) {
            $row_count = $result_count->fetch_assoc();
            $product_count = $row_count["product_count"];
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<?php
require 'connection.php'; 


$totalProductsQuery = "SELECT SUM(product_weight * product_price) AS total_products_amount FROM products";
$totalProductsResult = mysqli_query($conn, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['total_products_amount'];

$totalSoldQuery = "SELECT SUM(total_amount) AS total_sold_amount FROM sold_products";
$totalSoldResult = mysqli_query($conn, $totalSoldQuery);
$totalSold = mysqli_fetch_assoc($totalSoldResult)['total_sold_amount'];

// Calculate the difference 
$difference = $totalProducts - $totalSold;

$conn->close();
?>

// for Piechart
<?php
require 'connection.php';

//calculate total amount of products
$totalProductsQuery = "SELECT SUM(product_weight * product_price) AS total_products_amount FROM products";
$totalProductsResult = mysqli_query($conn, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['total_products_amount'];

// calculate total amount from sold products
$totalSoldQuery = "SELECT SUM(total_amount) AS total_sold_amount FROM sold_products";
$totalSoldResult = mysqli_query($conn, $totalSoldQuery);
$totalSold = mysqli_fetch_assoc($totalSoldResult)['total_sold_amount'];

// Calculate percentages
$total = $totalProducts;
$percentageSold = ($totalSold / $total) * 100;
$percentageRemaining = ($totalProducts - $totalSold) / $total * 100;

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
        <title>RC Fish Market - Sales Report</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForAdministrator.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

<style>
#Sales{
    background-color: #2F353B;
}
#Product{
    background-color: none;
}
.spacing{
     margin-bottom: 200px;
}

/**DASHBOARD CONTENT */
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
    margin-top:200px;
    margin-left:4.4%;;
}
.totalrevenue{
    position:absolute;
    background-color:  #dcdcdc ;
    width:49%;
    height:150px;
     font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:#333333;
    border-radius:5px;
}
.totalrevenue h3{
    font-size:30px;
    font-family: Haettenschweiler;
    padding-top:5px;
    color:#333333;
}
.total2{
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
    margin-top:-160px;
}
.total2 h3{
    font-size:30px;
    font-family: Haettenschweiler;
    padding-top:5px;
}
.dashthird input{
    background-color:transparent;
    border:none;
    color:#333333;
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
        padding-top:20px;
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
#fishChart{
    margin-top:100px;
}
.revchart{
    margin-top:200px;
}
#forprinting{
    width:30px;
    height:28px;
    position:absolute;
    margin-left:79%;
    cursor: pointer;    
    border:2px solid #333333 ;
    padding:5px;
    border-radius:5px;


}
@media print {
    #forprinting {
    display: none !important; /* Hide the print button */
    }
}
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a id="RCFM" class="navbar-brand ps-3" href="Home.php">              
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
                            <a style="padding-left:17px;" class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                Dashboard
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="CashierDash1" class="nav-link" href="#">Quantity</a>
                                    <a id="CashierDash" class="nav-link" href="ForAdministrator2.php">Weight</a>
                                </nav>
                            </div>
                            
                            <a id="Product2" class="nav-link" href="AdProduct2.php">
                                <div class="sb-nav-link-icon"></i></div>
                                Products
                            </a>
                            <a id="Transaction" class="nav-link" href="AdTransaction.php">
                                <div class="sb-nav-link-icon"></i></div>
                                Transactions 
                            </a>







                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a id="Product" class="nav-link" href="AdProduct.php">
                                <div class="sb-nav-link-icon"></i></div>
                                Inventory
                            </a>
                            <a id="Sales" class="nav-link" href="Sales.php">
                                <div class="sb-nav-link-icon"></i></div>
                                Sales
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
                <main style="margin-top:-20px;">
                    <div class="container-fluid px-4">

                        <h1 style="font-family: Haettenschweiler; font-size:40px; font-weight:200;
                        letter-spacing:2px;" class="mt-4">SALES REPORT</h1>
                        <ol class="breadcrumb mb-4">
                        </ol>
                        
                        
                    </div>   
                    <div class="forprint">
                        <img id="forprinting" src="../Img/print.png" alt="Print" onclick="window.print()">
                    </div>

                    <div class="dashboardcont">
                        <div class="dashthird">
                            <div class="totalrevenue">
                                <h3>TOTAL REVENUE</h3>
                                <input style="padding-right:20px;" type="text" value="<?php echo $formattedRevenue; ?>" readonly >
                                <h3 id="pesosign">php</h3>
                            </div>
                            <div class="total2">
                                <h3>TOTAL SALES</h3>
                                <input style="padding-right:20px; color:whitesmoke;" type="text" value="<?php echo number_format($total_sold_amount, 2); ?>" readonly>
                                <h3 id="pesosign">php</h3>
                            </div>

                            <div style="margin-left:50%; margin-top:-160px; width:20%;" class="totalrevenue">
                                <h3>SOLD PRODUCTS</h3>
                                <input style="width:90%; text-align:center;" type="text" value="<?php echo $product_count; ?>" readonly>
                            </div>

                            <div style="margin-left:50%; width:49.5%;" class="totalrevenue">
                                <h3>REMAINING REVENUE</h3>
                                <input type="text" value="<?php echo number_format( $difference, 2);  ?>" readonly>
                                <h3 id="pesosign">php</h3>
                            </div>
                           
                            
                            <div style="margin-left:71%;margin-top:-160px; width:28.5%;" class="totalrevenue">
                                <div style="padding-left:-50px;margin-left:-80px;margin-top:-20px; margin-right:0px; bacground-color:transparent;" id="piechart" style="width: 300%; height: 300px;"></div>
                            </div>
                            <div class="revchart">
                                <canvas id="revenueChart" width="400" height="300"></canvas>
                            </div>

                            <canvas id="fishChart" width="200" height="120"></canvas>
                        </div>

                        

                    </div> 

                </main>
                
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
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Category', 'Percentage'],
                        ['Sold', <?php echo $percentageSold; ?>],
                        ['Remaining', <?php echo $percentageRemaining; ?>]
                    ]);

                    var options = {
                        pieSliceText: 'percentage',
                        slices: {
                            0: { color: '#1e3163' },
                            1: { color: '#333333' }
                        },
                        backgroundColor: 'transparent',
                        pieSliceTextStyle: {
                            fontSize: 14
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            </script>
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
            var ctx = document.getElementById('fishChart').getContext('2d');

            // Create the chart
            var fishChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Tuna',
                        data: [<?php echo $tunaCount; ?>],
                        backgroundColor: '#5A8E49', // Tuna
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Mackerel',
                        data: [<?php echo $mackerelCount; ?>],
                        backgroundColor: '#1F3B4D', // Mackerel
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Sardines',
                        data: [<?php echo $sardinesCount; ?>],
                        backgroundColor: '#9B301C', // Sardines
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Salmon',
                        data: [<?php echo $salmonCount; ?>],
                        backgroundColor: '#E59D73', // Salmon
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Cod',
                        data: [<?php echo $codCount; ?>],
                        backgroundColor: '#2B3A67', // Cod
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Premium',
                        data: [<?php echo $premiumCount; ?>],
                        backgroundColor: '#FFA500', // Premium
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }, {
                        label: 'Standard',
                        data: [<?php echo $standardCount; ?>],
                        backgroundColor: '#32CD32', // Standard
                        borderColor: 'rgba(0, 0, 0, 1)', // Border color for all bars
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <script>
        // Data for the chart
        var revenueData = {
            labels: ['Total Products', 'Total Sold', 'Difference'],
            datasets: [{
                label: 'Revenue Sales Report',
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: '#1e3163',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(52, 152, 219, 5)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(52, 152, 219, 1)',
                data: [<?php echo $totalProducts; ?>, <?php echo $totalSold; ?>, <?php echo $difference; ?>]
            }]
        };

        // Configuration options for the chart
        var revenueOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        // Get the canvas element
        var revenueCanvas = document.getElementById('revenueChart').getContext('2d');

        // Create the chart using Chart.js
        var revenueChart = new Chart(revenueCanvas, {
            type: 'line', // Specify the chart type (line)
            data: revenueData,
            options: revenueOptions
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
