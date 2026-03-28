<?php 

session_start();
require_once '../db.php';

$id = $_GET['id'] ?? null;
$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nameUpt = $_POST['nameUpt'];
    $descriptionUpt = $_POST['descriptionUpt'];

    try{
        $stmt = $pdo->prepare("UPDATE categorie SET name = :name, description = :description WHERE id = :id");
        $stmt->bindParam(':name', $nameUpt);
        $stmt->bindParam(':description', $descriptionUpt);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $rowaffecter = $stmt->rowCount();

        if($rowaffecter > 0){
            $success = "Category updated successfully!";
        } else {
            $error = "Category not found.";
        }

    }catch(PDOException $e){
        $error = "Database error: " . $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Admin</title>
    <link rel="stylesheet" href="../Css/edit-category.css?v=<?php echo time(); ?>">
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
                <li><a id="menuDashboard"  href="dashboard.php">📋 Dashboard Admin</a></li>
                <li><a id="menuSettings" href="categories.php">📂 Categories</a></li>
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
        <h1>Edit Category</h1>

        <?php if($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <section>
            <form method="POST">
                <input type="text" name="nameUpt" placeholder="Category Name" required>
                <textarea name="descriptionUpt" placeholder="Category Description" required></textarea>
                <button type="submit">Update Category</button>
            </form>
        </section>
    </main>
</body>
</html>