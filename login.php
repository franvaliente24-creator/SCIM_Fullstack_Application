<?php
// Start session if needed for handling login errors or user states
session_start();

// Handle form submission / API routing to auth-service
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation / integration point with auth-service (/api/v1/auth/login)
    // In a microservices setup, this can make a curl request to the API Gateway / auth-service
    if (!empty($email) && !empty($password)) {
        // Example placeholder for authentication logic against auth_db users table
        // Replace or connect this block with your auth-service backend endpoint
        $success = true; // Set to true upon valid credentials validation
        
        if ($success) {
            $_SESSION['user_email'] = $email;
            header('Location: /index.php');
            exit();
        } else {
            $error_message = 'Invalid email or password. Please try again.';
        }
    } else {
        $error_message = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Great Solomon Manpower Services Inc.</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f2ff] min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-100 p-8 mx-4">
        
        <!-- Brand Logo & Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="text-indigo-600 text-4xl mb-3">
                <!-- Crown / Manpower Logo SVG Representation -->
                <svg class="w-12 h-12 fill-current" viewBox="0 0 24 24">
                    <path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.55 18.55 20 18 20H6C5.45 20 5 19.55 5 19V18H19V19Z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Sign In</h1>
            <p class="text-xs text-slate-500 mt-1">Great Solomon Manpower Services Inc.</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="mb-4 p-3 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="space-y-4">
            
            <!-- Email Field -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? 'fam.admin@greatsolomonmpservices.com'); ?>"
                           class="w-full pl-10 pr-4 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-slate-800 placeholder-slate-400" 
                           placeholder="name@greatsolomonmpservices.com">
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required 
                           class="w-full pl-10 pr-10 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-slate-800 placeholder-slate-400" 
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <i id="toggleIcon" class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password Options -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                    <span class="text-slate-600">Remember me</span>
                </label>
                <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs rounded-xl shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sign In
            </button>

        </form>

    </div>

    <!-- Script for Password Visibility Toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>