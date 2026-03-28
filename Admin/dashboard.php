<?php
session_start();
require_once '../db.php';

// Récupérer les statistiques
try {
    // Total des prompts
    $stmtTotalPrompts = $pdo->prepare("SELECT COUNT(*) as total FROM prompt");
    $stmtTotalPrompts->execute();
    $totalPrompts = $stmtTotalPrompts->fetch(PDO::FETCH_ASSOC)['total'];

    // Total des utilisateurs
    $stmtTotalUsers = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $stmtTotalUsers->execute();
    $totalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC)['total'];

    // Total des catégories
    $stmtTotalCategories = $pdo->prepare("SELECT COUNT(*) as total FROM categorie");
    $stmtTotalCategories->execute();
    $totalCategories = $stmtTotalCategories->fetch(PDO::FETCH_ASSOC)['total'];

    // Top 5 utilisateurs (qui ont créé le plus de prompts)
    $stmtTopUsers = $pdo->prepare("
        SELECT u.username, u.email, COUNT(p.id) as prompt_count 
        FROM users u 
        LEFT JOIN prompt p ON u.id = p.user_id 
        WHERE u.role = 'user'
        GROUP BY u.id 
        ORDER BY prompt_count DESC 
        LIMIT 5
    ");
    $stmtTopUsers->execute();
    $topUsers = $stmtTopUsers->fetchAll(PDO::FETCH_ASSOC);

    // Prompts par catégorie
    $stmtPromptsByCategory = $pdo->prepare("
        SELECT c.name, COUNT(p.id) as count 
        FROM categorie c 
        LEFT JOIN prompt p ON c.id = p.categorie_id 
        GROUP BY c.id 
        ORDER BY count DESC
    ");
    $stmtPromptsByCategory->execute();
    $categoryData = $stmtPromptsByCategory->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Prompt Repository</title>
    <link rel="stylesheet" href="../Css/dashboardAdmin.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a id="menuDashboard" class="active" href="dashboard.php">📋 Dashboard Admin</a></li>
                <li><a id="menuSettings" href="categories.php">📂 Categories</a></li>
            </ul>
        </nav>

        <div class="user-profile" id="profileCard">
            <img src="../img/user.svg" alt="User Avatar" class="user-avatar" id="userAvatar">
            <div class="user-info" id="userInfo">
                <div class="user-name" id="userName"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Administrateur'; ?></div>
                <div class="user-role" id="userRole">Admin Account</div>
            </div>
        </div>
        <a id="logoutButton" class="logout-link" href="../Auth/logout.php">Déconnexion</a>
    </header>

    <main id="dashboardContent">
        <section class="header-section">
            <h1>Tableau de Bord Admin</h1>
            <p class="subtitle">Bienvenue sur votre espace de gestion</p>
        </section>

        <!-- Statistiques Principales -->
        <section class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalPrompts; ?></div>
                    <div class="stat-label">Prompts Total</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Utilisateurs Actifs</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📂</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalCategories; ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
        </section>

        <!-- Graphiques Modernes -->
        <section class="charts-section">
            <div class="charts-grid">
                <div class="chart-container">
                    <h2>📊 Prompts par Catégorie</h2>
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="chart-container">
                    <h2>🥧 Distribution en Pourcentage</h2>
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Top Utilisateurs -->
        <section class="top-users-section">
            <h2>🏆 Top Utilisateurs Contributeurs</h2>
            <div class="users-grid">
                <?php if(count($topUsers) > 0): ?>
                    <?php foreach($topUsers as $index => $user): ?>
                        <div class="user-card rank-<?php echo $index + 1; ?>">
                            <div class="rank-badge"><?php echo $index + 1; ?></div>
                            <div class="user-avatar-large"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                            <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                            <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                            <div class="prompt-count">
                                <span class="count-number"><?php echo $user['prompt_count']; ?></span>
                                <span class="count-label">prompts</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data">Aucun utilisateur pour le moment</div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        // Données
        const categoryData = <?php echo json_encode($categoryData); ?>;
        const categories = categoryData.map(d => d.name);
        const counts = categoryData.map(d => d.count);

        // Couleurs dégradées modernes
        const colors = [
            'rgba(239, 68, 68, 0.8)',
            'rgba(219, 39, 39, 0.8)',
            'rgba(185, 28, 28, 0.8)',
            'rgba(153, 27, 27, 0.8)',
            'rgba(127, 29, 29, 0.8)',
            'rgba(91, 33, 33, 0.8)'
        ];

        const borderColors = [
            '#ef4444',
            '#dc2626',
            '#b91c1c',
            '#991b1b',
            '#7f1d1d',
            '#5b2121'
        ];

        // ========== GRAPHIQUE 1: BARRES ==========
        const ctx1 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Nombre de Prompts',
                    data: counts,
                    backgroundColor: colors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 10,
                    hoverBackgroundColor: 'rgba(239, 68, 68, 1)',
                    hoverBorderColor: '#ef4444',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#ffffff',
                            font: { size: 13, weight: 'bold' },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.x + ' prompts';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { 
                            color: '#8b92a5',
                            font: { size: 12 }
                        },
                        grid: { 
                            color: 'rgba(239, 68, 68, 0.1)',
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: { 
                            color: '#8b92a5',
                            font: { size: 13, weight: '500' }
                        },
                        grid: { 
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // ========== GRAPHIQUE 2: PIE (CAMEMBERT) ==========
        const ctx2 = document.getElementById('categoryPieChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: counts,
                    backgroundColor: colors,
                    borderColor: '#1a0f0f',
                    borderWidth: 3,
                    hoverBorderColor: '#ef4444',
                    hoverBorderWidth: 4,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            font: { size: 12, weight: '500' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>