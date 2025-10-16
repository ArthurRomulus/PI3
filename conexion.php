<?php

$conn = new mysqli("localhost", "root","" ,"tienda_db");

if ($conn->connect_error){
    die("Database error 504, cannot connect with database.");
}

?>