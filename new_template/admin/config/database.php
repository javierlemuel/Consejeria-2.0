<?php

// config/database.php
// $host = 'localhost';
// $username = 'estccom';
// $password = 'Prog@840ccom';
// $database = 'ConsejeriaCCOM';


$host = "localhost"; // XAMPP crea el localhost
$username = "root"; // Usuario de phpMyAdmin
$password = ""; //contrasena de phpMyAdmin
$database = "counseling_db"; 


/*
$host = "136.145.29.193"; // XAMPP crea el localhost
$username = "emamarsa"; // Usuario de phpMyAdmin
$password = "ema84023"; //contrasena de phpMyAdmin
$database = "emamarsa_db"; 
*/

// $host = "136.145.29.193"; // XAMPP crea el localhost
// $username = "natramri"; // Usuario de phpMyAdmin
// $password = "nat84023"; //contrasena de phpMyAdmin
// $database = "natramri_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
