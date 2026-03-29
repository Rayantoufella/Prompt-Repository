<?php
// Start session
session_start();

/*
// Uncomment to require login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Auth/login.php");
    exit;
}
*/

// Include database connection
require_once "../db.php";

// Function to get CSS class based on category name
function getCategoryClass($categoryName) {
    $mapping = [
        'IA' => 'category-ia',
        'Dev mobile' => 'category-mobile',
        'Data' => 'category-data',
        'Dev web' => 'category-web'
    ];
    return $mapping[$categoryName] ?? 'category-default';
}

// Get prompt ID from URL
$id = $_GET["id"] ?? null;

// If no ID provided, redirect to list
if (!$id) {
    header("Location: list.php");
    exit;
}

// Get prompt details from database
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $prompt = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If prompt not found, show error
    if (!$prompt) {
        die("Prompt not found.");
    }
}
catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($prompt['title']); ?> - Prompt Repository</title>
    <link rel="stylesheet" href="../Css/detail.css?v=<?php echo time(); ?>">
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
                <li><a id="menuDashboard" href="../index.php"><span class="nav-icon"><img src="../img/dashboard.svg"    alt=""></span> Dashboard</a></li>
                <li><a id="menuCategories" class="active" href="list.php"><span class="nav-icon"><img src="../img/community.svg" alt="Community"></span> Community</a></li>
                <li><a id="menuAddPrompt" href="created.php"><span class="nav-icon"><img src="../img/add-prompt.svg" alt="Add Prompt"></span> Add Prompt</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info" id="userInfo">
                <div class="user-name" id="userName"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                <div class="user-role" id="userRole">user Account</div>
            </div>
        </div>

        <a id="logoutButton" class="logout-link" href="../Auth/logout.php">Déconnexion</a>

    </header>

    <main>
        <div class="page-header">
            <a href="list.php" class="back-link">← Back to Community</a>
        </div>

        <div class="detail-card">
            <!-- Header Section -->
            <div class="detail-header">
                <div class="header-top">
                    <span class="detail-category <?php echo getCategoryClass($prompt['category_name']); ?>"><?php echo htmlspecialchars($prompt['category_name']); ?></span>
                </div>
                <h1 class="detail-title"><?php echo htmlspecialchars($prompt['title']); ?></h1>
            </div>

            <!-- Context Section -->
            <div class="detail-section">
                <h2 class="detail-section-title"> Context</h2>
                <div class="detail-content">
                    <?php echo nl2br(htmlspecialchars($prompt['context'])); ?>
                </div>
            </div>

            <!-- Author Section -->
            <div class="detail-section author-section">
                <h2 class="detail-section-title"> Author Information</h2>
                <div class="author-info">
                    <div class="author-card">
                        <div class="author-avatar">  <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar"></div>
                        <div class="author-details">
                            <div class="author-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                            <div class="author-role">Contributor</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="detail-section actions-section">
                <div class="detail-actions">
                    <a href="list.php" class="btn-back">← Back to Prompts</a>
                </div>
            </div>
        </div>
    </main>

   

</body>
</html>