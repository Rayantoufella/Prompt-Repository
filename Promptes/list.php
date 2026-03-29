<?php
// Include database connection
require_once("../db.php");

// Get category ID from URL if exists
$listId = $_GET["id"] ?? null;

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

// Get all prompts from database
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id");
    $stmt->execute();
    $allPrompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($allPrompts)) {
        $allPrompts = [];
    }
}
catch(Exception $e) {
    die("Error fetching prompts: " . $e->getMessage());
}

// Handle search functionality
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $recherche = trim($_POST["search"] ?? "");
    
    if(empty($recherche)) {
        echo "<script>alert('Please enter a search term');</script>";
    } else {
        try {
            $searchTerm = "%$recherche%";
            $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id WHERE p.title LIKE :search OR p.context LIKE :search");
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
            $allPromptsSearch = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($allPromptsSearch)) {
                echo "<script>alert('No prompts found for \"$recherche\"');</script>";
                $allPrompts = [];
            } else {
                $allPrompts = $allPromptsSearch;
            }
        }
        catch(PDOException $e) {
            die("Error searching prompts: " . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository - Community</title>
    <link rel="stylesheet" href="../Css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Css/list.css?v=<?php echo time(); ?>">
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
        <!-- Page Header -->
        <div class="list-header">
            <h1 class="list-title">Community Prompts</h1>
            <p class="list-subtitle">Browse, test, and implement world-class prompts crafted by the community.</p>
        </div>

        <form method="POST" class="search">
            <input type="text" placeholder="Search prompts..." name="search" required>
            <input type="submit" value="Search">
        </form>

        <div class="return-page">
            <a href="list.php"> Back to All Prompts</a>
        </div>

        <!-- Prompts Grid -->
        <div class="prompts-grid">
            
            <!-- If no prompts found, show empty state -->
            <?php if(empty($allPrompts)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No prompts yet</h3>
                    <p>Be the first to create a prompt!</p>
                    <a href="created.php" class="btn-create">+ Create Prompt</a>
                </div>
            
            <!-- Otherwise, display all prompts in cards -->
            <?php else: ?>
                <?php foreach($allPrompts as $prompt): ?>
                    <div class="prompt-card">
                        <!-- Category tag with color -->
                        <div class="card-header">
                            <span class="card-category <?php echo getCategoryClass($prompt['category_name']); ?>">
                                <?php echo htmlspecialchars($prompt['category_name']); ?>
                            </span>
                        </div>
                        
                        <!-- Prompt title -->
                        <h3 class="card-title">
                            <?php echo htmlspecialchars($prompt['title']); ?>
                        </h3>
                        
                        <!-- Prompt preview text -->
                        <p class="card-excerpt">
                            <?php echo htmlspecialchars(mb_strimwidth($prompt['context'], 0, 120, '...')); ?>
                        </p>
                        
                        <!-- View prompt button -->
                        <div class="card-footer">
                            <a href="detail.php?id=<?php echo $prompt['id']; ?>" class="card-link">
                                View Prompt →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </main>

</body>
</html>