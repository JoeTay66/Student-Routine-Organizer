<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $data = [
            'title' => 'Login - LIFEISMESS',
            'error' => '',
            'email' => ''
        ];
        
        $this->loadView('auth/login', $data);
    }
    
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit();
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $data = [
            'title' => 'Login - LIFEISMESS',
            'error' => '',
            'email' => $email
        ];
        
        if (empty($email) || empty($password)) {
            $data['error'] = 'Please fill in all fields.';
            $this->loadView('auth/login', $data);
            return;
        }
        
        $user = $this->userModel->authenticate($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header('Location: index.php?page=dashboard');
            exit();
        } else {
            $data['error'] = 'Invalid email or password.';
            $this->loadView('auth/login', $data);
        }
    }
    
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $data = [
            'title' => 'Register - LIFEISMESS',
            'error' => '',
            'success' => '',
            'name' => '',
            'email' => ''
        ];
        
        $this->loadView('auth/register', $data);
    }
    
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=register');
            exit();
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        $data = [
            'title' => 'Register - LIFEISMESS',
            'error' => '',
            'success' => '',
            'name' => $name,
            'email' => $email
        ];
        
        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $data['error'] = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $data['error'] = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirm_password) {
            $data['error'] = 'Passwords do not match.';
        } elseif ($this->userModel->emailExists($email)) {
            $data['error'] = 'Email address is already registered.';
        } else {
            if ($this->userModel->create($name, $email, $password)) {
                $data['success'] = 'Account created successfully! You can now login.';
                $data['name'] = '';
                $data['email'] = '';
            } else {
                $data['error'] = 'Registration failed. Please try again.';
            }
        }
        
        $this->loadView('auth/register', $data);
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?logout=success');
        exit();
    }
    
    public function showDashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $user_name = $_SESSION['user_name'] ?? 'Student';
        $current_time = date('H');
        $greeting = '';
        
        if ($current_time < 12) {
            $greeting = 'Good morning';
        } elseif ($current_time < 17) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }
        
        $stats = $this->userModel->getDashboardStats($_SESSION['user_id']);
        $recent_activities = $this->userModel->getRecentActivities($_SESSION['user_id']);
        
        $data = [
            'title' => 'Dashboard - LIFEISMESS',
            'user_name' => $user_name,
            'greeting' => $greeting,
            'stats' => $stats,
            'recent_activities' => $recent_activities
        ];
        
        // 修复这里的路径
        $this->loadDashboardView($data);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("View file not found: $viewFile");
        }
    }
    
    // 专门为dashboard创建的视图加载函数
    private function loadDashboardView($data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/dashboard/index.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("Dashboard view file not found: $viewFile");
        }
    }
}
?>