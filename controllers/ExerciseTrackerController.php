<?php
class ExerciseTrackerController {
    private $exerciseModel;
    private $userModel;
    
    public function __construct() {
        $this->exerciseModel = new ExerciseTracker();
        $this->userModel = new User();
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $page = isset($_GET['exercise_page']) ? max(1, (int)$_GET['exercise_page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get exercises with pagination
        $exercises = $this->exerciseModel->getByUserId($user_id, $limit, $offset);
        
        // Get statistics
        $stats = $this->exerciseModel->getTotalStats($user_id);
        $weekly_stats = $this->exerciseModel->getWeeklyStats($user_id);
        $popular_exercises = $this->exerciseModel->getPopularExercises($user_id);
        
        $data = [
            'title' => 'Exercise Tracker - LIFEISMESS',
            'exercises' => $exercises,
            'stats' => $stats,
            'weekly_stats' => $weekly_stats,
            'popular_exercises' => $popular_exercises,
            'current_page' => $page,
            'user_name' => $_SESSION['user_name'] ?? 'User'
        ];
        
        $this->loadView('exercise/index', $data);
    }
    
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleCreate();
        }
        
        $data = [
            'title' => 'Add Exercise - LIFEISMESS',
            'error' => '',
            'success' => ''
        ];
        
        $this->loadView('exercise/create', $data);
    }
    
    private function handleCreate() {
        $user_id = $_SESSION['user_id'];
        $exercise_name = trim($_POST['exercise_name'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $calories_burned = trim($_POST['calories_burned'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        $data = [
            'title' => 'Add Exercise - LIFEISMESS',
            'error' => '',
            'success' => '',
            'exercise_name' => $exercise_name,
            'duration' => $duration,
            'calories_burned' => $calories_burned,
            'notes' => $notes
        ];
        
        // Validation
        if (empty($exercise_name)) {
            $data['error'] = 'Exercise name is required.';
        } elseif (empty($duration) || !is_numeric($duration) || $duration <= 0) {
            $data['error'] = 'Please enter a valid duration in minutes.';
        } elseif (empty($calories_burned) || !is_numeric($calories_burned) || $calories_burned < 0) {
            $data['error'] = 'Please enter a valid number of calories burned.';
        } else {
            $exercise_id = $this->exerciseModel->create($user_id, $exercise_name, $duration, $calories_burned, $notes);
            
            if ($exercise_id) {
                // Log activity
                $this->userModel->logActivity($user_id, 'exercise', "Completed $exercise_name for $duration minutes");
                
                header('Location: index.php?page=exercise&success=created');
                exit();
            } else {
                $data['error'] = 'Failed to add exercise. Please try again.';
            }
        }
        
        $this->loadView('exercise/create', $data);
    }
    
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $exercise_id = $_GET['id'] ?? null;
        if (!$exercise_id) {
            header('Location: index.php?page=exercise');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $exercise = $this->exerciseModel->getById($exercise_id, $user_id);
        
        if (!$exercise) {
            header('Location: index.php?page=exercise&error=not_found');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleEdit($exercise_id, $user_id);
        }
        
        $data = [
            'title' => 'Edit Exercise - LIFEISMESS',
            'error' => '',
            'success' => '',
            'exercise' => $exercise
        ];
        
        $this->loadView('exercise/edit', $data);
    }
    
    private function handleEdit($exercise_id, $user_id) {
        $exercise_name = trim($_POST['exercise_name'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $calories_burned = trim($_POST['calories_burned'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        $exercise = $this->exerciseModel->getById($exercise_id, $user_id);
        
        $data = [
            'title' => 'Edit Exercise - LIFEISMESS',
            'error' => '',
            'success' => '',
            'exercise' => [
                'id' => $exercise_id,
                'exercise_name' => $exercise_name,
                'duration' => $duration,
                'calories_burned' => $calories_burned,
                'notes' => $notes,
                'created_at' => $exercise['created_at']
            ]
        ];
        
        // Validation
        if (empty($exercise_name)) {
            $data['error'] = 'Exercise name is required.';
        } elseif (empty($duration) || !is_numeric($duration) || $duration <= 0) {
            $data['error'] = 'Please enter a valid duration in minutes.';
        } elseif (empty($calories_burned) || !is_numeric($calories_burned) || $calories_burned < 0) {
            $data['error'] = 'Please enter a valid number of calories burned.';
        } else {
            if ($this->exerciseModel->update($exercise_id, $user_id, $exercise_name, $duration, $calories_burned, $notes)) {
                // Log activity
                $this->userModel->logActivity($user_id, 'exercise', "Updated exercise: $exercise_name");
                
                header('Location: index.php?page=exercise&success=updated');
                exit();
            } else {
                $data['error'] = 'Failed to update exercise. Please try again.';
            }
        }
        
        $this->loadView('exercise/edit', $data);
    }
    
    public function delete() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $exercise_id = $_GET['id'] ?? null;
        if (!$exercise_id) {
            header('Location: index.php?page=exercise');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $exercise = $this->exerciseModel->getById($exercise_id, $user_id);
        
        if (!$exercise) {
            header('Location: index.php?page=exercise&error=not_found');
            exit();
        }
        
        if ($this->exerciseModel->delete($exercise_id, $user_id)) {
            // Log activity
            $this->userModel->logActivity($user_id, 'exercise', "Deleted exercise: " . $exercise['exercise_name']);
            
            header('Location: index.php?page=exercise&success=deleted');
        } else {
            header('Location: index.php?page=exercise&error=delete_failed');
        }
        exit();
    }
    
    public function stats() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        
        $stats = $this->exerciseModel->getTotalStats($user_id);
        $weekly_stats = $this->exerciseModel->getWeeklyStats($user_id);
        $popular_exercises = $this->exerciseModel->getPopularExercises($user_id, 10);
        
        $data = [
            'title' => 'Exercise Statistics - LIFEISMESS',
            'stats' => $stats,
            'weekly_stats' => $weekly_stats,
            'popular_exercises' => $popular_exercises,
            'user_name' => $_SESSION['user_name'] ?? 'User'
        ];
        
        $this->loadView('exercise/stats', $data);
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
}
?>