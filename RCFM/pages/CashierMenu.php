<?php
require 'connection.php';

// Define the function to get background color based on product type
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
                echo "<script>alert('".$row['product_name']." is out of stock');</script>";
            } else {
                echo "<script>alert('Error deleting product with 0 weight: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error storing deleted product with 0 weight: " . mysqli_error($conn) . "');</script>";
        }
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
        <title>CASHIER</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/ForCashier.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            #CashierMenu{
                background-color: #2F353B;
            }
            .cashierMenu{
                width:100%;
                height:650px;
                margin-top:5px;
                border:none;
            }.cashierMenu2{
                width:43%;
                height:645px;
                border: 1px solid gray;
                border-radius:6px;
            }
            .cashierMenuNav{
                width:100%;
                height:27px;
                background-color:#49525C;
                border-radius:5px 5px 0px 0px;
                border:none;

            }
            .cashierMenuNav2{
                width:94%;
                height:24px;
                margin-left:-1%;
                display: flex;
                flex-direction: row; /* Align items horizontally */
                justify-content: space-between;

            }
            .cashierMenuNav2 ol{
                padding-top:3px;
                font-weight:500;
            }
            .cashierMenuContent{
                width:94%;
                height:310px;
                border-top:1px solid lightgray;
                border-bottom:1px solid lightgray;
                margin-left:3%;
            }
            .cashierMenu3{
                margin-top:5px;
                position:relative;
                width:55%;
                top:-100%;
                left:45%;
                height:800px;
                border: 1px solid gray;
                border-radius:6px;
            }

            /*QUANTITY BUTTON */
            .quantitybtn{
                width:30%;
            }
            .qty{
                width:30px;
                text-align:center;
            }

            /*LIST OF PRODUCT */
            .toggle-button {
                padding: 10px 20px;
                border: 2px solid white;
                cursor: pointer;
                font-size:15px;
            }
            .toggle-button.selected {
                background-color: #0D6EFD;
                color: #fff;
                border:2px solid black;
            }
            .listofproducts {
                width: 100%;
            }

            .toggle-button {
                width: 60%; /* Set the width to auto to allow each button to occupy its natural width */
                height:25%;
                margin-right: 20px;
                margin-top:10px;
                border-radius:5px;

            }
            .output{
                font-size:13px;


            }
            .output td{
                padding-left:50px;
            }
            .productDisplay{
                padding-top:10px;
            }

            /*QUANTITY BTN */
            #qtyASbtn{
                position:absolute;
                width:9%;
                height:50px;
                background-color:#0D6EFD;
                border:none;
                border-radius:5px;
                font-size:30px;
                color:whitesmoke;
                margin-top:140px;
                margin-left:91%;
            }
            #qtyASbtn1{
                position:absolute;
                width:9%;
                height:50px;
                background-color:#0D6EFD;
                border:none;
                border-radius:5px;
                font-size:30px;
                color:whitesmoke;
                margin-top:140px;
                margin-left:67%;
            }
            #qtyASbtn1output{
                position:absolute;
                width:12%;
                height:50px;
                margin-top:140px;
                margin-left:77%;
                border-radius:5px;
                font-size:30px;
                background-color:whitesmoke;
                border-color:lightgray;
            }
            #getDatabtn{
                position:absolute;
                width:33%;
                height:6%;
                margin-left:62%;
                border-radius:5px;
                margin-top:500px;
                background-color:#0D6EFD;
                border:none;
                font-size:20px;
                color:whitesmoke;
                
            }
            #getDatabtnCancel{
                position:absolute;
                width:33%;
                height:6%;
                margin-left:62%;
                border-radius:5px;
                margin-top:555px;
                background-color:#2F353B;
                border:none;
                font-size:20px;
                color:whitesmoke;
                
            }
            #proceedAnotherOrder{
                position:absolute;
                width:33%;
                height:6%;
                margin-left:62%;
                border-radius:5px;
                margin-top:630px;
                background-color:#0D6EFD;
                border:none;
                font-size:20px;
                color:whitesmoke;    
                text-decoration:none;
                text-align:center;
                padding-top:8px;
            }
            #counterInput{
                background-color:transparent;
                border:none;
                color:white;
                width:200px;
            }
            #counterInput:hover{
                background-color:transparent;
                border:none;
            }
            .buttonlistprod{
                margin-top:-400px;
            }



            .productDisplayContainer {
                max-height: 300px; 
                overflow-y: auto;
            }

            .productDisplay {

            }
            .productDisplay {
                min-height: 150px;
                max-height: 200px;
                overflow-y: auto;
            }

            /**POS CALCULATORS */
            .insertAmount{
                position:absolute;
                width:64%;
                height:290px;
                margin-left:1.5%;
                margin-top:500px;
                border-radius:5px;
            }
            .calculatorDisplay {
                width: 67%;
                height: 50px;
                margin-bottom: 5px;
                font-size: 25px;
                text-align: right;
                border-radius:5px 0px 0px 5px;
                padding-right:10px;
                color:black;
                font-family:Digital-7;
                letter-spacing:4px;
            }

            .calculatorButtons button {
                width: 22%;
                height: 50px;
                margin: 2px;
                font-size: 20px;
                margin-left:-2px;
                border-radius:5px;

            }
            #insclear{
                width: 21%;
                height: 50px;
                font-size: 20px;
                margin-left:.5%;
                border-radius:0px 5px 5px 0px;
            }
            #totalAmountDisplay{
                width:12%;
                position: absolute;
                margin-top:399px;
                margin-left:20%;
                font-size:30px;
                font-family: Haettenschweiler;
                letter-spacing:4px;
                border:none;

            }
            #totalAmountDisplaytext{
                position: absolute;
                margin-top:380px;
                margin-left:20%;
                font-size:18px;
                font-family: Haettenschweiler;
                letter-spacing:3px;
            }
            #totalAmountDisplayphp{
                position:absolute;
                font-size:20px;
                margin-top:410px;
                margin-left:30.5%;
                font-family: Haettenschweiler;
            }
            .receipt hr{
                position:absolute;
                width:13.5%;
                margin-top:435px;
                margin-left:20%;
            }


            #paymentforTotal{
                width:12%;
                position: absolute;
                margin-top:470px;
                margin-left:20%;
                font-size:30px;
                font-family: Haettenschweiler;
                background-color:transparent;
                border:none;
                letter-spacing:4px;
            }
            #paymentforTotaltext{
                position: absolute;
                margin-top:450px;
                margin-left:20%;
                font-size:18px;
                font-family: Haettenschweiler;
                letter-spacing:3px;

            }
            #paymentforTotalphp{
                position:absolute;
                font-size:20px;
                margin-top:485px;
                margin-left:30.5%;
                font-family: Haettenschweiler;
            }
            #paymentforTotalhr{
                position:absolute;
                position:absolute;
                width:13.5%;
                margin-top:510px;
                margin-left:20%;
            }
            #PAY {
                background-color: #1e3163;
                width: 45%;
                color: whitesmoke;
            }

            #PAY:disabled {
                background-color: gray;
            }
            #proceedAnotherOrder:disabled{
                background-color: gray;
            }
            #forchange{
                width:12%;
                position: absolute;
                margin-top:560px;
                margin-left:20%;
                font-size:30px;
                font-family: Haettenschweiler;
                background-color:transparent;
                border:none;
                letter-spacing:4px;
            }

            #forchangetext{
                position: absolute;
                margin-top:540px;
                margin-left:20%;
                font-size:18px;
                font-family: Haettenschweiler;
                letter-spacing:3px;
            }
            #forchangephp{
                position:absolute;
                font-size:20px;
                margin-top:570px;
                margin-left:30.5%;
                font-family: Haettenschweiler;
            }
            #forchangehr{
                position:absolute;
                width:13.5%;
                margin-top:595px;
                margin-left:20%;
            }
            .nav a{
                font-size:17px;
            }
            #toggleButton{
                width:50px;
            }
            .listofproductscont2{
                margin-top:-130px;
                margin-left:-5%;
            }
            .listofproductscont {
        display: flex;
        flex-wrap: wrap;
        height: 490px; /* Set a fixed height for the scrolling area */
        overflow-y: scroll; /* Enable vertical scrolling */
        width: 100%;
         /* Optional: Add a border for visual clarity */
        padding: 10px; /* Optional: Add padding */
        box-sizing: border-box; /* Ensure padding and border are included in the element's total width and height */
    }
    .product-price{
        font-size:15px;
    }
    .product-weight{
        font-size:12px;
        padding-left:40%;
    }
    .toggle-button.selected {

            border: 5px solid #0D6EFD; /* Optional: Add a border to highlight the selection */
        }

    .listofproductscont button {
        flex: 1 0 30%; /* Each button will take at least 30% of the row */
        margin: 5px;
        box-sizing: border-box;
        text-align: center;
        border: 1px solid #ccc;
        cursor: pointer;
        color: #fff;
        height:150px;
        text-align:left;
        padding-left:15px;
    }

    .listofproductscont button .product-name,
    .listofproductscont button .product-price,
    .listofproductscont button .product-type,
    .listofproductscont button .product-grade {
        display: block;
    }
    .product-type,
    .product-grade{
        font-size:13px;
    }

    .listofproductscont button .product-name {
        font-weight: bold;
        margin-bottom: 5px;
        font-size:20px;
    }

    .listofproductscont button .product-price {
        color: white;
        font-weight:bold; /* Slightly different color for the price text */
    }

    /* Ensuring that the products are displayed in three rows */
    .listofproductscont button:nth-child(3n+1) {
        clear: left;
    }
    #proceedAnotherOrder {
    display: none;
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
                                Orders History
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
                    <div class="receipt">
                        <input type="number" id="totalAmountDisplay" readonly>
                        <hr>
                        <input type="number" id="paymentforTotal" readonly>
                        <input type="number" id="forchange" readonly>

                    </div>
                    <div class="receipttext">
                        <h4 id="totalAmountDisplaytext">TOTAL</h4>
                        <ol id="totalAmountDisplayphp">php</ol>

                        <h4 id="paymentforTotaltext">PAYMENT</h4>
                        <ol id="paymentforTotalphp">php</ol>
                        <hr id="paymentforTotalhr">

                        <h4 id="forchangetext">CHANGE</h4>
                        <ol id="forchangephp">php</ol>
                        <hr id="forchangehr">

                    </div>
                        <div class="cashierMenu">
                            <div class="cashierMenu2">
                                <div class="cashierMenuNav" style="width:100%;">
                                <p style="padding-left:90%; font-size:17px; ">
                                <input type="text" id="counterInput" value="#0000" readonly>
                                    </p>
                                </div>
                            <div class="cashierMenuNav2">
                                <ol style="padding-left:5%; margin-right:8.5%;">ITEM DESCRIPTION</ol>
                                <ol style="margin-left:12%;">PRICE</ol>
                                <ol style="margin-left:5%;">QUANTITY</ol>
                                <ol style="margin-right:30px;">TOTAL</ol>
                            </div>
                            <div class="cashierMenuContent">
                    <div id="productDisplayContainer" class="productDisplayContainer">
                        <div id="productDisplay"></div>
                    </div>
                    
                </div>
                </div>
                <div class="cashierMenu3">
                    <div class="productmenu" style="width:100%;">
                        <div class="listofproducts">
                        <div class="insertAmount" id="insertAmount">
                            <input type="text" id="calculatorDisplay" class="calculatorDisplay"  placeholder="ENTER AMOUNT">
                            <button id="insclear" onclick="clearDisplay()">C</button>
                            <div class="calculatorButtons">
                                <button onclick="addToDisplay('1')" id="button1" disabled>1</button>
                                <button onclick="addToDisplay('2')" id="button2" disabled>2</button>
                                <button onclick="addToDisplay('3')" id="button3" disabled>3</button>
                                <button onclick="clearLast()" id="buttonDel" disabled>DEL</button>

                                <button onclick="addToDisplay('4')" id="button4" disabled>4</button>
                                <button onclick="addToDisplay('5')" id="button5" disabled>5</button>
                                <button onclick="addToDisplay('6')" id="button6" disabled>6</button>
                                <button onclick="addToDisplay('100')" id="button100" disabled>100</button>

                                <button onclick="addToDisplay('7')" id="button7" disabled>7</button>
                                <button onclick="addToDisplay('8')" id="button8" disabled>8</button>
                                <button onclick="addToDisplay('9')" id="button9" disabled>9</button>
                                <button onclick="addToDisplay('500')" id="button500" disabled>500</button>

                                <button onclick="addToDisplay('0')" id="button0" disabled>0</button>
                                <button onclick="addToDisplay('00')" id="button00" disabled>00</button>
                                <button onclick="pay()" id="PAY" disabled>PAY</button>
                            </div>
                        </div>
                            
                        <div class="listofproductscont">
                                    <?php
                                    // Fetch products from your database
                                    $sql = "SELECT * FROM products";
                                    $result = $conn->query($sql);

                                    // Store products in an array
                                    $products = [];
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $products[] = $row;
                                        }
                                    }

                                    // Sort products by type
                                    usort($products, function($a, $b) {
                                        return strcmp($a['product_type'], $b['product_type']);
                                    });

                                    // Check if there are products in the array
                                    if (count($products) > 0) {
                                        $buttonCount = 1; // Counter for button IDs
                                        foreach ($products as $row) {
                                            $productName = $row["product_name"];
                                            $productPrice = $row["product_price"];
                                            $productType = $row["product_type"];
                                            $productGrade = $row["product_grade"];
                                            $productWeight = $row["product_weight"];
                                            $bgColor = getProductBgColor($productType);
                                            echo "<button id='toggleButton{$buttonCount}' class='toggle-button' style='background-color: {$bgColor};' data-name='{$productName}' data-price='{$productPrice}' data-type='{$productType}' data-grade='{$productGrade}' data-weight='{$productWeight}' onclick='toggleSelected(\"toggleButton{$buttonCount}\")'>
                                                <span class='product-name'>{$productName}</span>
                                                <span class='product-type'>{$productType}</span>
                                                <span class='product-grade'>{$productGrade}</span>
                                                <span class='product-price'>{$productPrice}php</span>
                                                <span class='product-weight'>Available: <u>{$productWeight}kg</u></span>
                                            </button>";
                                            $buttonCount++;
                                        }
                                    } else {
                                        echo "<h6 style='margin-left:45%; margin-top:200px;'>No products found.</h6>";
                                    }
                                    ?>
                                    <br>
                                    <hr>
                                </div>

                            <div class="listofproductscont2"> 
                                <!--setting quantity-->                   
                                <form id='myform1' class='quantity' action='#'>
                                <input  id="qtyASbtn1" type='button' value='-' class='qtyminus minus' field='quantity1'/>
                                <input id="qtyASbtn1output" type='text' name='quantity1' value='0' class='qty' />
                                <input id="qtyASbtn" type='button' value='+' class='qtyplus plus' field='quantity1' />
                            </div>                            
                        <div class="buttonlistprod">
                        <button id="getDatabtn" onclick="getData()">Confirm</button>
                        <button id="getDatabtnCancel" onclick="clearAll()">Cancel</button>
                        <a id="proceedAnotherOrder" href="CashierMenu.php">New Order</a>
                                
                        </div>
                        
                    </div>
                    

                </div>
            </div>                      
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

        <script>
            document.getElementById('myform1').addEventListener('click', updateQuantity);
            function updateQuantity(event) {
                const target = event.target;
                if (target.classList.contains('qtyminus') || target.classList.contains('qtyplus')) {
                    const inputField = target.parentNode.querySelector('.qty');
                    const fieldName = inputField.getAttribute('name');
                    let newValue = parseInt(inputField.value);

                    // Update the quantity based on the button clicked
                    if (target.classList.contains('qtyminus')) {
                        newValue = newValue > 0 ? newValue - 1 : 0;
                    } else if (target.classList.contains('qtyplus')) {
                        newValue += 1;
                    }
                    inputField.value = newValue;
                    console.log(`Quantity for ${fieldName}: ${newValue}`);
                }
            }
            function toggleSelected(buttonId) {
                const button = document.getElementById(buttonId);
                const isSelected = button.classList.contains('selected');
                if (isSelected) {
                    button.classList.remove('selected');
                } else {
                    button.classList.add('selected');
                }
            }

            const getTotalAmountUpdater = () => {
                let totalAmount = 0;

                const updateTotalAmountDisplay = (amount) => {
                    const totalAmountDisplay = document.getElementById('totalAmountDisplay');
                    totalAmountDisplay.value = amount.toFixed(2);
                };

                const addToTotalAmount = (amount) => {
                    totalAmount += amount;
                    updateTotalAmountDisplay(totalAmount);
                    
                };

                return addToTotalAmount;

            };
            
            const addToTotalAmount = getTotalAmountUpdater();

            function getData() {
    const selectedButtons = document.querySelectorAll('.toggle-button.selected');
    const productDisplayContainer = document.getElementById('productDisplayContainer');
    const counterInput = document.getElementById('counterInput');
    const insertAmount = document.getElementById('insertAmount');

    let counterValue = parseInt(counterInput.value) || 1;

    if (selectedButtons.length === 0) {
        alert("No product selected");
        return;
    }

    selectedButtons.forEach(button => {
        const productName = button.getAttribute('data-name');
        const productPrice = parseFloat(button.getAttribute('data-price'));
        const productQuantity = parseInt(document.querySelector('input[name="quantity1"]').value);
        const productType = button.getAttribute('data-type'); // Retrieve type
        const productGrade = button.getAttribute('data-grade'); // Retrieve grade

        // Subtract the quantity from the database
        subtractQuantityFromDatabase(productName, productQuantity, productType, productGrade);

        const productTotal = productPrice * productQuantity;
        addToTotalAmount(productTotal);

        const productElement = document.createElement('div');
        productElement.classList.add('output');
        productElement.innerHTML = `
            <h5 style="padding-top:10px; font-size: 13px; width:100%; display: flex; justify-content: space-between;">
                <span style="flex: 1; margin-left:5px; margin-right: 85px;">${productName}</span>
                <span style="flex: 1; margin-left:100px; margin-right: 10px;">${productPrice.toFixed(2)} php/kg</span>
                <span style="flex: 1; margin-left:40px; margin-right:10px;">x${productQuantity}</span>
                <span style="flex: 1; ">${productTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} php</span>
            </h5>
            <hr>
        `;
        productDisplayContainer.appendChild(productElement);
        counterInput.value = '#' + ('000' + counterValue).slice(-4);
        counterValue++;

    });

    clearInputs();
    selectedButtons.forEach(button => {
        button.classList.remove('selected');
    });
    enableButtons(insertAmount);

    document.getElementById('getALLDATA').disabled = false;
}

