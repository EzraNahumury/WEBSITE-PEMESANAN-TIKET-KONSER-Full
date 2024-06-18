<?php
$mysqli = new mysqli("localhost", "root", "", "mydatabase");

// Memeriksa koneksi
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>