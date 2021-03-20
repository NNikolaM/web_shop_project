<?php

$conn = new mysqli("localhost", "root", "", "shoes_db");
if ($conn->connect_error)
    die('GRESKA: ' . $conn->connect_error);

?>