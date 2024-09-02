<?php
require 'connection.php';

if (isset($_POST["productId"])) {
    $productId = $_POST["productId"];

    $query = "SELECT * FROM products WHERE id = '$productId'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(array());
    }
}
?>