function subtractQuantityFromDatabase(productName, quantity, type, grade) {
    // Send an AJAX request to update the database
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'sold_products.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Quantity subtracted successfully');
            } else {
                console.error('Error:', xhr.statusText);
            }
        }
    };
    // Include type and grade in the request data
    xhr.send(`productName=${productName}&quantity=${quantity}&type=${type}&grade=${grade}`);
}
            // Function to enable buttons
            function enableButtons(element) {
                const buttons = element.querySelectorAll('.calculatorButtons button');
                buttons.forEach(button => {
                    button.disabled = false;
                });
            }
            function clearInputs() {
                document.querySelector('input[name="quantity1"]').value = '0';
            }
            function clearDisplay() {
                const productDisplayContainer = document.getElementById('productDisplayContainer');
                productDisplayContainer.innerHTML = '';
            }
            function addToDisplay(value) {
                const display = document.getElementById('calculatorDisplay');
                display.value += value;
            }


            function clearDisplay() {
                const display = document.getElementById('calculatorDisplay');
                display.value = '';
            }

            function clearLast() {
                const display = document.getElementById('calculatorDisplay');
                display.value = display.value.slice(0, -1);

                
            }
            function calculateChange() {
                var totalAmount = parseFloat(document.getElementById("totalAmountDisplay").value);
                var amountPaid = parseFloat(document.getElementById("paymentforTotal").value);
                var change = amountPaid - totalAmount;
                document.getElementById("forchange").value = change.toFixed(2);
            }
            function pay() {
                var amount = document.getElementById("calculatorDisplay").value;
                document.getElementById("paymentforTotal").value = parseFloat(amount).toFixed(2);
                calculateChange();
                

                var proceedLink = document.getElementById('proceedAnotherOrder');
    proceedLink.style.display = 'inline'; // Display the link
    proceedLink.removeAttribute('disabled'); // Enable the link
            }

            function clearAll() {
                document.querySelector('input[name="quantity1"]').value = '0';

                const productDisplayContainer = document.getElementById('productDisplayContainer');
                productDisplayContainer.innerHTML = '';

                const display = document.getElementById('calculatorDisplay');
                display.value = '';

                const totalAmountDisplay = document.getElementById('totalAmountDisplay');
                totalAmountDisplay.value = '';

                const counterInput = document.getElementById('counterInput');
                counterInput.value = '#0000';

                const paymentforTotal = document.getElementById('paymentforTotal');
                paymentforTotal.value = '';

                const forchange = document.getElementById('forchange');
                forchange.value = '';
                const selectedButtons = document.querySelectorAll('.toggle-button.selected');
                selectedButtons.forEach(button => {
                    button.classList.remove('selected');
                });
                const insertAmount = document.getElementById('insertAmount');
                const buttons = insertAmount.querySelectorAll('.calculatorButtons button');
                buttons.forEach(button => {
                    button.disabled = true;
                });
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
    function Logout(){
    window.location.href = "../index.php";
    exit();
    }
</script>
    </body>
</html>