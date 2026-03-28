<?php

require_once("../db.php");

$listId = $_GET["id"] ?? null;

try{

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM prompt p INNER JOIN categorie c ON p.categorie_id = c.id");

$stmt->execute();

$allPrompts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($allPrompts)){
    $allPrompts = [];
}

}catch(Exception $e){
    die("Error fetching prompts: " . $e->getMessage());
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

        <a id="logoutButton" class="logout-link" href="Auth/logout.php">Déconnexion</a>

    </header>

    <main>
        <!-- Page Header -->
        <div class="list-header">
            <h1 class="list-title">Community Prompts</h1>
            <p class="list-subtitle">Browse, test, and implement world-class prompts crafted by the community.</p>
        </div>

        <!-- Prompts Grid -->
        <div class="prompts-grid">
            <?php if(empty($allPrompts)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No prompts yet</h3>
                    <p>Be the first to create a prompt!</p>
                    <a href="created.php" class="btn-create">+ Create Prompt</a>
                </div>
            <?php else: ?>
                <?php foreach($allPrompts as $prompt): ?>
                    <div class="prompt-card">
                        <div class="card-header">
                            <span class="card-category"><?php echo htmlspecialchars($prompt['category_name']); ?></span>
                            <span class="card-bookmark" title="Bookmark">🔖</span>
                        </div>
                        <h3 class="card-title"><?php echo htmlspecialchars($prompt['title']); ?></h3>
                        <p class="card-excerpt"><?php echo htmlspecialchars(mb_strimwidth($prompt['context'], 0, 120, '...')); ?></p>
                        <div class="card-footer">
                            <a href="detail.php?id=<?php echo $prompt['id']; ?>" class="card-link">View Prompt →</a>
                           
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>