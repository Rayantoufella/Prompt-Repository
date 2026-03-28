<?php
session_start();

/*if (!isset($_SESSION['user_id'])) {
    header("Location: ../Auth/login.php");
    exit;
}*/

require_once "../db.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: list.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $prompt = $stmt->fetch(PDO::FETCH_ASSOC);

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
                <div class="logo-subtitle" id="siteTagline">AI Platform</div>
            </div>
        </div>

        <nav id="sideNav">
            <ul id="menuList">
                <li><a id="menuDashboard" href="../index.php"><span class="nav-icon">📊</span> Dashboard</a></li>
                <li><a id="menuCategories" class="active" href="list.php"><span class="nav-icon">📂</span> Community</a></li>
                <li><a id="menuAddPrompt" href="created.php"><svg class="nav-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="4"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Add Prompt</a></li>
                <li><a id="menuSettings" href="#"><span class="nav-icon">⚙️</span> Settings</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
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

        <div class="detail-card">
            <!-- Header Section -->
            <div class="detail-header">
                <div class="header-top">
                    <span class="detail-category"><?php echo htmlspecialchars($prompt['category_name']); ?></span>
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