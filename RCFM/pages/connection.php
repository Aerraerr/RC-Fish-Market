<?php

$conn = mysqli_connect("localhost:3307","root","");
/*if(mysqli_connect_errno()){
    die("connection error" . mysqli_connect_error());

}
else{
    echo "CONNECTION SUCCESS";
} dai na kaini hahahah*/

// Cpang check kung may koniksyon
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select database
$db_selected = mysqli_select_db($conn, "inventory");

// Check if the database selection was successful
if (!$db_selected) {
    die("Database selection failed: " . mysqli_error($conn));
}
?>
