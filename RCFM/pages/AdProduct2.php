<?php
require 'connection.php';

if (isset($_POST["addProduct"])) {
    $productID = $_POST["productID"];
    $productName = $_POST["productName"];
    $productType = $_POST["productType"];
    $productGrade = $_POST["productGrade"];
    $productWeight = $_POST["productWeight"];
    $productPrice = $_POST["productPrice"];

    // Calculate expiration date
    $expirationDays = ($productGrade === "STA") ? 7 : 10;
    $expirationDate = date('Y-m-d', strtotime("+$expirationDays days")); 

    $query = "INSERT INTO products (id, product_name, product_type, product_grade, product_weight, product_price, expiration_date) 
              VALUES ('$productID','$productName', '$productType', '$productGrade', '$productWeight', '$productPrice', '$expirationDate')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('New product added');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<?php
function getProductBgColor($productType) {
    switch ($productType) {
        case 'Tuna':
            return '#5A8E49'; // Green
        case 'Mackerel':
            return '#1F3B4D'; // Blue
        case 'Sardines':
            return '#9B301C'; // Red
        case 'Salmon':
            return '#E59D73'; // Light Red
        case 'Cod':
            return '#2B3A67'; // Dark Blue
        case 'Premium':
            return '#FFA500'; // Orange
        case 'Standard':
            return '#32CD32'; // Lime Green
        default:
            return '#000000'; // Default black
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
        <title>RC Fish Market - List of Products</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForAdministrator.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

<style>
#Product2{
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


#listofprod1{
    border:1px solid black;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Adjust alignment as needed */
}

.product {
    width: calc(110% - 25px);
    border-radius: 10px;
    transition: transform 0.3s ease-in-out; /* Smooth transition */
}

.product:hover {
    transform: scale(1.1); /* Zoom effect */
}

.product p {
    margin: 0;
    color:whitesmoke;
    margin-bottom:-5px;
    padding-left:5px;
    padding-right:10px;
}

.product-name {
    font-weight: bold;
    font-size:20px;
    letter-spacing:1px;
}

.product-price {
    color: white;
}
.fishdashinside{
    margin-top:12px;
    margin-left:63px;  
    margin-bottom:15px;
    width:75px;  
    height:80px;
}
.BOX{
    margin-left:8px;
    width: 18%;
    height:400px;

}

.product-type,.product-grade,.product-weight,.expiration-date{
    font-size:15px;
    padding-bottom:5px;
    color:lightgray;
}
.product-price{
    color:whitesmoke;
    font-size:15px;
    font-weight: bold;
    margin-top:10px;
    padding-top:10px;
}
.showchoices{
    position:absolute;
    margin-top:-55px;
    margin-left:64%;
    border:none;
}
.showchoices select{
    padding-left:10px;
    width:150px;
    font-size:15px;
    border:none;
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
                                    <a id="CashierDash1" class="nav-link" href="ForAdministrator.php">Quantity</a>
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
            <main style="margin-left:5%;">
    <div class="container-fluid px-4">
        <h1 style="font-family: Haettenschweiler; font-size:40px; font-weight:200; letter-spacing:2px;" class="mt-4">LIST OF PRODUCTS</h1>
        <hr style="width:93%;"><br>

        <div class="showchoices">
            <form method="POST">
                <select name="showDiv" id="showDiv" onchange="this.form.submit()">
                    <option value="Available" <?php if(isset($_POST['showDiv']) && $_POST['showDiv'] == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="OutofStock" <?php if(isset($_POST['showDiv']) && $_POST['showDiv'] == 'OutofStock') echo 'selected'; ?>>Out of Stock</option>
                    <option value="Not Available" <?php if(isset($_POST['showDiv']) && $_POST['showDiv'] == 'Not Available') echo 'selected'; ?>>Not Available</option>
                </select>
            </form>
        </div>

        <ol class="breadcrumb mb-4" id="productList">
            <!-- Here goes the PHP code to display products -->
            <?php
            require 'connection.php';

            // Function to display available products
            function displayAvailableProducts($conn) {
                $productResult = mysqli_query($conn, "SELECT * FROM products");
                if ($productResult && mysqli_num_rows($productResult) > 0) {
                    while ($row = mysqli_fetch_assoc($productResult)) {
                        $bgColor = getProductBgColor($row['product_type']); // Make sure this function is defined
                        echo "<li class='BOX'>";
                        echo "<div class='product' style='background-color: $bgColor; padding: 10px; margin-bottom: 10px;'>";
                        echo "<img class='fishdashinside' src='../Img/fishdash.png' alt='Fish' width='100' height='100'>";
                        echo "<p class='product-name'>" . $row['product_name'] . "</p>";
                        echo "<p class='product-type'>Type: " . $row['product_type'] . "</p>";
                        echo "<p class='product-grade'>Quality: " . $row['product_grade'] . "</p>";
                        echo "<p class='expiration-date'>Exp Date: " . $row['expiration_date'] . "</p>";
                        echo "<p style='font-weight:bold;'class='product-weight'>Available Stock(kg): " . $row['product_weight'] . " kg</p>";
                        echo "<p class='product-price'>Price: " . number_format($row['product_price'], 2, '.', ',') . " php/kg</p><br>";
                        echo "</div>"; // Close the product div
                        echo "</li>";
                    }
                } else {
                    echo "<li>No products found.</li>"; // Add a list item here
                }
            }

            // Function to display out of stock products
            function displayOutOfStockProducts($conn) {
                $productResult = mysqli_query($conn, "SELECT * FROM outofstock_products");
                if ($productResult && mysqli_num_rows($productResult) > 0) {
                    while ($row = mysqli_fetch_assoc($productResult)) {
                        $bgColor = getProductBgColor($row['product_type']); // Make sure this function is defined
                        echo "<li class='BOX'>";
                        echo "<div class='product' style='background-color: $bgColor; padding: 10px; margin-bottom: 10px;'>";
                        echo "<img class='fishdashinside' src='../Img/fishdash.png' alt='Fish' width='100' height='100'>";
                        echo "<p class='product-name'>" . $row['product_name'] . "</p>";
                        echo "<p class='product-type'>Type: " . $row['product_type'] . "</p>";
                        echo "<p class='product-grade'>Quality: " . $row['product_grade'] . "</p>";
                        echo "<p class='expiration-date'>Exp Date: " . $row['expiration_date'] . "</p>";
                        echo "<p style='font-weight:bold;'class='product-weight'>Available Stock(kg): " . $row['product_weight'] . " kg</p>";
                        echo "<p style='margin-left:18%;'class='product-price'>OUT OF STOCK</p><br>";
                        echo "</div>"; // Close the product div
                        echo "</li>";
                    }
                } else {
                    echo "<li>No out of stock products found.</li>"; // Add a list item here
                }
            }

            // Function to display deleted products (assuming you have a table for deleted products)
            function displayDeletedProducts($conn) {
                $productResult = mysqli_query($conn, "SELECT * FROM expired_products"); // Replace with your table name
                if ($productResult && mysqli_num_rows($productResult) > 0) {
                    while ($row = mysqli_fetch_assoc($productResult)) {
                        $bgColor = getProductBgColor($row['product_type']); // Make sure this function is defined
                        echo "<li class='BOX' style='filter: blur(1px);'>";
                        echo "<div class='product' style='background-color: $bgColor; padding: 10px; margin-bottom: 10px;'>";
                        echo "<img class='fishdashinside' src='../Img/fishdash.png' alt='Fish' width='100' height='100'>";
                        echo "<p class='product-name'>" . $row['product_name'] . "</p>";
                        echo "<p class='product-type'>Type: " . $row['product_type'] . "</p>";
                        echo "<p class='product-grade'>Quality: " . $row['product_grade'] . "</p>";
                        echo "<p class='product-weight'>Weight: " . $row['product_weight'] . " kg</p>";
                        echo "<p class='expiration-date'>Exp Date: " . $row['expiration_date'] . "</p>";
                        echo "<p style='margin-left:30px;' class='product-price'>NOT AVAILABLE</p><br>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>No deleted products found.</li>"; // Add a list item here
                }
            }

            // Display products based on selection
            if (isset($_POST['showDiv'])) {
                $selection = $_POST['showDiv'];
                if ($selection == "Available") {
                    displayAvailableProducts($conn);
                } elseif ($selection == "OutofStock") {
                    displayOutOfStockProducts($conn);
                } elseif ($selection == "Not Available") {
                    displayDeletedProducts($conn);
                }
            } else {
                // Default display
                displayAvailableProducts($conn);
            }
            ?>
            <!-- End of PHP code for displaying products -->
        </ol> 
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
    function toggleProductList() {
        var showDiv = document.getElementById("showDiv").value;
        var availableProducts = document.getElementById("availableProducts");
        var deletedProducts = document.getElementById("deletedProducts");

        if (showDiv === "Available") {
            availableProducts.style.display = "block";
            deletedProducts.style.display = "none";
        } else if (showDiv === "Not Available") {
            availableProducts.style.display = "none";
            deletedProducts.style.display = "block";
        }
    }
</script>
<script>
    function Logout(){
    window.location.href = "../index.php";
    exit();
    }
</script>
    </body>
</html>
