<?php 
session_start();
require_once "db.php";

/*if (!isset($_SESSION["username"])){
    header("Location: auth/login.php");
    exit();
}*/



try {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM prompt p 
        INNER JOIN categorie c ON p.categorie_id = c.id
        ORDER BY p.id DESC
    ");   
    $prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching prompts: " . $e->getMessage());
}







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository - Dashboard</title>
    <link rel="stylesheet" href="Css/dashboard.css?v=<?php echo time(); ?>">
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
                <li><a id="menuDashboard" class="active" href="index.php"><span class="nav-icon">📊</span> Dashboard</a></li>
                <li><a id="menuCategories" href="Promptes/list.php"><span class="nav-icon">📂</span> Community</a></li>
                <li><a id="menuAddPrompt" href="Promptes/created.php"><svg class="nav-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="4"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg> Add Prompt</a></li>
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







    <main id="contentArea">

        <section class="stats-overview">
            <h2>Workspace Overview</h2>
            <p class="stats-subtitle">Monitor your repository performance and active AI prompt sequences across all connected LLM endpoints.</p>
            
            <div class="stats-grid">

                <div class="stat-card stat-card-green">
                    <div class="stat-icon">📋</div>
                    <div class="stat-content">
                        <p class="stat-label">Total Prompts</p>
                        <h3 class="stat-value"><?php echo count($prompts); ?></h3>
                        <span class="stat-change">+12%</span>
                    </div>
                </div>

                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">📂</div>
                    <div class="stat-content">
                        <p class="stat-label">Categories</p>
                        <h3 class="stat-value">4</h3>
                        <span class="stat-change">Global</span>
                    </div>
                </div>

                <div class="stat-card stat-card-orange">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <p class="stat-label">Active Users</p>
                        <h3 class="stat-value">156</h3>
                        <span class="stat-change">+3</span>
                    </div>
                </div>

                <div class="stat-card stat-card-green-alt">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-content">
                        <p class="stat-label">Activity Today</p>
                        <h3 class="stat-value">42</h3>
                        <span class="stat-change">Live</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Prompts Section -->
        <section class="recent-prompts">
            <div class="section-header">
                <h3>Recent Prompt Executions</h3>
                <p class="section-subtitle">Review and manage recent generated prompts across model</p>
                <a href="Promptes/created.php" class="btn-new">+ New Prompt</a>
            </div>

            <div class="table-container">
                <table class="prompts-table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>CATEGORY</th>
                            <th>DATE CREATED</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $displayed = 0;
                        

                        $categoryMap = [
                            'IA' => 'category-ia',
                            'Dev mobile' => 'category-mobile',
                            'Data' => 'category-data',
                            'Dev web' => 'category-web'
                        ];
                        
                        foreach($prompts as $prompt): 
                            if($displayed >= 5) break;
                            $displayed++;
                            $status = ['approved', 'pending', 'rejected'];
                            $statusIndex = $displayed % 3;
                            $date = date('M d, Y', strtotime('now'));
                            

                            $categoryClass = isset($categoryMap[$prompt['category_name']]) 
                                ? $categoryMap[$prompt['category_name']] 
                                : 'category-web';
                        ?>
                            <tr>
                                <td class="prompt-name">
                                    <span class="prompt-icon"><?php echo $prompt['title'][0]; ?></span>
                                    <?php echo htmlspecialchars($prompt['title']); ?>
                                </td>
                                <td>
                                    <span class="category-badge <?php echo $categoryClass; ?>">
                                        <?php echo htmlspecialchars($prompt['category_name'] ?? 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td><?php echo $date; ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($status[$statusIndex]); ?>">
                                        ● <?php echo $status[$statusIndex]; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="Promptes/edit.php?id=<?php echo $prompt['id']; ?>" title="Edit">✏️</a>
                                    <a href="Promptes/delete.php?id=<?php echo $prompt['id']; ?>" title="Delete" onclick="return confirm('Are you sure?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span>Showing 1 to 5 of <?php echo count($prompts); ?> results</span>
                <div class="pagination-buttons">
                    <button class="page-btn">‹</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">›</button>
                </div>
            </div>
        </section>

    </main>






    </main>
</body>
</html>