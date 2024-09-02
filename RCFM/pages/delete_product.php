<?php
require 'connection.php';

if(isset($_POST["productId"])) {
    $productId = $_POST["productId"];
    
    // Delete the product from the database
    $deleteQuery = "DELETE FROM products WHERE product_id = '$productId'";
    
    if(mysqli_query($conn, $deleteQuery)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>