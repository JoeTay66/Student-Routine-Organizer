<?php
// index.php - Main Router for MVC Structure
session_start();

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include configuration
require_once 'config.php';

// Autoload controllers
spl_autoload_register(function ($class) {
    $controllerFile = __DIR__ . '/controllers/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
    }
    
    $modelFile = __DIR__ . '/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
    }
});

// Get page and action from URL parameters
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Check for logout success message
$logout_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = 'You have been successfully logged out.';
}

// Route handling
try {
    switch ($page) {
        case 'home':
            showHomePage($logout_message);
            break;
            
        case 'login':
            $controller = new AuthController();
            if ($action === 'handle') {
                $controller->handleLogin();
            } else {
                $controller->showLogin();
            }
            break;
            
        case 'register':
            $controller = new AuthController();
            if ($action === 'handle') {
                $controller->handleRegister();
            } else {
                $controller->showRegister();
            }
            break;
            
        case 'dashboard':
            $controller = new AuthController();
            $controller->showDashboard();
            break;
            
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
            
            
        // Placeholder for other modules
        case 'diary':
            echo '<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #f8fafc; font-family: Inter, sans-serif;">
                    <div style="text-align: center; padding: 3rem; background: #1e293b; border-radius: 1rem; border: 1px solid #334155;">
                        <i class="fas fa-book" style="font-size: 4rem; color: #667eea; margin-bottom: 1rem;"></i>
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; background: linear-gradient(135deg, #667eea, #f093fb); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Diary Journal</h1>
                        <p style="color: #cbd5e1; margin-bottom: 2rem;">Coming Soon! We\'re working hard to bring you an amazing journaling experience.</p>
                        <a href="index.php?page=dashboard" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #f093fb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                  </div>';
            break;
            
        case 'money':
            echo '<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #f8fafc; font-family: Inter, sans-serif;">
                    <div style="text-align: center; padding: 3rem; background: #1e293b; border-radius: 1rem; border: 1px solid #334155;">
                        <i class="fas fa-wallet" style="font-size: 4rem; color: #fbbf24; margin-bottom: 1rem;"></i>
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; background: linear-gradient(135deg, #667eea, #f093fb); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Money Tracker</h1>
                        <p style="color: #cbd5e1; margin-bottom: 2rem;">Coming Soon! Take control of your finances with our comprehensive money management tools.</p>
                        <a href="index.php?page=dashboard" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #f093fb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                  </div>';
            break;
            
        case 'habit':
            echo '<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #f8fafc; font-family: Inter, sans-serif;">
                    <div style="text-align: center; padding: 3rem; background: #1e293b; border-radius: 1rem; border: 1px solid #334155;">
                        <i class="fas fa-target" style="font-size: 4rem; color: #8b5cf6; margin-bottom: 1rem;"></i>
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; background: linear-gradient(135deg, #667eea, #f093fb); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Habit Tracker</h1>
                        <p style="color: #cbd5e1; margin-bottom: 2rem;">Coming Soon! Build lasting habits and track your progress with our powerful habit management system.</p>
                        <a href="index.php?page=dashboard" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #f093fb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                  </div>';
            break;
            
        default:
            // 404 page
            http_response_code(404);
            echo '<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; color: #f8fafc; font-family: Inter, sans-serif;">
                    <div style="text-align: center; padding: 3rem; background: #1e293b; border-radius: 1rem; border: 1px solid #334155;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; color: #f8fafc;">Page Not Found</h1>
                        <p style="color: #cbd5e1; margin-bottom: 2rem;">The page you are looking for does not exist.</p>
                        <a href="index.php" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #f093fb); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;">
                            <i class="fas fa-home"></i> Go Home
                        </a>
                    </div>
                  </div>';
            break;
    }
} catch (Exception $e) {
    // Error handling
    error_log($e->getMessage());
    echo "An error occurred. Please try again.";
}

function showHomePage($logout_message = '') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LIFEISMESS - Student Routine Organizer</title>
        <link rel="stylesheet" href="assets/style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    </head>
    <body>
        <!-- Header -->
        <header class="main-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <h1><i class="fas fa-graduation-cap"></i> LIFEISMESS</h1>
                        <p class="tagline">Organize Your Student Life</p>
                    </div>
                    <div class="main-nav">
                        <ul>
                            <li><a href="#home" class="nav-link active">Home</a></li>
                            <li><a href="#features" class="nav-link">Features</a></li>
                            <li><a href="#about" class="nav-link">About</a></li>
                        </ul>
                        <div class="auth-buttons">
                            <a href="index.php?page=login" class="btn btn-secondary" style="margin-right: 0.5rem;">Login</a>
                            <a href="index.php?page=register" class="btn btn-primary">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php if ($logout_message): ?>
            <div style="background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.3); color: #86efac; padding: 1rem; text-align: center; margin-top: 80px;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($logout_message); ?>
            </div>
        <?php endif; ?>

        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h2 class="hero-title">Master Your Student Routine</h2>
                    <p class="hero-subtitle">
                        Transform chaos into clarity with our all-in-one platform designed specifically for students. 
                        Track your fitness, manage finances, journal your thoughts, and build lasting habits.
                    </p>
                    <div class="hero-buttons">
                        <a href="index.php?page=register" class="btn btn-primary">Get Started Free</a>
                        <a href="index.php?page=login" class="btn btn-secondary">Already a Member?</a>
                    </div>
                </div>
                <div class="hero-image">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features-section">
            <div class="container">
                <h2 class="section-title">Four Essential Tools for Student Success</h2>
                <div class="features-grid">
                    <!-- Exercise Tracker -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>Exercise Tracker</h3>
                        <p>Stay fit and healthy with our comprehensive exercise tracking system. Log workouts, set fitness goals, and monitor your progress.</p>
                        <a href="views/ExerciseTracker/index.php" class="feature-link">
                            <span>Start Tracking</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Diary Journal -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Diary Journal</h3>
                        <p>Reflect on your daily experiences and track your personal growth. Write, reflect, and discover patterns in your student journey.</p>
                        <a href="index.php?page=diary" class="feature-link">
                            <span>Start Writing</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Money Tracker -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Money Tracker</h3>
                        <p>Take control of your student budget. Track expenses, set spending limits, and develop healthy financial habits early.</p>
                        <a href="index.php?page=money" class="feature-link">
                            <span>Manage Money</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Habit Tracker -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-infinity"></i>
                        </div>
                        <h3>Habit Tracker</h3>
                        <p>Build positive habits and break bad ones. Create custom habits, set reminders, and visualize your progress over time.</p>
                        <a href="index.php?page=habit" class="feature-link">
                            <span>Build Habits</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">4</div>
                        <div class="stat-label">Essential Tools</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Student Focused</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Available</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Possibilities</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Why LIFEISMESS?</h2>
                        <p>
                            Student life can feel overwhelming and chaotic. Between classes, assignments, social activities, 
                            and personal growth, it's easy to lose track of what matters most.
                        </p>
                        <p>
                            LIFEISMESS acknowledges that life gets messy, but provides you with the tools to organize 
                            that mess into meaningful progress. Our platform brings together the four pillars of student 
                            wellness: physical health, mental well-being, financial literacy, and personal development.
                        </p>
                        <div class="about-features">
                            <div class="about-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Simple and intuitive interface</span>
                            </div>
                            <div class="about-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Designed specifically for students</span>
                            </div>
                            <div class="about-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Track multiple aspects of your life</span>
                            </div>
                            <div class="about-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Build lasting positive habits</span>
                            </div>
                        </div>
                    </div>
                    <div class="about-image">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Organize Your Life?</h2>
                    <p>Join thousands of students who have transformed their daily routines with LIFEISMESS.</p>
                    <a href="index.php?page=register" class="btn btn-primary btn-large">Start Your Journey</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3><i class="fas fa-graduation-cap"></i> LIFEISMESS</h3>
                        <p>Empowering students to live organized, purposeful lives through smart tracking and habit building.</p>
                    </div>
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#home">Home</a></li>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Tools</h4>
                        <ul>
                            <li><a href="index.php?page=exercise">Exercise Tracker</a></li>
                            <li><a href="index.php?page=diary">Diary Journal</a></li>
                            <li><a href="index.php?page=money">Money Tracker</a></li>
                            <li><a href="index.php?page=habit">Habit Tracker</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Connect</h4>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 LIFEISMESS. All rights reserved. Made with ❤️ for students.</p>
                </div>
            </div>
        </footer>

        <script>
            // Smooth scrolling and other JavaScript from previous version
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            window.addEventListener('scroll', () => {
                const sections = document.querySelectorAll('section[id]');
                const navLinks = document.querySelectorAll('.nav-link');
                
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.feature-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        </script>
    </body>
    </html>
    <?php
}
?>