<?php 

session_start();
require_once '../db.php';

try {
    $stmt = $pdo->prepare('SELECT * FROM categorie');
    $stmt->execute();

    $resultCat = $stmt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){

    die("Database error: " . $e->getMessage());

}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $description = $_POST['description'];

    try{
        $stmt = $pdo->prepare("INSERT INTO categorie (name, description) VALUES (:name, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

    }catch(PDOException $e){
        die("Database error: " . $e->getMessage());
    }

}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Admin - Prompt Repository</title>
    <link rel="stylesheet" href="../Css/categories.css?v=<?php echo time(); ?>">
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
                <li><a id="menuDashboard"  href="dashboard.php"><img src="../img/dashboard.svg" alt="dashboard"> Dashboard Admin</a></li>
                <li><a id="menuSettings" href="categories.php"><img src="../img/category.svg" alt="categories"> Categories</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info" id="userInfo">
                <div class="user-name" id="userName"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></div>
                <div class="user-role" id="userRole">Admin Account</div>
            </div>
        </div>
        <a id="logoutButton" class="logout-link" href="../Auth/logout.php">Déconnexion</a>
    </header>

    <main id="contentArea">
        <section class="overview-panel" id="dashboardOverview">
            <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h1>
        </section>

        <div class="add-Category">
            <h2>Add New Category</h2>
            <form  method="POST">
                <input type="text" name="name" placeholder="Category Name" required>
                <textarea name="description" placeholder="Category Description" required></textarea>
                <button type="submit">Add Category</button>
            </form>
        </div>

        <br><br><br>

        <?php foreach($resultCat as $cat): ?>
            <div class="category-card">
                <h2><?php echo htmlspecialchars($cat['name']); ?></h2>
                <p><?php echo htmlspecialchars($cat['description']); ?></p>
                <a href="edit_category.php?id=<?php echo $cat['id']; ?>">Edit</a>
                <a href="delet_categorie.php?id=<?php echo $cat['id']; ?>">Delete</a>
            </div>
        <?php endforeach; ?>



        
        




    </main>
</body>
</html>