<?php 
session_start() ;

require_once "../db.php";

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $confirm = $_POST["confirm_password"] ?? "";

    if (empty($username) || empty($password) || empty($email) || empty($confirm)){
        $error = "⚠️ Veuillez remplir tous les champs";
    }
    else if($password !== $confirm){
        $error = "❌ Les mots de passe ne correspondent pas";
    }
    else if(strlen($password) < 6){
        $error = "❌ Le mot de passe doit contenir au moins 6 caractères";
    }
    else {
        $query = "SELECT email FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if($user){
            $error = "❌ Cet email ou ce nom d'utilisateur est déjà utilisé";
        }
        else{
            try{
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();

                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['message'] = "✅ Inscription réussie! Bienvenue!";
                
                header("Location: ../index.php");
                exit();
            }catch(PDOException $e){
                $error = "❌ Erreur: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Prompt Manager</title>
    <link rel="stylesheet" href="../Css/register.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Créer un <span>Compte</span></h1>
        
        <?php if($error): ?>
            <div class="message error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="message success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <input type="submit" value="S'inscrire">
        </form>

        <div class="form-footer">
            Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a>
        </div>
    </div>
</body>
</html>
