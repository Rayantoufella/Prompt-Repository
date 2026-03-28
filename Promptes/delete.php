<?php
session_start();

$deleteId = $_GET['id'] ?? null;

if (!$deleteId) {
    header("Location: list.php");
    exit();
}

try {
    require_once '../db.php';
    

    $stmt = $pdo->prepare("DELETE FROM prompt WHERE id = ?");
    $result = $stmt->execute([$deleteId]);
    
    if ($result) {
        header("Location: ../index.php");
        exit();
    } else {
        die("Error deleting prompt");
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
