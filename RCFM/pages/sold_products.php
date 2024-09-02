<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['productName'];
    $quantity = $_POST['quantity'];
    $type = $_POST['type']; // Assuming 'type' is received via POST
    $grade = $_POST['grade']; // Assuming 'grade' is received via POST

    // Retrieve the product price from the database
    $priceQuery = "SELECT product_price FROM products WHERE product_name = '$productName'";
    $priceResult = mysqli_query($conn, $priceQuery);

    if ($priceResult) {
        $row = mysqli_fetch_assoc($priceResult);
        $productPrice = $row['product_price'];

        // Calculate the total amount
        $totalAmount = $productPrice * $quantity;

        // Update the sold products table with product details and total amount
        $insertQuery = "INSERT INTO sold_products (product_name, type, grade, quantity_sold, product_price, total_amount) 
                        VALUES ('$productName', '$type', '$grade', $quantity, $productPrice, $totalAmount)";

        if (mysqli_query($conn, $insertQuery)) {
            echo "Quantity subtracted and sold product added successfully";

            // Update product weight in the products table
            $subtractQuery = "UPDATE products SET product_weight = product_weight - $quantity WHERE product_name = '$productName'";
            if (mysqli_query($conn, $subtractQuery)) {
                echo "Product quantity subtracted successfully";
            } else {
                echo "Error subtracting product quantity: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting sold product: " . mysqli_error($conn);
        }
    } else {
        echo "Error retrieving product price: " . mysqli_error($conn);
    }
}
?>
