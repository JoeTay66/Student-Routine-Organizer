<?php
$current_page = $_GET['page'];
$user_name = $_SESSION['user_name'] ?? 'Student';
?>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>LIFEISMESS</span>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
            <a href="index.php?page=dashboard" class="menu-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="menu-item <?php echo $current_page === 'exercise' ? 'active' : ''; ?>">
            <a href="views/ExerciseTracker/index.php" class="menu-link">
                <i class="fas fa-heartbeat"></i>
                <span>Exercise Tracker</span>
            </a>
        </li>
        <li class="menu-item <?php echo $current_page === 'diary' ? 'active' : ''; ?>">
            <a href="index.php?page=diary" class="menu-link">
                <i class="fas fa-book"></i>
                <span>Diary Journal</span>
            </a>
        </li>
        <li class="menu-item <?php echo $current_page === 'money' ? 'active' : ''; ?>">
            <a href="index.php?page=money" class="menu-link">
                <i class="fas fa-coins"></i>
                <span>Money Tracker</span>
            </a>
        </li>
        <li class="menu-item <?php echo $current_page === 'habit' ? 'active' : ''; ?>">
            <a href="index.php?page=habit" class="menu-link">
                <i class="fas fa-infinity"></i>
                <span>Habit Tracker</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                <div class="user-status">Student</div>
            </div>
        </div>
        <a href="index.php?page=logout" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>