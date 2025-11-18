<?php
class User {
    
    public function create($name, $email, $password) {
        global $con;
        
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $name = mysqli_real_escape_string($con, $name);
            $email = mysqli_real_escape_string($con, $email);
            
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            
            if (mysqli_query($con, $sql)) {
                return true;
            } else {
                error_log("User creation failed: " . mysqli_error($con));
                return false;
            }
            
        } catch (Exception $e) {
            error_log("User creation failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function authenticate($email, $password) {
        global $con;
        
        try {
            $email = mysqli_real_escape_string($con, $email);
            
            $sql = "SELECT id, name, email, password FROM users WHERE email = '$email'";
            $result = mysqli_query($con, $sql);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                if (password_verify($password, $user['password'])) {
                    // Remove password from returned data
                    unset($user['password']);
                    return $user;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Authentication failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function emailExists($email) {
        global $con;
        
        try {
            $email = mysqli_real_escape_string($con, $email);
            
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = '$email'";
            $result = mysqli_query($con, $sql);
            
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                return $row['count'] > 0;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Email check failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getDashboardStats($userId) {
        global $con;
        
        try {
            $userId = (int)$userId; // Convert to integer for safety
            $stats = [];
            
            // Get exercise count
            $sql = "SELECT COUNT(*) as count FROM exercises WHERE user_id = $userId";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $stats['exercises_completed'] = $row['count'];
            } else {
                $stats['exercises_completed'] = 0;
            }
            
            // Get diary entries count
            $sql = "SELECT COUNT(*) as count FROM diary_entries WHERE user_id = $userId";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $stats['diary_entries'] = $row['count'];
            } else {
                $stats['diary_entries'] = 0;
            }
            
            // Get expenses count
            $sql = "SELECT COUNT(*) as count FROM expenses WHERE user_id = $userId";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $stats['expenses_tracked'] = $row['count'];
            } else {
                $stats['expenses_tracked'] = 0;
            }
            
            // Get active habits count
            $sql = "SELECT COUNT(*) as count FROM habits WHERE user_id = $userId AND active = 1";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $stats['habits_maintained'] = $row['count'];
            } else {
                $stats['habits_maintained'] = 0;
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Dashboard stats failed: " . $e->getMessage());
            return [
                'exercises_completed' => 0,
                'diary_entries' => 0,
                'expenses_tracked' => 0,
                'habits_maintained' => 0
            ];
        }
    }
    
    public function getRecentActivities($userId, $limit = 10) {
        global $con;
        
        try {
            $userId = (int)$userId;
            $limit = (int)$limit;
            
            $sql = "SELECT type, action, created_at 
                    FROM user_activities 
                    WHERE user_id = $userId 
                    ORDER BY created_at DESC 
                    LIMIT $limit";
            
            $result = mysqli_query($con, $sql);
            $activities = [];
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Calculate time ago
                    $time_ago = $this->timeAgo($row['created_at']);
                    
                    $activities[] = [
                        'type' => $row['type'],
                        'action' => $row['action'],
                        'time' => $time_ago
                    ];
                }
            }
            
            // If no activities found, return some default ones for demo
            if (empty($activities)) {
                return [
                    [
                        'type' => 'exercise',
                        'action' => 'Welcome! Start by logging your first workout',
                        'time' => 'Get started now'
                    ],
                    [
                        'type' => 'diary',
                        'action' => 'Share your thoughts in the diary section',
                        'time' => 'Coming soon'
                    ],
                    [
                        'type' => 'money',
                        'action' => 'Track your expenses to manage your budget',
                        'time' => 'Coming soon'
                    ],
                    [
                        'type' => 'habit',
                        'action' => 'Build positive habits for success',
                        'time' => 'Coming soon'
                    ]
                ];
            }
            
            return $activities;
            
        } catch (Exception $e) {
            error_log("Recent activities fetch failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Helper function to calculate time ago
    private function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        
        return floor($time/31536000) . ' years ago';
    }
    
    // Add activity to user's activity log
    public function logActivity($userId, $type, $action) {
        global $con;
        
        try {
            $userId = (int)$userId;
            $type = mysqli_real_escape_string($con, $type);
            $action = mysqli_real_escape_string($con, $action);
            
            $sql = "INSERT INTO user_activities (user_id, type, action) VALUES ($userId, '$type', '$action')";
            
            return mysqli_query($con, $sql);
            
        } catch (Exception $e) {
            error_log("Activity logging failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Get user by ID
    public function findById($id) {
        global $con;
        
        try {
            $id = (int)$id;
            
            $sql = "SELECT id, name, email, created_at FROM users WHERE id = $id";
            $result = mysqli_query($con, $sql);
            
            if ($result && mysqli_num_rows($result) > 0) {
                return mysqli_fetch_assoc($result);
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("User lookup failed: " . $e->getMessage());
            return false;
        }
    }
}
?>