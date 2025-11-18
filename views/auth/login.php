<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Soft UI Clean Design */
        :root {
            --soft-bg: #f8f9fa;
            --soft-white: #ffffff;
            --soft-gray-100: #f1f3f4;
            --soft-gray-200: #e9ecef;
            --soft-gray-300: #dee2e6;
            --soft-gray-400: #ced4da;
            --soft-gray-500: #adb5bd;
            --soft-gray-600: #6c757d;
            --soft-gray-700: #495057;
            --soft-gray-800: #343a40;
            --soft-gray-900: #212529;
            --soft-primary: #5e72e4;
            --soft-primary-dark: #4c63d2;
            --soft-secondary: #8392ab;
            --soft-success: #2dce89;
            --soft-danger: #f5365c;
            --soft-warning: #fb6340;
            --soft-info: #11cdef;
            --soft-shadow-xs: 0 3px 6px rgba(0, 0, 0, 0.07);
            --soft-shadow-sm: 0 5px 10px rgba(0, 0, 0, 0.08);
            --soft-shadow-md: 0 8px 25px rgba(0, 0, 0, 0.1);
            --soft-shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.1);
            --soft-shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.1);
            --soft-radius-sm: 0.25rem;
            --soft-radius: 0.5rem;
            --soft-radius-lg: 0.75rem;
            --soft-radius-xl: 1rem;
            --soft-font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            font-family: var(--soft-font-family);
            background: var(--soft-bg);
            color: var(--soft-gray-700);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--soft-bg) 0%, #ffffff 100%);
            padding: 2rem 1rem;
            position: relative;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(94, 114, 228, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(45, 206, 137, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-card {
            background: var(--soft-white);
            border-radius: var(--soft-radius-xl);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            box-shadow: var(--soft-shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.5s ease-out;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--soft-primary), var(--soft-info));
            border-radius: var(--soft-radius-xl) var(--soft-radius-xl) 0 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--soft-gray-800);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .auth-header h1 i {
            color: var(--soft-primary);
            margin-right: 0.5rem;
        }

        .auth-header p {
            color: var(--soft-gray-600);
            font-size: 0.95rem;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--soft-gray-700);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--soft-white);
            border: 1px solid var(--soft-gray-300);
            border-radius: var(--soft-radius);
            color: var(--soft-gray-700);
            font-size: 0.875rem;
            transition: all 0.15s ease;
            box-shadow: var(--soft-shadow-xs);
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--soft-primary);
            box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1), var(--soft-shadow-sm);
            background: var(--soft-white);
        }

        .form-input:hover {
            box-shadow: var(--soft-shadow-sm);
        }

        .form-input::placeholder {
            color: var(--soft-gray-500);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: var(--soft-radius);
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--soft-primary);
            color: white;
            box-shadow: var(--soft-shadow-sm);
            border: 1px solid var(--soft-primary);
        }

        .btn-primary:hover {
            background: var(--soft-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--soft-shadow-md);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: var(--soft-shadow-xs);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--soft-radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            border: 1px solid;
            box-shadow: var(--soft-shadow-xs);
        }

        .alert-error {
            background: #fef5f5;
            border-color: #fed7d7;
            color: #c53030;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
            color: var(--soft-gray-500);
            font-size: 0.875rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--soft-gray-300);
        }

        .divider span {
            background: var(--soft-white);
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-links p {
            margin-bottom: 0.5rem;
            color: var(--soft-gray-600);
            font-size: 0.875rem;
        }

        .auth-links a {
            color: var(--soft-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .auth-links a:hover {
            color: var(--soft-primary-dark);
            text-decoration: underline;
        }

        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1rem;
            }
            
            .auth-card {
                padding: 2rem 1.5rem;
                max-width: 100%;
            }
            
            .auth-header h1 {
                font-size: 1.5rem;
            }
            
            .form-input {
                padding: 0.875rem;
            }
            
            .btn {
                padding: 0.875rem 1.25rem;
            }
        }

        /* Focus States for Accessibility */
        .btn:focus,
        .form-input:focus,
        .auth-links a:focus {
            outline: 2px solid var(--soft-primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-graduation-cap"></i> LIFEISMESS</h1>
                <p>Welcome back! Please sign in to your account.</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?page=login&action=handle" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required
                        autocomplete="email"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </button>
            </form>
            
            <div class="divider">
                <span>or</span>
            </div>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="index.php?page=register">Create one here</a></p>
                <p><a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const inputs = document.querySelectorAll('.form-input');

            // Clean form submission with loading state
            form.addEventListener('submit', function() {
                loginBtn.classList.add('btn-loading');
                loginBtn.querySelector('span').textContent = 'Signing In...';
            });

            // Simple input validation feedback
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = 'var(--soft-danger)';
                    } else {
                        this.style.borderColor = 'var(--soft-success)';
                    }
                });

                input.addEventListener('input', function() {
                    this.style.borderColor = 'var(--soft-gray-300)';
                });
            });
        });
    </script>
</body>
</html>