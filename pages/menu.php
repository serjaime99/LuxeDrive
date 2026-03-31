<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../classes/categorie.php';
require_once __DIR__ . '/../classes/vehicle.php';

$vehicule = new Vehicule();
$categorie = new Categorie();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeDrive - Our Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: rgb(37, 99, 235);
            --secondary: #FFFFFF;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary), rgb(37, 143, 235));
        }

        .hover\:bg-gradient-primary:hover {
            background: linear-gradient(135deg, rgb(37, 143, 235), var(--primary));
        }

        .shadow-soft {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-100" data-user-id="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest'; ?>">
    <!-- Navigation -->
    <nav class="bg-gradient-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-white">LuxeDrive</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="../index.php" class="text-white hover:text-gray-200 transition-all">Home</a>
                    <a href="#" class="text-white hover:text-gray-200 transition-all font-bold">Collection</a>
                    <a href="#" class="text-white hover:text-gray-200 transition-all">Services</a>
                    <?php if (isset($_SESSION["role_id"])) { ?>
                        <div>
                            <a href="../profils/client.php"><img width="35px" class="bg-white rounded-full shadow-soft" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nom']); ?>&background=random" alt="Profile"></a>
                        </div>
                    <?php } else { ?>
                        <a href="../authentification/login.php" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 font-bold transition-all">Sign In</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-white py-12 border-b">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Fleet</h1>
            <p class="text-lg text-gray-600">Discover our selection of prestige vehicles</p>
        </div>
    </header>

    <!-- Filters -->
    <div class="bg-white py-6 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <select class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="categoryFilter">
                    <option value="all">All Categories</option>
                    <?php
                    $arr = $categorie->showCategorie();
                    foreach ($arr as $row) {
                        echo "<option value='" . $row['Categorie_id'] . "'>" . $row['nom'] . "</option>";
                    }
                    ?>
                </select>
                <input type="search" placeholder="Search by name..." name="searchByName" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="searchByName">
            </div>
        </div>
    </div>

    <!-- Vehicle Grid -->
    <div class="max-w-7xl mx-auto px-4 mb-12">
        <div id="container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            $rows = $vehicule->showAllVehicule();
            foreach ($rows as $row) {
                $carImg = "https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80";
                $marque = strtolower($row['marque']);
                if (strpos($marque, 'ferrari') !== false) $carImg = "https://images.unsplash.com/photo-1592198084033-aade902d1aae?auto=format&fit=crop&w=800&q=80";
                else if (strpos($marque, 'porsche') !== false) $carImg = "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80";
                else if (strpos($marque, 'audi') !== false) $carImg = "https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=800&q=80";
            ?>
                <div class="bg-white rounded-lg shadow-soft overflow-hidden transition-all hover:shadow-lg">
                    <img src="<?php echo $carImg; ?>" alt="Car" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <span class="text-sm text-blue-600 font-medium"><?php echo $row['nom'] ?></span>
                        <h3 class="text-xl font-bold text-gray-900 mt-1"><?php echo $row['marque'] . " " . $row['modele'] ?></h3>
                        <p class="text-gray-600">Performance & Luxury</p>
                        <div class="mt-4 flex justify-between items-center border-t pt-4">
                            <span class="text-2xl font-bold text-gray-900"><?php echo $row['prix'] . "$" ?><span class="text-sm text-gray-600">/day</span></span>
                            <?php if (isset($_SESSION['role_id'])) { ?>
                                <a href="reservation.php?vehiculeId=<?php echo $row['vehicule_id'] ?>&clientId=<?php echo $_SESSION['user_id'] ?>" class="bg-gradient-primary text-white px-4 py-2 rounded hover:bg-gradient-primary transition-all">Book Now</a>
                            <?php } else { ?>
                                <a href="../authentification/login.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-all">Sign In</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
            <p>&copy; 2024 LuxeDrive. All rights reserved.</p>
        </div>
    </footer>
</body>
<script src="../scripte/scripte.js"></script>

</html>
