<?php 
session_start();
require_once '../db.php';

$msg_success = "";
$msg_error = "";




try{
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $title = trim($_POST['promptTitle'] ?? "");
        $category = $_POST['category'] ?? "";
        $context = trim($_POST['promptContent'] ?? "");

        if(empty($title) || empty($category) || empty($context)){
            $msg_error = "Please fill in all required fields";
        } else {
            $stmtCat = $pdo->prepare("SELECT id FROM categorie WHERE name = :name");
            $stmtCat->bindParam(':name', $category);
            $stmtCat->execute();
            $cat = $stmtCat->fetch(PDO::FETCH_ASSOC);
            
            if(!$cat){
                $msg_error = " Selected category does not exist";
            } else {
                $categoryId = $cat['id'];
                
                $stmt = $pdo->prepare("INSERT INTO prompt (title, categorie_id, context) VALUES (:title, :categorie_id, :context)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':categorie_id', $categoryId);
                $stmt->bindParam(':context', $context);
                $stmt->execute();

                $msg_success = "✅ Prompt created successfully";
            }
        }
    }

}catch(PDOException $e){
    die("Database error: " . $e->getMessage());
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Prompt</title>
        <link rel="stylesheet" href="../Css/created.css">
</head>
<body>  
    <header class="sidebar">
        <div class="logo-container">
            <div class="logo-icon">⚡</div>
            <div>
                <div class="logo-name">Prompt Repository</div>
                <div class="logo-subtitle">AI Platform</div>
            </div>
        </div>

        <nav>
            <ul>
                <li><a href="../index.php"><span class="icon">📊</span> Dashboard</a></li>
                <li><a href="list.php"><span class="icon">📂</span> Community</a></li>
                <li><a href="created.php" class="active"><span class="icon">➕</span> Add Prompt</a></li>
                <li><a href="#"><span class="icon">⚙️</span> Settings</a></li>
            </ul>
        </nav>

        <div class="user-profile">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info">
                <div class="user-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                <div class="user-role">Pro Account</div>
            </div>
        </div>

        <a href="../Auth/logout.php" class="logout-link">Déconnexion</a>
    </header>

    <main>
        <!-- Top Search Bar -->
        <div class="top-bar">

            <div class="top-bar-right">
                <span class="status-badge">
                    <span class="status-dot"></span>
                    All changes saved
                </span>
                <button class="icon-btn">🔔</button>
                <button class="icon-btn">⚙️</button>
            </div>
        </div>

        <div>
                <?php if(!empty($msg_success)): ?>
                    <div class="message success">
                        <?php echo htmlspecialchars($msg_success); ?>
                    </div>
                <?php endif; ?>
    
                <?php if(!empty($msg_error)): ?>
                    <div class="message error">
                        <?php echo htmlspecialchars($msg_error); ?>
                    </div>
                <?php endif; ?>
        </div>



        <!-- Form Wrapper -->
        <div class="form-wrapper">
            <!-- Form Title -->
            <div class="form-header">
                <div class="form-header-left">
                    <h1>New Prompt Entity</h1>
                    <p class="form-description">Define the core logic and metadata for your AI agent.</p>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="created.php">
                
                <!-- Prompt Title -->
                <div class="form-section">
                    <label class="form-label">Prompt Title</label>
                    <input type="text" name="promptTitle" class="form-input" placeholder="Enter prompt title" >
                    <p class="form-note">TITLE IS REQUIRED TO SAVE AS ACTIVE</p>
                </div>

                <!-- Two Columns: Category & Tags -->
                <div class="form-row">                
                    <!-- Category Dropdown -->
                    <div class="form-section">
                        <label class="form-label">Primary Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php
                            try {
                                $catStmt = $pdo->query("SELECT DISTINCT name FROM categorie ORDER BY name");
                                while ($cat = $catStmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"" . htmlspecialchars($cat['name']) . "\">" . htmlspecialchars($cat['name']) . "</option>";
                                }
                            } catch (Exception $e) {
                                echo "<option value=\"\">Error loading categories</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Prompt Content -->
                <div class="form-section">
                    <label class="form-label">Prompt Instruction Set</label>
                    <textarea name="promptContent" class="form-textarea" placeholder="Act as a senior software architect..." ></textarea>
                    <p class="form-note">Write clear and detailed instructions for the AI model</p>
                </div>



                <!-- Form Buttons -->
                <div class="form-buttons">
                    <a href="../index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Deploy Prompt</button>
                </div>

            </form>
        </div>

        <!-- Tips Section -->
        <div class="tips-section">
            <div class="tip-card">
                <div class="tip-icon">💡</div>
                <h3 class="tip-title">Pro Tip</h3>
                <p class="tip-description">Use clear and concise instructions for the AI model. The better your instructions, the better the results.</p>
            </div>

            <div class="tip-card">
                <div class="tip-icon">⚡</div>
                <h3 class="tip-title">Optimization</h3>
                <p class="tip-description">Include specific keywords and examples in your prompt to guide the AI towards more accurate responses.</p>
            </div>

            <div class="tip-card">
                <div class="tip-icon">🔄</div>
                <h3 class="tip-title">Version Control</h3>
                <p class="tip-description">Every save creates a snapshot. You can revert to previous versions if needed.</p>
            </div>
        </div>

    </main>
</body>
</html>