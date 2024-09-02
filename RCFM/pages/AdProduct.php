<?php
require 'connection.php';

if (isset($_POST["addProduct"])) {
    $productID = $_POST["productID"];
    $productName = $_POST["productName"];
    $productType = $_POST["productType"];
    $productGrade = $_POST["productGrade"];
    $productWeight = $_POST["productWeight"];
    $productPrice = $_POST["productPrice"];

    // Calculate expiration date based on product grade
    $expirationDays = ($productGrade === "STA") ? 7 : 10;
    $expirationDate = date('Y-m-d', strtotime("+$expirationDays days"));

    $query = "INSERT INTO products (id, product_name, product_type, product_grade, product_weight, product_price, expiration_date) 
              VALUES ('$productID', '$productName', '$productType', '$productGrade', '$productWeight', '$productPrice', '$expirationDate')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('New product added');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

// Check if delete button is clicked
if(isset($_POST["deleteProduct"])) {
    $productId = $_POST["productId"];
    
    // Retrieve the product data before deleting
    $retrieveQuery = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $retrieveQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Insert the deleted product data into the deleted_products table
        $insertQuery = "INSERT INTO deletedproducts (product_name, product_type, product_grade, product_weight, expiration_date, product_price) 
                        VALUES ('" . $row['product_name'] . "', '" . $row['product_type'] . "', '" . $row['product_grade'] . "', 
                                '" . $row['product_weight'] . "', '" . $row['expiration_date'] . "', '" . $row['product_price'] . "')";

        if(mysqli_query($conn, $insertQuery)) {
            // Now delete the product from the main table
            $deleteQuery = "DELETE FROM products WHERE id = $productId";
            
            if(mysqli_query($conn, $deleteQuery)) {
                echo "<script>alert('Product deleted');</script>";
            } else {
                echo "<script>alert('Error deleting product: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error storing deleted product: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Product not found');</script>";
    }
}



// Modify the product query to include ORDER BY product_type ASC
$productQuery = "SELECT * FROM products ORDER BY product_type ASC";
$productResult = mysqli_query($conn, $productQuery);


$zeroWeightQuery = "SELECT * FROM products WHERE product_weight = 0";
$zeroWeightResult = mysqli_query($conn, $zeroWeightQuery);

if ($zeroWeightResult && mysqli_num_rows($zeroWeightResult) > 0) {
    while ($row = mysqli_fetch_assoc($zeroWeightResult)) {
        // Insert the product data into the deleted_products table
        $insertQuery = "INSERT INTO outofstock_products (product_name, product_type, product_grade, product_weight, expiration_date, product_price) 
                        VALUES ('" . $row['product_name'] . "', '" . $row['product_type'] . "', '" . $row['product_grade'] . "', 
                                '" . $row['product_weight'] . "', '" . $row['expiration_date'] . "', '" . $row['product_price'] . "')";

        if (mysqli_query($conn, $insertQuery)) {
            // Now delete the product from the main table
            $deleteQuery = "DELETE FROM products WHERE id = " . $row['id'];

            if (mysqli_query($conn, $deleteQuery)) {
                echo "<script>alert('Product with 0 weight deleted');</script>";
            } else {
                echo "<script>alert('Error deleting product with 0 weight: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error storing deleted product with 0 weight: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>
<?php
if (isset($_POST["editProduct"])) {
    // Retrieve form data
    $productId = $_POST["productId"];
    $productName = $_POST["editProductName"];
    $productType = $_POST["editProductType"];
    $productGrade = $_POST["editProductGrade"];
    $productWeight = $_POST["editProductWeight"]; // Ensure the field name matches
    $productPrice = $_POST["editProductPrice"];

    // Update the database with the new values using prepared statements
    $query = "UPDATE products SET product_name=?, product_type=?, product_grade=?, product_weight=?, product_price=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssddi", $productName, $productType, $productGrade, $productWeight, $productPrice, $productId);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch and display products
$productResult = $conn->query("SELECT * FROM products");
?>

<?php
require 'connection.php';

// Disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

// Check for expired products
$checkExpiredQuery = "SELECT * FROM products WHERE expiration_date < CURDATE()";
$expiredResult = mysqli_query($conn, $checkExpiredQuery);

if ($expiredResult && mysqli_num_rows($expiredResult) > 0) {
    while ($row = mysqli_fetch_assoc($expiredResult)) {
        // Insert the expired product data into the expired_products table
        $insertExpiredQuery = "INSERT INTO expired_products (product_id, product_name, product_type, product_grade, product_weight, product_price, expiration_date, expired_on) 
                               VALUES ('" . $row['id'] . "', '" . $row['product_name'] . "', '" . $row['product_type'] . "', '" . $row['product_grade'] . "', 
                                       '" . $row['product_weight'] . "', '" . $row['product_price'] . "', '" . $row['expiration_date'] . "', CURDATE())";

        if (mysqli_query($conn, $insertExpiredQuery)) {
            // Now delete the product from the main table
            $deleteExpiredQuery = "DELETE FROM products WHERE id = " . $row['id'];

            if (mysqli_query($conn, $deleteExpiredQuery)) {
                echo "<script>alert('Expired product deleted.');</script>";
            } else {
                echo "<script>alert('Error deleting expired product: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error storing expired product: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
?>






<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>RC Fish Market - Inventory</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForAdministrator.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
    /* Adjustments for editing modal */
    #myModalEdit {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
    }

    #myModalEdit .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 24%;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        animation-name: animatetop;
        animation-duration: 0.4s;
    }

    #myModalEdit input,
    #myModalEdit select {
        width: 90%;
        height: 38px;
        margin-top: 10px;
        padding-left: 10px;
        font-size:15px;
    }

    #myModalEdit input[type="number"] {
        width: 50%; /* Adjust as needed */
        margin-right: 2%; /* Adjust as needed */
        font-size:15px;
    }

    #myModalEdit button {
        width: 100%;
        margin-top: 20px;
        background-color: #212529;
        color: whitesmoke;
        border: none;
        border-radius: 2px;
        height: 38px;
        font-size: 15px;
        cursor: pointer;
    }

    #myModalEdit button:hover {
        background-color: #2F353B;
    }

    #myModalEdit h5 {
        width: 250px;
        margin-left: 10px;
        margin-top: -10px;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    #myModalEdit span {
        font-size: 16px;
        margin-left: -15px;
    }
</style>
        <style>
        #Product{
            background-color: #2F353B;
        }
        #Dashboard{
            background-color: none;
        }
        #productType{
            width:305px;
            height:38px;
            margin-top:10px;
            margin-left:2.5%;
            padding-left:9px;
        
        }
        .AddNew input, select{
            width:305px;
            height:40px;
            margin-top:10px;
            margin-left:2.5%;
            padding-left:10px;
            font-size:17px;
        }
        .AddNew span{
            font-size:18px;
            margin-left:-15px;
        }
        .addproductbtn1 {
            position: absolute; /* Change to relative positioning */
            left: 82%; /* Set left position */
            width: 17%;
            height: 28px;
            font-size: 15px;
            border-radius: 3px;
            background-color: #212529;
            color: whitesmoke;
            padding-left: 20px;
            border: none;
            margin-top:0px;
        }

        .addproductbtn1 h3 {
            padding-right:5px;
            margin-left: 12px;
            margin-top: -3px;
            position: absolute;
        }
        .addproductbtn1:hover {
            background-color: #2F353B;
            color: whitesmoke;
            border:1px whitesmoke solid;
        }
        .addproductbtn{
            position:absolute;
            right:0px;
            width:320px;
            height:35px;
            font-size:15px;
            top:-20px;
            margin-left:3%;
            border-radius:2px;
            background-color:#212529;
            color:whitesmoke;
        }
        .addproductbtn:hover {
            background-color: #2F353B;
            color: whitesmoke;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 24%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            animation-name: animatetop;
            animation-duration: 0.4s;
        }
        #datatablesSimple{
            width:100%;
        }

        #datatablesSimple thead th {
            background-color: none;
            border:solid 1px gray;
            padding-right:50px;
            padding-left:10px;
            padding-top:10px;
        }
        #datatablesSimple tbody tr:nth-child(even) {
        background-color: #f2f2f2;
        }
        #datatablesSimple tbody tr:hover {
        background-color: #d4edda;
        }
        #datatablesSimple tbody .action-btn {
        padding: 6px 12px;
        border: none;
        background-color: #dc3545;
        color: #fff;
        cursor: pointer;
        }
        #datatablesSimple tbody .action-btn:hover {
        background-color: #c82333;
        }
        #productGrade{
            position:absolute;
            width:150px;
            height:27px;
            margin-left:-150px;
            margin-top:47px;
            
        }
        #productWeight{
            width:150px;
        }
        #addprodimg{
            position:absolute;

        }

        .fortotal {
            position: absolute;
            top: 0;
            right: 0;
            margin-top: 30px; /* Adjust as needed */
            margin-right: 80px; /* Adjust as needed */
            font-size:30px;
            font-weight:bold;
        }
        #totalAmount{
            background-color:transparent;
            font-weight:bold;
            border:none;
            width:150px;

        }
        .mt-4{
            font-family: Haettenschweiler;
            font-size:40px;
            letter-spacing:2px;
        }
        #navbarDropdown{
            padding-top:17px;
        }
        #productFilter{
            position:absolute;
            font-size:16px;
            height:28px;
            letter-spacing:1px;
            border-radius:3px;
            margin-left:25%;
            border:1px solid lightgray;
            padding-left:10px;
            width: 9%;
        }
        #productFilter2{
            position:absolute;
            font-size:16px;
            height:28px;
            letter-spacing:1px;
            border-radius:3px;
            margin-left:38.5%;
            border:1px solid lightgray;
            padding-left:10px;
            width: 9%;
        }
        #forsearch{
            position:absolute;
        }
        #searchInput{
            margin-left:0%;
            height:26px;
            padding-left:10px;
            font-size:15px;
            letter-spacing:1px;
            border-radius:3px;
            border:1px solid lightgray;
        }
        .displayproductstr{
            height:25px;
            font-size:14px;
            margin:auto;
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
        #pagination{
            margin-top:0px;
            position:absolute;
            margin-left:37%;
            height:200px;
        }
        #pagination a{
            font-size:16px;
            border:none;
            background-color:transparent;
            text-decoration:none;

        }
        #datatablesSimple{
            font-size:14px;
            
        }
        #datatablesSimple tr{
            padding-top:20px;
            padding-right:20px;
        }
        .datatable-input{
            width:250px;
        }
        #deletebtn:hover{
            border-bottom:2px solid blue;
        }
        #editbtn:hover{
            border-bottom:2px solid blue;
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
                <li  class="nav-item dropdown">
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
                <main>
                    
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">INVENTORY</h1>
                        <ol class="breadcrumb mb-4">
                        </ol>

                <!--
                        <div class="fortotal" >
    
                        <p>Total: <input type="number" id="totalAmount" readonly></p>

                        </div> -->


                        <div style="margin-bottom:20px;" class="spacing"></div>
                       
                        <div style="margin-bottom:50px;" class="card mb-4">
                            <div class="card-header" style="width:100%; height:40px;">
                           
                                <button class="addproductbtn1" onclick="openModal()"><h3 style="margin-left:17px; margin-top:-3px;position:absolute;">+</h3>Add Product</button>
                                
                            
                                

                            </div>
                            <div class="card-body" style="height:600px;width:100%;">
                    <!-- Button to open the modal -->
                    
                    <form method="post">
                    <!-- The Modal -->
                    <div id="myModal" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span onclick="closeModal()" style="margin-left:320px; margin-top:-20px; font-size:22px; color:gray; float: right; cursor: pointer;">&times;</span>
                            <div class="AddNew">
                                <h5 style="width:250px; margin-left:10px; margin-top:-10px; margin-bottom:20px; letter-spacing:1px;">ADD NEW PRODUCT</h5>
                                <input type="text" name="productName" id="productName" placeholder="Product Name">
                                <select name="productType" id="productType">
                                    <option style="color:lightgray;" value="" selected disabled>Type</option>
                                    <option value="Tuna">Tuna</option>
                                    <option value="Mackerel">Mackerel</option>
                                    <option value="Salmon">Salmon</option>
                                    <option value="Sardines">Sardines</option>
                                    <option value="Cod">Cod</option>
                                    <!-- Add more options as needed -->
                                </select>
                                <select style="padding-left:10px; height:38px; margin-top:58px;" name="productGrade" id="productGrade" onchange="changeColor(this)">
                                    <option value="" selected style="color: lightgray; ">Grade</option>
                                    <option value="STA">Standard</option>
                                    <option value="PRE">Premium</option>
                                </select>
                                <input type="number" name="productWeight" id="productWeight" placeholder="Stock | kg">
                                <input type="number" name="productPrice" id="productPrice" placeholder="Price">

                                <br><br><br><br><br>
                                <button style="top:-25px; width:300px; left:20px; " 
                                class="addproductbtn" type="submit" name="addProduct" id="addproduct" onclick="addProduct()">Add Product</button>

                            </div>
                        </div>
                    </div>
                    </form>
                    <form method="post">
                        <!-- The Modal -->
                        <div id="myModalEdit" class="modal2" style="display: none;">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <span onclick="closeModal()" style="margin-left:320px; margin-top:-20px; font-size:22px; color:gray; float: right; cursor: pointer;">&times;</span>
                                <div class="AddNew">
                                    <h5 style="width:250px; margin-left:10px; margin-top:-10px; margin-bottom:20px; letter-spacing:1px;">EDIT PRODUCT</h5>
                                    <input type="hidden" name="productId" id="editProductId">
                                    <input type="text" name="editProductName" id="editProductName" placeholder="Product Name">
                                    <select style="margin-left:8px;" name="editProductType" id="editProductType">
                                        <option style="color:lightgray;" value="" selected >Type</option>
                                        <option value="Tuna">Tuna</option>
                                        <option value="Mackerel">Mackerel</option>
                                        <option value="Salmon">Salmon</option>
                                        <option value="Sardines">Sardines</option>
                                        <option value="Cod">Cod</option>
                                    </select>
                                    <select style="margin-left:8px; padding-left:10px;" name="editProductGrade" id="editProductGrade">
                                        <option value="" selected style="color: lightgray; ">Grade</option>
                                        <option value="STA">Standard</option>
                                        <option value="PRE">Premium</option>
                                    </select>
                                    <label style="margin-left:10px;" for="">Weight : <input type="number" name="editProductWeight" id="editProductWeight" placeholder="Stock | kg"></label>
                                    <label style="margin-left:10px;" for="">Price : <input style="margin-left:17px;" type="number" name="editProductPrice" id="editProductPrice" placeholder="Price"></label>
                                    <br><br><br><br><br>
                                    <button style="margin-top:350px; width:300px; left:20px; " class="addproductbtn" type="submit" name="editProduct">Edit Product</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table  id="datatablesSimple">
                            <thead style="border-top-left-radius: 5px; border-top-right-radius: 5px; border-radius:5px;">
                                <tr style="height:50px; font-size:13px; ">
                                    <th style="width:10%;">Product Name</th>
                                    <th style="width:10%;">Type</th>
                                    <th style="width:10%;">Quality</th>
                                    <th style="width:10%;">Stock (kg)</th>
                                    <th style="width:10%;">Exp.Date</th>
                                    <th style="width:20%;">Price</th>
                                    <th style="width:20%;">Total</th>
                                    <th style="width:10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productList">
                            <!-- PHP-generated rows will be inserted here -->
                            <?php
                            // PHP code to fetch and display products
                            while ($row = mysqli_fetch_assoc($productResult)) {
                                echo "<tr style='border-left:1px solid lightgray;border-right :1px solid lightgray;border-bottom:1px solid lightgray;'>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:10px;'>" . htmlspecialchars($row['product_name']) . "</td>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:20px;'>" . htmlspecialchars($row['product_type']) . "</td>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:20px;'>" . htmlspecialchars($row['product_grade']) . "</td>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:20px;'>" . htmlspecialchars($row['product_weight']) . " kg</td>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:20px;'>" . htmlspecialchars($row['expiration_date']) . "</td>";
                                echo "<td style='font-size:14px; padding-top:30px; padding-left:20px;'><b>" . number_format($row['product_price'], 2, '.', ',') . "</b> php/kg</td>";
                                echo "<td id='totalprice' style='font-size:14px; padding-left:20px; font-weight:bold ;border-right :1px solid black;'><b>" . number_format($row['product_weight'] * $row['product_price'], 2, '.', ',') . " php</b></td>";
                                echo "<td>";
                                echo "<form method='post' action=''>";
                                echo "<input type='hidden' name='productId' value='" . htmlspecialchars($row['id']) . "'>";
                                echo "<button id='deletebtn' style='margin-top:-10px;border:none; background-color:transparent;width:20px; margin-left:20%;' name='deleteProduct'>";
                                echo "<img src='../Img/trash.png' alt='Delete' style=' margin-left:-70%;width:21px; height:21px;'>";
                                echo "</button>";
                                echo "</form>";
                                echo "<img id='editbtn'style='width:20px;cursor:pointer; position:absolute; margin-top:-21px; margin-left:5%;' src='../Img/file-edit.png' alt='Edit' style='width:24px; height:24px; cursor:pointer;' onclick='openModal2(\"" . htmlspecialchars($row['id']) . "\", \"" . htmlspecialchars($row['product_name']) . "\", \"" . htmlspecialchars($row['product_type']) . "\", \"" . htmlspecialchars($row['product_grade']) . "\", \"" . htmlspecialchars($row['product_weight']) . "\", \"" . htmlspecialchars($row['product_price']) . "\")'>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    

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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.min.js"></script>
        <script>

        </script>
        <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable();
        });
        var modal = document.getElementById("myModal");

        // Open the modal
        let products = [];
            function addProduct() {
                const productName = document.getElementById('productName').value;
                const productType = document.getElementById('productType').value;
                const productGrade = document.getElementById('productGrade').value;
                const productWeight = parseFloat(document.getElementById('productWeight').value);
                const productPrice = parseFloat(document.getElementById('productPrice').value);
                let productExpiration = "";
        
                // Calculate expiration date in JavaScript (for display)
                const today = new Date();
                if (productGrade === "STA") {
                    productExpiration = new Date(today.setDate(today.getDate() + 7)).toISOString().slice(0, 10); 
                } else if (productGrade === "PRE") {
                    productExpiration = new Date(today.setDate(today.getDate() + 10)).toISOString().slice(0, 10); 
                }
                const productTotal = productWeight * productPrice;

                if (productName && productType && productGrade && productWeight && productPrice && productTotal) {
                    const existingIndex = products.findIndex(p => p.name === productName && p.type === productType);
                    if (existingIndex !== -1) {
                        // Update existing product
                        products[existingIndex] = { name: productName, type: productType, grade: productGrade, weight: productWeight, price: productPrice, total: productTotal, expiration: productExpiration };
                    } else {
                        // Add new product
                        const product = { name: productName, type: productType, grade: productGrade, weight: productWeight, price: productPrice, total: productTotal, expiration: productExpiration };
                        products.push(product);
                        document.getElementById('productName').value = '';
                        document.getElementById('productType').value = '';
                        document.getElementById('productGrade').value = '';
                        document.getElementById('productWeight').value = '';
                        document.getElementById('productPrice').value = '';
                        modal.style.display = "none";
                        closeModal();
                    }
                    displayProducts();
                    clearInputs();

                } else {
                    alert('Please enter all product information.');
                }
            }
        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
        function deleteProduct(index) {
            products.splice(index, 1);
            displayProducts();
        }

        function displayProducts() {
            const sortedProducts = products.slice().sort((a, b) => a.type.localeCompare(b.type)); // Sort products by type
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            sortedProducts.forEach((product, index) => {
                productList.innerHTML += `
                    <tr >
                        <td class="displayproductstr" style="padding-left:10px;border-left: 1px solid lightgray; border-right: 1px solid lightgray; border:solid 1px lightgray;">${product.name}</td>
                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid transparent; border-bottom: 1px solid lightgray;">${product.type}</td>
                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid transparent; border-bottom: 1px solid lightgray;">${product.grade}</td>
                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid transparent; border-bottom: 1px solid lightgray;">${product.weight} kg</td>
                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid transparent; border-bottom: 1px solid lightgray;">${product.expiration}</td>

                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid transparent; border-bottom: 1px solid lightgray;">${product.price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} php/kg</td>
                        <td class="displayproductstr" style="font-weight:bold; padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} php</td>
                        <td class="displayproductstr" style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;"><button onclick="deleteProduct(${index})">Delete</button> <button onclick="editProduct(${index})">Edit</button></td>
                    </tr>
                `;
            });

            document.getElementById('totalAmount').value = totalAmount.toFixed(2);
        }

        function displayFilteredProducts(filteredProducts) {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';
            filteredProducts.forEach((product, index) => {
                productList.innerHTML += `
                    <tr class="displayproductstr">
                        <td style="padding-left:10px;border-left: 1px solid lightgray; border-right: 1px solid lightgray; border:solid 1px lightgray;">${product.name}</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.type}</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.grade}</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.weight} kg</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.expiration}</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} php</td>
                        <td style="font-weight:bold; padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;">${product.total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} php</td>
                        <td style="padding-left:10px;border-right: 1px solid lightgray; border-bottom: 1px solid lightgray;"><button onclick="deleteProduct(${index})">Delete</button> <button onclick="editProduct(${index})">Edit</button></td>
                    </tr>
                `;
            });
        }

        function clearInputs() {
            document.getElementById('productName').value = '';
            document.getElementById('productType').value = '';
            document.getElementById('productGrade').value = '';
            document.getElementById('productWeight').value = '';
            document.getElementById('productPrice').value = '';
            modal.style.display = "none";

        }

        function editProduct(index) {
        const product = products[index];

        // Ask user if they want to edit the product
        if (confirm('Do you want to edit this product?')) {
            // If user confirms, populate fields and open modal
            document.getElementById('productName').value = product.name;
            document.getElementById('productType').value = product.type;
            document.getElementById('productName').disabled = true;
            document.getElementById('productType').disabled = true;
            document.getElementById('productGrade').value = product.grade;
            document.getElementById('productWeight').value = product.weight;
            document.getElementById('productPrice').value = product.price;
            openModal();
        }
    }
    function filterProducts() {
    var filterType = document.getElementById("productFilter").value;
    var filterGrade = document.getElementById("productFilter2").value;
    var rows = document.getElementById("datatablesSimple").getElementsByTagName("tr");
    var found = false;

    for (var i = 0; i < rows.length; i++) {
        var type = rows[i].getElementsByTagName("td")[1].innerText.trim(); // Index adjusted for type
        var grade = rows[i].getElementsByTagName("td")[2].innerText.trim(); // Index adjusted for grade

        if ((filterType === "All" || type === filterType) && (filterGrade === "All" || grade === filterGrade)) {
            rows[i].style.display = ""; // Show row if it matches filters
            found = true;
        } else {
            rows[i].style.display = "none"; // Hide row if it doesn't match filters
        }
    }

    if (!found) {
        displayNoResultsMessage(); // Show no results message if no rows match
    } else {
        hideNoResultsMessage(); // Hide no results message if rows are found
    }
}






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
        function openModal2(productId, productName, productType, productGrade, productWeight, productPrice) {
            document.getElementById('editProductId').value = productId;
            document.getElementById('editProductName').value = productName;
            document.getElementById('editProductType').value = productType;
            document.getElementById('editProductWeight').value = productWeight;
            document.getElementById('editProductGrade').value = productGrade;
            document.getElementById('editProductPrice').value = productPrice;

            // Show the modal
            document.getElementById('myModalEdit').style.display = "block";
        }

        function closeModal() {
            document.getElementById('myModalEdit').style.display = "none";
        }
        function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            closeModal();
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
