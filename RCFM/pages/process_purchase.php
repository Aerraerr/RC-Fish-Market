<?php
// Connect to your database
$conn = new mysqli('localhost', 'username', 'password', 'inventory');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive data from AJAX request
$data = json_decode(file_get_contents("php://input"), true);

// Insert each product into the database
foreach ($data as $product) {
    $productName = $conn->real_escape_string($product['name']);
    $productPrice = $product['price'];
    $productQuantity = $product['quantity'];
    $productTotal = $product['total'];

    $sql = "INSERT INTO sold_products (product_name, product_price, quantity, total) 
            VALUES ('$productName', '$productPrice', '$productQuantity', '$productTotal')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>