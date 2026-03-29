<?php
// Start session
session_start();

// Include database connection
require_once '../db.php';

// Get prompt ID from URL
$deleteId = $_GET['id'] ?? null;

// If no ID provided, redirect to list
if (!$deleteId) {
    header("Location: list.php");
    exit();
}

// Delete the prompt from database
try {
    $stmt = $pdo->prepare("DELETE FROM prompt WHERE id = ?");
    $result = $stmt->execute([$deleteId]);
    
    // If deletion successful, redirect to home page
    if ($result) {
        header("Location: ../index.php");
        exit();
    } else {
        die("Error deleting prompt");
    }
}
catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
