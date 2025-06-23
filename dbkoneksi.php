<?php
// dbkoneksi.php

$host = "localhost";
$username = "root";
$password = "";
$database = "calendar";


$konek = mysqli_connect($host, $username, $password, $database);


if (!$konek) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>