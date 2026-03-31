<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../connection/connect.php';
require_once __DIR__ . '/../classes/user.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();

    if ($user->authenticate($email, $password)) {
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>LuxeDrive - Login</title>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left column - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <div class="mb-10 text-center md:text-left">
                    <a href="../index.php" class="text-3xl font-bold text-blue-600 mb-8 block">LuxeDrive</a>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome Back</h1>
                    <p class="text-gray-500">
                        Don't have an account?
                        <a href="signUp.php" class="text-blue-600 font-bold hover:underline">Create one for free</a>
                    </p>
                </div>

                <?php if (isset($error_message)) { ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-500"></i></div>
                            <div class="ml-3"><p class="text-sm text-red-700"><?php echo $error_message; ?></p></div>
                        </div>
                    </div>
                <?php } ?>

                <form class="space-y-6" method="post">
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="name@company.com" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="••••••••" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 font-bold hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all">
                        Sign In
                    </button>
                </form>
                
                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-gray-400 text-sm">Automotive Excellence &copy; 2024</p>
                </div>
            </div>
        </div>

        <!-- Right column - Image -->
        <div class="hidden lg:block flex-1 bg-gray-100">
            <img
                src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=1200&q=80"
                alt="Luxury Car Login"
                class="w-full h-full object-cover">
        </div>
    </div>
</body>

</html>
