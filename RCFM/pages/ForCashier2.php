<?php
require 'connection.php';

// Query to calculate total weight
$totalWeightQuery = "SELECT SUM(product_weight) AS total_weight FROM products";
$totalWeightResult = mysqli_query($conn, $totalWeightQuery);

if ($totalWeightResult) {
    $totalWeightRow = mysqli_fetch_assoc($totalWeightResult);
    $totalWeight = number_format($totalWeightRow['total_weight'], 2, '.', ''); // Format without decimal places
} else {
    // Error handling if query fails
    $totalWeight = 0;
}

$premiumWeightQuery = "SELECT SUM(product_weight) AS premium_weight FROM products WHERE product_grade = 'PRE'";
$premiumWeightResult = mysqli_query($conn, $premiumWeightQuery);
$premiumWeightRow = mysqli_fetch_assoc($premiumWeightResult);
$premiumWeight = number_format($premiumWeightRow['premium_weight'], 2, '.', ''); // Format without decimal places

// Calculate total weight of standard fish products
$standardWeightQuery = "SELECT SUM(product_weight) AS standard_weight FROM products WHERE product_grade = 'STA'";
$standardWeightResult = mysqli_query($conn, $standardWeightQuery);
$standardWeightRow = mysqli_fetch_assoc($standardWeightResult);
$standardWeight = number_format($standardWeightRow['standard_weight'], 2, '.', ''); // Format without decimal places
// Query to calculate each fish type weight
$tunaWeightQuery = "SELECT SUM(product_weight) AS tuna_weight FROM products WHERE product_type = 'Tuna'";
$tunaWeightResult = mysqli_query($conn, $tunaWeightQuery);
$tunaWeight = intval(mysqli_fetch_assoc($tunaWeightResult)['tuna_weight']);

$mackerelWeightQuery = "SELECT SUM(product_weight) AS mackerel_weight FROM products WHERE product_type = 'Mackerel'";
$mackerelWeightResult = mysqli_query($conn, $mackerelWeightQuery);
$mackerelWeight = intval(mysqli_fetch_assoc($mackerelWeightResult)['mackerel_weight']);

$sardinesWeightQuery = "SELECT SUM(product_weight) AS sardines_weight FROM products WHERE product_type = 'Sardines'";
$sardinesWeightResult = mysqli_query($conn, $sardinesWeightQuery);
$sardinesWeight = intval(mysqli_fetch_assoc($sardinesWeightResult)['sardines_weight']);

$salmonWeightQuery = "SELECT SUM(product_weight) AS salmon_weight FROM products WHERE product_type = 'Salmon'";
$salmonWeightResult = mysqli_query($conn, $salmonWeightQuery);
$salmonWeight = intval(mysqli_fetch_assoc($salmonWeightResult)['salmon_weight']);

$codWeightQuery = "SELECT SUM(product_weight) AS cod_weight FROM products WHERE product_type = 'Cod'";
$codWeightResult = mysqli_query($conn, $codWeightQuery);
$codWeight = intval(mysqli_fetch_assoc($codWeightResult)['cod_weight']);

// Query to calculate total number of fish products
$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM products";
$totalProductsResult = mysqli_query($conn, $totalProductsQuery);
$totalProductsRow = mysqli_fetch_assoc($totalProductsResult);
$totalProducts = $totalProductsRow['total_products'];

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
        <link rel="stylesheet" href="../css/ForAdministrator.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

<style>
#Dashboard{
    background-color: #2F353B;
}
#CashierDash{
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
    height:100px;
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
    height:100px;
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
    height:100px;
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
    height:100px;
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
    height:100px;
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
    margin-top:-15px;
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
    margin-top:380px;
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
    background-color:  #808080;
    width:49.5%;
    height:150px;
     font-family: Haettenschweiler;
    font-size: 30px;
    letter-spacing: 2px;
    padding-left: 20px;
    color:whitesmoke;
    margin-left:50%;
    border-radius:5px;
}
.total2 h3{
    font-size:30px;
    font-family: Haettenschweiler;
    padding-top:5px;
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
    margin-top:-2px;
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
                        ADMINISTRATOR
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
                                        <input type="number" value="<?php echo $totalWeight; ?>" readonly>
                                        <img id="fishdash1" src="../Img/fishdash.png" alt="">
                                        <ol class="oltag2">kg</ol>
                                    </div>
                                        <div class="premiumprod">
                                            <h3>PREMIUM</h3>
                                            <input type="number" value="<?php echo $premiumWeight; ?>" readonly>
                                            <img id="fishdash1" src="../Img/fishdash.png" alt="">
                                            <ol class="oltag2">kg</ol>
                                        </div>
                                        <div class="standardprod">
                                            <h3>STANDARD</h3>
                                            <input type="number" value="<?php echo $standardWeight; ?>" readonly>
                                            <img id="fishdash1" src="../Img/fishdash.png" alt="">
                                            <ol class="oltag2">kg</ol>
                                        </div>
                                </div>
                            </div>

                            <!-- Second section -->
                            <div class="dashsecond">
                                <div class="tuna">
                                    <h3>TUNA</h3>
                                    <input type="number" value="<?php echo $tunaWeight; ?>" readonly>
                                    <ol class="oltag1">kg</ol>
                                </div>
                                <div class="mackerel">
                                    <h3>MACKEREL</h3>
                                    <input type="number" value="<?php echo $mackerelWeight; ?>" readonly>
                                    <ol class="oltag1">kg</ol>
                                </div>
                                <div class="sardines">
                                    <h3>SARDINES</h3>
                                    <input type="number" value="<?php echo $sardinesWeight; ?>" readonly>
                                    <ol class="oltag1">kg</ol>
                                </div>
                                <div class="salmon">
                                    <h3>SALMON</h3>
                                    <input type="number" value="<?php echo $salmonWeight; ?>" readonly>
                                    <ol class="oltag1">kg</ol>
                                </div>
                                <div class="cod">
                                    <h3>COD</h3>
                                    <input type="number" value="<?php echo $codWeight; ?>" readonly>
                                    <ol class="oltag1">kg</ol>
                                </div>
                            </div>

                            <!-- Third section -->
                            <div class="dashthird">
                                <canvas id="fishChart" width="200" height="120"></canvas>
                            </div>

                    </div> 
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
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
    </body>
</html>
