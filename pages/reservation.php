<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../classes/vehicle.php';
require_once __DIR__ . '/../classes/avis.php';

$vehicule = new Vehicule();

// Handle success and error messages
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    $alertType = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['date_invalide'])) {
    $message = $_SESSION['date_invalide'];
    $alertType = 'error';
    unset($_SESSION['date_invalide']);
} else {
    $message = '';
    $alertType = '';
}

// Redirect unauthorized users
if ($_SESSION['role_id'] != 2) {
    header('location: ../authentification/signUp.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeDrive - Reservation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: rgb(37, 99, 235);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary), rgb(37, 143, 235));
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Messages -->
    <?php if ($message != '') { ?>
        <div class="fixed top-0 left-1/2 transform -translate-x-1/2 z-50 w-80 mt-4 px-4 py-3 rounded-lg text-center 
          <?php echo $alertType == 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>"
            x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-200" x-init="setTimeout(() => show = false, 5000)">
            <span><?php echo htmlspecialchars($message); ?></span>
            <button @click="show = false" class="absolute top-1 right-1 text-xl font-bold">&times;</button>
        </div>
    <?php } ?>

    <!-- Navigation -->
    <nav class="bg-gradient-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-white">LuxeDrive</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="../index.php" class="text-white hover:text-gray-200 transition duration-300">Home</a>
                    <a href="menu.php" class="text-white hover:text-gray-200 transition duration-300">Collection</a>
                    <div>
                        <a href="../profils/client.php"><img width="25px" class="bg-white rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nom']); ?>&background=random" alt="Profile"></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <?php
                if (isset($_GET['vehiculeId'])) {
                    $rows = $vehicule->showSpiceficAllVehicule($_GET['vehiculeId']);
                    foreach ($rows as $row) {
                        $carImg = "https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80";
                        $marque = strtolower($row['marque']);
                        if (strpos($marque, 'ferrari') !== false) $carImg = "https://images.unsplash.com/photo-1592198084033-aade902d1aae?auto=format&fit=crop&w=800&q=80";
                        else if (strpos($marque, 'porsche') !== false) $carImg = "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80";
                        else if (strpos($marque, 'audi') !== false) $carImg = "https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=800&q=80";
                ?>
                        <div class="md:w-1/2">
                            <img src="<?php echo $carImg; ?>" alt="Car Image" class="w-full h-96 object-cover">
                        </div>

                        <div class="md:w-1/2 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold uppercase"><?php echo htmlspecialchars($row['nom']); ?></span>
                                <span class="text-2xl font-bold text-gray-900">$<?php echo htmlspecialchars($row['prix']); ?>/day</span>
                            </div>

                            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($row['marque']); ?></h1>
                            <p class="text-gray-600 text-xl mb-6"><?php echo htmlspecialchars($row['modele']); ?></p>

                            <form class="space-y-4" method="post" action="../classes/user.php?vehicule_Id=<?php echo $row['vehicule_id']; ?>&clientId=<?php echo $_SESSION['user_id']; ?>">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1 tracking-wide uppercase text-gray-500">Pick-up Date</label>
                                        <input type="date" class="w-full border rounded-lg p-2" name="date_debut" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1 tracking-wide uppercase text-gray-500">Return Date</label>
                                        <input type="date" class="w-full border rounded-lg p-2" name="date_fin" required>
                                    </div>
                                </div>
                                <input name="reservation_submit" type="submit" class="cursor-pointer text-white bg-gradient-primary w-full py-3 rounded-lg text-lg font-bold mt-6 shadow-md hover:opacity-90 transition duration-300" value="Reserve Now">
                            </form>
                        </div>
                <?php } } ?>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12 text-center">
        <p>&copy; 2024 LuxeDrive. All rights reserved.</p>
    </footer>
</body>

</html>
