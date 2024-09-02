
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>RC Fish Market - List of Product</title>
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
            <main>
                <div class="container-fluid px-4">
                    <h1 style="font-size:80px; position:absolute;" class="mt-4">RC FISH MARKET</h1>
                </div>
                <div style="position:absolute; margin-top:20px; margin-left:38%; " class="map">
                <iframe style="border-radius:10px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d667.288024418256!2d123.76114591596246!3d13.139684896327516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a10228ec30cc5d%3A0xf57b9cba06b6d88a!2sLegazpi%20City%20Fishport!5e0!3m2!1sen!2sph!4v1716105808312!5m2!1sen!2sph" width="700px" height="700px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                
            </main>

                
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
