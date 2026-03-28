<?php
session_start();

$deleteId = $_GET['id'] ?? null;

if (!$deleteId) {
    header("Location: ../Admin/categories.php");
    exit();
}

try {
    require_once '../db.php';
    
    // Utiliser une transaction
    $pdo->beginTransaction();
    
    try {
        // Étape 1 : Supprimer tous les prompts de cette catégorie
        $stmtDeletePrompts = $pdo->prepare("DELETE FROM prompt WHERE categorie_id = :id");
        $stmtDeletePrompts->execute([':id' => $deleteId]);
        
        // Étape 2 : Supprimer la catégorie
        $stmt = $pdo->prepare("DELETE FROM categorie WHERE id = :id");
        $result = $stmt->execute([':id' => $deleteId]);
        
        // Valider la transaction
        $pdo->commit();
        
        if ($result) {
            header("Location: ../Admin/categories.php");
            exit();
        } else {
            die("Error deleting category");
        }
    } catch (Exception $e) {
        // Annuler la transaction si erreur
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

