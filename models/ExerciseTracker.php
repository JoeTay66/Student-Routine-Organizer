<?php
class ExerciseTracker {
    
    public function create($user_id, $exercise_name, $duration_minutes, $calories_burned, $notes = '') {
        global $con;
        
        try {
            $user_id = (int)$user_id;
            $exercise_name = mysqli_real_escape_string($con, $exercise_name);
            $duration_minutes = (int)$duration_minutes;
            $calories_burned = (int)$calories_burned;
            $notes = mysqli_real_escape_string($con, $notes);
            
            $sql = "INSERT INTO exercises (user_id, exercise_name, duration_minutes, calories_burned, notes, exercise_date) 
                    VALUES ($user_id, '$exercise_name', $duration_minutes, $calories_burned, '$notes', CURDATE())";
            
            if (mysqli_query($con, $sql)) {
                return mysqli_insert_id($con);
            } else {
                error_log("Exercise creation failed: " . mysqli_error($con));
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Exercise creation failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getByUserId($user_id, $limit = 10, $offset = 0) {
        global $con;
        
        try {
            $user_id = (int)$user_id;
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT id, exercise_name, duration_minutes, calories_burned, notes, exercise_date, created_at 
                    FROM exercises 
                    WHERE user_id = $user_id 
                    ORDER BY exercise_date DESC, created_at DESC 
                    LIMIT $limit OFFSET $offset";
            
            $result = mysqli_query($con, $sql);
            $exercises = [];
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $exercises[] = $row;
                }
            }
            
            return $exercises;
            
        } catch (Exception $e) {
            error_log("Exercise fetch failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id, $user_id) {
        global $con;
        
        try {
            $id = (int)$id;
            $user_id = (int)$user_id;
            
            $sql = "SELECT id, exercise_name, duration, calories_burned, notes, created_at 
                    FROM exercises 
                    WHERE id = $id AND user_id = $user_id";
            
            $result = mysqli_query($con, $sql);
            
            if ($result && mysqli_num_rows($result) > 0) {
                return mysqli_fetch_assoc($result);
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Exercise lookup failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $user_id, $exercise_name, $duration, $calories_burned, $notes = '') {
        global $con;
        
        try {
            $id = (int)$id;
            $user_id = (int)$user_id;
            $exercise_name = mysqli_real_escape_string($con, $exercise_name);
            $duration = (int)$duration;
            $calories_burned = (int)$calories_burned;
            $notes = mysqli_real_escape_string($con, $notes);
            
            $sql = "UPDATE exercises 
                    SET exercise_name = '$exercise_name', 
                        duration = $duration, 
                        calories_burned = $calories_burned, 
                        notes = '$notes'
                    WHERE id = $id AND user_id = $user_id";
            
            return mysqli_query($con, $sql);
            
        } catch (Exception $e) {
            error_log("Exercise update failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id, $user_id) {
        global $con;
        
        try {
            $id = (int)$id;
            $user_id = (int)$user_id;
            
            $sql = "DELETE FROM exercises WHERE id = $id AND user_id = $user_id";
            
            return mysqli_query($con, $sql);
            
        } catch (Exception $e) {
            error_log("Exercise deletion failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getTotalStats($user_id) {
        global $con;
        
        try {
            $user_id = (int)$user_id;
            
            $sql = "SELECT 
                        COUNT(*) as total_exercises,
                        SUM(duration_minutes) as total_duration,
                        SUM(calories_burned) as total_calories,
                        AVG(duration_minutes) as avg_duration
                    FROM exercises 
                    WHERE user_id = $user_id";
            
            $result = mysqli_query($con, $sql);
            
            if ($result) {
                $stats = mysqli_fetch_assoc($result);
                return [
                    'total_exercises' => (int)$stats['total_exercises'],
                    'total_duration' => (int)$stats['total_duration'],
                    'total_calories' => (int)$stats['total_calories'],
                    'avg_duration' => round($stats['avg_duration'], 1)
                ];
            }
            
            return [
                'total_exercises' => 0,
                'total_duration' => 0,
                'total_calories' => 0,
                'avg_duration' => 0
            ];
            
        } catch (Exception $e) {
            error_log("Exercise stats failed: " . $e->getMessage());
            return [
                'total_exercises' => 0,
                'total_duration' => 0,
                'total_calories' => 0,
                'avg_duration' => 0
            ];
        }
    }
    
    public function getWeeklyStats($user_id) {
        global $con;
        
        try {
            $user_id = (int)$user_id;
            
            $sql = "SELECT 
                        exercise_date as date,
                        COUNT(*) as exercises_count,
                        SUM(duration_minutes) as total_duration,
                        SUM(calories_burned) as total_calories
                    FROM exercises 
                    WHERE user_id = $user_id 
                        AND exercise_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY exercise_date
                    ORDER BY exercise_date DESC";
            
            $result = mysqli_query($con, $sql);
            $weekly_stats = [];
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $weekly_stats[] = [
                        'date' => $row['date'],
                        'exercises_count' => (int)$row['exercises_count'],
                        'total_duration' => (int)$row['total_duration'],
                        'total_calories' => (int)$row['total_calories']
                    ];
                }
            }
            
            return $weekly_stats;
            
        } catch (Exception $e) {
            error_log("Weekly stats failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getPopularExercises($user_id, $limit = 5) {
        global $con;
        
        try {
            $user_id = (int)$user_id;
            $limit = (int)$limit;
            
            $sql = "SELECT 
                        exercise_name,
                        COUNT(*) as frequency,
                        SUM(duration) as total_duration,
                        AVG(calories_burned) as avg_calories
                    FROM exercises 
                    WHERE user_id = $user_id 
                    GROUP BY exercise_name 
                    ORDER BY frequency DESC, total_duration DESC
                    LIMIT $limit";
            
            $result = mysqli_query($con, $sql);
            $popular_exercises = [];
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $popular_exercises[] = [
                        'exercise_name' => $row['exercise_name'],
                        'frequency' => (int)$row['frequency'],
                        'total_duration' => (int)$row['total_duration'],
                        'avg_calories' => round($row['avg_calories'], 1)
                    ];
                }
            }
            
            return $popular_exercises;
            
        } catch (Exception $e) {
            error_log("Popular exercises fetch failed: " . $e->getMessage());
            return [];
        }
    }
}
?>