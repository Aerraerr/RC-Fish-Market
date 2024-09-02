<?php
// update_product_weight.php

// Database connection
$conn = new mysqli("localhost:3307","root","");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = intval($_POST['product_id']);
    $quantity = floatval($_POST['quantity']);

    // Fetch current product weight
    $sql = "SELECT product_weight FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($currentWeight);
    $stmt->fetch();
    $stmt->close();

    // Calculate new weight
    $newWeight = $currentWeight - $quantity;

    // Update product weight in the database
    $sql = "UPDATE products SET product_weight = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $newWeight, $productId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product weight updated successfully.";
    } else {
        echo "Failed to update product weight.";
    }

    $stmt->close();
}

$conn->close();
?>
