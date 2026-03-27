<?php


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository - Dashboard</title>
    <link rel="stylesheet" href="../Css/dashboard.css?v=<?php echo time(); ?>">
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
                <li><a id="menuDashboard"  href="Promptes/list.php"><span class="nav-icon">📊</span> Dashboard</a></li>
                <li><a id="menuCategories" class="active" href="Prompts/list.php"><span class="nav-icon">📂</span> Community</a></li>
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



    <main>


    <div class="search">
        <input type="text" placeholder="Search prompts..." id="searchInput">
        <button id="searchButton">Search</button>
    </div>





    </main>
</body>
</html>