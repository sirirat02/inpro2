<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb2";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
$conn -> set_charset("utf8") or die("");


?>
