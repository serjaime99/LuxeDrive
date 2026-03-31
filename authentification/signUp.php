<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../connection/connect.php';
require_once __DIR__ . '/../classes/user.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new user();

    if ($user->register($nom, $prenom, $email, $password)) {
        header("location: login.php");
        exit();
    } else {
        $error_message = "Email already registered!";
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
    <title>LuxeDrive - Sign Up</title>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left column - Sign Up Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <div class="mb-10 text-center md:text-left">
                    <a href="../index.php" class="text-3xl font-bold text-blue-600 mb-8 block">LuxeDrive</a>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Create Account</h1>
                    <p class="text-gray-500">
                        Already have an account?
                        <a href="login.php" class="text-blue-600 font-bold hover:underline">Log in to your account</a>
                    </p>
                </div>

                <?php if (isset($error_message)) { ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-sm text-red-700"><?php echo $error_message; ?></p>
                    </div>
                <?php } ?>

                <form class="space-y-6" method="post">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-bold text-gray-700 uppercase mb-2">First Name</label>
                            <input type="text" name="prenom" id="firstName" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" placeholder="John" required>
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-bold text-gray-700 uppercase mb-2">Last Name</label>
                            <input type="text" name="nom" id="lastName" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" placeholder="Doe" required>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 uppercase mb-2">Email Address</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" placeholder="john@example.com" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 uppercase mb-2">Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all">
                        Get Started
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-gray-400 text-sm">Join the Elite Club &copy; 2024</p>
                </div>
            </div>
        </div>

        <!-- Right column - Image -->
        <div class="hidden lg:block flex-1 bg-gray-100">
            <img
                src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1200&q=80"
                alt="Luxury Car Signup"
                class="w-full h-full object-cover">
        </div>
    </div>
</body>

</html>
