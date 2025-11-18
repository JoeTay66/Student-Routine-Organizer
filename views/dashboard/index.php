<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Student';
$user_id = $_SESSION['user_id'];

$userModel = new User();

$current_hour = (int)date('H');
if ($current_hour < 12) {
    $greeting = "Good Morning";
} elseif ($current_hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

$stats = $userModel->getDashboardStats($user_id);

$recent_activities = $userModel->getRecentActivities($user_id, 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIFEISMESS - Student Life Organizer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/dashboard.css">
    <link rel="stylesheet" href="assets/sidebar.css">
</head>
<body>
    <div class="dashboard-layout">
        <?php 
        include __DIR__ . '/../../views/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <div class="dashboard-main">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-title">
                    <h1>Dashboard</h1>
                    <p>Overview of your student life</p>
                </div>
                <div class="header-actions">
                    <button class="header-btn">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button class="header-btn">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="main-content">
                <div class="container">
                    <!-- Welcome Section -->
                    <div class="welcome-section">
                        <div class="welcome-content">
                            <h1 class="welcome-title">
                                <?php echo htmlspecialchars($greeting); ?>, <?php echo htmlspecialchars($user_name); ?>! 
                                <span class="wave">👋</span>
                            </h1>
                            <p class="welcome-subtitle">Ready to organize your student life today? Let's make progress together.</p>
                        </div>
                        <div class="quick-stats">
                            <div class="stat-item">
                                <div class="stat-icon exercise">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-number"><?php echo $stats['exercises_completed']; ?></span>
                                    <span class="stat-label">Workouts</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon diary">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-number"><?php echo $stats['diary_entries']; ?></span>
                                    <span class="stat-label">Entries</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon money">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-number"><?php echo $stats['expenses_tracked']; ?></span>
                                    <span class="stat-label">Expenses</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon habit">
                                    <i class="fas fa-infinity"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-number"><?php echo $stats['habits_maintained']; ?></span>
                                    <span class="stat-label">Habits</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Grid -->
                    <div class="dashboard-grid">
                        <!-- Tools Section -->
                        <div class="dashboard-section">
                            <h2 class="section-title">
                                <i class="fas fa-tools"></i> Your Tools
                            </h2>
                            <div class="tools-grid">
                                <!-- Exercise Tracker -->
                                <div class="tool-card exercise-card">
                                    <div class="tool-header">
                                        <div class="tool-icon">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <div class="tool-info">
                                            <h3>Exercise Tracker</h3>
                                            <p>Track your fitness journey</p>
                                        </div>
                                    </div>
                                    <div class="tool-stats">
                                        <div class="tool-stat">
                                            <span class="stat-value"><?php echo $stats['exercises_completed']; ?></span>
                                            <span class="stat-text">Workouts Completed</span>
                                        </div>
                                    </div>
                                    <div class="tool-actions">
                                        <a href="views/ExerciseTracker/index.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-play"></i> Start Workout
                                        </a>
                                    </div>
                                </div>

                                <!-- Diary Journal -->
                                <div class="tool-card diary-card">
                                    <div class="tool-header">
                                        <div class="tool-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div class="tool-info">
                                            <h3>Diary Journal</h3>
                                            <p>Reflect on your day</p>
                                        </div>
                                    </div>
                                    <div class="tool-stats">
                                        <div class="tool-stat">
                                            <span class="stat-value"><?php echo $stats['diary_entries']; ?></span>
                                            <span class="stat-text">Entries Written</span>
                                        </div>
                                    </div>
                                    <div class="tool-actions">
                                        <a href="index.php?page=diary" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-pen"></i> Write Entry
                                        </a>
                                    </div>
                                </div>

                                <!-- Money Tracker -->
                                <div class="tool-card money-card">
                                    <div class="tool-header">
                                        <div class="tool-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="tool-info">
                                            <h3>Money Tracker</h3>
                                            <p>Manage your budget</p>
                                        </div>
                                    </div>
                                    <div class="tool-stats">
                                        <div class="tool-stat">
                                            <span class="stat-value"><?php echo $stats['expenses_tracked']; ?></span>
                                            <span class="stat-text">Expenses Tracked</span>
                                        </div>
                                    </div>
                                    <div class="tool-actions">
                                        <a href="index.php?page=money" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-plus"></i> Add Expense
                                        </a>
                                    </div>
                                </div>

                                <!-- Habit Tracker -->
                                <div class="tool-card habit-card">
                                    <div class="tool-header">
                                        <div class="tool-icon">
                                            <i class="fas fa-infinity"></i>
                                        </div>
                                        <div class="tool-info">
                                            <h3>Habit Tracker</h3>
                                            <p>Build lasting habits</p>
                                        </div>
                                    </div>
                                    <div class="tool-stats">
                                        <div class="tool-stat">
                                            <span class="stat-value"><?php echo $stats['habits_maintained']; ?></span>
                                            <span class="stat-text">Active Habits</span>
                                        </div>
                                    </div>
                                    <div class="tool-actions">
                                        <a href="index.php?page=habit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-target"></i> Check Habits
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Section -->
                        <div class="dashboard-section activity-section">
                            <h2 class="section-title">
                                <i class="fas fa-clock"></i> Recent Activity
                            </h2>
                            <div class="activity-list">
                                <?php if (!empty($recent_activities)): ?>
                                    <?php foreach ($recent_activities as $activity): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon <?php echo $activity['type']; ?>">
                                                <?php
                                                $icons = [
                                                    'exercise' => 'fas fa-heartbeat',
                                                    'diary' => 'fas fa-journal-whills',
                                                    'money' => 'fas fa-coins',
                                                    'habit' => 'fas fa-infinity'
                                                ];
                                                ?>
                                                <i class="<?php echo $icons[$activity['type']] ?? 'fas fa-info-circle'; ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <p class="activity-text"><?php echo htmlspecialchars($activity['action']); ?></p>
                                                <span class="activity-time"><?php echo htmlspecialchars($activity['time']); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="activity-item">
                                        <div class="activity-icon exercise">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text">Welcome to LIFEISMESS! Start by exploring our tools.</p>
                                            <span class="activity-time">Get started now</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="dashboard-section">
                            <h2 class="section-title">
                                <i class="fas fa-chart-line"></i> Weekly Progress
                            </h2>
                            <div class="progress-cards">
                                <div class="progress-card">
                                    <div class="progress-header">
                                        <span class="progress-title">This Week</span>
                                        <?php 
                                        $total_activities = array_sum($stats);
                                        $trend_class = $total_activities > 10 ? 'up' : ($total_activities > 5 ? 'neutral' : 'down');
                                        $trend_symbol = $total_activities > 10 ? '↗' : ($total_activities > 5 ? '→' : '↙');
                                        $trend_percentage = min(100, ($total_activities * 8)); 
                                        ?>
                                        <span class="progress-trend <?php echo $trend_class; ?>">
                                            <?php echo $trend_symbol; ?> +<?php echo $trend_percentage; ?>%
                                        </span>
                                    </div>
                                    <div class="progress-stats">
                                        <div class="progress-stat">
                                            <span class="progress-number"><?php echo $total_activities; ?></span>
                                            <span class="progress-label">Activities</span>
                                        </div>
                                        <div class="progress-stat">
                                            <span class="progress-number"><?php echo min(100, $trend_percentage); ?>%</span>
                                            <span class="progress-label">Goal Rate</span>
                                        </div>
                                    </div>
                                    <div class="progress-message">
                                        <?php if ($total_activities >= 15): ?>
                                            <p>Excellent work! You're exceeding your goals.</p>
                                        <?php elseif ($total_activities >= 8): ?>
                                            <p>Great progress! Keep up the good work.</p>
                                        <?php elseif ($total_activities >= 3): ?>
                                            <p>Good start! Try to be more active this week.</p>
                                        <?php else: ?>
                                            <p>Time to get started! Set some goals and track your progress.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div> <!-- End dashboard-main -->
    </div> <!-- End dashboard-layout -->

    <!-- Dashboard Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dashboard functionality
            initializeDashboard();
            
            // Initialize mobile menu toggle
            initializeMobileMenu();
            
            // Initialize tool cards interactions
            initializeToolCards();
            
            // Animate numbers on page load
            animateStatNumbers();
        });

        // Dashboard initialization
        function initializeDashboard() {
            // Add smooth transitions to all elements
            const elements = document.querySelectorAll('.tool-card, .activity-item, .quick-action-btn');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
                el.classList.add('fade-in-up');
            });
        }

        // Animate stat numbers
        function animateStatNumbers() {
            const statNumbers = document.querySelectorAll('.stat-number, .stat-value, .progress-number');
            statNumbers.forEach(element => {
                const finalNumber = parseInt(element.textContent);
                if (!isNaN(finalNumber) && finalNumber > 0) {
                    animateNumber(element, 0, finalNumber, 1000);
                }
            });
        }

        // Mobile menu toggle
        function initializeMobileMenu() {
            // Create mobile menu toggle button for smaller screens
            if (window.innerWidth <= 1024) {
                const header = document.querySelector('.dashboard-header');
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'mobile-menu-toggle';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.style.cssText = `
                    background: none;
                    border: none;
                    font-size: 1.25rem;
                    color: #64748b;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
                    cursor: pointer;
                    margin-right: 1rem;
                `;
                
                header.insertBefore(toggleBtn, header.firstChild);
                
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.dashboard-sidebar');
                    const isOpen = sidebar.style.transform === 'translateX(0px)';
                    
                    if (isOpen) {
                        sidebar.style.transform = 'translateX(-100%)';
                        this.innerHTML = '<i class="fas fa-bars"></i>';
                    } else {
                        sidebar.style.transform = 'translateX(0px)';
                        this.innerHTML = '<i class="fas fa-times"></i>';
                    }
                });
            }
        }

        // Tool cards interactions
        function initializeToolCards() {
            const toolCards = document.querySelectorAll('.tool-card');
            
            toolCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.08)';
                });
            });

            // Quick action buttons
            const quickActionBtns = document.querySelectorAll('.quick-action-btn');
            quickActionBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Add ripple effect
                    createRipple(e, this);
                    
                    // Show feedback
                    const action = this.querySelector('span').textContent;
                    showToast(`Opening ${action}...`, 'success');
                });
            });
        }

        // Utility Functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function animateNumber(element, start, end, duration) {
            const startTime = performance.now();
            const range = end - start;
            
            function updateNumber(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(start + (range * easeOutCubic(progress)));
                
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = end;
                }
            }
            
            requestAnimationFrame(updateNumber);
        }

        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 2rem;
                right: 2rem;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                border-radius: 0.5rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                max-width: 300px;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after delay
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        function createRipple(event, element) {
            const circle = document.createElement('span');
            const diameter = Math.max(element.clientWidth, element.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - element.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - element.offsetTop - radius}px`;
            circle.style.position = 'absolute';
            circle.style.borderRadius = '50%';
            circle.style.background = 'rgba(255, 255, 255, 0.3)';
            circle.style.transform = 'scale(0)';
            circle.style.animation = 'ripple 0.6s linear';
            circle.style.pointerEvents = 'none';
            
            const ripple = element.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            circle.classList.add('ripple');
            element.appendChild(circle);
        }
    </script>
</body>
</html>