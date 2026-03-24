<?php 
session_start();

require_once "../db.php";

$success_login = "";
$error_login = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if(empty($email) || empty($password)){
        $error_login = "⚠️ Veuillez remplir tous les champs";
    }
    else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($user)){
                $error_login = "❌ Email ou mot de passe incorrect";
            }
            else if(!password_verify($password, $user['password'])){
                $error_login = "❌ Email ou mot de passe incorrect";
            }
            else {
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['message'] = "✅ Connexion réussie! Bienvenue!";
                
                header("Location: ../index.php");
                exit();
            }
        } catch(PDOException $e){
            $error_login = "❌ Erreur: " . $e->getMessage();
        }
    }
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Prompt Manager</title>
    <link rel="stylesheet" href="../Css/login.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Se <span>Connecter</span></h1>

        <?php if(!empty($error_login)): ?>
            <div class="message error">
                ❌ <?php echo htmlspecialchars($error_login); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($success_login)): ?>
            <div class="message success">
                ✅ <?php echo htmlspecialchars($success_login); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <input type="submit" value="Se connecter">
        </form>

        <div class="divider"></div>

        <div class="form-footer">
            Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous ici</a>
        </div>
    </div>
</body>
</html>