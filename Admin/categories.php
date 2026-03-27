<?php ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Prompt</title>
        <link rel="stylesheet" href="../Css/dashboard.css">
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
                <li><a id="menuDashboard"  href="../index.php">📋 Dashboard</a></li>
                <li><a id="menuPrompts"  href="../Promptes/list.php">📝 My Prompts</a></li>
                <li><a id="menuCategories" class="active" href="categories.php">📂 Categories</a></li>
                <li><a id="menuAddPrompt"  href="../Promptes/created.php"> Add Prompt</a></li>
                <li><a id="menuSettings" href="#">⚙️ Settings</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info" id="userInfo">
                <div class="user-name" id="userName"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                <div class="user-role" id="userRole">Pro Account</div>
            </div>
        </div>

        <a id="logoutButton" class="logout-link" href="../Auth/logout.php" style="display: block; padding: 12px 16px; margin-top: 20px; background-color: #ff4757; color: white; text-align: center; border-radius: 6px; text-decoration: none; font-weight: 500; transition: background-color 0.3s ease;">Déconnexion</a>
    </header>

    <main id="contentArea">
        <section class="overview-panel" id="dashboardOverview">
            <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h1>
            <p>Choisissez un menu pour démarrer.</p>
        </section>
    </main>
</body>
</html>