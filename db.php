<?php 

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "prompt_repo";

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


}catch (PDOException $e) {

    die("Connection failed: " . $e->getMessage());
    
}