<?php
// Start session
session_start();

// Include database connection
require_once '../db.php';

// Get prompt ID from URL
$editId = $_GET['id'] ?? null;

// If no ID provided, redirect to list
if (!$editId) {
    header("Location: list.php");
    exit();
}

// Get current prompt data from database
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id WHERE p.id = ?");
    $stmt->execute([$editId]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If prompt not found, show error
    if (!$edit) {
        die("Prompt not found. You cannot edit this prompt.");
    }
}
catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Handle form submission for updating the prompt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = trim($_POST['title'] ?? "");
    $categoryId = $_POST['category_id'] ?? "";
    $context = trim($_POST['context'] ?? "");
    
    // Check if all required fields are filled
    if (empty($title) || empty($categoryId) || empty($context)) {
        die("Please fill in all required fields.");
    }
    
    // Update prompt in database
    try {
        $stmt = $pdo->prepare("UPDATE prompt SET title = ?, categorie_id = ?, context = ? WHERE id = ?");
        $stmt->execute([$title, $categoryId, $context, $editId]);
        
        // Redirect to list after successful update
        header("Location: list.php");
        exit();
    }
    catch (Exception $e) {
        die("Error updating prompt: " . $e->getMessage());
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository - Edit Prompt</title>
    <link rel="stylesheet" href="../Css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Css/edit.css?v=<?php echo time(); ?>">
</head>
<body>
    <header id="sidebar">
        <div class="logo-container" id="brand">
            <div class="logo-icon">⚡</div>
            <div class="logo-text">
                <div class="logo-name" id="siteName">Prompt Repository</div>
                <div class="logo-subtitle" id="siteTagline">Prompt Platform</div>
            </div>
        </div>

        <nav id="sideNav">
            <ul id="menuList">
                <li><a id="menuDashboard" href="../index.php"><span class="nav-icon"><img src="../img/dashboard.svg" alt="Dashboard"></span> Dashboard</a></li>
                <li><a id="menuCategories" class="active" href="list.php"><span class="nav-icon"><img src="../img/community.svg" alt="Community"></span> Community</a></li>
                <li><a id="menuAddPrompt" href="created.php"><span class="nav-icon"><img src="../img/add-prompt.svg" alt="Add Prompt"></span> Add Prompt</a></li>
                <li><a id="menuSettings" href="#"><span class="nav-icon"><img src="../img/settings.svg" alt="Settings"></span> Settings</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info" id="userInfo">
                <div class="user-name" id="userName"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                <div class="user-role" id="userRole">Pro Account</div>
            </div>
        </div>

        <a id="logoutButton" class="logout-link" href="../Auth/logout.php">Déconnexion</a>

    </header>



    <main>
        <div class="page-header">
            <a href="list.php" class="back-link">← Back to Community</a>
        </div>

        <div class="edit-form-container">
            <h2>📝 Edit Prompt</h2>
            <form method="POST" action="edit.php?id=<?php echo htmlspecialchars($editId); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit['id']); ?>">
                
                <div class="form-group">
                    <label for="title">Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="<?php echo htmlspecialchars($edit['title']); ?>" 
                        placeholder="Enter prompt title"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php
                        try {
                            $catStmt = $pdo->query("SELECT DISTINCT id, name FROM categorie ORDER BY name");
                            while ($cat = $catStmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($cat['id'] == $edit['categorie_id']) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($cat['id']) . "\" $selected>" . htmlspecialchars($cat['name']) . "</option>";
                            }
                        } catch (Exception $e) {
                            echo "<option value=\"\">Error loading categories</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="context">Context / Description</label>
                    <textarea 
                        id="context" 
                        name="context" 
                        placeholder="Enter detailed prompt description or context..."
                        required
                    ><?php echo htmlspecialchars($edit['context']); ?></textarea>
                </div>

                <button type="submit" class="btn-submit"> Update Prompt</button>
            </form>
        </div>
    </main>
</body>
</html>